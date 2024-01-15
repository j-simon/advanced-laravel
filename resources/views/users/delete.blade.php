<!-- resources/views/users/delete.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2>Delete User</h2>

            <p>Are you sure you want to delete the user "{{ $user->name }}"?</p>

            <form action="{{ route('users.destroy', $user->id) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
