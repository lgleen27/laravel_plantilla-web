@extends('layouts.admin-layout')

@section('title', 'Nuevo Usuario')
@section('header', 'Crear Usuario')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-3xl mx-auto">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @include('admin.users._form', ['submitLabel' => 'Crear usuario'])
    </form>
</div>
@endsection