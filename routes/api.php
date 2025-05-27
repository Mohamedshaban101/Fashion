<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cart\CheckoutController;
use App\Http\Controllers\User\ProductsController;
use App\Http\Controllers\Cart\AddToCartController;
use App\Http\Controllers\Cart\ClearCartController;
use App\Http\Controllers\User\ShowOrderController;
use App\Http\Controllers\Cart\PlaceOrderController;
use App\Http\Controllers\Cart\UpdateCartController;
use App\Http\Controllers\User\ShowProductController;
use App\Http\Controllers\Cart\StripePaymentController;
use App\Http\Controllers\User\UpdateProfileController;
use App\Http\Controllers\Admin\Brands\BrandsController;
use App\Http\Controllers\Admin\Colors\ColorsController;
use App\Http\Controllers\Admin\Orders\OrdersController;
use App\Http\Controllers\Cart\RemoveItemCartController;
use App\Http\Controllers\Auth\GithubSocialiteController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\Brands\StoreBrandController;
use App\Http\Controllers\Admin\Colors\StoreColorController;
use App\Http\Controllers\Admin\Brands\DeleteBrandController;
use App\Http\Controllers\Admin\Brands\UpdateBrandController;
use App\Http\Controllers\Admin\Colors\DeleteColorController;
use App\Http\Controllers\Admin\Categories\CategoriesController;
use App\Http\Controllers\Admin\Products\StoreProductController;
use App\Http\Controllers\Admin\Products\DeleteProductController;
use App\Http\Controllers\Admin\Products\UpdateProductController;
use App\Http\Controllers\Admin\Categories\StoreCategoryController;
use App\Http\Controllers\Admin\Categories\DeleteCategoryController;
use App\Http\Controllers\Admin\Categories\UpdateCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class)->name('login');
Route::get('email/verify/{id}/{hash}' , function(Request $request , $id , $hash){
    $user = User::findOrFail($id);

    if(!$request->hasValidSignature()){
        return response()->json([
            'status' => false,
            'message'=> 'invalid or expired email verification link'
        ],403);
    }

    if($user->hasVerifiedEmail()){
        return response()->json([
            'status' => false,
            'message'=> 'email already verify',
        ]); 
    }

    if(! hash_equals((string) $hash , $user->getEmailForVerification())){
        return response()->json([
            'status' => false,
            'message'=> 'invalid hash'
        ]);
    }

    $user->markEmailAsVerified();
    event(new Verified($user));
    return response()->json([
        'status' => true,
        'message' => 'Email verified successfully'
    ]);
})->middleware('signed')->name('verification.verify');

Route::post('email/resend' , function(Request $request){
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email' , $request->email)->first();

    if($user->hasVerifiedEmail()){
        return response()->json('email already verify');
    }

    $user->sendEmailVerificationNotification();

    return response()->json('verification email resend');
});
// socialite with google
Route::get('google/auth' , [GoogleSocialiteController::class , 'redirectToGoogle']);
Route::get('auth/google/callback' , [GoogleSocialiteController::class , 'handleGoogle']);

//socialite with github
Route::get('github/auth' , [GithubSocialiteController::class , 'redirectToGithub']);
Route::get('auth/github/callback' , [GithubSocialiteController::class , 'handleGithub']);
Route::middleware(['JWTAuth'])->group(function(){
    Route::post('logout' , LogoutController::class);
    // Routes for cart
    Route::get('cart' , CartController::class);
    Route::post('cart/add' , AddToCartController::class);
    Route::post('cart/update' , UpdateCartController::class);
    Route::delete('cart/remove/item/{id}' , RemoveItemCartController::class);
    Route::delete('cart/clear' , ClearCartController::class);
    // route for checkout
    Route::get('checkout' , CheckoutController::class);

    // Route for place order
    Route::post('placeOrder' , PlaceOrderController::class);

    // route for payment
    Route::post('pay' , StripePaymentController::class);

    //Route for order
    Route::get('orders' , OrderController::class);
    Route::get('orders/{id}' , ShowOrderController::class);

    //Route for profile
    Route::get('profile/general' , ProfileController::class);
    Route::post('profile/update' , UpdateProfileController::class);
});

Route::middleware(['CheckAdmin'])->group(function(){

    // Routes For Categories
    Route::get('admin/categories' , CategoriesController::class);
    Route::post('admin/store/category' , StoreCategoryController::class);
    Route::post('admin/update/category/{id}' , UpdateCategoryController::class);
    Route::delete('admin/delete/category/{id}' , DeleteCategoryController::class);

    // Routes For Products
    Route::get('admin/products' , ProductsController::class);
    Route::post('admin/store/product' , StoreProductController::class);
    Route::post('admin/update/product/{id}' , UpdateProductController::class);
    Route::delete('admin/delete/product/{id}' , DeleteProductController::class);


    // Routes For Brands
    Route::get('admin/brands' , BrandsController::class);
    Route::post('admin/store/brand' , StoreBrandController::class);
    Route::post('admin/update/brand/{id}' , UpdateBrandController::class);
    Route::delete('admin/delete/brand/{id}' , DeleteBrandController::class);

    // Routes for colors
    Route::get('admin/colors' , ColorsController::class);
    Route::post('admin/color/store' , StoreColorController::class);
    Route::delete('admin/color/delete/{id}' , DeleteColorController::class);


    // Routes for orders
    Route::get('admin/orders' , OrdersController::class);
});

Route::get('/' , HomeController::class)->middleware(['verified']);
Route::get('products' , ProductsController::class);
Route::get('products/{id}' , ShowProductController::class);
