@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Bienvenido</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6 col-md-3">
                            <a href="/orders/report" class="thumbnail">VEHICULOS
                                <img src="/img/auto.jpg" alt="...">
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <a href="/invoices/report" class="thumbnail">FACTURACION
                                <img src="/img/billings.png" alt="...">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
