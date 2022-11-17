<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

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

Route::middleware(['guest'])->get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('short-urls')->name('short-urls.')->controller(ShortURLController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('other-users/index', 'indexOthersShortUrls')->name('other-users.index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('visits/{id}', 'indexVisits')->name('visits.index');
        Route::get('shortened-urls/{shortURLKey}', '\AshAllenDesign\ShortURL\Controllers\ShortURLController')->name('redirect');
    });
});

require __DIR__.'/auth.php';
