@extends('layouts.admin-layout')

@section('title', 'Nuevo Producto')
@section('header', 'Crear Nuevo Producto')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-5xl mx-auto">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @include('admin.products._form', [
            'submitLabel' => 'Guardar nuevo producto',
        ])
    </form>
</div>
@endsection