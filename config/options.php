<?php

return [
	'sunat' => [
		'tipo_de_comprobante' => [
			'FACTURA' => 1,
			'BOLETA' => 2,
			'NOTA DE CREDITO' => 3,
			'NOTA DE DEBITO' => 4,
		],
		'cliente_tipo_de_documento' => [
			'' => 1,
			'RUC' => 6,
			'DNI' => 1,
			'CEX' => 4,
			'PAS' => 7,
		],
		'moneda' => [
			'S/.' => 1,
			'US$' => 2,
		],
	],
	'status_sunat' => ['SIN ENVIAR', 'ENVIADO', 'ACEPTADO', 'RECHAZADO', 'ANULACION ENVIADA', 'ANULACION ACEPTADA', 'ANULACION RECHAZADA']
];