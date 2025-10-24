@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')

<div>
    <relatorio-admin-kanban :corretores="{{ json_encode($corretores) }}" :planos="{{ json_encode($planos) }}" />
</div>

@endsection