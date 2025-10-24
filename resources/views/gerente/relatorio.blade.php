@extends('layouts.user')

@section('page-name')
<li class="breadcrumb-item"><a href="/user/relatorio" class="text-muted">Relatório</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <user-relatorio :leads="{{ json_encode($leads) }}" />
            </div>
        </div>
    </div>
</div>

@endsection