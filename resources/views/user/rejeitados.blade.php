@extends('layouts.user')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')
<div>
    <leads-rejeitados :leads="{{ json_encode($leads) }}" />
</div>
@endsection