<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\PostModelNotFoundException;
use App\Models\Post;
use App\Services\Auto;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Auto $car, Request $request)
    {
        if ($request->has("search_term")) {
            //

            $posts = Post::where("title", "like", "%" . $request->search_term . "%")
                ->get();
            echo "
        <h5>Liste aller Posts</h5>
        <table>

            <tr>
                <th>Id</th>
                <th>Titel</th>
                <th>Beschreibung</th>
               
            </tr>";


            foreach ($posts as $post) {
                echo "<tr>
                <td>$post->id</td>
                <td>$post->title</td>
                <td>$post->text</td>
                </tr>";
            }
            echo "</table>";
        }
        if ($request->has("id")) {
            //

            $post = Post::find($request->id);

            // Auslösen einer eigenen Exception
            // 
            // 1. Variante: Man weiss was gleich schief geht
            // wenn mit $post nachher die Ausgabe versucht wird,
            // kann es schief gehen, weil der post nicht gefunden wurde

            //  if (!$post) { 
            //       throw new PostModelNotFoundException();
            //   }

            // 2. Variante: Man weiss nicht warum, sondern nur das es nicht geht
             try {
                echo "<tr>
                        <td>$post->id</td>
                        <td>$post->title</td>
                        <td>$post->text</td>
                       </tr>";
             } catch (\Exception $e) {
                 throw new PostModelNotFoundException($e);
             }
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Post anlegen
        \App\Models\Post::create(
            [
                'title' => $request->title,
                "text" => $request->text,
                "user_id" => 1, // auth()->user(),
                "active" => true
            ]
        );

        // Startseite neuladen
        return redirect("/");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
