@extends('layouts.app')
@section('content')

<br><br><a href="/produkte_anlegen">Produkte anlegen für die Gates/Policies</a><br>
<br>
<h4>Posts - mit Gate geschützt - aktiv Status kann nur vom Besitzer geändert werden</h4>

<table>

    <tr>
        <th>Titel</th>
        <th>Beschreibung</th>
        <th>Username Name</th>
        <th>User ID</th>
        <th style="padding-left:15px;">aktiv</th>
    </tr>


    @foreach($posts as $post)
    <tr>
        <td>{{$post->title}}</td>
        <td>{{$post->text}}</td>
        <td>{{$post->user->name}}</td>
        <td style="text-align:right">{{$post->user->id}}</td>
        <td style="text-align:left"><b>
                {{-- 2a. Gates
                Die Optik muss gegeben die Logik des Gates abgebildet werden
                anzeigen oder nicht anzeigen
                --}}
                <div style="padding-left:15px;">
                    {{$post->active}} @if($post->active) ✅ @else ❌ @endif
                {{-- @can('toggle-post', $post) --}}
                @canany(['toggle-post', 'always-toggle-post'], $post)
                <a  href="/post/{{$post->id}}/toggle"></b> kann geändert
            werden</a>
                @endcan
                </div>
        </td>


    </tr>
    @endforeach
</table>
<br><br>
@can('delete-user','App\Models\User')
<a href="/user/{{auth()->user()->id}}/delete">Account loschen</a>
@endcan
@endsection