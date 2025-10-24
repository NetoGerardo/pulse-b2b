@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Propostas</a></li>
@endsection

@section('content')
<div>
    <admin-administradoras :administradoras="{{ json_encode($administradoras) }}" />
</div>
@endsection