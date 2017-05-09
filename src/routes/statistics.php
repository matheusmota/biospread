<h1>Statistics about the data extracted from spreadsheets available on the Web </h1>

<div id="stats">
	<p>
		<b>Statistics shows the total number of instances, classes and
			predicates recognized by our prototype.</b>
	</p>
	<p>
		-Total number of triples in the <b>http://purl.org/biospread/</b>
		graph: <span id="triples"><img src="./layout1/img/load.gif"
			alt="loading ..." /> </span> and counting <img
			src="./layout1/img/load.gif" alt="loading ..." />
	</p>
	<p>
		-Total number of distinct semantic classes: <span id="classes"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>
	<p>
		-Total number of distinct predicates: <span id="preds"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>kingdoms</b>: <span id="kingdom"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>phylums</b>: <span id="phylum"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>class</b>: <span id="class"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>orders</b>: <span id="order"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>families</b>: <span
			id="family"><img src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>genus</b>: <span id="genus"><img
			src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>

	<p>
		-Total number of distinct recognized <b>species</b>: <span
			id="species"><img src="./layout1/img/load.gif" alt="loading ..." /> </span>
	</p>



</div>

<b>-Total number of instances of a certain class:</b>
<p id="pie">
	<img src="./layout1/img/load.gif" alt="loading ..." />
</p>


<b>-Number of subjects using some properties:</b>
<p id="bar">
	<img src="./layout1/img/load.gif" />
</p>

<b>-Number of species related to some kingdom:</b>
<p id="barKingdom">
	<img src="./layout1/img/load.gif" />
</p>

<b>-Number of species related to some phylum:</b>
<p id="barPhylum">
	<img src="./layout1/img/load.gif" />
</p>


<b>-Number of species related to some class:</b>
<p id="barClass">
	<img src="./layout1/img/load.gif" />
</p>


<b>-Number of species related to some order:</b>
<p id="barOrder">
	<img src="./layout1/img/load.gif" />
</p>

<b>-Number of species related to some family:</b>
<p id="barFamily">
	<img src="./layout1/img/load.gif" />
</p>

<b>-Number of species related to some genus:</b>
<p id="barGenus">
	<img src="./layout1/img/load.gif" />
</p>


<b>-Number of collected itens related to some species:</b>
<p id="barSpecies">
	<img src="./layout1/img/load.gif" />
</p>



<script>
var bgraph = new BGraph();	
var prefix = bgraph.getPrefixes();
google.load("visualization", "1", {packages:["corechart"]});

var q1 = prefix+" SELECT (COUNT(*) AS ?no) { ?s ?p ?o  }";
var q2 = prefix+" SELECT (COUNT(distinct ?o) AS ?no) { ?s rdf:type ?o }";
var q3 = prefix+" SELECT (Count(distinct ?p) AS ?no) { ?s ?p ?o }";
var q4 = prefix+" SELECT ?class (COUNT(?s) AS ?count) { ?s a ?class } GROUP BY ?class ORDER BY ?count";
var q5 = prefix+" SELECT ?p (COUNT(DISTINCT ?s ) AS ?Subjects ) { ?s ?p ?o } GROUP BY ?p ORDER BY desc(?Subjects)";

//kingdoms
var q6  = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:KingdomConcept }";
var q20 = prefix+" SELECT ?label (COUNT(DISTINCT ?s ) AS ?NumberOfSpecies ) { ?uri a geospecies:KingdomConcept .  ?s a geospecies:SpeciesConcept . ?s geospecies:inKingdom ?uri . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} GROUP BY ?label ORDER BY desc(?NumberOfSpecies) LIMIT 3";

//phylum
var q7 = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:PhylumConcept  }";
var q21 = prefix+" SELECT ?label (COUNT(DISTINCT ?s ) AS ?NumberOfSpecies ) { ?uri a geospecies:PhylumConcept .  ?s a geospecies:SpeciesConcept . ?s geospecies:inPhylum ?uri . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} GROUP BY ?label ORDER BY desc(?NumberOfSpecies) LIMIT 20";

//class
var q8 = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:ClassConcept  }";
var q22 = prefix+" SELECT ?label (COUNT(DISTINCT ?s ) AS ?NumberOfSpecies ) { ?uri a geospecies:ClassConcept .  ?s a geospecies:SpeciesConcept . ?s geospecies:inClass ?uri . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} GROUP BY ?label ORDER BY desc(?NumberOfSpecies) LIMIT 20";

//Order
var q9 = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:OrderConcept  }";
var q23 = prefix+" SELECT ?label (COUNT(DISTINCT ?s ) AS ?NumberOfSpecies ) { ?uri a geospecies:OrderConcept .  ?s a geospecies:SpeciesConcept . ?s geospecies:inOrder ?uri . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} GROUP BY ?label ORDER BY desc(?NumberOfSpecies) LIMIT 20";

//family
var q10 = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:FamilyConcept  }";
var q24 = prefix+" SELECT ?label (COUNT(DISTINCT ?s ) AS ?NumberOfSpecies ) { ?uri a geospecies:FamilyConcept .  ?s a geospecies:SpeciesConcept . ?s geospecies:inFamily ?uri . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL')} GROUP BY ?label ORDER BY desc(?NumberOfSpecies) LIMIT 20";

//genus
var q11 = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:GenusConcept  }";
var q25 = prefix+" SELECT ?label (COUNT(DISTINCT ?s ) AS ?NumberOfSpecies ) { ?uri a geospecies:GenusConcept .  ?s a geospecies:SpeciesConcept . ?s geospecies:inGenus ?uri . ?uri rdfs:label ?label . FILTER(str(?label)!='null') FILTER(str(?label)!='NULL') } GROUP BY ?label ORDER BY desc(?NumberOfSpecies) LIMIT 20";

//species
var q12 = prefix+" SELECT (COUNT(distinct ?a) AS ?no) { ?a ?b geospecies:SpeciesConcept  }";

function ajaxQuery(q) {
	return $.ajax({url: "http://sparql.lis.ic.unicamp.br/?default-graph-uri=http%3A%2F%2Fpurl.org%2Fbiospread%2F&query="+encodeURIComponent(q)+"&format=json&callback=?", 
dataType: "jsonp"});
}

function plotText(queryString,elem,label) {
	ajaxQuery(queryString).done(function(data) {
	// data = $.parseJSON(data); // For debugging only
	if(data.results.bindings.length>0) {
		$(elem).html(data.results.bindings[0][label].value);
	} else $(elem).html("No results found");
	});
}

function plotPie(queryString,elem) {
	ajaxQuery(queryString).done(function(data) {
	// data = $.parseJSON(data); // For debugging only
	if(data.results.bindings.length>0) {
				
		var data = new google.visualization.DataTable(bgraph.SparqlJSON2GoogleJSON(data));
		var viz = new google.visualization.PieChart($(elem)[0]);
				
		var options = {
			is3D:true,
			height:200,
			width:550,
			chartArea:{left:0,top:10,width:"100%",height:"100%"}
			};
			
		viz.draw(data,options);
		
	}});
}

function plotBar(queryString,elem) {
	ajaxQuery(queryString).done(function(data) {
	// data = $.parseJSON(data); // For debugging only
	if(data.results.bindings.length>0) {
				
		var data = new google.visualization.DataTable(bgraph.SparqlJSON2GoogleJSON(data));
		var viz = new google.visualization.BarChart($(elem)[0]);
		
		var options = {
			legend: {position: 'none	'},
            bar: {groupWidth: "30%"},
            width:550, height:400,
            is3D: true,
            vAxis: {textStyle:{fontSize: 10}},
            hAxis: {logScale: false},
            chartArea:{left:120,top:0,width:"55%",height:"95%"}
            };
		
		viz.draw(data,options);
		
	}});
}

function plotBarWithHeight(queryString,elem,height) {
	ajaxQuery(queryString).done(function(data) {
		// data = $.parseJSON(data); // For debugging only
		if(data.results.bindings.length>0) {
					
			var data = new google.visualization.DataTable(bgraph.SparqlJSON2GoogleJSON(data));
			var viz = new google.visualization.BarChart($(elem)[0]);
			
			var options = {
				legend: {position: 'none	'},
	            bar: {groupWidth: "30%"},
	            width:600, height:height,
	            is3D: true,
	            vAxis: {textStyle:{fontSize: 10}},
	            hAxis: {logScale: false},
	            chartArea:{left:0,top:0,width:"70%",height:"90%"}
	            };
			
			viz.draw(data,options);
			
		}});
	}


plotText(q1,"#triples","no");
plotText(q2,"#classes","no");
plotText(q3,"#preds","no");
plotText(q6,"#kingdom","no");
plotText(q7,"#phylum","no");
plotText(q8,"#class","no");
plotText(q9,"#order","no");
plotText(q10,"#family","no");
plotText(q11,"#genus","no");
plotText(q12,"#species","no");

google.setOnLoadCallback(function() {
plotPie(q4,"#pie");
plotBar(q5,"#bar");
plotBarWithHeight(q20,"#barKingdom",250);
plotBarWithHeight(q21,"#barPhylum",300);
plotBarWithHeight(q22,"#barClass",300);
plotBarWithHeight(q23,"#barOrder",300);
plotBarWithHeight(q24,"#barFamily",300);
plotBarWithHeight(q25,"#barGenus",300);


});



</script>


<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery-1.8.2.min.js"><\/script>')</script>

<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

