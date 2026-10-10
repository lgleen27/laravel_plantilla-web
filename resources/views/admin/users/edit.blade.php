@extends('layouts.admin-layout')

@section('title', 'Editar Usuario')
@section('header', 'Editar: ' . $user->name)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-3xl mx-auto">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @method('PUT')
        @include('admin.users._form', ['submitLabel' => 'Guardar cambios'])
    </form>
</div>
@endsection