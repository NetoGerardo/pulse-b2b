@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/admin/turnos" class="text-muted">Turnos</a></li>
@endsection

@section('content')

<div>
    <propostas-pendentes :propostas="{{ json_encode($propostas) }}" :planos="{{ json_encode($planos) }}"/>
</div>

@endsection