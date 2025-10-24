@extends('layouts.admin')

@section('page-name')
<li class="breadcrumb-item"><a href="/admin/turnos" class="text-muted">Turnos</a></li>
@endsection

@section('content')


<div>
    <admin-list-turnos :turnos="{{ json_encode($turnos) }}" :supervisores="{{ json_encode($supervisores) }}" />
</div>

@endsection