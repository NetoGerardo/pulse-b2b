@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Corretor</a></li>
@endsection

@section('content')

<div>
    <calendario-financeiro-recebido :planos="{{ json_encode($planos) }}" />
</div>

@endsection