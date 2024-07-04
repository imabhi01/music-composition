<?php

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
    return redirect()->route('composition.create');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/audio/index', [App\Http\Controllers\AudioController::class, 'index'])->name('audio.index');

    Route::get('/audio/create', [App\Http\Controllers\AudioController::class, 'create'])->name('audio.create');

    Route::post('/audio/save', [App\Http\Controllers\AudioController::class, 'save'])->name('audio.save');

    Route::delete('/audio/{id}', [App\Http\Controllers\AudioController::class, 'destroy'])->name('audio.destroy'); 
    
    Route::get('/composition/create', [App\Http\Controllers\CompositionController::class, 'create'])->name('composition.create');
    
    Route::post('/composition/save', [App\Http\Controllers\CompositionController::class, 'store'])->name('composition.save'); 

    Route::get('/composition/index', [App\Http\Controllers\CompositionController::class, 'index'])->name('composition.index'); 
    
    Route::delete('/composition/{id}', [App\Http\Controllers\CompositionController::class, 'destroy'])->name('composition.destroy'); 

});

