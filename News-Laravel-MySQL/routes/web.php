<?php


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\News\HomeController;
use App\Http\Controllers\News\AuthController;


use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\RssController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

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
    //return view('home');
});



Route::get('/admin/user', function () {
    return "/admin/user";
});

// Route::get('/admin/slider', function () {
//     return "/admin/slider";
// });

// Route::get('/admin/category', function () {
//     return "/admin/category";
// });



//$prefixAdmin = config('zvn.prefix_admin', 'hehe');
$prefixAdmin = Config::get('zvn.url.prefix_admin', 'hehe');
$prefixNews  = Config::get('zvn.url.prefix_news', 'hehe');

// ================ ADMIN ============//
Route::group(['prefix' => $prefixAdmin, 'middleware' => ['permission.admin']],function () {
    //==========DASHBOARD===========

    $prefix = 'dashboard';
    Route::prefix($prefix)->group(function () use ($prefix) {
        //$controller = ucfirst($prefix) . 'Controller';
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    });

    //==========SLIDER========

    $prefix = 'slider';

    Route::prefix($prefix)->group(function () use ($prefix) {
        //$controller = ucfirst($prefix) . 'Controller';
        Route::get('/', [SliderController::class, 'index'])->name('slider');
        Route::get('form/{id?}', [SliderController::class, 'form'])->where('id', '[0-9]+')->name($prefix . '/form');
        Route::post('save', [SliderController::class, 'save'])->name($prefix . '/save');
        Route::get('delete/{id}', [SliderController::class, 'delete'])->where('id', '[0-9]+')->name($prefix .'/delete');
        Route::get('change-status-{status}/{id}', [SliderController::class, 'status'])->where('id', '[0-9]+')->name($prefix .'/status');
    });

    //==========CATEGORY========

    $prefix = 'category';
    Route::prefix($prefix)->group(function () use ($prefix) {
        //$controller = ucfirst($prefix) . 'Controller';

        Route::get('/', [CategoryController::class, 'index'])->name('category');
        Route::get('form/{id?}', [CategoryController::class, 'form'])->where('id', '[0-9]+')->name($prefix .'/form');
        Route::post('save', [CategoryController::class, 'save'])->name($prefix . '/save');
        Route::get('delete/{id}', [CategoryController::class, 'delete'])->where('id', '[0-9]+')->name($prefix .'/delete');
        Route::get('change-is-home-{isHome}/{id}', [CategoryController::class, 'isHome'])->name('isHome');
        Route::get('change-display-{display}/{id}', [CategoryController::class, 'display'])->name('display');
        Route::get('change-status-{status}/{id}', [CategoryController::class, 'status'])->where('id', '[0-9]+')->name($prefix . '/status');
    });

     //==========ARTICLE========

     $prefix = 'article';
     Route::prefix($prefix)->group(function () use ($prefix) {
         //$controller = ucfirst($prefix) . 'Controller';

         Route::get('/', [ArticleController::class, 'index'])->name('article');
         Route::get('form/{id?}', [ArticleController::class, 'form'])->where('id', '[0-9]+')->name($prefix .'/form');
         Route::post('save', [ArticleController::class, 'save'])->name($prefix .'/save');
         Route::get('delete/{id}', [ArticleController::class, 'delete'])->where('id', '[0-9]+')->name($prefix .'/delete');
         Route::get('change-status-{status}/{id}', [ArticleController::class, 'status'])->where('id', '[0-9]+')->name($prefix . '/status');
         Route::get('change-type-{type}/{id}', [ArticleController::class, 'type'])->name('type');

     });

      //==========USER========

      $prefix = 'user';
      Route::prefix($prefix)->group(function () use ($prefix) {
          //$controller = ucfirst($prefix) . 'Controller';

          Route::get('/', [UserController::class, 'index'])->name('user');
          Route::get('form/{id?}', [UserController::class, 'form'])->where('id', '[0-9]+')->name($prefix .'/form');
          Route::post('save', [UserController::class, 'save'])->name($prefix . '/save');
          Route::post('change-password', [UserController::class, 'changePassword'])->name($prefix . '/change-password');
          Route::post('change-level-post', [UserController::class, 'changeLevelPost'])->name($prefix . '/change-level-post');
          Route::get('delete/{id}', [UserController::class, 'delete'])->where('id', '[0-9]+')->name($prefix .'/delete');
          Route::get('change-status-{status}/{id}', [UserController::class, 'status'])->where('id', '[0-9]+')->name($prefix . '/status');
          Route::get('change-level-{level}/{id}', [UserController::class, 'level'])->name('level');

      });

      //===========RSS==========
      $prefix = 'rss';

      Route::prefix($prefix)->group(function () use ($prefix) {
          //$controller = ucfirst($prefix) . 'Controller';
          Route::get('/', [RssController::class, 'index'])->name('rss');
          Route::get('form/{id?}', [RssController::class, 'form'])->where('id', '[0-9]+')->name($prefix . '/form');
          Route::post('save', [RssController::class, 'save'])->name($prefix . '/save');
          Route::get('delete/{id}', [RssController::class, 'delete'])->where('id', '[0-9]+')->name($prefix .'/delete');
          Route::get('change-status-{status}/{id}', [RssController::class, 'status'])->where('id', '[0-9]+')->name($prefix .'/status');
      });




});

//============== NEWS ==============//
Route::prefix($prefixNews)->group(function () {

    // =========== HOME PAGE ============//
    $prefixN = '';
    Route::prefix($prefixN)->group(function () use ($prefixN) {
        //$controller = ucfirst($prefix) . 'Controller';
        Route::get('/', 'App\Http\Controllers\News\HomeController@index')->name('home');

    });
    // ============ CATEGORY ============
    $prefixN = 'chuyen-muc';
    $controllerName = 'category';
    Route::prefix($prefixN)->group(function () use ($prefixN) {
        //$controller = ucfirst($prefix) . 'Controller';
        Route::get('/{category_name}-{category_id}.html', 'App\Http\Controllers\News\CategoryController@index')
                -> where('category_name', '[0-9a-zA-Z_-]+')
                -> where('category_id', '[0-9]+')
                -> name('category/index');

    });

     // ============ ARTICLE ============
     $prefixN = 'bai-viet';
     $controllerName = 'article';
     Route::prefix($prefixN)->group(function () use ($prefixN) {
         //$controller = ucfirst($prefix) . 'Controller';
         Route::get('/{article_name}-{article_id}.html', 'App\Http\Controllers\News\ArticleController@index')
                 -> where('article_name', '[0-9a-zA-Z_-]+')
                 -> where('article_id', '[0-9]+')
                 -> name('article/index');

     });

      // ============ LOGIN ============
      $prefixN = 'auth';
      //$controllerName = 'auth';
      Route::prefix($prefixN)->group(function () use ($prefixN) {
        //$controller = ucfirst($prefix) . 'Controller';
        Route::get('/login', 'App\Http\Controllers\News\AuthController@login')->name($prefixN . '/login')->middleware('check.login');
        Route::post('/postLogin', 'App\Http\Controllers\News\AuthController@postLogin')->name($prefixN . '/postLogin');
        Route::get('/logout', 'App\Http\Controllers\News\AuthController@logout')->name($prefixN . '/logout');

    });

    $prefixN = 'rss';
    Route::prefix($prefixN)->group(function () use ($prefixN) {
        //$controller = ucfirst($prefix) . 'Controller';
        Route::get('/', 'App\Http\Controllers\News\RssController@index')->name($prefixN . '/index');
        Route::get('/get-gold', 'App\Http\Controllers\News\RssController@getGold')->name($prefixN . '/get-gold');
        Route::get('/get-coin', 'App\Http\Controllers\News\RssController@getCoin')->name($prefixN . '/get-coin');

    });

});
