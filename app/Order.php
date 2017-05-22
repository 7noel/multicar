<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	//protected $connection = 'masaki';
	protected $table = 'ordtra';
	protected $primaryKey = 'NroOrden';
	public $timestamps = false;
	protected $fillable = [];

	
}
