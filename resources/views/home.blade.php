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
                    
                    @php 
                        $isEditor = auth()->user()->hasPermissionTo('otro');
                        echo $isEditor;
                    @endphp
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
