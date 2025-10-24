@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Produtos</a></li>
@endsection

@section('content')
<div>
    <admin-produtos :produtos="{{ json_encode($produtos) }}"  :operadoras="{{ json_encode($operadoras) }}"/>
</div>
@endsection