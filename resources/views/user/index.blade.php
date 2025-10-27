@extends('layouts.user')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')


<user-index :estados="{{ json_encode($estados) }}" :ultima_campanha="{{ json_encode($ultima_campanha) }}" />

@endsection