<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CritiqueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function(){
    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // });
    Route::get('/user',[UserController::class, 'getUser']);
    // Blogs
    Route::post("create-blog",[BlogController::class, 'createBlog']);
    Route::post("update-blog",[BlogController::class, 'updateBlog']);
    Route::get("delete-blog/{id}",[BlogController::class, 'deleteBlogById']);
    // Blogs End

    // Services
    Route::post("create-service",[ServiceController::class, 'createService']);
    Route::post("update-service",[ServiceController::class, 'updateService']);
    Route::get("get-services",[ServiceController::class, 'getServices']);
    Route::get("get-service/{id}",[ServiceController::class, 'getServiceById']);
    Route::get("delete-service/{id}",[ServiceController::class, 'deleteServiceById']);
    // Services End

    // Packages
    Route::post("create-package",[PackageController::class, 'createPackage']);
    Route::post("update-package",[PackageController::class, 'updatePackage']);
    Route::get("get-packages",[PackageController::class, 'getPackages']);
    Route::get("get-package/{id}",[PackageController::class, 'getPackageById']);
    Route::get("delete-package/{id}",[PackageController::class, 'deletePackageById']);
    // Packages End

    // Testimonials
    Route::post("create-testimonial",[TestimonialController::class, 'createTestimonial']);
    Route::post("update-testimonial",[TestimonialController::class, 'updateTestimonial']);
    Route::get("get-testimonials",[TestimonialController::class, 'getTestimonials']);
    Route::get("get-testimonial/{id}",[TestimonialController::class, 'getTestimonialById']);
    Route::get("delete-testimonial/{id}",[TestimonialController::class, 'deleteTestimonialById']);
    // Testimonials End

    // Orders
    Route::post("create-order",[OrderController::class, 'createOrder']);
    Route::post("update-order",[OrderController::class, 'updateOrder']);
    Route::post("upload-questions",[OrderController::class, 'uploadQuestionsFile']);
    Route::post("upload-answers",[OrderController::class, 'uploadAnswersFile']);
    Route::get("get-orders",[OrderController::class, 'getOrders']);
    Route::get("get-order/{id}",[OrderController::class, 'getOrderById']);
    Route::get("get-orders-by-user",[OrderController::class, 'getOrdersByUser']);

    // Critiques
    Route::post("update-critique",[CritiqueController::class, 'updateCritique']);
    Route::get("get-critiques",[CritiqueController::class, 'getCritiques']);
    Route::get("get-critique/{id}",[CritiqueController::class, 'getCritiqueById']);
    Route::get("get-critiques-by-user",[CritiqueController::class, 'getCritiquesByUser']);
});

// Critiques
Route::post("create-critique",[CritiqueController::class, 'createCritique']);

//Stripe
Route::post("stripe",[OrderController::class, 'stripe']);

// Blogs
Route::get("get-blogs",[BlogController::class, 'getBlogs']);
Route::get("get-blog/{id}",[BlogController::class, 'getBlogById']);
// Blogs End

// Services
Route::get("get-services/{type}",[ServiceController::class, 'getServicesByType']);
// Services End

// Packages
Route::get("get-packages/{type}",[PackageController::class, 'getPackagesByType']);
// Packages End

// Testimonials
Route::get("get-active-testimonials",[TestimonialController::class, 'getActiveTestimonials']);
// Testimonials End

Route::post("login",[UserController::class, 'Login']);
Route::post("register",[UserController::class, 'Register']);
