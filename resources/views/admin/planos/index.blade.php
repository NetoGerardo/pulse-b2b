@extends('layouts.admin')

@section('page-name')
    <li class="breadcrumb-item"><a href="/user" class="text-muted">Planos</a></li>
@endsection

@section('content')

<div>
    <admin-list-planos :planos="{{ json_encode($planos) }}"/>
</div>

@endsection