<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	//protected $connection = 'masaki';
	protected $table = 'ventas';
	protected $primaryKey = 'NroVenta';
	public $timestamps = false;
	protected $fillable = [];


}
