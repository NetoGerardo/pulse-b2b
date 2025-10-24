@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Listas</a></li>
@endsection

@section('content')

<div>
    <listar-listas :listas="{{ json_encode($listas) }}" />
</div>

<div>
    <admin-importar-lista :origens="{{ json_encode($origens) }}" :listas="{{ json_encode($listas) }}" />
</div>

@endsection