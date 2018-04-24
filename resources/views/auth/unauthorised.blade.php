@extends('layouts.app')
@section('title', '401-Unauthorised')
@section('pageName', 'Unauthorised')
@section('content')
    <div class="text-center">
        <h1 class="warning-text">401-Unauthorised</h1>
        <p class="warning-text-description">You don't have the correct privileges to perform this action</p>
        <p class="warning-text-description">Please ensure you are logged in, or using an account with permission to
        perform this action</p>
    </div>
@endsection