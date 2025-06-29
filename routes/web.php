<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\RestoTableController;

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

Route::resource('tables', RestoTableController::class);
Route::get('/scan/table/{id}', [RestoTableController::class, 'show'])->name('table.view');
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::post('/place-order',[OrderController::class, 'store'])->name('place.order');
Route::get('/orders', [OrderController::class, 'index'])->name('order.index');



// Route::view('/botman/chat', 'botman.chat');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/menu', [MenuItemController::class, 'showCategories'])->name('menu.categories');
Route::get('/menu/category/{id}', [MenuItemController::class, 'showByCategory'])->name('menu.byCategory');
Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::post('/menu', [MenuItemController::class, 'store'])->name('menu.store');
Route::put('/menu/{id}', [MenuItemController::class, 'update'])->name('menu.update');
Route::delete('/menu/{id}', [MenuItemController::class, 'destroy'])->name('menu.destroy');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::post('/resto-tables/{id}/toggle-lock', [RestoTableController::class, 'toggleLock']);
Route::get('/sales-report', [OrderController::class, 'salesReport'])->name('order.sales');
Route::get('/sales/served/export', [OrderController::class, 'exportPdf'])->name('sales.export.pdf');
Route::get('/manage-orders', [OrderController::class, 'manage'])->name('orders.manage');
Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('/orders/accepted', [OrderController::class, 'accepted'])->name('orders.accepted');
Route::delete('/order-items/{id}', [OrderController::class, 'destroy'])->name('order-items.destroy');










Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
