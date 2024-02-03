@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h1>Selamat datang {{ Auth::user()->name }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
