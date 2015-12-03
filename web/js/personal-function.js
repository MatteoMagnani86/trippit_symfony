
//attraverso il metodo Ajax viene richiamato dinamicamente un file esterno
function richiamaDati(path,data,classe){
	$.ajax({
		type: "POST", 									//tipo di chiamata
		url: path, 										//URL della risorsa da contattare
		data: data, 									//dati da fornire alla risorsa remota
		dataType: "html", 								//definizone del formato della risposta
		success: function(response){ 					//azione da effettuare in caso di successo
			$(classe).html(response);
		},
		error: function(){ 								//azione da effettuare in caso di fallimento (errori 400,500)
			alert("Non tutti i dati sono stati caricati.");
		}
	});
}