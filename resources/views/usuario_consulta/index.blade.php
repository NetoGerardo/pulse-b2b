@extends('layouts.usuario_consulta')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <usuario-consulta-listar-leads-dia :leads="{{ json_encode($leads) }}" />
            </div>
        </div>
    </div>
</div>

@endsection