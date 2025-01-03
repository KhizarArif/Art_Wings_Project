<?php


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\NewArrivalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TempImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// ============================ Front Routes ===============================
Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/cart', 'cart')->name('front.cart');
    Route::get('/all_products/{subcategorySlug?}', 'subProducts')->name('frontend.subProducts');
    Route::get('checkouts', 'checkouts')->name('front.checkouts');
    Route::get('thankyou/{id}', 'thankyou')->name('front.thankyou');
    Route::post('add_to_cart', 'addToCart')->name('front.addToCart');
    Route::post('update_cart', 'updateCart')->name('front.updateCart');
    Route::post('get_shipping_amount', 'getShippingAmount')->name('shipping.getShippingAmount');
    Route::post('process_checkout', 'processCheckout')->name('front.processCheckout');
    Route::post('filter_categories', 'filterCategories')->name('front.filterCategories');
    Route::delete('delete_cart', 'deleteToCart')->name('front.deleteToCart');

    Route::get('image_gallery', function () {
        return view('frontend.imageGallary');
    });

    // Get Initial Category When Home Page is Loaded
    Route::get('get_initial_category', 'getInitialCategory')->name('front.getInitialCategory');
});



// ============================ Admin Routes ===============================

Route::group(["prefix" => "dashboard"], function () {

    // Registration and Login Authentication Middlewate for Admin Start

    Route::controller(AdminAuthController::class)->group(function () {
        Route::get('/login', 'index')->name('admin.login');
        Route::post('/authenticate', 'authenticate')->name('admin.authenticate');
    });

    // Registration and Login Authentication Middlewate for Admin End


    // Authentication and Authorization Middleware for Admin Start
    Route::middleware(['middleware' => 'auth_admin'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/', 'index')->name('admin.dashboard');
            Route::get('/logout', 'logout')->name('admin.logout');
        });

        // Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        //     Route::get('', 'index')->name('categories.index');
        //     Route::get('create', 'create')->name('categories.create');
        //     Route::get('edit/{id}', 'edit')->name('categories.edit');
        //     Route::post('store', 'store')->name('categories.store');
        //     Route::delete('delete/{id}', 'destroy')->name('categories.delete');
        // });

        Route::controller(SubCategoryController::class)->prefix('categories')->group(function () {
            Route::get('', 'index')->name('subcategories.index');
            Route::get('create', 'create')->name('subcategories.create');
            Route::get('edit/{id}', 'edit')->name('subcategories.edit');
            Route::post('store', 'store')->name('subcategories.store');
            Route::delete('delete/{id}', 'destroy')->name('subcategories.delete');
            // Update Product Controller Image
            Route::post('sub_category_image/update', 'updateSubCategoryImage')->name('subcategories.updateImage');
            Route::delete('sub_category_image', 'deleteSubCategoryImage')->name('subcategories.deleteImage');
        });

        Route::controller(ExhibitionController::class)->prefix('exhibitions')->group(function () {
            Route::get('', 'index')->name('exhibitions.index');
            Route::get('create', 'create')->name('exhibitions.create');
            Route::get('edit/{id}', 'edit')->name('exhibitions.edit');
            Route::post('store', 'store')->name('exhibitions.store');
            Route::delete('delete/{id}', 'destroy')->name('exhibitions.delete');
            // Update Product Controller Image
            Route::post('exhibition_image/update', 'updateExhibitionImage')->name('exhibitions.updateImage');
            Route::delete('exhibition_image', 'deleteExhibitionImage')->name('exhibitions.deleteImage');
        });

        Route::controller(ProductController::class)->prefix('products')->group(function () {
            Route::get('', 'index')->name('product.index');
            Route::get('create', 'create')->name('product.create');
            Route::get('edit/{id}', 'edit')->name('product.edit');
            Route::post('store', 'store')->name('product.store');
            Route::delete('delete/{id}', 'destroy')->name('product.delete');

            // Update Product Controller Image
            Route::post('product_image/update', 'updateProductImage')->name('products.updateImage');
            Route::delete('product_image', 'deleteProductImage')->name('products.deleteImage');

            // Featured Products Details 
            Route::prefix('featured_products')->group(function () {
                Route::get('', 'featuredProduct')->name('featured_products.index');
                Route::get('create', 'createFeaturedProduct')->name('featured_products.create');
                Route::get('edit/{id}', 'editFeaturedProduct')->name('featured_products.edit');
                Route::post('store', 'storeFeaturedProduct')->name('featured_products.store');
                Route::delete('delete/{id}', 'destroyFeaturedProduct')->name('featured_products.delete');
            });
        });

        Route::controller(NewArrivalController::class)->prefix('new_arrivals')->group(function () {
            Route::get('', 'index')->name('new_arrivals.index');
            Route::get('create', 'create')->name('new_arrivals.create');
            Route::get('edit/{id}', 'edit')->name('new_arrivals.edit');
            Route::post('store', 'store')->name('new_arrivals.store');
            Route::delete('delete/{id}', 'destroy')->name('new_arrivals.delete');

            // Update Product Controller Image
            Route::post('new_arrival_image/update', 'updateProductImage')->name('new_arrivals.updateImage');
            Route::delete('new_arrival_image', 'deleteProductImage')->name('new_arrivals.deleteImage');
        });

        // Order Details 
        Route::controller(OrderController::class)->prefix('orders')->group(function () {
            Route::get('', 'index')->name('orders.index');
            Route::get('edit/{id}', 'edit')->name('orders.edit');
            Route::delete('delete/{id}', 'destroy')->name('orders.delete');
            Route::post('change_status/{id}', 'changeOrderStatus')->name('orders.changeOrderStatus');
            Route::post('send_email_invoice/{id}', 'sendEmailInvoice')->name('orders.sendEmailInvoice');
        });


        // Shipping Routes
        Route::controller(ShippingController::class)->prefix('shipping')->group(function () {
            Route::get('/create', 'create')->name('shipping.create');
            Route::post('/store', 'store')->name('shipping.store');
            Route::get('edit/{id}', 'edit')->name('shipping.edit');
            Route::put('update/{id}', 'update')->name('shipping.update');
            Route::delete('delete/{id}', 'destroy')->name('shipping.delete');
        });


        // Temperory Images route
        Route::post('/upload_image', [TempImageController::class, 'create'])->name('image.create');
        Route::delete('/delete_image/{id}', [TempImageController::class, 'deleteImage'])->name('delete.image');

        Route::get('/getSlug', function (Request $request) {
            $slug = "";
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                "status" => true,
                "slug" => $slug,
            ]);
        })->name('getSlug');
    });

    // Authentication and Authorization Middleware for Admin End
});
