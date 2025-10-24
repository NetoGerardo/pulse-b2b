@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')

<div>
    <admin-listas :solicitacoes="{{ json_encode($solicitacoes) }}" :listas="{{ json_encode($listas) }}" />
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card card-hover">
                            <div class="p-2 bg-primary text-center">
                                <h1 class="font-light text-white">{{ $leads_hoje }}</h1>
                                <h6 class="text-white">Total contatos</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card card-hover">
                            <div class="p-2 bg-cyan text-center">
                                <h1 class="font-light text-white">{{ $leads_recebidos }}</h1>
                                <h6 class="text-white">Contatos recebidos</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card card-hover">
                            <div class="p-2 bg-success text-center">
                                <h1 class="font-light text-white">{{ $positivos }}</h1>
                                <h6 class="text-white">Contatos Positivos</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card card-hover">
                            <div class="p-2 bg-danger text-center">
                                <h1 class="font-light text-white">{{ $negativos }}</h1>
                                <h6 class="text-white">Contatos Negativos</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <admin-create-lead :origens="{{ json_encode($origens) }}" />
                </div>

                <admin-list-leads :corretores="{{ json_encode($corretores) }}" :leads="{{ json_encode($leads) }}" />
            </div>
        </div>
    </div>
</div>

@endsection