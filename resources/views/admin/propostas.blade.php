@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Propostas</a></li>
@endsection

@section('content')
<div>
    <user-propostas-relatorio :tipo="{{ json_encode($tipo) }}" :supervisores="{{ json_encode($supervisores) }}" :propostas="{{ json_encode($propostas_relatorio) }}" :produtos="{{ json_encode($produtos) }}" :status="{{ json_encode($status) }}" :administradoras="{{ json_encode($administradoras) }}" :operadoras="{{ json_encode($operadoras) }}"/>
</div>
@endsection