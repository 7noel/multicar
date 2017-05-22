<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	//protected $connection = 'masaki';
	protected $table = 'ordtra';
	protected $primaryKey = 'NroOrden';
	public $timestamps = false;
	protected $fillable = ['TipoOrden', 'CodCliente', 'NomCliente', 'RUC', 'DniExt', 'DNI', 'Direccion', 'Distrito', 'Provincia', 'Departam', 'Telefonos', 'Celular', 'Contacto1', 'Email', 'FecIngreso', 'received_at', 'approved_at', 'arrival_parts', 'programmed_at', 'delivered_at', 'status', 'rate', 'statusfull', 'invoice', 'Placa', 'Marca', 'Modelo', 'Version', 'Tipo', 'Color', 'Serie', 'NoMotor', 'TotRep', 'TotSer', 'TotPlanch', 'TotPintura', 'Total', 'Moneda', 'Atencion', 'Fecha1', 'Hora1', 'Usuario1', 'vendedor_id', 'vendedor_cod', 'vendedor', 'formapago', 'dato1'];

	
}
