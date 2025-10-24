@extends('layouts.user')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')
<div>
    <solicitacoes-lista :solicitacoes_lista="{{ json_encode($solicitacao_lista) }}" />
</div>
@endsection