@extends('layouts.user-connection')

@section('content')

<div id="myModal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header text-center">
                <img class="modal-content" id="qrcode" src="{{ asset('dist/img/loading.gif') }}" style="max-width:400px">
                <br />
                <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center">

                <div id="caption"></div>
            </div>
        </div>
    </div>
</div>

<br />
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Minhas conexões</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 25%">Nome</th>
                    <th style="width: 40px">Status</th>
                    <th style="width: 25%">Sessão</th>
                    <th style="width: 20%">Ações</th>
                    <th style="width: 10%">Reiniciar</th>
                    <th style="width: 10%">Teste</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="#">1.</td>
                    <td data-label="Nome" style="color: black">{{$container->nome}}</td>
                    <td data-label="Status">
                        <connection-status :my_container="{{ json_encode($container) }}" :usuario="{{json_encode($usuario)}}" />
                    </td>
                    <td>
                        <qr-code :my_container="{{ json_encode($container) }}" :usuario="{{json_encode($usuario)}}" />
                    </td>
                    <td>
                        <delete-token :my_container="{{ json_encode($container) }}" :usuario="{{json_encode($usuario)}}" />
                    </td>
                    <td>
                        <restart-connection :my_container="{{ json_encode($container) }}" :usuario="{{json_encode($usuario)}}" />
                    </td>
                    <td>
                        <enviar-teste :container="{{ json_encode($container) }}" :usuario="{{json_encode($usuario)}}" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

@endsection