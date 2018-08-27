@extends('layouts.app')

@section('title', 'Transacción')

@section('content')
@if (session('status'))
    <ul class="alert alert-{!! session('status')[0] !!}">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('status')[1] }}
    </ul>
@endif
<div class="container mt-5 justify-content-center">
    <h2>Su transacción fúe {!! $responseReasonText !!} con un estado {!! $transactionState !!}</h2>
</div>
@endsection
