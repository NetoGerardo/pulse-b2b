@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Listas</a></li>
@endsection

@section('content')

<div>
    <solicitacoes-listas-admin :listas="{{ json_encode($listas) }}" :solicitacoes="{{ json_encode($solicitacoes) }}"/>
</div>

@endsection