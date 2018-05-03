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
		$status = [""=>"Seleccionar", "RECIBIDO"=>"RECIBIDO", "APROBADO"=>"APROBADO", "DESMONTAJE"=>"DESMONTAJE", "PLANCHADO"=>"PLANCHADO", "PINTURA"=>"PINTURA", "ARMADO"=>"ARMADO", "MECANICA"=>"MECANICA", "ENTREGADO"=>"ENTREGADO"];
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


    public function exportExcel()
    {
    	set_time_limit(0);
    	$productos = \DB::table('store_month')
    		->where('Fecha', '2016-01-01')
    		->orderBy('Almacen', 'ASC')
    		->orderBy('CodInterno', 'ASC')
    		->get();

    	$kardex = \DB::table('v_kardex')
    		->where('Fecha', '<=','2016-12-31')
    		->where('Fecha', '>=','2016-01-01')
    		->orderBy('Fecha', 'ASC')
    		->get();

    	$und['1 LT'] = '08';
		$und['1/4G'] = '99 (1/4G)';
		$und['1/8G'] = '99 (1/8G)';
		$und['1GLN'] = '09';
		$und['1LT'] = '08';
		$und['5GAL'] = '99 (5GAL)';
		$und['GAL'] = '09';
		$und['GR'] = '06';
		$und['JG01'] = '99 (JG01)';
		$und['JGO2'] = '99 (JGO2)';
		$und['JGO3'] = '99 (JGO3)';
		$und['JGO4'] = '99 (JGO4)';
		$und['KG'] = '01';
		$und['ML'] = '13';
		$und['MM'] = '99 (MM)';
		$und['MT.'] = '15';
		$und['PZA'] = '07';
		$und['UND'] = '07';
		$und['X KG'] = '01';
		$und['X MT'] = '15';
		$und['COMB'] = '99 COMB';
		$und['J47'] = '99 J47';
		$und['PLG'] = '99 PLG';
		$und['PQT'] = '99 PQT';

		foreach ($productos as $key => $value) {
			$value = (array) $value;
			$alm = $value['Almacen'];
			$cod = $value['CodInterno'];
			$arrayProducto[$alm][$cod] = $value;
		}
		//dd($arrayProducto[1]['01463S5DA00']);
    	foreach ($kardex as $key => $valor) {
    		$valor = (array) $valor;
			$arrayKardex[$valor['Almacen']][$valor['Codigo']][] = $valor;
		}
		//dd(count($arrayKardex[9]['WLHP100']));
		$data = [];
		foreach ($arrayProducto as $keyAlm => $arrayProducto2) {
			foreach ($arrayProducto2 as $keyCod => $producto) {
				//dd($producto);
				$fecha = explode('-', $producto['Fecha']);
				$row['KPERIODO'] = $fecha[0].$fecha[1].'00';
				$row['KANEXO'] = '00010'.$producto['Almacen'];
				$row['KCATALOGO'] = '9';
				$row['KTIPEXIST'] = '02';
				$row['KCODEXIST'] = $producto['CodInterno'];
				$row['KFECDOC'] = '01/01/2016';
				$row['KTIPODOC'] = '00';
				$row['KSERDOC'] = '00';
				$row['KNUMDOC'] = '00';
				$row['KTIPOPE'] = '16';
				$row['KDESEXIST'] = $producto['Descripcion'];
				$row['KUNIMED'] = $und[$producto['Unidad']];
				$row['KMETVAL'] = '1';
				$row['KUNIING'] = '0.00';
				$row['KCOSING'] = '0.00';
				$row['KTOTING'] = '0.00';
				$row['KUNIRET'] = '0.00';
				$row['KCOSRET'] = '0.00';
				$row['KTOTRET'] = '0.00';
				$row['KSALFIN'] = number_format($producto['Stock'],2);
				$row['KCOSFIN'] = number_format($producto['ValorPromedio'],2);
				$row['KTOTFIN'] = number_format($producto['Stock']*$producto['ValorPromedio'],2);
				$row['KESTOPE'] = '1';
				$row['KINTDIAMAY'] = '';
				$row['KINTVTACOM'] = '';
				$row['KINTREG'] = '';
				$data[] = $row;
				$vfinal =$producto['ValorPromedio'];
		        $saldo = $producto['Stock'];
				if (isset($arrayKardex[$keyAlm][$keyCod])) {
					foreach ($arrayKardex[$keyAlm][$keyCod] as $key2 => $kardex) {
						switch ($kardex['Documento']) {
							case 'FACTURA':
								$tipo='01';
								break;
							case 'BOLETA':
								$tipo='03';
								break;
							case 'NOTA DE CREDITO':
								$tipo='07';
								break;
							case 'NOTA DE DEBITO':
								$tipo='08';
								break;
							case 'GUIA':
								$tipo='09';
								break;				
							default:
								$tipo='00';
								break;
						}
						if ($kardex['Entrada'] > 0) {
							if ($kardex['Documento']=='TRANSFERENCIA') {
								$tipoOp = '11';
							} elseif ($kardex['Documento']=='NOTA DE CREDITO') {
								$tipoOp = '05';
							} else{
								$tipoOp = '02';
							}
						}
						if ($kardex['Salida'] > 0) {
							if ($kardex['Documento']=='TRANSFERENCIA') {
								$tipoOp = '11';
							} elseif ($kardex['Documento']=='NOTA DE CREDITO') {
								$tipoOp = '06';
							} elseif ($kardex['Documento']=='ORDEN') {
								$tipoOp = '10';
							} else{
								$tipoOp = '01';
							}
						}
						//calcula valor de compra
				        if ($vfinal<0) {
			        		$vfinal = $vfinal*(-1);
			        	}
				        $vmov=$vfinal;
				        if (!isset($kardex['TipoCambio']) or $kardex['TipoCambio']==0) {
				        	$kardex['TipoCambio'] = 3.25;
				        }
				        if ($kardex['Entrada']>0 && $kardex['Documento']=='FACTURA' && $kardex['ValorUnitario']>0) {
				        	if ($kardex['Moneda']=='S/.') {
				        		$vmov = $kardex['ValorUnitario'];
				        	}
				        	if ($kardex['Moneda']=='US$' && $kardex['TipoCambio'] > 0) {
				        		$vmov = $kardex['ValorUnitario']*$kardex['TipoCambio'];
				        	}
				        	if ($vmov<0) {
				        		$vmov = $vmov*(-1);
				        	}
				        }
				        if ($vmov<0) {
			        		$vmov = $vmov*(-1);
			        	}

			        	$fecha = explode('-', $kardex['Fecha']);
						$row['KPERIODO'] = $fecha[0].$fecha[1].'00';
						$row['KFECDOC'] = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
						$row['KTIPODOC'] = $tipo;
						$row['KSERDOC'] = $kardex['Serie'];
						$row['KNUMDOC'] = $kardex['Numero'];
						$row['KTIPOPE'] = $tipoOp;
						$row['KUNIING'] = '0.00';
						$row['KCOSING'] = '0.00';
						$row['KTOTING'] = '0.00';
						$row['KUNIRET'] = '0.00';
						$row['KCOSRET'] = '0.00';
						$row['KTOTRET'] = '0.00';
						
						//entradas
				        if ($kardex['Entrada'] > 0) {
							$row['KUNIING'] = number_format($kardex['Entrada'],2);
							$row['KCOSING'] = number_format($vmov,2);
							$row['KTOTING'] = number_format(($kardex['Entrada']*$vmov),2);
				        }
				        
				        //salidas
				        if ($kardex['Salida'] > 0) {
							$row['KUNIRET'] = number_format($kardex['Salida'],2);
							$row['KCOSRET'] = number_format($vmov,2);
							$row['KTOTRET'] = number_format(($kardex['Salida']*$vmov),2);
				        }
				        //saldo final
				        $saldox = $saldo + $kardex['Entrada'] - $kardex['Salida'];
				        //$saldox = round($saldox,2);
				        if ($saldox > 0 and $saldo > 0 and $kardex['Entrada']>0) {
					        $vfinal = (($vfinal*$saldo) + ($vmov*$kardex['Entrada'])) / ($saldox);
				        } else{
				        	$vfinal = $vmov;
				        }
				        if ($saldox==0) {
				        	$vfinal = 0;
				        }
				        //$vfinal=round($vfinal,2);
				        $saldo = $saldox;

						$row['KSALFIN'] = number_format($saldo,2);
						$row['KCOSFIN'] = number_format($vfinal,2);
						$row['KTOTFIN'] = number_format(($saldox * $vfinal),2);
						$row['KINTDIAMAY'] = '';
						$row['KINTVTACOM'] = '';
						$row['KINTREG'] = '';
						$data[] = $row;
					}
				}
			}
		}


		\Excel::create('Filename', function($excel) use($data){
			$excel->sheet('Sheetname', function($sheet) use($data) {
			$sheet->fromArray($data);
		    });
		})->export('xlsx');
    }

}