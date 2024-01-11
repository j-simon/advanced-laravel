<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

Route::get('/', function (Request $request) {

    //Log::channel('slack')->info("IP-Adresse: ".$request->ip());
    //logger()->channel('daily')->critical("IP-Adresse: ".request()->ip());

    return view('welcome');
});

// MVC Impressum-Seite:
// hier wird unsichtbar in einer before middleware
// der Aufruf der Seite in der /storage/logs/laravel-statistik.log 
// protokolliert
Route::get('/impressum', function () {
     return "Impressum";
})->middleware("statistic");

// MVC Seite 
// für eine before und after middleware, die den Content korrekt mit einer rot - Färbung umgibt
Route::get('/seite', function ( Request $request) {
     
    echo "-Content-<br>";
    logger()->channel('statistics')->info( $request);
})->middleware(['vorher','nachher']);


// uebung_02
Route::get("/uebung_02",function(){
    return view("uebung_02");
});

Route::post("/egg",function () {
    return "Der normale Inhalt!";  
})->middleware(("easteregg"));

// uebung_03
Route::get("/onion",function () {
   return view("uebung_03");
})->middleware("before.Layer","after.Layer");


// Dies die User-Authentifizierungslogik, mit dem laravel/ui installiert
use Illuminate\Support\Facades\Auth;
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Ende