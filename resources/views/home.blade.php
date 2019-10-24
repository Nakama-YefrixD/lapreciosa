@extends('layouts.blank')
@section('title')
    Inicio
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
<<<<<<< HEAD
                    @php 
                        $isEditor = auth()->user()->hasPermissionTo('otro');
                        echo $isEditor;
                    @endphp
=======
                    
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
