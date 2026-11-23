@props(['estado'])
<span class="badge text-bg-{{ $estado === 'disponible' ? 'success' : 'danger' }}">
    {{ ucfirst($estado) }}
</span>