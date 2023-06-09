<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blade\UserController;
use App\Http\Controllers\Blade\RoleController;
use App\Http\Controllers\Blade\PermissionController;
use App\Http\Controllers\Blade\HomeController;
use App\Http\Controllers\Blade\ApiUserController;
use App\Http\Controllers\Blade\CarsController;
use App\Http\Controllers\Blade\CountryController;
use App\Http\Controllers\Blade\BotuserController;
use App\Http\Controllers\Blade\OrderController;
use App\Http\Controllers\Blade\MailingController;
/*
|--------------------------------------------------------------------------
| Blade (front-end) Routes
|--------------------------------------------------------------------------
|
| Here is we write all routes which are related to web pages
| like UserManagement interfaces, Diagrams and others
|
*/

// Default laravel auth routes
Auth::routes();


// Welcome page
Route::get('/', function (){
    return redirect()->route('home');
})->name('welcome');


// Web pages
Route::group(['middleware' => 'auth'],function (){

    // there should be graphics, diagrams about total conditions
    Route::get('/home', [HomeController::class,'index'])->name('home');

    // Users
    Route::get('/users',[UserController::class,'index'])->name('userIndex');
    Route::get('/user/add',[UserController::class,'add'])->name('userAdd');
    Route::post('/user/create',[UserController::class,'create'])->name('userCreate');
    Route::get('/user/{id}/edit',[UserController::class,'edit'])->name('userEdit');
    Route::post('/user/update/{id}',[UserController::class,'update'])->name('userUpdate');
    Route::delete('/user/delete/{id}',[UserController::class,'destroy'])->name('userDestroy');
    Route::get('/user/theme-set/{id}',[UserController::class,'setTheme'])->name('userSetTheme');

    // Permissions
    Route::get('/permissions',[PermissionController::class,'index'])->name('permissionIndex');
    Route::get('/permission/add',[PermissionController::class,'add'])->name('permissionAdd');
    Route::post('/permission/create',[PermissionController::class,'create'])->name('permissionCreate');
    Route::get('/permission/{id}/edit',[PermissionController::class,'edit'])->name('permissionEdit');
    Route::post('/permission/update/{id}',[PermissionController::class,'update'])->name('permissionUpdate');
    Route::delete('/permission/delete/{id}',[PermissionController::class,'destroy'])->name('permissionDestroy');

    // Roles
    Route::get('/roles',[RoleController::class,'index'])->name('roleIndex');
    Route::get('/role/add',[RoleController::class,'add'])->name('roleAdd');
    Route::post('/role/create',[RoleController::class,'create'])->name('roleCreate');
    Route::get('/role/{role_id}/edit',[RoleController::class,'edit'])->name('roleEdit');
    Route::post('/role/update/{role_id}',[RoleController::class,'update'])->name('roleUpdate');
    Route::delete('/role/delete/{id}',[RoleController::class,'destroy'])->name('roleDestroy');

    // ApiUsers
    Route::get('/api-users',[ApiUserController::class,'index'])->name('api-userIndex');
    Route::get('/api-user/add',[ApiUserController::class,'add'])->name('api-userAdd');
    Route::post('/api-user/create',[ApiUserController::class,'create'])->name('api-userCreate');
    Route::get('/api-user/show/{id}',[ApiUserController::class,'show'])->name('api-userShow');
    Route::get('/api-user/{id}/edit',[ApiUserController::class,'edit'])->name('api-userEdit');
    Route::post('/api-user/update/{id}',[ApiUserController::class,'update'])->name('api-userUpdate');
    Route::delete('/api-user/delete/{id}',[ApiUserController::class,'destroy'])->name('api-userDestroy');
    Route::delete('/api-user-token/delete/{id}',[ApiUserController::class,'destroyToken'])->name('api-tokenDestroy');

    // Cars
    Route::get('/cars', [CarsController::class,'index'])->name('carIndex');
    Route::get('/car/add', [CarsController::class,'add'])->name('carsAdd');
    Route::post('/car/create',[CarsController::class,'create'])->name('carCreate');
    Route::get('/car/{car_id}/edit',[CarsController::class,'edit'])->name('carEdit');
    Route::post('/car/update/{car_id}',[CarsController::class,'update'])->name('carUpdate');
    Route::delete('/car/delete/{car_id}',[CarsController::class,'destroy'])->name('carDestroy');

    // Cities
    Route::get('/countries',[CountryController::class, 'index'])->name('countryIndex');
    Route::get('/country/add',[CountryController::class, 'add'])->name('countryAdd');
    Route::post('/country/create',[CountryController::class, 'create'])->name('countryCreate');
    Route::get('/country/{id}/edit',[CountryController::class, 'edit'])->name('countryEdit');
    Route::post('/country/update/{id}',[CountryController::class, 'update'])->name('countryUpdate');
    Route::delete('/country/delete/{id}',[CountryController::class,'destroy'])->name('countryDestroy');

    //Orders
    Route::get('/order', [OrderController::class,'index'])->name('orderIndex');
    Route::post('/order/status', [OrderController::class,'status'])->name('orderStatus');
    Route::get('/order-export', [OrderController::class, 'orderExport'])->name('order-export');

    //Mailings
    Route::get('/mailing', [MailingController::class,'index'])->name('mailingIndex');
    Route::get('/mailing/add', [MailingController::class,'add'])->name('mailingAdd');
    Route::post('/mailing/create', [MailingController::class,'create'])->name('mailingCreate');
    Route::get('/mailing/{mailing_id}/edit', [MailingController::class,'edit'])->name('mailingEdit');
    Route::post('/mailing/update/{mailing_id}', [MailingController::class,'update'])->name('mailingUpdate');
    Route::delete('/mailing/delete/{id}', [MailingController::class,'destroy'])->name('mailingDestroy');
    Route::post('/mailing/status', [MailingController::class,'status'])->name('mailingStatus');

    
    //Botusers
    Route::get('/botusers',[BotuserController::class, 'index'])->name('botuserIndex');
    Route::get('/botusers/{botuser_id}/edit',[BotuserController::class, 'edit'])->name('botuserEdit');
    Route::post('/botusers/update/{botuser_id}',[BotuserController::class, 'update'])->name('botuserUpdate');
    Route::get('/botusers/{botuser_id}/show',[BotuserController::class, 'show'])->name('botuserShow');

    Route::get('/botusers/{botuser_id}/editaddress',[BotuserController::class, 'editaddress'])->name('botuserEditaddress');
    Route::post('/botusers/updateaddress/{botuser_id}',[BotuserController::class, 'updateaddress'])->name('botuserUpdateaddress');
});

// Change language session condition
Route::get('/language/{lang}',function ($lang){
    $lang = strtolower($lang);
    if ($lang == 'ru' || $lang == 'uz')
    {
        session([
            'locale' => $lang
        ]);
    }
    return redirect()->back();
});

/*
|--------------------------------------------------------------------------
| This is the end of Blade (front-end) Routes
|-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\
*/
