$('select').select2({
	placeholder: "Seleccione una opción",
	language: {
		noResults: function () {
			return "No hay resultados encontrados!";
		}
	}
});
