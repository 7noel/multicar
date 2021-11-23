<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	//protected $connection = 'masaki';
	protected $table = 'ventas';
	protected $primaryKey = 'NroVenta';
	public $timestamps = false;
	protected $fillable = ['status_sunat', 'email', 'email1', 'email2', 'respuesta_sunat', 'respuesta_anulacion'];


}
