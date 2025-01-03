<?php

namespace App\Http\Services;
 
use App\Models\FeaturedProduct;
use App\Models\NewArrival;
use App\Models\NewArrivalImage;
use App\Models\Product;
use App\Models\ProductImage; 
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

class NewArrivalServices
{
    public function index()
    {
        $newArrivals = NewArrival::with('newArrivalImages')->orderBy('id', 'desc')->get();
        return view('admin.newArrival.index', compact('newArrivals'));
    }

    public function create()
    { 
        return view('admin.newArrival.create');
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'short_description' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'original_price' => 'required', 
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();

            return response()->json(['error' => $message]);
        }

        if (!$request->id) {
            $productExists = NewArrival::where('title', $request->title)->exists();

            if ($productExists) {
                $message = 'Product with this name already exists.';
                return response()->json(['error' => $message]);
            }
        }

        if ($validator->passes()) {

            $newArrivalProducts = $request->id ? NewArrival::find($request->id) : new NewArrival();
            $newArrivalProducts->title = $request->title;
            $newArrivalProducts->slug = $request->slug;
            $newArrivalProducts->detail_description = $request->detail_description;
            $newArrivalProducts->short_description = $request->short_description;
            $newArrivalProducts->price = $request->price;
            $newArrivalProducts->original_price = $request->original_price; 
            $newArrivalProducts->qty = $request->qty;
            $newArrivalProducts->status = $request->status;
            $newArrivalProducts->save();

            if (!$request->id && !empty($request->image_array)) {
                foreach ($request->image_array as  $temp_value_image) {
                    $tempImageInfo = TempImage::find($temp_value_image);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $newArrivalImage = $request->id ? NewArrivalImage::find($request->id) : new NewArrivalImage();
                    $newArrivalImage->new_arrival_id = $newArrivalProducts->id;
                    $newArrivalImage->image = "NULL";
                    $newArrivalImage->save();

                    $newImageName = $newArrivalProducts->slug . '_' . $newArrivalImage->id . '_' . time() . '.' . $ext;
                    $newArrivalImage->image = $newImageName;
                    $newArrivalImage->save();

                    // For Large Image  
                    try {
                        $spath = public_path() . '/temp/' . $tempImageInfo->name;
                        $dpath = public_path() . '/uploads/NewArrival/large/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(1400, 900);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }

                    // For Small Image  
                    try {
                        $dpath = public_path() . '/uploads/NewArrival/small/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(300, 300);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }
                }
            };


            $message = $request->id ? 'Product updated successfully.' : 'Product created successfully.';

            return response()->json(['status' => true, 'message' => $message]);
        }
    }

    public function edit($request)
    {
        $newArrivalProduct = NewArrival::find($request->id);
        $newArrivalImages = NewArrivalImage::where('new_arrival_id', $request->id)->get(); 
        return view('admin.newArrival.edit', compact('newArrivalProduct', 'newArrivalImages'));
    }

    public function updateProductImage($request)
    {

        $image = $request->file; 
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new NewArrivalImage();
        $productImage->new_arrival_id = $request->new_arrival_id;
        $productImage->image = "NULL";
        $productImage->save();

        $newImageName = $request->slug . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $newImageName;
        $productImage->save();

        try {
            $dpath = public_path() . '/uploads/NewArrival/large/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(1400, 900);
            $image->save($dpath);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // For Small Image  
        try {
            $dpath = public_path() . '/uploads/NewArrival/small/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(300, 300);
            $image->save($dpath);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return response()->json([
            "status" => true,
            "image_id" => $productImage->id,
            "ImagePath" => asset('uploads/NewArrival/small/' . $productImage->image),
            "message" => 'Image Saved Successfully!',
        ]);
    }


    public function deleteProductImage($request)
    {
        $productImage = NewArrivalImage::find($request->id);
        File::delete(public_path() . '/uploads/NewArrival/large/' . $productImage->image);
        File::delete(public_path() . '/uploads/NewArrival/small/' . $productImage->image);
        $productImage->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }


    public function destroy($id)
    {
        $product = NewArrival::find($id);

        $productImages = NewArrivalImage::where('new_arrival_id', $product->id)->get();
        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path() . '/uploads/NewArrival/large/' . $productImage->image);
                File::delete(public_path() . '/uploads/NewArrival/small/' . $productImage->image);
            }
            NewArrivalImage::where('new_arrival_id', $product->id)->delete();
        }

        $product->delete();

        return response()->json([
            "status" => true,
            "message" => 'Product Deleted Successfully! ',
        ]);
    }
}
