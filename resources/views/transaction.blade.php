@extends('layouts.app')

@section('content')
<div class="container mt-5 justify-content-center">

    @if (session('status'))
    <ul class="alert alert-{!! session('status')[0] !!}">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('status')[1] }}
    </ul>
    @endif

    <div class="card mx-auto">
        <div class="card-body">
            <h5 class="text-center card-title">Sistema de transacciones PlaceToPay</h5>
            <h6 class="card-subtitle mb-2 text-muted text-center">Por favor diligencie <strong>todos</strong> los campos</h6>
            <form action="{{ url('transaction') }}" method="POST" class="form-horizontal">
                @csrf

                <div class="col form-inline">
                    <div class="form-group mt-3 col-6">
                        <label for="bankInterface" class="col-md-3">Tipo de cuenta</label>
                        <select name="bankInterface" required class="form-control col-md-4">
                            <option value="">Seleccionar...</option>
                            @foreach ($bankInterface as $key => $item)
                            <option value="{{$key}}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group mt-3 col-md-6">
                        <label for="bankCode" class="col-md-2">Banco</label>
                        <select name="bankCode" required class="form-control col-md-8">
                            @foreach ($banks as $key => $item)
                            <option value="{{$key}}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col form-inline">
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Tipo de documento</label>
                        <select name="payer[documentType]" required class="form-control col-md-6">
                            <option value="">Seleccionar...</option>
                            @foreach ($documentType as $key => $item)
                            <option value="{{$key}}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="documentType" class="col-md-4">Numero de documento</label>
                        <input type="text" name="payer[document]" class="form-control col-md-8" required>
                    </div>
                </div>
                <div class="col form-inline">
                    <div class="form-group mt-3 col-md-6">
                      <label for="documentType" class="col-md-3">Nombre(s)</label>
                      <input type="text" name="payer[firstName]" class="form-control" required>
                    </div>
                    <div class="form-group mt-3 col-md-6">
                      <label for="documentType" class="col-md-3">Apellidos</label>
                      <input type="text" name="payer[lastName]" class="form-control" required>
                    </div>
                </div>
                <div class="col form-inline">
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Correo Electrónico</label>
                        <input type="email" name="payer[emailAddress]" class="form-control" required>
                    </div>
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Dirección</label>
                        <input type="text" name="payer[address]" class="form-control" required>
                    </div>
                </div>
                <div class="col form-inline">
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Departamento</label>
                        <input type="text" name="payer[province]" class="form-control" required>
                    </div>
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Ciudad</label>
                        <input type="text" name="payer[city]" class="form-control" required>
                    </div>
                </div>
                <div class="col form-inline">
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Número Teléfono</label>
                        <input type="tel" name="payer[phone]" class="form-control" required>
                    </div>
                    <div class="form-group mt-3 col-md-6">
                        <label for="documentType" class="col-md-3">Número Celular</label>
                        <input type="tel" name="payer[mobile]" class="form-control" required>
                    </div>
                </div>

                <div class="form-group mt-2">

                    <button class="btn btn-primary float-right m-1" type="submit">Pagar con PSE</button>
                    <a href="{{ url('resultTransaction') }}"
                        class="card-link btn btn-outline-success float-right m-1">
                        Ver Transacciones
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
