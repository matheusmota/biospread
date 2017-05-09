/* MIT License
 * Copyright (c) 2012 Semantic Computation Research Group
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and 
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of 
 * the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
 * DEALINGS IN THE SOFTWARE.
 *  
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Some parts of BGraph are taken from sgvizler project: http://code.google.com/p/sgvizler/
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */
function BGraph() {
	this.opts = {};

	this.opts['namespace'] = {
		'geospecies' : "http://rdf.geospecies.org/ont/geospecies#",
		'biospread-owl' : "http://purl.org/biospread/ontology/",
		'biospread' : "http://purl.org/biospread/resource/",
		'rdf' : "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
		'rdfs' : "http://www.w3.org/2000/01/rdf-schema#",
		'owl' : "http://www.w3.org/2002/07/owl#",
		'xsd' : "http://www.w3.org/2001/XMLSchema#",
		'foaf' : "http://xmlns.com/foaf/0.1/",
		'geo' : "http://www.w3.org/2003/01/geo/wgs84_pos#",
		'db-owl' : "http://dbpedia.org/ontology/",
		'tisc' : "http://observedchange.com/tisc/ns#",
		'vcard' : "http://www.w3.org/2006/vcard/ns#"
	};

	this.opts['types'] = {};
	this.opts['properties'] = [];

	// Returns prefixes as string for sparql query
	this.getPrefixes = function() {
		var prefixString = "";

		for (ns in bgraph.opts.namespace)
			prefixString += "prefix " + ns + ": <" + bgraph.opts.namespace[ns]
					+ "> ";

		return prefixString;
	};

	// Returns list of prefixes as string with line breaks
	this.getPrefixList = function() {
		var prefixString = "";

		for (ns in bgraph.opts.namespace)
			prefixString += "prefix " + ns + ": " + bgraph.opts.namespace[ns]
					+ "\n";

		return prefixString;
	};

	// Adds new prefix to opts
	this.addPrefix = function(prefix, namespace) {
		bgraph.opts.namespace[prefix] = namespace;
	}

	// Main loop for transformer
	this.SparqlJSON2GoogleJSON = function(stable) {
		var c, r, srow, grow, gvalue, stype, sdatatype, gcols = [], grows = [], gdatatype = [], // for
		// easy
		// reference
		// of
		// datatypes
		scols = stable.head.vars, srows = stable.results.bindings;

		for (c = 0; c < scols.length; c += 1) {
			r = 0;
			stype = null;
			sdatatype = null;
			// find a row where there is a value for this column
			while (typeof srows[r][scols[c]] === 'undefined'
					&& r + 1 < srows.length) {
				r += 1;
			}
			if (typeof srows[r][scols[c]] !== 'undefined') {
				stype = srows[r][scols[c]].type;
				sdatatype = srows[r][scols[c]].datatype;
			}
			gdatatype[c] = this.getGoogleJsonDatatype(stype, sdatatype);
			gcols[c] = {
				'id' : scols[c],
				'label' : scols[c],
				'type' : gdatatype[c]
			};
		}

		// loop rows
		for (r = 0; r < srows.length; r += 1) {
			srow = srows[r];
			grow = [];
			// loop cells
			for (c = 0; c < scols.length; c += 1) {
				gvalue = null;
				if (typeof srow[scols[c]] !== 'undefined'
						&& typeof srow[scols[c]].value !== 'undefined') {
					gvalue = this.getGoogleJsonValue(srow[scols[c]].value,
							gdatatype[c], srow[scols[c]].type);
				}
				grow[c] = {
					'v' : gvalue
				};
			}
			grows[r] = {
				'c' : grow
			};
		}
		return {
			'cols' : gcols,
			'rows' : grows
		};
	};

	// Transforms SPARQL JSON values to Google JSON values
	this.getGoogleJsonValue = function(value, gdatatype, stype) {
		var newvalue;
		if (gdatatype === 'number') {
			newvalue = Number(value);
		} else if (gdatatype === 'date') {
			// assume format yyyy-MM-dd
			newvalue = new Date(value.substr(0, 4), value.substr(5, 2), value
					.substr(8, 2));
		} else if (gdatatype === 'datetime') {
			// assume format yyyy-MM-ddZHH:mm:ss
			newvalue = new Date(value.substr(0, 4), value.substr(5, 2), value
					.substr(8, 2), value.substr(11, 2), value.substr(14, 2),
					value.substr(17, 2));
		} else if (gdatatype === 'timeofday') {
			// assume format HH:mm:ss
			newvalue = [ value.substr(0, 2), value.substr(3, 2),
					value.substr(6, 2) ];
		} else { // datatype === 'string' || datatype === 'boolean'
			if (stype === 'uri') { // replace namespace with prefix
				newvalue = this.prefixify(value);
			} else
				newvalue = value;
		}
		return newvalue;
	};

	// Transforms SPARQL JSON datatypes to Google JSON datatypes
	this.getGoogleJsonDatatype = function(stype, sdatatype) {
		var gdatatype = 'string', xsdns = bgraph.opts.namespace.xsd;

		if (typeof stype !== 'undefined'
				&& (stype === 'typed-literal' || stype === 'literal')) {
			if (sdatatype === xsdns + "float" || sdatatype === xsdns + "double"
					|| sdatatype === xsdns + "decimal"
					|| sdatatype === xsdns + "int"
					|| sdatatype === xsdns + "long"
					|| sdatatype === xsdns + "integer"
					|| sdatatype === xsdns + "gYearMonth"
					|| sdatatype === xsdns + "gYear"
					|| sdatatype === xsdns + "gMonthDay"
					|| sdatatype === xsdns + "gDay"
					|| sdatatype === xsdns + "gMonth") {
				gdatatype = 'number';
			} else if (sdatatype === xsdns + "boolean") {
				gdatatype = 'boolean';
			} else if (sdatatype === xsdns + "date") {
				gdatatype = 'date';
			} else if (sdatatype === xsdns + "dateTime") {
				gdatatype = 'datetime';
			} else if (sdatatype === xsdns + "time") {
				gdatatype = 'timeofday';
			}
		}
		return gdatatype;
	};

	// Prexifies URI
	this.prefixify = function(url) {
		var ns = null;
		for (ns in bgraph.opts.namespace) {
			if (bgraph.opts.namespace.hasOwnProperty(ns)
					&& url.lastIndexOf(bgraph.opts.namespace[ns], 0) === 0) {
				return url.replace(bgraph.opts.namespace[ns], ns + ":");
			}
		}
		return url;
	};

	// Unprexifies URI
	this.unprefixify = function(qname) {
		var ns = null;
		for (ns in bgraph.opts.namespace) {
			if (bgraph.opts.namespace.hasOwnProperty(ns)
					&& qname.lastIndexOf(ns + ":", 0) === 0) {
				return qname.replace(ns + ":", bgraph.opts.namespace[ns]);
			}
		}
		return qname;
	};

};
