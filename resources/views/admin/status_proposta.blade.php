@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Status das propostas</a></li>
@endsection

@section('content')
<div>
    <admin-status-proposta :status="{{ json_encode($status) }}" />
</div>
@endsection