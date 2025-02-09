<?php

namespace App\Http\Services;
 
use App\Models\FeaturedProduct;
use App\Models\Product;
use App\Models\ProductImage; 
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

class ProductServices
{
    public function index()
    {
        $products = Product::with('productImages')->orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $subCategories = SubCategory::orderBy("id", "desc")->where('status', "active")->get();
        return view('admin.products.create', compact('subCategories'));
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'short_description' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'original_price' => 'required',
            'sub_category_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();

            return response()->json(['error' => $message]);
        }

        if (!$request->id) {
            $productExists = Product::where('title', $request->title)->exists();

            if ($productExists) {
                $message = 'Product with this name already exists.';
                return response()->json(['error' => $message]);
            }
        }

        if ($validator->passes()) {

            $product = $request->id ? Product::find($request->id) : new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->detail_description = $request->detail_description;
            $product->short_description = $request->short_description;
            $product->price = $request->price;
            $product->original_price = $request->original_price;
            $product->sub_category_id = $request->sub_category_id;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->save();

            if (!$request->id && !empty($request->image_array)) {
                foreach ($request->image_array as  $temp_value_image) {
                    $tempImageInfo = TempImage::find($temp_value_image);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = $request->id ? ProductImage::find($request->id) : new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $newImageName = $product->slug . '_' . $productImage->id . '_' . time() . '.' . $ext;
                    $productImage->image = $newImageName;
                    $productImage->save();

                    // For Large Image  
                    try {
                        $spath = public_path() . '/temp/' . $tempImageInfo->name;
                        $dpath = public_path() . '/uploads/product/large/' . $newImageName;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($spath);
                        $image->resize(1400, 900);
                        $image->save($dpath);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }

                    // For Small Image  
                    try {
                        $dpath = public_path() . '/uploads/product/small/' . $newImageName;
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
        $editProduct = Product::find($request->id);
        $productImages = ProductImage::where('product_id', $request->id)->get();
        $subCategories = SubCategory::orderBy('id', 'desc')->get();
        return view('admin.products.edit', compact('editProduct', 'productImages', 'subCategories'));
    }

    public function updateProductImage($request)
    {

        $image = $request->file; 
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = "NULL";
        $productImage->save();

        $newImageName = $request->slug . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $newImageName;
        $productImage->save();

        try {
            $dpath = public_path() . '/uploads/product/large/' . $newImageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(1400, 900);
            $image->save($dpath);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // For Small Image  
        try {
            $dpath = public_path() . '/uploads/product/small/' . $newImageName;
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
            "ImagePath" => asset('uploads/product/small/' . $productImage->image),
            "message" => 'Image Saved Successfully!',
        ]);
    }


    public function deleteProductImage($request)
    {
        $productImage = ProductImage::find($request->id);
        File::delete(public_path() . '/uploads/product/large/' . $productImage->image);
        File::delete(public_path() . '/uploads/product/small/' . $productImage->image);
        $productImage->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        $productImages = ProductImage::where('product_id', $product->id)->get();
        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path() . '/uploads/product/large/' . $productImage->image);
                File::delete(public_path() . '/uploads/product/small/' . $productImage->image);
            }
            ProductImage::where('product_id', $product->id)->delete();
        }

        $product->delete();

        return response()->json([
            "status" => true,
            "message" => 'Product Deleted Successfully! ',
        ]);
    }

    public function featuredProduct()
    {
        $featured = FeaturedProduct::all();
        return view('admin.featured.index', compact('featured'));
    }

    public function createFeaturedProduct()
    {
        return view('admin.featured.create');
    }
    public function storeFeaturedProduct($request)
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'description' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        if ($validator->passes()) {
            $featured = $request->id ? FeaturedProduct::find($request->id) : new FeaturedProduct();
            $featured->name = $request->name;
            $featured->slug = $request->slug;
            $featured->description = $request->description;
            $featured->qty = $request->qty;
            $featured->price = $request->price;
            $featured->status = $request->status;
            $featured->showHome = $request->show_on_home;
            $featured->save();

            if (!empty($request->image)) {
                $tempImageInfo = TempImage::find($request->image);
                $extArray = explode('.', $tempImageInfo->name);
                $ext = last($extArray);
                $newImageName = $request->slug . '-' . $featured->id . '-' . time() . '.' . $ext;
                $featured->image = $newImageName;
                $featured->save();

                // For Large Image
                try {
                    $spath = public_path() . '/temp/' . $tempImageInfo->name;
                    $dpath = public_path() . '/uploads/featured/' . $newImageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($spath);
                    $image->resize(1400, 900);
                    $image->save($dpath);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
            $message = $request->id ? 'Featured Product updated successfully.' : 'Featured Product created successfully.';
            return response()->json(['status' => true, 'message' => $message]);
        }
    }

    public function editFeaturedProduct($request){
        $editFeatured = FeaturedProduct::find($request->id);
        return view('admin.featured.edit', compact('editFeatured'));
    }

    public function destroyFeaturedProduct($id){
        $featured = FeaturedProduct::find($id);
        $featured->delete();
        return response()->json(['success' => true, 'message' => 'Featured Product deleted successfully']);
    }
}
