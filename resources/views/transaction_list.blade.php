@extends('layouts.app')

@section('title', 'Lista de transacciones')

@section('content')
<div class="container mt-5 justify-content-center">

    <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">PlaceToPay</h3>
                <h6 class="card-subtitle mb-2 text-center">Listado de transacciones</h6>
            </div>
            <div class="card-body">
                <a href="{{url('/')}}" class="btn btn-outline-primary">Volver</a>

                <table class="table table-responsive table-striped mt-3">
                    <thead class="table-dark">
                    <th>Transaction ID</th>
                    <th>Session ID</th>
                    <th>Transaction State</th>
                    <th>Response Code</th>
                    <th>Response Reason Text</th>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{!! $transaction->transactionID !!}</td>
                            <td>{!! $transaction->sessionID !!}</td>
                            <td>{!! $transaction->transactionState !!}</td>
                            <td>{!! $transaction->responseCode !!}</td>
                            <td>{!! $transaction->responseReasonText !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>
                                <h3 class="text-center text-danger">No hay registros
                                    disponibles</h3>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
</div>
@endsection
