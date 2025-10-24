@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/admin/relatorio" class="text-muted">Relatório</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <admin-ranking :corretores="{{ json_encode($corretores) }}"/>
            </div>
        </div>
    </div>
</div>

@endsection