$(document).ready(function () {
	$('.uppercase').change(function(){
		var cadena=$(this).val();
		cadena = cadena.replace("  "," ");
		cadena = cadena.toUpperCase();
		$(this).val(cadena);
	});

	//consultar ordenes
	$('#frmReportOrders').submit(function(e){
		e.preventDefault();
		var local=$('#lstLocal').val();
		var tipo=$('#lstTipos').val();
		var date1=$('#date1').val();
		var date2=$('#date2').val();
		var cia=$('#lstInsurances').val();
		var status=$('#lstStatus').val();
		loadOrders(local,date1,date2,tipo,cia,status);
	});
	$('#frmReportInvoices').submit(function(e){
		e.preventDefault();
		var local=$('#lstLocal').val();
		var date1=$('#date1').val();
		var date2=$('#date2').val();
		var status=$('#lstStatus').val();
		loadInvoices(local,date1,date2,status);
	});
});

function loadOrders(local,date1,date2,tipo,cia,status) {
	$("#tblOrders").empty();
	var page = "/orders/report/ajax/" + date1 + "/" +date2 + "/" + tipo + "?cia=" + cia + "&status=" + status;
	if (local == "comas") {
		page = "/orders/report/ajax/" + date1 + "/" +date2 + "/" + tipo + "?cia=" + cia + "&status=" + status;
	}
	$.get(page, function (data) {
		//console.log(data);
		$.each(data, function (index, ot) {
 			//console.log(ot.NroOrden+"-")
 			renderTemplateDetailOrders(ot);
 		});
	});
}

function loadInvoices(local,date1,date2,status) {
	$("#tblInvoices").empty();
	var page = "/invoices/report/ajax/" + date1 + "/" +date2 + "/" + status;
	if (local == "comas") {
		page = "/invoices/report/ajax/" + date1 + "/" +date2 + "/" + status;
	}
	$.get(page, function (data) {
		//console.log(data);
		$.each(data, function (index, v) {
 			console.log(v)
 			renderTemplateDetailInvoices(v);
 		});
	});
}

function activateTemplate (id) {
	var t = document.querySelector(id);
	return document.importNode(t.content, true);
}

function renderTemplateDetailOrders (data) {
	var clone = activateTemplate("#template-detail");
 	clone.querySelector("[data-ot]").innerHTML = data.NroOrden;
 	clone.querySelector("[data-f1]").innerHTML = fecha(data.FecIngreso);
 	clone.querySelector("[data-placa]").innerHTML = data.Placa;
 	clone.querySelector("[data-marca]").innerHTML = data.Marca;
 	clone.querySelector("[data-modelo]").innerHTML = data.Modelo;
 	clone.querySelector("[data-seguro]").innerHTML = data.CiaSeguros;
 	clone.querySelector("[data-f2]").innerHTML = fecha(data.approved_at);
 	clone.querySelector("[data-f3]").innerHTML = fecha(data.arrival_parts);
 	clone.querySelector("[data-cliente]").innerHTML = data.NomCliente;
 	clone.querySelector("[data-status]").innerHTML = data.statusfull;
 	clone.querySelector("[data-f4]").innerHTML = fecha(data.programmed_at);
 	clone.querySelector("[data-f5]").innerHTML = fecha(data.delivered_at);
	//$("#tblOrders").empty();
	$("#tblOrders").append(clone);
}
function renderTemplateDetailInvoices (data) {
	var clone = activateTemplate("#template-detail");
	clone.querySelector("[data-nroventa]").innerHTML = '<a target="_blank" href="/invoices/'+data.NroVenta+'/edit">'+data.NroVenta+'</a>';
 	clone.querySelector("[data-f1]").innerHTML = fecha(data.Fecha);
 	clone.querySelector("[data-doc]").innerHTML = data.DctoVenta + " " + data.Serie + "-" + data.Numero;
 	clone.querySelector("[data-total]").innerHTML = data.Moneda+" "+data.Total;
 	clone.querySelector("[data-ot]").innerHTML = data.NroOrden;
 	clone.querySelector("[data-placa]").innerHTML = data.Placa;
 	clone.querySelector("[data-marca]").innerHTML = data.Marca;
 	clone.querySelector("[data-modelo]").innerHTML = data.Modelo;
 	clone.querySelector("[data-cliente]").innerHTML = data.NomCliente;
 	clone.querySelector("[data-status]").innerHTML = data.EstadoFactura;
	//$("#tblOrders").empty();
	$("#tblInvoices").append(clone);
}
function fecha(input) {
    var ptrn = /(\d{4})\-(\d{2})\-(\d{2})/;
    if(!input || !input.match(ptrn)) {
        return null;
    }
    return input.replace(ptrn, '$2/$3/$1');
};