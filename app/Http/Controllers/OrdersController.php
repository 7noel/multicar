<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Order;
use App\Insurance;

class OrdersController extends Controller {


	public function __construct() {
		
	}

	public function reportOrder()
	{
		$tipos = [""=>'Seleccionar'] + Order::select('TipoOrden')->groupBy('TipoOrden')->pluck('TipoOrden', 'TipoOrden')->toArray();
		$status = [""=>'Seleccionar'] + Order::select('status')->where('status','!=','')->groupBy('status')->pluck('status', 'status')->toArray();
		$insurances = [""=>'Seleccionar'] + Insurance::select('RUC','Nombre')->groupBy('RUC','Nombre')->pluck('Nombre', 'RUC')->toArray();
		
		return view('orders.report', compact('tipos','status','insurances'));
	}

	public function ajaxReportOrder($date1, $date2, $tipo = null)
	{
		$cia = request('cia');
		$status = request('status');
		//dd($cia);
		$query = Order::query();
		if ($date1 <= $date2) {
			$query = $query->where('FecIngreso', '<=', $date2)->where('FecIngreso','>=',$date1);
			if ($tipo != null and $tipo != '') {
				$query = $query->where('TipoOrden', $tipo);
			}
			if ($cia != null and $cia != '') {
				$query = $query->where('CiaRUC', $cia);
			}
			if ($status != null and $status != '') {
				$query = $query->where('status', $status);
			}
			$result = $query->get();
		} else {
			$result = '';
		}
		
		return \Response::json($result);
	}
}
