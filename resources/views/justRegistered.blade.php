@extends('layouts.app')
@section('pageName', 'My Account')
@section('title', 'My Account')

@section('content')
    <h1>Thank you!</h1>

    <h2>{{ Auth::user()->name }}, you are now registered as an organiser.</h2>
    <h2>You'll soon be redirected to your account page.</h2>
    <h3>if not, click <a href="{{ url('/myAccount') }}">here</a></h3>

    <script>
        $(function(){
            try {
                localStorage.removeItem("liked-events");
            } catch (err) {
                console.log(err.message);
            }

            window.location.replace('{{ url('/myAccount') }}')
        })
    </script>

@endsection
