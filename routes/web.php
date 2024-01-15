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


use App\Http\Controllers\UserController;

Route::resource('users', UserController::class);


// MVC Impressum-Seite:
// hier wird unsichtbar in einer before middleware
// der Aufruf der Seite in der /storage/logs/laravel-statistik.log 
// protokolliert
Route::get('/impressum', function () {
    return "Impressum";
})->middleware("statistic");

// MVC Seite 
// für eine before und after middleware, die den Content korrekt mit einer rot - Färbung umgibt
Route::get('/seite', function (Request $request) {

    echo "-Content-<br>";
    logger()->channel('statistics')->info($request);
})->middleware(['vorher', 'nachher']);


// uebung_02
Route::get("/uebung_02", function () {
    return view("uebung_02");
});

Route::post("/egg", function () {
    return "Der normale Inhalt!";
})->middleware(("easteregg"));

// uebung_03
Route::get("/onion", function () {
    return view("uebung_03");
})->middleware("before.Layer", "after.Layer");


// uebung_05
// Dies die User-Authentifizierungslogik, mit dem laravel/ui installiert
use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Ende


Route::get("/secret", function () {
    echo "du bist im gesicherten/angemeldeten Bereich";
})->middleware("auth");

// github
// Route::get("/auth/github",[App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
// Route::get("/auth/github/callback",[App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);


// irgendeinen socialite provider
// hier wird der Anmeldevorgang ausgelöst 
Route::get("/auth/{provider}", [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get("/auth/{provider}/callback", [App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);

// uebung_08
Route::get("/produkte_anlegen", function () {

    $post = new \App\Models\Post;
    $post->title = "Dies ist ein Kühschrank";
    $post->text = "Dieser kostet 250 EURO";
    $post->active = true;
    $post->user_id = 2;
    $post->save();

    \App\Models\Post::create(
        [
            'title' => "Mikrowelle",
            "text" => "Schöne Mikrowelle für 100 EURO",
            "user_id" => 2,
            "active" => true
        ]
    );

    \App\Models\Post::create(
        [
            'title' => "Buch",
            "text" => "Schönes Buch für 10 EURO",
            "user_id" => 1,
            "active" => true
        ]
    );
});



Route::get('/', function (Request $request) {
    if (auth()->user())
        echo "Hallo " . auth()->user()->name . ": <b style='color:green'>user->id=" . auth()->user()->id . "</b>, du bist angemeldet!";
    else
        echo "Hallo, du bist <b style='color:red'>NICHT</b> angemeldet!";

    //Log::channel('slack')->info("IP-Adresse: ".$request->ip());
    //logger()->channel('daily')->critical("IP-Adresse: ".request()->ip());

    $posts = \App\Models\Post::all();
    //$posts = \App\Models\Post::with('user')->get(); // eager loading -> N+1 Problem wird verbessert!
    
    return view('welcome_new', compact("posts"));
});

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

Route::get('post/{post}/toggle', function (App\Models\Post $post) {
    //     2b Gates  
    //     Die Route mit der Logik, die ja unsichtbar ist,
    //     muss gegen falschen user geschützt werden
    // 
    /*   if (Gate::allows('toggle-post', $post)) {
        // User is authorized to toggle the post.
        $post->toggleActivity();
        return redirect('/');
    } else {
        // User is not authorized to update the post.
        return redirect('/');
    } */

    // uebung_11:
    if (Gate::none(['toggle-post', 'always-toggle-post'], $post)) {
        abort(403);
    }
    $post->toggleActivity();

    return redirect('/');

  
});




Route::get("zeigeUploadFormular", function () {
    return view("upload_formular");
});


Route::post("/uploadImage", function (Request $request) {
    //dd($request);//->file[0]);
    $originalFilename = $request->bild->getClientOriginalName();
    // vorkontrolle - validieren

    // $request->validate([
    //     'bild'=> 'required | max:100 | mimes:png | dimensions:min_width:1000,min_height=1000' //mimes:png'
    // ]
    // );

    // $request->bild->store(); // ohne originalNAme mit zeichen/zahlenkolonne
    $request->file('bild')->storeAs(
        'public',
        $request->bild->getClientOriginalName() //mit dem Client Original Name
    );
    session(
        [
            "bild_name" => $request->bild->getClientOriginalName()
        ]
    ); // bildname in der Session speichern

return redirect("/");

});

Route::get("zeigeUploadBildAn", function () {
    return view("view_bild");
});

Route::get("zeigeAlleBilderAn", function () {
    $alleDateien= Storage::files("public");

    foreach($alleDateien as $datei)
        echo $datei."<a href='/bildAnzeigen/$datei'> Bild anzeigen</a> "." <a href='/bildLoeschen/$datei'>Bild löschen</a><br>";
});

Route::get("bildAnzeigen/public/{bild}", function ($bild) {
    
    
    return "<img src='/storage/$bild'>";
    
    
});

Route::get("bildLoeschen/public/{bild}", function ($bild) {
    
    Storage::delete("public/".$bild);

    return redirect("/zeigeAlleBilderAn");
    
});


// uebung_12
// eine Route für das Upload-Formular
Route::get('/upload', function () {
    return view('upload');
});

// und noch eine für die POST-Action:
Route::post('upload', function (Request $request) {

    if ($request->file('image')->isValid()) {

        $request->validate([
            'image' => 'required|max:1024|mimes:png',
        ]);

        //dd($request->image);
        $request->image->store('imagesbilder'); 
        // storage/app/imagesbilder  hier kommts an!!!!
    }
});

// uebung_13
// use Illuminate\Support\Facades\Storage;

Route::get('upload_uebung_13/', function () {
    $path = 'public/'.auth()->user()->id;
    Storage::makeDirectory($path);
    $files = Storage::allFiles($path);
    $directories = Storage::allDirectories($path);

    return view('upload_uebung_13', compact('files', 'directories'));
});


Route::post('directory', function (Request $request) {
    $path = 'public/'.auth()->user()->id;
    Storage::makeDirectory($path.'/'.$request->directory);

    return redirect('upload_uebung_13/');
});


Route::post('upload_uebung_13', function (Request $request) {
    $path = 'public/'.auth()->user()->id;
    if ($request->file('image')->isValid()) {
        $request->validate([
            'image' => 'required|max:1024|mimes:png',
        ]);

        Storage::putFile($path, $request->image);
        
        return redirect('/upload_uebung_13');
    }
});