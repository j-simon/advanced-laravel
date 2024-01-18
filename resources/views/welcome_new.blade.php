@extends('layouts.app')
@section('content')

<br><br><a href="/produkte_anlegen">Produkte anlegen für die Gates/Policies</a><br>
<br>
<h4>Posts - mit Gate geschützt - aktiv Status kann nur vom Besitzer geändert werden</h4>

<h5>Neuen Post anlegen</h5>
<form action="/posts" method="POST">
    @csrf
    Titel
    <input type="text" name="title">
    Beschreibung
    <input type="text" name="text">
    <input type="submit" value="anlegen">

</form>
<h5>Liste aller Posts</h5>
<table>

    <tr>
        <th>Id</th>
        <th>Titel</th>
        <th>Beschreibung</th>
        <th>Username Name</th>
        <th>User ID</th>
        <th style="padding-left:15px;">aktiv</th>
        <th>Aktion</th>
    </tr>


    @foreach($posts as $post)
    <tr>
        <td>{{$post->id}}</td>
        <td>{{$post->title}}</td>
        <td>{{$post->text}}</td>

        <td>{{$post->user->name?? "nicht zugeordnet"}}</td>
        <td style="text-align:right">{{$post->user->id?? "nicht zugeordnet"}}</td>

        <td style="text-align:left"><b>
                {{-- 2a. Gates
                Die Optik muss gegeben die Logik des Gates abgebildet werden
                anzeigen oder nicht anzeigen
                --}}
                <div style="padding-left:15px;">
                    {{$post->active}} @if($post->active) ✅ @else ❌ @endif
                    {{-- @can('toggle-post', $post) --}}
                    @canany(['toggle-post', 'always-toggle-post'], $post)
                    <a href="/post/{{$post->id}}/toggle">
            </b> kann geändert
            werden</a>
            @endcan
            </div>
        </td>
        <td><a class="btn btn-info" href="/produkt_kaufen/{{$post->id}}">kaufen</td>

    </tr>
    @endforeach
</table>
<br><br>


<a href="/zeigeUploadFormular">Bild-Upload</a><br>
<a href="/zeigeUploadBildAn">hochgeladenes Bild anzeigen</a><br>
<a href="/zeigeAlleBilderAn">alle Bilder anzeigen</a><br><br>

<a href="/sft_server_als_storage">SFT-Server als storage nutzen, Passwort muss eingetrageb sein, vorher
    prüfen!</a><br><br>
<h4> Übungen 12 und 13</h4>
<a href="/upload">Bild-Upload Übung12</a><br>
<a href="/upload_uebung_13">Bild-Upload Übung13</a><br>

<br>
<h4>Mails</h4>
<a href="ansicht_im_browser">Mails ansehen/senden</a><br>
@endsection