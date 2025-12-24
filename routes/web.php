<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Privacy\ProcessingActivityController;

Route::get('/', function () {
    return view('core/extencion');
});

Route::resource('rat', ProcessingActivityController::class);
