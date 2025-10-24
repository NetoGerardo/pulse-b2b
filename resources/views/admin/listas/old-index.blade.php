@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Planos</a></li>
@endsection

@section('content')

<div>
    <importar-lista :origens="{{ json_encode($origens) }}" :corretores="{{ json_encode($corretores) }}"/>
</div>

@endsection