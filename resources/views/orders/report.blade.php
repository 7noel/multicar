@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reporte de Ordenes</div>
            	{!! Form::open(['url' => '#', 'class'=>'form-horizontal']) !!}
            		<div class="form-group  form-group-sm">
						{!! Form::label('local','Local', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::select('local', ['ate'=>'ATE', 'comas'=>'COMAS'], null, ['class'=>'form-control', 'id'=>'lstLocal']); !!}
						</div>
						{!! Form::label('tipo','Tipo OT', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::select('tipo', $tipos, 'SINIESTROS', ['class'=>'form-control', 'id'=>'lstTipos']); !!}
						</div>
					</div>
            		<div class="form-group  form-group-sm">
						{!! Form::label('date1','Desde', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::date('date1', \Carbon\Carbon::now(), ['class'=>'form-control', 'id'=>'date1']); !!}
						</div>
						{!! Form::label('date2','Hasta', ['class'=>'col-sm-2 control-label']) !!}
						<div class="col-sm-4">
						{!! Form::date('date2', \Carbon\Carbon::now(), ['class'=>'form-control', 'id'=>'date2']); !!}
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
</div>
@endsection
