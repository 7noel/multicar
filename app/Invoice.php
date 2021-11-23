<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	protected $table = 'ventas';
	protected $primaryKey = 'NroVenta';
	public $timestamps = false;
	protected $fillable = ['CondPago', 'FechaVence', 'Dias', 'status_sunat', 'email', 'email1', 'email2', 'respuesta_sunat', 'respuesta_anulacion'];


}
