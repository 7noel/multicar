<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
	protected $table = 'exchanges';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = ['fecha', 'venta', 'compra'];


}
