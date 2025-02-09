<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\SubCategoryImage;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

class SubCategoryServices
{
    public function index()
    {
        $subCategories = SubCategory::orderBy('id', 'desc')->with('category')->get(); 
        return view('admin.subCategory.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('id', 'asc')->select('name', 'id')->get(); 
         return view('admin.subCategory.create', compact('categories'));
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required', 
            'show_on_home' => 'required',
            'category_id' => 'required',

        ]);

        if ($validator->fails()) {
            $message = $validator->errors();

            return response()->json(['error' => $message]);
        }

        if (empty($request->id)) {
            $categoryExists = SubCategory::where('name', $request->name)->exists();

            if ($categoryExists) {
                $message = 'Sub Category with this name already exists.';

                return response()->json(['error' => $message]);
            }
        }

        if ($validator->passes()) {

            $subCategory = $request->id ? SubCategory::find($request->id) : new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;    
            $subCategory->category_id = $request->category_id; 
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->show_on_home;
            $subCategory->save();

            if (!$request->id && !empty($request->image_array)) {
                foreach ($request->image_array as  $temp_value_image) {
                    $tempImageInfo = TempImage::find($temp_value_image);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $subCategoryImage = $request->id ? SubCategoryImage::find($request->id) : new SubCategoryImage();
                    $subCategoryImage->sub_category_id = $subCategory->id;
                    $subCategoryImage->image = "NULL";
                    $subCategoryImage->save();

                    $newImageName = $subCategory->slug . '_' . $subCategoryImage->id . '_' . time() . '.' . $ext;
                    $subCategoryImage->image = $newImageName;
                    $subCategoryImage->save();

                    // For Large Image  
                    try {
                        $spath = public_path() . '/temp/' . $tempImageInfo->name;
                        $dpath = public_path() . '/uploads/subCategory/large/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(1400, 900);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }

                    // For Small Image  
                    try {
                        $dpath = public_path() . '/uploads/subCategory/small/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(300, 300);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }
                }
            };


            $message = $request->id ? 'Sub Category Update successfully' : 'Sub Category created successfully.';
            return response()->json(['status' => true, 'message' => $message]);
        }
    }


    public function edit($request)
    {
        $subCategory = SubCategory::find($request->id);
        $subCategoryImages = SubCategoryImage::where('sub_category_id', $request->id)->get();
        $categories = Category::orderBy('id', 'asc')->select('name', 'id')->get(); 
        return view('admin.subCategory.edit', compact('subCategory', 'subCategoryImages', 'categories'));
    }

    public function destroy($id)
    {
        $product = SubCategory::find($id);
        $product->delete();

        return response()->json([
            "status" => true,
            "message" => 'Sub Category Deleted Successfully! ',
        ]);
    }

    public function updateSubCategoryImage($request)
    {

        $image = $request->file;
        // dd($image);
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $subCateogryImage = new SubCategoryImage();
        $subCateogryImage->sub_category_id = $request->sub_category_id;
        $subCateogryImage->image = "NULL";
        $subCateogryImage->save();

        $newImageName = $request->slug . '-' . $subCateogryImage->id . '-' . time() . '.' . $ext;
        $subCateogryImage->image = $newImageName;
        $subCateogryImage->save();

        try {
            $dpath = public_path() . '/uploads/subCategory/large/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(1400, 900);
            $image->save($dpath);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // For Small Image  
        try {
            $dpath = public_path() . '/uploads/subCategory/small/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(300, 300);
            $image->save($dpath);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return response()->json([
            "status" => true,
            "image_id" => $subCateogryImage->id,
            "ImagePath" => asset('uploads/subCategory/small/' . $subCateogryImage->image),
            "message" => 'Image Saved Successfully!',
        ]);
    }


    public function deleteSubCategoryImage($request)
    {
        $subCategoryImage = SubCategoryImage::find($request->id);
        File::delete(public_path() . '/uploads/subCategory/large/' . $subCategoryImage->image);
        File::delete(public_path() . '/uploads/subCategory/small/' . $subCategoryImage->image);
        $subCategoryImage->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

}
