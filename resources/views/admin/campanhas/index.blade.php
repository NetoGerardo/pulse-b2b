@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')

<admin-listar-campanhas :users="{{ json_encode($users) }}" :estados="{{ json_encode($estados) }}"/>

@endsection