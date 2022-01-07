<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController as RoleController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('admin/', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::POST('admin/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login');

    Route::get('admin/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');
/*Route::group(['middleware' => ['auth']], function() {

    //Route::resource('roles', [App\Http\Controllers\Admin\RoleController::class]);

    Route::resource('users', [App\Http\Controllers\Admin\UserController::class]);
//    Route::resource('products', ProductController::class);

});*/
//namespace("Admin")->
Route::prefix('admin')->middleware(['auth'])->group(function(){
        Route::resources([
            'users'         => App\Http\Controllers\Admin\UserController::class,
            'roles'         => App\Http\Controllers\Admin\RoleController::class,
            'permissions'   => App\Http\Controllers\Admin\PermissionController::class,
            'cms-pages'    => App\Http\Controllers\Admin\CmsPageController::class,
            'cms-pages/{id}/gallery'=> App\Http\Controllers\Admin\CmsPageGalleryController::class,
        ]);
         Route::get('cms-pages/{id}/gallery/create',[App\Http\Controllers\Admin\CmsPageGalleryController::class,'create'] )->name('cms-pages-gallery.create');
        Route::POST('cms-pages/{id}/gallery/create',[App\Http\Controllers\Admin\CmsPageGalleryController::class,'store'] )->name('cms-page-gallery.store');
        Route::get('cms-pages/{id}/gallery/{gallery_id}/update',[App\Http\Controllers\Admin\CmsPageGalleryController::class,'edit'] )->name('cms-pages-gallery.edit');

        Route::get('cms-pages/{id}/gallery',[App\Http\Controllers\Admin\CmsPageGalleryController::class,'index'] )->name('cms-pages-gallery.index');
         Route::get('cms-pages/{id}/gallery/{gallery_id}/show',[App\Http\Controllers\Admin\CmsPageGalleryController::class,'show'] )->name('cms-pages-gallery.show');


         
    
});
Route::middleware(['auth'])->group(function(){
    Route::Any('admin/cms-page/render-image-block', [App\Http\Controllers\Admin\CmsPageController::class, 'renderImageBlock'])->name('renderImageBlock'); 
    Route::Any('admin/cms-page/render-text-block', [App\Http\Controllers\Admin\CmsPageController::class, 'renderTextBlock'])->name('renderTextBlock'); 
    Route::Any('admin/cms-page/render-video-block', [App\Http\Controllers\Admin\CmsPageController::class, 'renderVideoBlock'])->name('renderVideoBlock');    
    
});


/*Route::namespace("Admin")->prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('/', 'HomeController@index')->name('admin.home');
    Route::namespace('Auth')->group(function(){
        Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
        Route::post('/login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');
    });
         Route::resources([
            'users'  => UserController::class,
            'roles'  => RoleController::class,
           
            
        ]);
});*/

//https://www.w3adda.com/blog/laravel-separate-admin-panel-multiple-authentication-system-using-guards

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
     \UniSharp\LaravelFilemanager\Lfm::routes();
 });