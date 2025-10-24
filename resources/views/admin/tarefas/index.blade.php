@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Tarefas</a></li>
@endsection

@section('content')
<div>
    <admin-listar-tarefas />
</div>

@endsection