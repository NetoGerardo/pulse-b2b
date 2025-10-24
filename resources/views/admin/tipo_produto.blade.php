@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Propostas</a></li>
@endsection

@section('content')
<div>
    <admin-tipo-produto :tipo="{{ json_encode($tipo) }}" />
</div>
@endsection