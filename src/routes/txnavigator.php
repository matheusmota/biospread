
<script
	type="text/javascript"
	src="http://www.thefigtrees.net/lee/sw/sparql.js"></script>





<script
	src='http://dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.1'></script>
<script
	src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAASxziEiVlxZjWx32KYz1aehRms8KHK1hID18NaMPcTpBrkWwozRRiaGe1M74mL2f9P6px5kjcTaOZCA"
	type="text/javascript"></script>
<script
	src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=euzuro-openlayers"></script>
<script
	type="text/javascript" src="http://openlayers.org/api/OpenLayers.js"></script>

<script
	type="text/javascript" src="js/txnavigator_openlayers.js"></script>
<script
	type="text/javascript" src="js/txnavigator_sparqlmenu.js"></script>


<script src="js/txnavigator.js"></script>
<h1>A Taxonomy Navigator</h1>


<hr />
<p>
	<b>A JavaScript navigator for the extracted data. Navigate through the select boxes and retrieve more information about species</b>
</p>



<div align="left">
<select class="some-message" id="kingdom"
	onchange="loadOptionsFor(getPhylumQuery(),phylumId);">
	<option disabled="true" value="default" selected>Select a Kingdom</option>

</select>

<select class="some-message"  id="phylum" onchange="loadOptionsFor(getClassQuery(),classId);">
	<option disabled="true" value="default" selected>Select a phylum</option>

</select>
<select  class="some-message" id="class" onchange="loadOptionsFor(getOrderQuery(),orderId);">
	<option disabled="true" value="default" selected>Select a class</option>
</select>
<select  class="some-message" id="order" onchange="loadOptionsFor(getFamilyQuery(),familyId);">
	<option disabled="true" value="default" selected>Select a order</option>
</select>
<select class="some-message"  id="family" onchange="loadOptionsFor(getGenusQuery(),genusId);">
	<option disabled="true" value="default" selected>Select a family</option>
</select>
<select class="some-message"  id="genus"
	onchange="loadOptionsFor(getSpeciesQuery(),speciesId);">
	<option disabled="true" value="default" selected>Select a genus</option>
</select>
<select class="some-message"  id="species" onchange="updateSpecies(); fetch(speciesUri);">
	<option disabled="true" value="default" selected>Select a species</option>
</select>

</div>



<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery-1.8.2.min.js"><\/script>')</script>

<script src="js/plugins.js"></script>
<script src="js/main.js"></script>


<script>
google.setOnLoadCallback(function() {
	resetInfoPart();
	loadOptionsFor(kingomQuery,kingdomId);

});
</script>

<p><b>Taxonomy Path:</b>
<spam id="kingdomText"> none</spam>
<spam id="phylumText">none</spam>
<spam id="classText">none</spam>
<spam id="orderText">none</spam>
<spam id="familyText">none</spam>
<spam id="genusText">none</spam>
<spam id="speciesText">none</spam>

</p>



<table hidden="true" cellpadding="1" cellspacing="1" border="1"
	class="resultdisplay" id="tableresult">
	<thead>
		<tr>
			<th>Item</th>
			<th>Lat</th>
			<th>Long</th>

		</tr>
	</thead>
	<tbody></tbody>
	
</table>


<div id="speciesinfoblock">
<hr>
<h2>About: "<spam id="speciesname">Species name</spam>"</h2>


<!-- <p><b>Common names:</b> <spam id="speciescommonname"></spam></p>
<p><b>Country uri:</b> <spam id="speciescountry"></spam></p>
<p><b>Location uri:</b> <spam id="specieslocation"></spam></p>
<p><b>Locality text:</b> <spam id="specieslocality"></spam></p>
<p><b>Habitat text:</b> <spam id="specieshabitat"></spam></p>
 -->

<h2>Associated Events: Records(<spam id="numberofeventsitems" >0</spam>) </h2>
<div
	id="map_canvas" style="width: 700px; height: 600px"></div>

</div>
