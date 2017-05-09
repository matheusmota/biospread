//Create a new SPARQL client object that will use the Space data SPARQL endpoint 
var sparqler;

var defaultGrap = "http://purl.org/biospread/";

function ajaxQuery(q) {
	return $.ajax({
		url : "http://sparql.lis.ic.unicamp.br/?default-graph-uri=http%3A%2F%2Fpurl.org%2Fbiospread%2F&query=" + encodeURIComponent(q)
				+ "&format=json&callback=?",
		dataType : "jsonp"
	});
}

function resetInfoPart() {

	if (map != undefined) {
		map.destroy();
	}

	$("#speciesinfoblock").hide();
}

function loadOptionsFor(queryString, htmlID) {

	ajaxQuery(queryString).done(function(data) {
		// data = $.parseJSON(data); // For debugging only

		if (data.results.bindings.lenght == 0) {

			// s.options[s.options.length] = new Option("No data found",
			// " No data found");

		} else {

			$.each(data.results.bindings, function(i, item) {
				var s = document.getElementById(htmlID);
				s.options[s.options.length] = new Option(item.label.value + "", item.uri.value + "");

			});
		}

	});
}

var bgraph = new BGraph();
var prefix = bgraph.getPrefixes();

var kingdomId = "kingdom";
var phylumId = "phylum";
var classId = "class";
var orderId = "order";
var familyId = "family";
var genusId = "genus";
var speciesId = "species";
var kingomQuery = prefix
		+ " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where { ?uri a geospecies:KingdomConcept . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')  }";

var speciesUri;

$("#" + phylumId + "").each(function() {
	$(this).find('option').remove();
	$(this).append('<option disabled="true" value="default" selected>Select a phylum</option>');
});
$("#" + classId + "").each(function() {
	$(this).find('option').remove();
	$(this).append('<option disabled="true" value="default" selected>Select a class</option>');
});
$("#" + orderId + "").each(function() {
	$(this).find('option').remove();
	$(this).append('<option disabled="true" value="default" selected>Select a order</option>');
});
$("#" + familyId + "").each(function() {
	$(this).find('option').remove();
	$(this).append('<option disabled="true" value="default" selected>Select a family</option>');
});
$("#" + genusId + "").each(function() {
	$(this).find('option').remove();
	$(this).append('<option disabled="true" value="default" selected>Select a genus</option>');
});
$("#" + speciesId + "").each(function() {
	$(this).find('option').remove();
	$(this).append('<option disabled="true" value="default" selected>Select a species</option>');
});

// ok
function getPhylumQuery() {
	var kingdomUri = $("#" + kingdomId + " option:selected").val();
	var label = $("#" + kingdomId + " option:selected").text();

	$("#" + kingdomId + "Text").html("<a target=\"_blank\" href=\"" + kingdomUri + "\"> " + label + "</a>");
	$("#" + phylumId + "Text").html("");
	$("#" + classId + "Text").html("");
	$("#" + orderId + "Text").html("");
	$("#" + familyId + "Text").html("");
	$("#" + genusId + "Text").html("");
	$("#" + speciesId + "Text").html("");

	var query = prefix + " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where "
			+ " { ?uri a geospecies:PhylumConcept . ?uri rdfs:label ?label . " + "?uri geospecies:inKingdom <" + kingdomUri + "> . "
			+ " FILTER(str(?label)!='null') FILTER(str(?label)!='NULL') } order by ?label";

	$("#" + phylumId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a phylum</option>');
	});
	$("#" + classId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a class</option>');
	});
	$("#" + orderId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a order</option>');
	});
	$("#" + familyId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a family</option>');
	});
	$("#" + genusId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a genus</option>');
	});
	$("#" + speciesId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a species</option>');
	});
	resetInfoPart();
	return query;
	
	
	
	
	
	
	
	
}

function getClassQuery() {
	var kingdomUri = $("#" + kingdomId + " option:selected").val();
	var phylumUri = $("#" + phylumId + " option:selected").val();
	var label = $("#" + phylumId + " option:selected").text();

	$("#" + phylumId + "Text").html("<a target=\"_blank\" href=\"" + phylumUri + "\"> " + label + "</a>");
	$("#" + classId + "Text").html("");
	$("#" + orderId + "Text").html("");
	$("#" + familyId + "Text").html("");
	$("#" + genusId + "Text").html("");
	$("#" + speciesId + "Text").html("");

	var query = prefix + " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where "
			+ " { ?uri a geospecies:ClassConcept . ?uri rdfs:label ?label . " + " "
			+ "?uri geospecies:inPhylum <" + phylumUri + "> . " + " } order by ?label";

	$("#" + classId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a class</option>');
	});
	$("#" + orderId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a order</option>');
	});
	$("#" + familyId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a family</option>');
	});
	$("#" + genusId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a genus</option>');
	});
	$("#" + speciesId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a species</option>');
	});
	resetInfoPart();
	return query;
}

function getOrderQuery() {
	var kingdomUri = $("#" + kingdomId + " option:selected").val();
	var phylumUri = $("#" + phylumId + " option:selected").val();
	var classUri = $("#" + classId + " option:selected").val();
	var label = $("#" + classId + " option:selected").text();

	$("#" + classId + "Text").html("<a target=\"_blank\" href=\"" + classUri + "\"> " + label + "</a>");
	$("#" + orderId + "Text").html("");
	$("#" + familyId + "Text").html("");
	$("#" + genusId + "Text").html("");
	$("#" + speciesId + "Text").html("");

	var query = prefix + " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where "
			+ " { ?uri a geospecies:OrderConcept . ?uri rdfs:label ?label . " + " "
			+ " " + "?uri geospecies:inClass <" + classUri + "> . "
			+ " FILTER(str(?label)!='null') FILTER(str(?label)!='NULL') } order by ?label";

	$("#" + orderId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a order</option>');
	});
	$("#" + familyId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a family</option>');
	});
	$("#" + genusId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a genus</option>');
	});
	$("#" + speciesId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a species</option>');
	});
	resetInfoPart();
	return query;
}

function getFamilyQuery() {
	var kingdomUri = $("#" + kingdomId + " option:selected").val();
	var phylumUri = $("#" + phylumId + " option:selected").val();
	var classUri = $("#" + classId + " option:selected").val();
	var orderUri = $("#" + orderId + " option:selected").val();
	var label = $("#" + orderId + " option:selected").text();

	$("#" + orderId + "Text").html("<a target=\"_blank\" href=\"" + orderUri + "\"> " + label + "</a>");
	$("#" + familyId + "Text").html("");
	$("#" + genusId + "Text").html("");
	$("#" + speciesId + "Text").html("");

	var query = prefix + " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where "
			+ " { ?uri a geospecies:FamilyConcept . ?uri rdfs:label ?label . " + " "
			+ " " + "  " + "?uri geospecies:inOrder <" + orderUri
			+ "> . " + " FILTER(str(?label)!='null') FILTER(str(?label)!='NULL') } order by ?label";

	$("#" + familyId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a family</option>');
	});
	$("#" + genusId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a genus</option>');
	});
	$("#" + speciesId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a species</option>');
	});
	resetInfoPart();
	return query;
}
function getGenusQuery() {
	var kingdomUri = $("#" + kingdomId + " option:selected").val();
	var phylumUri = $("#" + phylumId + " option:selected").val();
	var classUri = $("#" + classId + " option:selected").val();
	var orderUri = $("#" + orderId + " option:selected").val();
	var familyUri = $("#" + familyId + " option:selected").val();
	var label = $("#" + familyId + " option:selected").text();

	$("#" + familyId + "Text").html("<a target=\"_blank\" href=\"" + familyUri + "\"> " + label + "</a>");
	$("#" + genusId + "Text").html("");
	$("#" + speciesId + "Text").html("");

	var query = prefix + " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where "
			+ " { ?uri a geospecies:GenusConcept . ?uri rdfs:label ?label . " + "  "
			+ "   " + "  " + "  " + "?uri geospecies:inFamily <" + familyUri + "> . " + "FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} order by ?label";
	$("#" + genusId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a genus</option>');
	});
	$("#" + speciesId + "").each(function() {
		$(this).find('option').remove();
		$(this).append('<option disabled="true" value="default" selected>Select a species</option>');
	});
	resetInfoPart();
	return query;
}
function getSpeciesQuery() {
	var kingdomUri = $("#" + kingdomId + " option:selected").val();
	var phylumUri = $("#" + phylumId + " option:selected").val();
	var classUri = $("#" + classId + " option:selected").val();
	var orderUri = $("#" + orderId + " option:selected").val();
	var familyUri = $("#" + familyId + " option:selected").val();
	var genusUri = $("#" + genusId + " option:selected").val();
	var label = $("#" + genusId + " option:selected").text();

	$("#" + genusId + "Text").html("<a target=\"_blank\"  href=\"" + genusId + "\"> " + label + "</a>");
	$("#" + speciesId + "Text").html("");

	var query = prefix + " SELECT  DISTINCT( ?uri ) ?label from <http://purl.org/biospread/> where "
			+ " { ?uri a geospecies:SpeciesConcept . ?uri rdfs:label ?label . " + "  "
			+ "  " + "  " + " " + " " + "?uri geospecies:inGenus <" + genusUri + "> . " +

			"FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} order by ?label";
	resetInfoPart();
	return query;
}

function updateSpecies() {
	speciesUri = $("#" + speciesId + " option:selected").val();
	var label = $("#" + speciesId + " option:selected").text();

	$("#" + speciesId + "Text").html("<a href=\"" + speciesUri + "\"> " + label + "</a>");
	// alert("<b>"+speciesId+":<b/> <a href=\""+ speciesUri+ "\">
	// "+speciesUri+"</a>");
	resetInfoPart();
}
