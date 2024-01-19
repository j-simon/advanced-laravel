<?php

use App\Events\Kauf;
use App\Events\OrderCompleted;
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
use App\Http\Controllers\PostController;
use App\Jobs\ConfirmUpload;
use App\Jobs\Exception;
use App\Jobs\LogAusgabe;

Route::resource('users', UserController::class);
Route::resource('posts', PostController::class);

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
    \App\Models\Post::create(
        [
            'title' => "Ente",
            "text" => "Schöne Ente für 13 EURO",
            "user_id" => 3,
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

    //$posts = \App\Models\Post::all();
    $posts = \App\Models\Post::with('user')->get(); // eager loading -> N+1 Problem wird verbessert!

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
    $alleDateien = Storage::files("public");

    foreach ($alleDateien as $datei)
        echo $datei . "<a href='/bildAnzeigen/$datei'> Bild anzeigen</a> " . " <a href='/bildLoeschen/$datei'>Bild löschen</a><br>";
});

Route::get("bildAnzeigen/public/{bild}", function ($bild) {


    return "<img src='/storage/$bild'>";
});

Route::get("bildLoeschen/public/{bild}", function ($bild) {

    Storage::delete("public/" . $bild);

    return redirect("/zeigeAlleBilderAn");
});


Route::get('/sft_server_als_storage', function () {
    $alleDateien = Storage::disk('sftp')->files("Bilder");

    foreach ($alleDateien as $datei)
        echo $datei . "<br>";

    //return view('welcome');
});

// uebung_12
// eine Route für das Upload-Formular
Route::get('/upload', function () {
    return view('upload');
});

// und noch eine für die POST-Action:
Route::post('/upload', function (Request $request) {

    if ($request->file('image')->isValid()) {

        $request->validate([
            'image' => 'required|max:1024|mimes:png',
        ]);


        //dd($request->image);
        $request->image->store('public/imagesbilder');
        // storage/app/imagesbilder  hier kommts an!!!!

        dispatch(new ConfirmUpload());
    }
});

// uebung_13
// use Illuminate\Support\Facades\Storage;

Route::get('upload_uebung_13/', function () {
    $path = 'public/' . auth()->user()->id;

    // dd($path);
    //Storage::makeDirectory($path);
    //dd("make ");
    $files = Storage::allFiles($path);
    $directories = Storage::allDirectories($path);

    return view('upload_uebung_13', compact('files', 'directories'));
});


Route::post('directory', function (Request $request) {
    $path = 'public/' . auth()->user()->id;
    Storage::makeDirectory($path . '/' . $request->directory);

    return redirect('/');
});


Route::post('upload_uebung_13', function (Request $request) {
    $path = 'public/' . auth()->user()->id . "/bilder";
    if ($request->file('image')->isValid()) {
        $request->validate([
            'image' => 'required|max:1024|mimes:png',
        ]);

        Storage::putFile($path, $request->image);

        return redirect('/upload_uebung_13');
    }
});

// uebung_19
use App\Mail\Quote;
use App\Mail\Image;
use App\Mail\Inspire;

// uebung_19 mails ansehen und uebung_20 senden per mailer aus env
use Illuminate\Support\Facades\Mail;

Route::get('/ansicht_im_browser', function () {

    // die 3 Versionen nacheinander ausprobieren,
    // jeweils die return statements in-/auskommntieren

    // 1 uebung_15 und 16
    $mail = new Quote();

    // echo "<br>"; // oder: return new Quote();

    // echo $mail->render(); 
    // echo "<br>"; 
    // // Mail::to('test@test.de')->send(new Quote());



    // 2
    $quote = 'coffee in the morning saves your life';
    $author = 'wise old man';
    // Mail::to('test@test.de')->send(new Inspire($quote, $author));
    //return new Inspire($quote, $author);

    // 3
    Mail::to('test@test.de')->send(new Image()); // mailtrap OK
    #Mail::to('jens.simon@gmx.net')->send(new Image()); // fuer gmx
    return new Image();
});

use App\Mail\Newsletter;
use App\Mail\NewsletterPersoenlich;
use App\Models\User;
use App\Notifications\FirmaNotification;
use App\Notifications\NewsletterNotification;

use App\Services\PostService;

Route::get("/sende_newsletter_mail", function () {

    //return new Newsletter();
    // mail empfaenger und inhalt 

    // Mail::to("jens.simon@gmx.net")->send(new Newsletter);
    //Mail::to("jens.simon2@gmx.net")->send(new Newsletter);

    Mail::to("jens.simon2@gmx.net")
        //  ->cc("jens.simon@gmx.net")
        //  ->bcc("jens.simon2@gmx.net")
        ->send(new NewsletterPersoenlich("Jens"));
});

use Illuminate\Support\Facades\Notification;

Route::get("/sende_notification", function () {
    auth()->loginUsingId(1);
    Notification::send(auth()->user(), new FirmaNotification);
});

Route::get("/sende_newsletter_notification", function () {

    auth()->loginUsingId(1);

    Notification::send(auth()->user(), new NewsletterNotification("Jens"));

    return "gesendet! schau bitte in der Mail, in Database und bei Twitter!";
});




Route::get('/queue_test', function () {

    for ($i = 0; $i < 10; $i++) {

        // ohne Zeitsteuerung (syncron)
        Log::alert('Aufgabe ausgeführt! ' . $i);

        // mit Zeitsteuerung über die Queue (asyncron)
        dispatch(function () use ($i) {
            Log::alert('Aufgabe in der Queue ausgeführt! ' . $i);
        })->delay(5);
    }

    return "fertig!";
});


Route::get('/ohne_queue_test', function () {

    for ($i = 0; $i < 10; $i++) {


        // ohne Zeitsteuerung (syncron)
        Log::alert('Aufgabe ausgeführt! ' . $i);
        sleep(1);
    }
    return "fertig!";
});
Route::get('/nur_queue_test', function () {

    for ($i = 0; $i < 10; $i++) {



        // mit Zeitsteuerung über die Queue (asyncron)
        dispatch(function () use ($i) {
            Log::alert('Aufgabe in der Queue ausgeführt! ' . $i);
            sleep(1);
        });
    }
    return "fertig!";
});



Route::get("/test_mails", function () {

    /*$zahl = 100;
    return new \App\Mail\Quote2($zahl);*/

    // return new Inspire("ein Zitat","Jens");
    //return new Image();
    Mail::to("jens.simon@gmx.net")->send(new Image);
});

Route::get("/syncron_test", function () {

    for ($i = 1; $i <= 10; $i++) {
        logger()->info("Aufgabe ohne Warteschlange - syncron - " . $i);

        // Verschiebe diese Codeausführung in einer in der Zukunft geplanten Zeit
        dispatch(function () use ($i) {
            //sleep(1);
            logger()->info("Aufgabe mit Warteschlange - asyncron - niedrigeprioritaet" . $i);
        })->onQueue('niedrigeprioritaet');


        dispatch(new LogAusgabe($i))->delay(5)->onQueue('hoheprioritaet');
    }

    return "fertig!";
});




//uebung_24

Route::get("/aufgabe_24", function () {




    dispatch(function () {
        logger()->alert("Aufgabe mit queue ausgeführt!");
    });



    logger()->alert("Aufgabe ohne queue ausgeführt!");
});


Route::get("/uebung_26", function () {

    //throw new \Exception;
    dispatch(new App\Jobs\Exception);
});

Route::get("/produkt_kaufen/{id}", function ($id) {


    event(new Kauf($id));
});

Route::get("test_service_1", function (PostService $postService) {
    dump($postService);
});


Route::get("test_service_2", function () {
    $postService = new PostService();
    dump($postService);
});


Route::get("/ordercompleted", function () {
    event(new OrderCompleted);
    return "Event OrderCompleted ausgelöst!";
});



use App\Services\Auto;

Route::get("/auto", function () {

    $car = new Auto();
    $car->setFarbe("rot");
    dump($car);
});


use Illuminate\Support\Facades\Http;

Route::get("/http_client_test", function (Auto $car, User $user) {

    // $response = Http::get('https://world.openfoodfacts.org/api/v2/product/%204025700001030.json');
    // $schokolade=json_decode($response->body());
    // //dd($schokolade->product->image_front_small_url);

    // echo "<img src='".$schokolade->product->image_front_small_url."'>";


    $response = Http::get('http://routinglaravel.test:8000/certificates/1');

    dump(json_decode($response->body()));
});

/* 
	uebung_31
*/

use App\Exceptions\CalcException;

Route::get('calc', function () {
    throw new CalcException(); // ohne Nachricht

    throw new CalcException('huups da gab es einen fehler!'); // mit Nachricht

});

/* 
	uebung_32:
*/


Route::get('product', function () {
    return view('product-search');
});

Route::post('product', function (Request $request) {
    $request->validate([
        'barcode' => 'required',
    ]);

    // uebung_32
    //$response = Http::get('https://de.openfoodfacts.org/api/v0/product/'.$request->barcode);

    // diese eine Zeile ist uebung_33!!!!!!!!!!!
    $response = cache()->rememberForever("product.{$request->barcode}", function () {
        return Http::beforeSending(function () {
            info('Anfrage bezüglich des Barcodes' . request()->barcode . ' gesendet.');
        })->get('https://de.openfoodfacts.org/api/v0/product/' . request()->barcode)->json();
    });

    //dd($response->json());
    return view('product-search', compact('response'));
});
