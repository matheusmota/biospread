function ajaxQuery(q) {
	return $.ajax({
		url : "http://sparql.lis.ic.unicamp.br/?default-graph-uri=http%3A%2F%2Fpurl.org%2Fbiospread%2F&query=" + encodeURIComponent(q)
				+ "&format=json&callback=?",
		dataType : "jsonp"
	});
}

function fetch(speciesUri) {
	// sparqler = new SPARQL.Service("http://sparql.lis.ic.unicamp.bra");
	// sparqler.addDefaultGraph(defaultGrap);

	// sparqler.setRequestHeader("Authentication", "Basic: " + basicAuthString);

	// var query = sparqler.createQuery();

	// query.addDefualtGraph(...) query.addNamedGraph(...) query.setPrefix(...)
	// query.setRequestHeader(...)
	// passes standard JSON results object to success callback
	// query.setPrefix("ldf", "http://thefigtrees.net/lee/ldf-card#");

	var queryString = prefix + " SELECT  *  from <http://purl.org/biospread/> where { " + "?itemUri  a        biospread-owl:collectionItem . "
			+ " ?itemUri  biospread-owl:InstanceOfSpecies <" + speciesUri + "> ." + "?itemUri  geo:long ?long . " + "?itemUri  geo:lat  ?lat . } limit 200	";

	var queryHandler = ajaxQuery(queryString);

	// queryHandler.fail(function(){
	// alert("Not able to do query");
	// });
	//	
	queryHandler.done(function(data) {
		// data = $.parseJSON(data); // For debugging only

		var number = 0;

		$("#tableresult tbody tr").remove();

		if (data.results.bindings.lenght == 0) {

			alert("vazio");

		} else {

			$("#speciesinfoblock").show();
			init();
			$.each(data.results.bindings, function(i, item) {

				var georef;
				number = number + 1;

				$("#tableresult tbody").append("<tr>");
				$("#tableresult tbody").append("<td>" + item.itemUri.value + "</td>");
				$("#tableresult tbody").append("<td>" + item.lat.value + "</td>");
				$("#tableresult tbody").append("<td>" + item.long.value + "</td>");
				$("#tableresult tbody").append("</tr>");

				// popup config
				var AutoSizeFramedCloud = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
					'autoSize' : true
				});
				popupClass = AutoSizeFramedCloud;

				// Html inside the poput enters here
				popupContentHTML = "<b>Item uri:</b> <a href=\"" + item.itemUri.value + "\" target=\"_blank\"> " + item.itemUri.value + "</a> ";
				popupContentHTML = popupContentHTML + "<br/><u><a href=\"javascript:activateTab('page1')\">About the collected item</a></u> | "
						+ "<u><a href=\"javascript:activateTab('page2')\">About the species</a> </u>" + "<div id=\"tabCtrl\">"
						+ "<div id=\"page1\" style=\"display: block;\">...</div>"
						+ "<div id=\"page2\" style=\"display: none;\">........</div>" + "</div>";

				if ((item.lat != undefined) && (item.long != undefined)) {
					georef = true;
				} else {
					georef = false;
				}
				// popup Instanciation
				ll = new OpenLayers.LonLat(item.long.value, item.lat.value).transform(map.displayProjection, map.projection);
				addMarker(ll, popupClass, popupContentHTML, true);

				if (georef) {
					addMarker(ll, popupClass, popupContentHTML, true);
				}

			});

			queryString = prefix + " SELECT  *  from <http://purl.org/biospread/> where { ";
			queryString = queryString + " <" + speciesUri + ">  rdfs:label    ?sname . ";
			queryString = queryString + " OPTIONAL{<" + speciesUri + ">   geospecies:inKingdom            ?kingdom . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:inPhylum             ?phylum . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:inClass              ?class . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:inOrder              ?order . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:inFamily             ?family . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:inGenus              ?genus . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:hasLocation          ?location . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:hasCountry           ?country . ";
			queryString = queryString + " <" + speciesUri + ">  geospecies:hasLocalityText      ?locality . ";
			queryString = queryString + " <" + speciesUri + ">  biospread-owl:hasHabitatText    ?habitat . } ";
			queryString = queryString + "  }	limit 1";

			var infoHandler = ajaxQuery(queryString);

			infoHandler.done(function(data) {
				if (data.results.bindings.lenght == 0) {
					alert("vazio");
				} else {

					$.each(data.results.bindings, function(i, item) {
						$("#speciesname").html("<i>"+ getItem(item.sname)+"</i>");
						$("#speciescientificname").html("<i>"+ getItem(item.sname)+"</i>");
						$("#speciescountry").html(""+ getItem(item.country)+"");
						$("#specieslocation").html(""+ getItem(item.location)+"");
						$("#specieslocality").html(""+ getItem(item.locality)+"");
						$("#specieshabitat").html(""+ getItem(item.habitat)+"");
						
						
					});
				}
			});
			
			queryString = queryString +" <"+ speciesUri + ">  geospecies:hasCommonName        ?cname . ";
			

			$("#numberofeventsitems").html(number);
			

		}

		// ll = new OpenLayers.LonLat(result["long"],
		// result["lat"]).transform(map.displayProjection, map.projection);
		// popupClass = AutoSizeFramedCloud;
		//
		// popupContentHTML = "<b>Species: </b> " + result["specieslabel"] +
		// "<br/><b>Location description:</b> " + result["locationlabel"] +
		// "<br/><br/>"
		// + img + "<br/>";
		//
		// if ((result["lat"] != undefined) && (result["long"] != undefined) &&
		// (result["status"] != "professor")) {
		// addMarker(ll, popupClass, popupContentHTML, true);
		// }
		//
		// if ((result["status"] == "professor")) {
		// addMarkerT(ll, popupClass, popupContentHTML, true);
		// }
		//
		// });
		// }
		//
	});
}

function getItem(item){
	if (item==undefined){
		return "";
	}else
		return item.value;

	
}
