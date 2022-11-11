<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('get_system_settings', [ApiController::class, 'get_system_settings']);
Route::post('user_signup', [ApiController::class, 'user_signup']);

Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('get_slider', [ApiController::class, 'get_slider']);
        Route::post('get_categories', [ApiController::class, 'get_categories']);
        Route::post('get_house_type', [ApiController::class, 'get_house_type']);
        Route::post('get_unit', [ApiController::class, 'get_unit']);
        Route::post('update_profile', [ApiController::class, 'update_profile']);
        Route::post('get_user_by_id', [ApiController::class, 'get_user_by_id']);
        Route::post('get_property', [ApiController::class, 'get_property']);
        Route::post('post_property', [ApiController::class, 'post_property']);
        Route::post('update_post_property', [ApiController::class, 'update_post_property']);
        Route::post('remove_post_images', [ApiController::class, 'remove_post_images']);
        Route::post('set_property_inquiry', [ApiController::class, 'set_property_inquiry']);
        Route::post('get_notification_list', [ApiController::class, 'get_notification_list']);
        Route::post('get_property_inquiry', [ApiController::class, 'get_property_inquiry']);
        Route::post('set_property_total_click', [ApiController::class, 'set_property_total_click']);
        Route::post('delete_user', [ApiController::class, 'delete_user']);
});
