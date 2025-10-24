@extends('layouts.user')

@section('page-name')
<li class="breadcrumb-item"><a href="/user" class="text-muted">Dashboard</a></li>
@endsection

@section('content')
<div>
    <corretor-index :origens="{{ json_encode($origens) }}" :planos="{{ json_encode($planos) }}"/>
</div>
@endsection