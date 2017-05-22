@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Reporte de Ordenes</div>
            	{!! Form::open(['url' => '#', 'class'=>'form-horizontal', 'id'=>'frmReportOrders']) !!}
            		<div class="form-group  form-group-sm">
						{!! Form::label('local','Local', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::select('local', ['ate'=>'ATE', 'comas'=>'COMAS'], null, ['class'=>'form-control', 'id'=>'lstLocal', 'required'=>'required']); !!}
						</div>
						{!! Form::label('tipo','Tipo OT', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::select('tipo', $tipos, 'SINIESTROS', ['class'=>'form-control', 'id'=>'lstTipos']); !!}
						</div>
					</div>
            		<div class="form-group  form-group-sm">
						{!! Form::label('date1','Desde', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::date('date1', \Carbon\Carbon::now(), ['class'=>'form-control', 'id'=>'date1', 'required'=>'required']); !!}
						</div>
						{!! Form::label('date2','Hasta', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::date('date2', \Carbon\Carbon::now(), ['class'=>'form-control', 'id'=>'date2', 'required'=>'required']); !!}
						</div>
					</div>
					<div class="form-group  form-group-sm">
						{!! Form::label('insurance','Seguro', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::select('insurance', $insurances, null, ['class'=>'form-control', 'id'=>'lstInsurances']); !!}
						</div>
						{!! Form::label('status','Status', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::select('status', $status, null, ['class'=>'form-control', 'id'=>'lstStatus']); !!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> GENERAR</button>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
        </div>

    </div>
				<table class="table table-condensed table-striped table-bordered">
					<thead>
						<tr>
							<th>OT</th>
							<th>F.Ingreso</th>
							<th>Placa</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Seguro</th>
							<th>F.Repuestos</th>
							<th>Cliente</th>
							<th>Status</th>
							<th>F.Estimada</th>
							<th>F.Entrega</th>
						</tr>
					</thead>
					<tbody id="tblOrders">
						
					</tbody>
				</table>
</div>
<template id="template-detail">
	<tr>
		<td data-ot>3902</td>
		<td data-f1>18/05/2017</td>
		<td data-placa>D1X-069</td>
		<td data-marca>CHEVROLET</td>
		<td data-modelo>SONIC</td>
		<td data-seguro>PACIFICO</td>
		<td data-f2>18/05/2017</td>
		<td data-cliente>MANZILLA MARCO</td>
		<td data-status>RECIBIDO</td>
		<td data-f3>18/05/2017</td>
		<td data-f4>18/05/2017</td>
	</tr>
</template>
@endsection
