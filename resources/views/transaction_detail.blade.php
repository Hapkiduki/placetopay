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

    <div class="card mx-auto">
        <div class="card-header bg-primary text-white">
            <h3 class="text-center">PlaceToPay</h3>
            <h2 class="text-center text-white-30">Código de transacción <strong>{!! $transactionID !!}</strong></h2>
        </div>
        <div class="card-body">
            <h4 class="text-center">Su transacción fúe <strong>{!! $responseReasonText !!}</strong> con un estado <strong>{!! $transactionState !!}</strong></h4>
            <a href="/" class="btn btn-outline-primary">Volver</a>
        </div>
    </div>
</div>
@endsection
