<?php

namespace App\Http\Services;

use App\Models\Exhibition;
use App\Models\ExhibitionImage;
use App\Models\FeaturedProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\alert;

class ExhibitionServices
{
    public function index()
    {
        $exhibitionProducts = Exhibition::with('exhibitionImages')->orderBy('id', 'desc')->get();
        return view('admin.exhibitions.index', compact('exhibitionProducts'));
    }

    public function create()
    {
        return view('admin.exhibitions.create');
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
            'showHome' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();

            return response()->json(['error' => $message]);
        }

        if (!$request->id) {
            $productExists = Exhibition::where('name', $request->name)->exists();

            if ($productExists) {
                $message = 'Exhibition with this name already exists.';
                return response()->json(['error' => $message]);
            }
        }

        if ($validator->passes()) {

            $storeExhibition = $request->id ? Exhibition::find($request->id) : new Exhibition();
            $storeExhibition->name = $request->name;
            $storeExhibition->slug = $request->slug;
            $storeExhibition->showHome = $request->showHome;
            $storeExhibition->status = $request->status;
            $storeExhibition->save();

            if (!$request->id && !empty($request->image_array)) {
                foreach ($request->image_array as  $temp_value_image) {
                    $tempImageInfo = TempImage::find($temp_value_image);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = $request->id ? ExhibitionImage::find($request->id) : new ExhibitionImage();
                    $productImage->exhibition_id = $storeExhibition->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $newImageName = $storeExhibition->slug . '_' . $productImage->id . '_' . time() . '.' . $ext;
                    $productImage->image = $newImageName;
                    $productImage->save();

                    // For Large Image  
                    try {
                        $spath = public_path() . '/temp/' . $tempImageInfo->name;
                        $dpath = public_path() . '/uploads/exhibition/large/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(1400, 900);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        alert($e->getMessage());
                        dd($e->getMessage());
                    }

       

                    // For Small Image  
                    try {
                        $dpath = public_path() . '/uploads/exhibition/small/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(300, 300);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        alert($e->getMessage());
                        dd($e->getMessage());
                    }
                }
            };


            $message = $request->id ? 'Exhibition updated successfully.' : 'Exhibition created successfully.';

            return response()->json(['status' => true, 'message' => $message]);
        }
    }

    public function edit($request)
    {
        $editExhibition = Exhibition::find($request->id);
        $exhibitionImages = ExhibitionImage::where('exhibition_id', $request->id)->get();
        return view('admin.exhibitions.edit', compact('editExhibition', 'exhibitionImages'));
    }

    public function destroy($id)
    {
        $product = Exhibition::find($id);

        $productImages = ExhibitionImage::where('exhibition_id', $product->id)->get();
        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path() . '/uploads/exhibition/large/' . $productImage->image);
                File::delete(public_path() . '/uploads/exhibition/small/' . $productImage->image);
            }
            ExhibitionImage::where('exhibition_id', $product->id)->delete();
        }

        $product->delete();

        return response()->json([
            "status" => true,
            "message" => 'Exhibition Deleted Successfully! ',
        ]);
    }

    public function updateExhibitionImage($request)
    {

        $image = $request->file;
        // dd($image);
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ExhibitionImage();
        $productImage->exhibition_id = $request->exhibition_id;
        $productImage->image = "NULL";
        $productImage->save();

        $newImageName = $request->slug . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $newImageName;
        $productImage->save();

        try {
            $dpath = public_path() . '/uploads/exhibition/large/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(1400, 900);
            $image->save($dpath);
        } catch (\Exception $e) {
            alert($e->getMessage());
            dd($e->getMessage());
        }

        // For Small Image  
        try {
            $dpath = public_path() . '/uploads/exhibition/small/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(300, 300);
            $image->save($dpath);
        } catch (\Exception $e) {
            alert($e->getMessage());
            dd($e->getMessage());
        }

        return response()->json([
            "status" => true,
            "image_id" => $productImage->id,
            "ImagePath" => asset( 'uploads/exhibition/small/' . $productImage->image),
            "message" => 'Image Saved Successfully!',
        ]);
    }


    public function deleteExhibitionImage($request)
    {
        $productImage = ExhibitionImage::find($request->id);
        File::delete(public_path() . '/uploads/exhibition/large/' . $productImage->image);
        File::delete(public_path() . '/uploads/exhibition/small/' . $productImage->image);
        $productImage->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
