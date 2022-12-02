<?php

use App\Http\Controllers\AdvertisementController;

use App\Http\Controllers\AreaMeasurementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BedroomController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PropertController;
use App\Http\Controllers\PropertysInquiryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\HouseTypeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\PropertysInquiry;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/new-migrate', function () {
    Artisan::call('migrate');
    return redirect()->back();
});


Route::get('/fresh-migrate', function () {
    Artisan::call('migrate:fresh');
    return redirect()->back();
});

Auth::routes();



Route::get('privacypolicy', [HomeController::class, 'privacy_policy']);


Route::middleware(['auth', 'checklogin'])->group(function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('view:cache');


    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'blank_dashboard'])->name('dashboard');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('about-us', [SettingController::class, 'index']);
    Route::get('privacy-policy', [SettingController::class, 'index']);
    Route::get('terms-conditions', [SettingController::class, 'index']);
    Route::get('system-settings', [SettingController::class, 'index']);


    /// STAT :: PASSWORD ROUTE
    Route::get('change-password', [App\Http\Controllers\HomeController::class, 'change_password'])->name('changepassword');
    Route::post('check-password', [App\Http\Controllers\HomeController::class, 'check_password'])->name('checkpassword');
    Route::post('store-password', [App\Http\Controllers\HomeController::class, 'store_password'])->name('changepassword.store');
    /// END :: PASSWORD ROUTE

    Route::post('settings', [SettingController::class, 'settings']);
    Route::post('set_settings', [SettingController::class, 'system_settings']);

    Route::resource('users', UserController::class);
    Route::post('users-update', [UserController::class, 'update']);
    Route::post('users-reset-password', [UserController::class, 'resetpassword']);

    Route::get('userList', [UserController::class, 'userList']);

    Route::resource('customer', CustomersController::class);
    Route::get('customerList', [CustomersController::class, 'customerList']);
    Route::post('customerstatus', [CustomersController::class, 'update'])->name('customer.customerstatus');

    Route::resource('slider', SliderController::class);
    Route::post('slider-order', [SliderController::class, 'update'])->name('slider.slider-order');
    Route::get('slider-destory/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    Route::get('get-property-by-category', [SliderController::class, 'getPropertyByCategory'])->name('slider.getpropertybycategory');
    Route::get('sliderList', [SliderController::class, 'sliderList']);

    Route::resource('article', ArticleController::class);
    Route::get('article_list', [ArticleController::class, 'show']);
    Route::get('article-destory/{id}', [ArticleController::class, 'destroy'])->name('article.destroy');

    Route::resource('advertisement', AdvertisementController::class);
    Route::get('advertisement_list', [AdvertisementController::class, 'show']);
    Route::post('advertisement-status', [AdvertisementController::class, 'updateStatus'])->name('advertisement.updateadvertisementstatus');

    Route::resource('package', PackageController::class);
    Route::get('package_list', [PackageController::class, 'show']);
    Route::post('package-update', [PackageController::class, 'update']);
    Route::post('package-status', [PackageController::class, 'updatestatus'])->name('package.updatestatus');





    Route::resource('categories', CategoryController::class);
    Route::get('categoriesList', [CategoryController::class, 'categoryList']);
    Route::post('categories-update', [CategoryController::class, 'update']);
    Route::post('categories-status', [CategoryController::class, 'updateCategory'])->name('customer.categoriesstatus');

    Route::resource('parameters', ParameterController::class);
    Route::get('parameter-list', [ParameterController::class, 'show']);

    Route::resource('housetype', HouseTypeController::class);
    Route::get('housetypeList', [HouseTypeController::class, 'typeList']);
    Route::post('house-type-update', [HouseTypeController::class, 'update']);
    // Route::post('house-type-status-update', [HouseTypeController::class, 'statusupdate'])->name('housetype.updatestatus');

    Route::resource('measurement', UnitController::class);
    Route::get('measurementList', [UnitController::class, 'measurementList']);
    Route::post('measurement-update', [UnitController::class, 'update']);

    /// STAT :: PROPERTY ROUTE
    Route::resource('property', PropertController::class);
    Route::get('getPropertyList', [PropertController::class, 'getPropertyList']);
    Route::post('property-status', [PropertController::class, 'updateStatus'])->name('property.updatepropertystatus');
    Route::post('property-gallery', [PropertController::class, 'removeGalleryImage'])->name('property.removeGalleryImage');
    Route::get('get-village-by-taluka', [PropertController::class, 'getVillageByTaluka'])->name('property.getVillageByTaluka');
    Route::get('property-destory/{id}', [PropertController::class, 'destroy'])->name('property.destroy');
    /// STAT :: PROPERTY ROUTE


    /// STAT :: PROPERTY ROUTE


    /// STAT :: PROPERTY INQUIRY
    Route::resource('property-inquiry', PropertysInquiryController::class);
    Route::get('getPropertyInquiryList', [PropertysInquiryController::class, 'getPropertyInquiryList']);
    Route::post('property-inquiry-status', [PropertysInquiryController::class, 'updateStatus'])->name('property-inquiry.updateStatus');
    /// END :: PROPERTY INQUIRY


    /// START :: NOTIFICATION
    Route::resource('notification', NotificationController::class);
    Route::get('notificationList', [NotificationController::class, 'notificationList']);
    Route::get('notification-delete', [NotificationController::class, 'destroy']);
    Route::post('notification-multiple-delete', [NotificationController::class, 'multiple_delete']);
    /// END :: NOTIFICATION
});
