@extends('layouts.admin-layout')

@section('title', 'Editar Producto')
@section('header', 'Editar: ' . $product->name)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-5xl mx-auto">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @method('PUT')
        <input type="hidden" name="origin_category" value="{{ request('origin_category', $product->categories->first()?->id) }}">
        <input type="hidden" name="return_search" value="{{ request('search') }}">
        @include('admin.products._form', [
            'submitLabel' => 'Guardar cambios',
        ])
    </form>
</div>
@endsection