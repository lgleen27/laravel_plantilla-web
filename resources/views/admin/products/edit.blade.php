@extends('layouts.admin-layout')

@section('title', 'Editar Producto')
@section('header', 'Editar: ' . $product->name)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-5xl mx-auto">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.products._form', [
            'submitLabel' => 'Guardar cambios',
        ])
    </form>
</div>
@endsection