<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Invoice;

class InvoicesController extends Controller {


	public function __construct() {
		
	}

	public function edit($id)
	{
		$model = Invoice::find($id);
		return view('invoices.edit', compact('model'));
	}

	public function update($id)
	{
		$data = \Request::all();
		$model = Invoice::find($id);
		// $rp = $this->consultarCpe($model);
		// dd(strlen($rp));
		// dd(json_decode($rp));
		$model->fill($data);
		if (isset($data['send_sunat'])) {
			$send_email = (isset($data['send_email'])) ? true : false;
			$respuesta = $this->generarComprobante($model, $send_email);
			$model->respuesta_sunat = $respuesta;
			$respuesta = json_decode($respuesta);
			if(isset($respuesta->aceptada_por_sunat)) {
				if ($respuesta->aceptada_por_sunat == true) {
					$model->status_sunat = 2;
				} else {
					$model->status_sunat = 1;
				}
			} else {
				$model->status_sunat = 0;
			}
		} elseif (isset($data['anular_sunat'])) {
			$respuesta = $this->generarAnulacion($model);
			$model->respuesta_anulacion = $respuesta;
			$respuesta = json_decode($respuesta);
			// dd($respuesta);
			if (isset($respuesta)) {
				$model->status_sunat = 5;
			} else {
				$model->status_sunat = 4;
			}
		} else {
			$respuesta = $this->consultarCpe($model);
			$model->respuesta_sunat = $respuesta;
			$respuesta = json_decode($respuesta);
			if(isset($respuesta->aceptada_por_sunat)) {
				if ($respuesta->aceptada_por_sunat == true) {
					$model->status_sunat = 2;
				} else {
					$model->status_sunat = 1;
				}
			}
		}
		$model->save();

		// dd($data);
		// $data['status_sunat'] = 1;
		// $model = Invoice::updateOrCreate(['NroVenta' => $id], $data);
		// dd($model);
		return redirect()->route('invoices.edit', ['id' => $id]);
	}

	public function reportInvoice()
	{
		$status = [""=>"Cualquiera", "ANULADO"=>"ANULADO", "PENDIENTE"=>"PENDIENTE", "CANCELADO"=>"CANCELADO"];
		
		return view('invoices.report', compact('status'));
	}

	public function ajaxReportInvoice($date1, $date2, $sunat, $status = null)
	{
		$query = Invoice::query();
		
		if ($date1 <= $date2) {
			$query = $query->where('Fecha', '<=', $date2)->where('Fecha','>=',$date1);
			if ($status != null and $status != '') {
				$query = $query->where('EstadoFactura', $status);
			}
			if ($sunat >= 0) {
				$query = $query->where('status_sunat', $sunat);
			}
			$result = $query->orderBy('Fecha', 'desc')->get();
		} else {
			$result = '';
		}
		
		return \Response::json($result);
	}

	public function consultarCpe($model, $anulacion = 0)
	{
		$data = [
			"operacion" => ($anulacion == 0) ? "consultar_comprobante" : "consultar_anulacion",
			"tipo_de_comprobante" => config('options.sunat.tipo_de_comprobante.'.trim($model->DctoVenta)),
			"serie" => $model->Serie,
			"numero" => $model->Numero
		];
		// $data = json_encode($data);
		$respuesta = $this->send($data);
		// dd(json_decode($respuesta));
		return $respuesta;

	}

	public function generarAnulacion($model)
	{
		$data = [
			"operacion" => "generar_anulacion",
			"tipo_de_comprobante" => config('options.sunat.tipo_de_comprobante.'.trim($model->DctoVenta)),
			"serie" => $model->Serie,
			"numero" => $model->Numero,
			"motivo" => "ERROR DEL SISTEMA",
			"codigo_unico"=>""
		];
		// dd($data);
		// $data = json_encode($data);
		$respuesta = $this->send($data);
		return $respuesta;
	}

	/**
	 * Genera Comprobante Electrónico
	 * @param  Proof $model Comprobante de Pago
	 * @return html        Retorna Respuesta
	 */
	public function generarComprobante($model, $send_email)
	{
		$data = $this->prepareCpe($model, $send_email);
		$respuesta = $this->send($data);
		return $respuesta; 
		//dd($respuesta);
		//$this->readRespuesta($respuesta);
	}

	/**
	 * Prepara el json a enviar a nubefact
	 * @param  Proof $model Comprobante de pago
	 * @return Array        array lista para ser formateada y enviada en formato json
	 */
	public function prepareCpe($model, $send_email=0)
	{
		$data = array(
		    "operacion"				=> "generar_comprobante",
		    "tipo_de_comprobante"               => config('options.sunat.tipo_de_comprobante.'.trim($model->DctoVenta)),
		    "serie"                             => $model->Serie,
		    "numero"				=> $model->Numero,
		    "sunat_transaction"			=> "1",
		    "cliente_tipo_de_documento"		=> config('options.sunat.cliente_tipo_de_documento.'.$model->DniExt),
		    "cliente_numero_de_documento"	=> trim($model->DNI),
		    "cliente_denominacion"              => $model->NomCliente,
		    "cliente_direccion"                 => $model->Direccion,
		    "cliente_email"                     => $model->email,
		    "cliente_email_1"                   => $model->email1,
		    "cliente_email_2"                   => $model->email2,
		    "fecha_de_emision"                  => date('d-m-Y', strtotime($model->Fecha)),
		    // "fecha_de_emision"                  => date('d-m-Y'),
		    "fecha_de_vencimiento"              => "",
		    "moneda"                            => config('options.sunat.moneda')[$model->Moneda],
		    "tipo_de_cambio"                    => $model->TipoCambio,
		    "porcentaje_de_igv"                 => "18.00",
		    "descuento_global"                  => abs($model->TotalDscto),
		    "total_descuento"                   => "",
		    "total_anticipo"                    => "",
		    "total_gravada"                     => abs($model->SubTotal),
		    "total_inafecta"                    => "",
		    "total_exonerada"                   => "",
		    "total_igv"                         => abs($model->TotIGV),
		    "total_gratuita"                    => "",
		    "total_otros_cargos"                => "",
		    "total"                             => abs($model->Total),
		    "percepcion_tipo"                   => "",
		    "percepcion_base_imponible"         => "",
		    "total_percepcion"                  => "",
		    "total_incluido_percepcion"         => "",
		    "detraccion"                        => "false",
		    "observaciones"                     => "",
		    "documento_que_se_modifica_tipo"    => ($model->reference_id != 0) ? config('options.sunat.tipo_de_comprobante.'.trim($model->DctoReferencia)) : "",
		    "documento_que_se_modifica_serie"   => ($model->reference_id != 0) ? $model->SerieReferencia : "",
		    "documento_que_se_modifica_numero"  => ($model->reference_id != 0) ? $model->NumeroReferencia : "",
		    "tipo_de_nota_de_credito"           => ($model->tipo_nc != 0) ? $model->tipo_nc : "",
		    "tipo_de_nota_de_debito"            => ($model->tipo_nd != 0) ? $model->tipo_nd : "",
		    "enviar_automaticamente_a_la_sunat" => "true",
		    "enviar_automaticamente_al_cliente" => ($send_email) ? "true" : "false",
		    "codigo_unico"                      => "",
		    "condiciones_de_pago"               => $model->CondPago,
		    "medio_de_pago"                     => "",
		    "placa_vehiculo"                    => $model->Placa,
		    "orden_compra_servicio"             => "",
		    "tabla_personalizada_codigo"        => "",
		    "formato_de_pdf"                    => "",
		);
		if (trim($model->Siniestro) != '' and trim($model->Poliza) != '' and trim($model->Franquicia) != '') {
  			$data['observaciones'] = 'SINIESTRO:'.$model->Siniestro.'//POLIZA:'.$model->Poliza.'//FRANQUICIA:'.$model->Moneda.' '.$model->Franquicia.'+IGV';
		} else {
			$ot = \DB::select("select * from ordtra where NroOrden = :id", ['id' => $model->NroOrden]);
			if (count($ot)>0) {
				$ot = $ot[0];
				$data['observaciones'] = 'OT: ' . $ot->NroOTProv . ' OC:' . $ot->NroOCProv;
			}
		}
		$data['observaciones'] .= (trim($model->NroOrden)=="") ? '' : "<br><b>ORDEN DE TRABAJO: </b>".$model->NroOrden;
		$data['observaciones'] .= (trim($model->Marca)=="") ? '' : "<br><b>MARCA: </b>".$model->Marca;
		$data['observaciones'] .= (trim($model->Modelo)=="") ? '' : "<br><b>MODELO: </b>".$model->Modelo;
		// $data['observaciones'] .= (trim($model->NroMotor)=="") ? '' : "<br><b>N° MOTOR: </b>".$model->NroMotor;
		// $data['observaciones'] .= (trim($model->Color)=="") ? '' : "<br><b>COLOR: </b>".$model->Color;
		// $data['observaciones'] .= (trim($model->Kilometraje)==0) ? '' : "<br><b>KILOMETRAJE: </b>".$model->Kilometraje;
		// $data['observaciones'] .= (trim($model->SerieMotor)=="") ? '' : "<br><b>NUMERO DE SERIE: </b>".$model->SerieMotor;

		$details = \DB::select('select * from iteventa where NroVenta = :id', ['id' => $model->NroVenta]);
		foreach ($details as $key => $detail) {
			$subtotal = abs($detail->Cantidad*$detail->PrecUnitario)-abs($detail->PrecDscto);
			$total = round(abs($subtotal)*1.18, 2);
			$igv = abs($total) - abs($subtotal);
			$data['items'][] = array(
				"unidad_de_medida"          => 'NIU',
				"codigo"                    => 'REPUESTOS',
				"descripcion"               => $detail->NomMaterial,
				"cantidad"                  => abs($detail->Cantidad),
				"valor_unitario"            => abs($detail->PrecUnitario),
				"precio_unitario"           => round(abs($detail->PrecUnitario)*1.18, 2),
				"descuento"                 => abs($detail->PrecDscto),
				"subtotal"                  => abs($detail->PrecTotal),
				"tipo_de_igv"               => '1',
				"igv"                       => $igv,
				"total"                     => $total,
				"anticipo_regularizacion"   => "false",
				"anticipo_documento_serie"  => "",
				"anticipo_documento_numero" => ""
			);
		}
		$details = \DB::select('select * from itevents where NroVenta = :id order by Familia', ['id' => $model->NroVenta]);
		foreach ($details as $key => $detail) {
			$subtotal = abs($detail->Cantidad*$detail->PrecUnitario)-abs($detail->PrecDscto);
			$total = round(abs($subtotal)*1.18, 2);
			$igv = abs($total) - abs($subtotal);
			$data['items'][] = array(
				"unidad_de_medida"          => 'ZZ',
				"codigo"                    => (strlen($detail->Familia)>9) ? '' : $detail->Familia,
				"codigo_producto_sunat"		=> "78181501",
				"descripcion"               => (strlen($detail->Familia)>9) ? $detail->Familia.' '.$detail->NomMaterial : $detail->NomMaterial,
				"cantidad"                  => abs($detail->Cantidad),
				"valor_unitario"            => abs($detail->PrecUnitario),
				"precio_unitario"           => round(abs($detail->PrecUnitario)*1.18, 2),
				"descuento"                 => abs($detail->PrecDscto),
				"subtotal"                  => $subtotal,
				"tipo_de_igv"               => '1',
				"igv"                       => $igv,
				"total"                     => $total,
				"anticipo_regularizacion"   => "false",
				"anticipo_documento_serie"  => "",
				"anticipo_documento_numero" => ""
			);
		}
		// dd($data);
		return $data;
		
	}

	/**
	 * Envía data json a nubefact
	 * @param  Array $data data lista para ser enviada
	 * @return Json            Respuesta de Nubefact
	 */
	public function send($data)
	{
		$data_json = json_encode($data);
		// RUTA para enviar documentos
		$ruta = env('NUBEFACT_RUTA');

		//TOKEN para enviar documentos
		$token = env('NUBEFACT_TOKEN');

		//Invocamos el servicio de NUBEFACT
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Token token="'.$token.'"',
			'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);

		return $respuesta;
	}

}
