@extends('layouts.app')

@section('content')
<div class="container">

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-custom">{{ $model->DctoVenta.' '.$model->Serie.'-'.$model->Numero }}</div>

				<div class="panel-body">

					{!! Form::model($model, ['route'=>[ 'invoices.update' , $model], 'method'=>'PUT', 'class'=>'form-horizontal']) !!}

					@if(Request::url() != URL::previous())
					<input type="hidden" name="last_page" value="{{ URL::previous() }}">
					@endif
					@if($model->status_sunat == 1 or $model->status_sunat == 2)
					<div class="form-group form-group-sm">
						<a href="{{ json_decode($model->respuesta_sunat)->enlace_del_pdf }}" class="btn btn-default"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Ver PDF</a>
						<a href="{{ json_decode($model->respuesta_sunat)->enlace_del_xml }}" class="btn btn-default"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Descargar XML</a>
					</div>
					@elseif(isset(json_decode($model->respuesta_sunat)->errors))
					<div class="form-group form-group-sm">
						<div class="col-sm-2">
							{!! Form::label('codigo', 'Codigo', ['class'=>'control-label']) !!}
							<p class="form-control-static bg-danger">{{ json_decode($model->respuesta_sunat)->codigo }}</p>
						</div>
						<div class="col-sm-10">
							{!! Form::label('errors', 'Error', ['class'=>'control-label']) !!}
							<p class="form-control-static bg-danger">{{ json_decode($model->respuesta_sunat)->errors }}</p>
						</div>
						
					</div>
					@elseif(isset(json_decode($model->respuesta_anulacion)->errors))
					<div class="form-group form-group-sm">
						<div class="col-sm-2">
							{!! Form::label('codigo', 'Codigo', ['class'=>'control-label']) !!}
							<p class="form-control-static bg-danger">{{ json_decode($model->respuesta_anulacion)->codigo }}</p>
						</div>
						<div class="col-sm-10">
							{!! Form::label('errors', 'Error', ['class'=>'control-label']) !!}
							<p class="form-control-static bg-danger">{{ json_decode($model->respuesta_anulacion)->errors }}</p>
						</div>
					</div>
					@endif
					<div class="form-group form-group-sm">
						<div class="col-sm-2">
							{!! Form::label('dni', $model->DniExt, ['class'=>'control-label']) !!}
							<p class="form-control-static">{{ $model->DNI }}</p>
						</div>
						<div class="col-sm-5">
							{!! Form::label('NomCliente','Cliente', ['class'=>'control-label']) !!}
							<p class="form-control-static">{{ $model->NomCliente }}</p>
						</div>
						<div class="col-sm-1">
							{!! Form::label('Orden', 'Orden', ['class'=>'control-label']) !!}
							<p class="form-control-static">{{ $model->Orden }}</p>
						</div>
						<div class="col-sm-2">
							{!! Form::label('Total','Total', ['class'=>'control-label']) !!}
							<p class="form-control-static">{{ $model->Moneda.' '.$model->Total }}</p>
						</div>
						<div class="col-sm-2">
							{!! Form::label('EstadoFactura','Estado', ['class'=>'control-label']) !!}
							<p class="form-control-static">{{ $model->EstadoFactura }}</p>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<div class="col-sm-3">
							{!! Form::label('status','STATUS SUNAT', ['class'=>'control-label']) !!}
							<p class="form-control-static bg-info text-center">{{ config('options.status_sunat.'.$model->status_sunat) }}</p>
						</div>
						@if($model->status_sunat == 0)
						<div class="col-sm-2">
						{!! Form::label('email','Email 1', ['class'=>'control-label']) !!}
							{!! Form::email('email', null, ['class'=>'form-control col-sm-2']) !!}
						</div>
						<div class="col-sm-2">
						{!! Form::label('email1','Email 2', ['class'=>'control-label']) !!}
							{!! Form::email('email1', null, ['class'=>'form-control col-sm-2']) !!}
						</div>
						<div class="col-sm-2">
						{!! Form::label('email2','Email 3', ['class'=>'control-label']) !!}
							{!! Form::email('email2', null, ['class'=>'form-control col-sm-2']) !!}
						</div>
						<label class="checkbox-inline">
							{!! Form::checkbox('send_sunat', '1') !!} Enviar a sunat
						</label>
						<label class="checkbox-inline">
							{!! Form::checkbox('send_email', '1', true) !!} Enviar Emails
						</label>
						@endif
						@if($model->status_sunat == 2)
						<label class="checkbox-inline">
							{!! Form::checkbox('anular_sunat', '1') !!} Anular
						</label>
						@endif
					</div>
					<div class="form-group form-group-sm">
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Actualizar</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
