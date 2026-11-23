@extends('layouts.bootstrap')
@section('title', 'Editar producto')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card shadow-sm">
        <div class="card-header">Editar producto</div>
        <div class="card-body">
            <form method="POST" action="{{ route('products.update', $product) }}">
                @csrf @method('PUT')
                @include('products._form')
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</div></div>
@endsection