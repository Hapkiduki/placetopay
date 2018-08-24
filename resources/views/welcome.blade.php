@extends('layouts.app')

@section('content')
<div class="container mt-5 justify-content-center">
    <h1 class="text-center">Sistema de transacciones PlaceToPay</h1>
    <form action="{{ url('transaction') }}" method="POST">
     @csrf
    <button class="btn btn-primary" type="submit">pinchame</button>
    </form>

    
<div class="form-group mt-3">
  <label for="bankInterface">Tipo de banco</label>
  <select name="bankInterface" required class="form-control col-md-2">
        <option value="">Seleccionar...</option>
        @foreach ($bankInterface as $key => $item)
            <option value="{{$key}}">{{ $item }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mt-3">
  <label for="documentType">Tipo de documento</label>
  <select name="documentType" required class="form-control col-md-2">
        <option value="">Seleccionar...</option>
        @foreach ($documentType as $key => $item)
            <option value="{{$key}}">{{ $item }}</option>
        @endforeach
    </select>
</div>
    
    
</div>
    
@endsection