@extends('layouts.bootstrap')
@section('title', 'Productos')
@section('content')
@php $esAdmin = auth()->user()->role === 'admin'; @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Catálogo de productos</h1>
    @if($esAdmin)
        <a href="{{ route('products.create') }}" class="btn btn-primary">Nuevo producto</a>
    @endif
</div>

<form method="GET" action="{{ route('products.index') }}" class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Buscar por nombre</label>
                <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Ej. mouse">
            </div>
            <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categories as $categoria)
                        <option value="{{ $categoria->id }}" @selected(request('category_id') == $categoria->id)>{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="disponible" @selected(request('estado') === 'disponible')>Disponible</option>
                    <option value="agotado" @selected(request('estado') === 'agotado')>Agotado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio mínimo</label>
                <input type="number" step="0.01" min="0" name="precio_min" value="{{ request('precio_min') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio máximo</label>
                <input type="number" step="0.01" min="0" name="precio_max" value="{{ request('precio_max') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ordenar por</label>
                <select name="orden" class="form-select">
                    <option value="fecha_desc" @selected($orden === 'fecha_desc')>Fecha: más recientes</option>
                    <option value="fecha_asc" @selected($orden === 'fecha_asc')>Fecha: más antiguos</option>
                    <option value="nombre_asc" @selected($orden === 'nombre_asc')>Nombre: A-Z</option>
                    <option value="nombre_desc" @selected($orden === 'nombre_desc')>Nombre: Z-A</option>
                    <option value="precio_desc" @selected($orden === 'precio_desc')>Precio: mayor a menor</option>
                    <option value="precio_asc" @selected($orden === 'precio_asc')>Precio: menor a mayor</option>
                    <option value="existencias_desc" @selected($orden === 'existencias_desc')>Existencias: mayor a menor</option>
                    <option value="existencias_asc" @selected($orden === 'existencias_asc')>Existencias: menor a mayor</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Por página</label>
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([10, 15, 25, 50] as $n)
                        <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if($errors->any())
            <div class="alert alert-danger mt-3 mb-0">
                @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
            </div>
        @endif
        <div class="mt-3">
            <button class="btn btn-primary">Filtrar</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Limpiar filtros</a>
        </div>
    </div>
</form>

<p class="text-muted mb-2">
    {{ $products->total() }} registros encontrados · Página {{ $products->currentPage() }} de {{ $products->lastPage() }}
</p>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Categoría</th>
                    <th class="text-end">Precio</th><th class="text-end">Existencias</th>
                    <th>Estado</th><th>Fecha de registro</th>
                    @if($esAdmin) <th class="text-end">Acciones</th> @endif
                </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->nombre }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($product->descripcion, 50) }}</td>
                    <td>{{ $product->category->nombre }}</td>
                    <td class="text-end">${{ number_format($product->precio, 2) }}</td>
                    <td class="text-end">{{ $product->existencias }}</td>
                    <td><x-status-badge :estado="$product->estado" /></td>
                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                    @if($esAdmin)
                        <td class="text-end">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="{{ $esAdmin ? 9 : 8 }}" class="text-center text-muted">No se encontraron productos.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection