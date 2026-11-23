<x-field name="nombre" label="Nombre del producto" :value="$product->nombre ?? null" required />

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="3"
              class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion', $product->descripcion ?? '') }}</textarea>
    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="category_id" class="form-label">Categoría</label>
    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
        <option value="">Selecciona una categoría</option>
        @foreach($categories as $categoria)
            <option value="{{ $categoria->id }}" @selected(old('category_id', $product->category_id ?? '') == $categoria->id)>{{ $categoria->nombre }}</option>
        @endforeach
    </select>
    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-4">
        <x-field name="precio" label="Precio" type="number" step="0.01" :value="$product->precio ?? null" required />
    </div>
    <div class="col-md-4">
        <x-field name="existencias" label="Existencias" type="number" step="1" :value="$product->existencias ?? 0" required />
    </div>
    <div class="col-md-4 mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror" required>
            @foreach(['disponible' => 'Disponible', 'agotado' => 'Agotado'] as $valor => $texto)
                <option value="{{ $valor }}" @selected(old('estado', $product->estado ?? 'disponible') === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>