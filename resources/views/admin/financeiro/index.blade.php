@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Corretor</a></li>
@endsection

@section('content')

<div>
    <calendario-financeiro :status="{{ json_encode($status) }}" :parcelas="{{ json_encode($parcelas) }}"/>
</div>

@endsection