<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
	//protected $connection = 'masaki';
	protected $table = 'seguros';
	protected $primaryKey = 'Registro';
	public $timestamps = false;
	protected $fillable = [];

	
}
