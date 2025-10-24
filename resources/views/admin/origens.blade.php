@extends('layouts.admin')

@section('content')

<div style="text-align: center;">
    <h2>Origens</h2>
</div>

<div>
    <nova-origem />
</div>

<div>
    <listar-origens :origens="{{ json_encode($origens) }}" />
</div>

@endsection