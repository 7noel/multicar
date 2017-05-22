<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Invoice;

class InvoicesController extends Controller {


	public function __construct() {
		
	}

	public function reportInvoice()
	{
		$status = [""=>"Seleccionar", "ANULADO"=>"ANULADO", "PENDIENTE"=>"PENDIENTE", "CANCELADO"=>"CANCELADO"];
		
		return view('invoices.report', compact('status'));
	}

	public function ajaxReportInvoice($date1, $date2, $status = null)
	{
		$query = Invoice::query();
		if ($date1 <= $date2) {
			$query = $query->where('Fecha', '<=', $date2)->where('Fecha','>=',$date1);
			if ($status != null and $status != '') {
				$query = $query->where('EstadoFactura', $status);
			}
			$result = $query->get();
		} else {
			$result = '';
		}
		
		return \Response::json($result);
	}
}
