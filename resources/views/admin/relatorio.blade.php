@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/admin/relatorio" class="text-muted">Relatório</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <admin-relatorio :corretores="{{ json_encode($corretores) }}" :leads="{{ json_encode($leads) }}" />
            </div>
        </div>
    </div>
</div>

@endsection