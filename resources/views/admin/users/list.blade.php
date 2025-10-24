@extends('layouts.admin')

@section('content')

<div>
    <admin-list-users :usuarios="{{ json_encode($usuarios) }}" :gerentes="{{ json_encode($gerentes) }}" :supervisores="{{ json_encode($supervisores) }}"/>
</div>
@endsection