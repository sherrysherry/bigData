$(function() { 	
	//Adjust the map
	$( "#progressbar" ).progressbar({
    value: 0
  });
  showSpectrum($( "#spectrum" ).attr( "starterParameter"));
	$( "#slider-range-min" ).slider({
	  range: "min",
	  min: 0,
	  max: 10,
	  value: 10,
	  slide: function( event, ui ) {
	    $( "#spectrum" ).attr( "starterParameter", (ui.value)/10 );
	    showSpectrum((ui.value)/10);
	  }
	});

	var width = 800,
		height = 500;

	var projection = d3.geo.mercator()
    .scale(150)
    .translate([width / 2, height / 2])
    .precision(.1);
	var path = d3.geo.path().projection(projection);
	var graticule = d3.geo.graticule();
	var voronoi = d3.geom.voronoi().x(function(d) {
		return d.x;
	}).y(function(d) {
		return d.y;
	}).clipExtent([
		[0, 0],
		[width, height]
	]);
	var param = getUrlVars();	
	$("#dataBase").html(param.db.substring(18));
	
	var svg = d3.select(".graph").append("svg")
	.call(d3.behavior.zoom().on("zoom", redraw))
	.attr("width", width).attr("height", height)
	.append("g");	

		queue().defer(d3.json, "us.json").defer(d3.json, "data_population.php?db=" + param.db).defer(d3.json, "data_connections.php?db=" + param.db).await(ready);
		
	function ready(error, us, people, connections) {
		var countries = topojson.feature(us, us.objects.countries).features;
			
		var personById = d3.map(),
			positions = [];
		people.forEach(function(d) {
			personById.set(d.user_id, d);
			d.outgoing = [];
			d.incoming = [];
		});
		connections.forEach(function(connection) {
			var source = personById.get(connection.sender),
				target = personById.get(connection.receiver),
				link = {
					source: source,
					target: target
				};
			source.outgoing.push(link);
			target.incoming.push(link);
		});
		people = people.filter(function(d) {
			if (d.count = Math.max(d.incoming.length, d.outgoing.length) / 20) {
				d[0] = +d.longitude;
				d[1] = +d.latitude;
				var position = projection(d);
				d.x = position[0];
				d.y = position[1];
				return true;
			}
		});		
		voronoi(people).forEach(function(d) {
			d.point.cell = d;
		});
		svg.append("path").datum(topojson.feature(us, us.objects.land)).attr("class", "states").attr("d", path);
		
		
		/* plot the world map */
		svg.append("path")
		    .datum(graticule)
		    .attr("class", "graticule")
		    .attr("d", path);
		
		svg.selectAll(".country")
      .data(countries)
	    .enter().insert("path", ".graticule")
      .attr("class", "country")
      .attr("d", path);
		
		/*
		svg.append("path").datum(topojson.mesh(us, us.objects.states, function(a, b) {
			return a !== b;
		})).attr("class", "state-borders").attr("d", path);
		*/
		
		var person = svg.append("g").attr("class", "people").selectAll("g").data(people.sort(function(a, b) {
			return b.count - a.count;
		})).enter().append("g").attr("class", "person");
		
		person.attr("id", function(d) {
			return 'person_'+d.user_id;
		}).append("g").attr("class", "person-arcs").selectAll("path").data(function(d) {			
			return d.outgoing;
		}).enter().append("path")
		.attr("d", function(d) {
			return path({
				type: "LineString",
				coordinates: [d.source, d.target],
			});
		});
		
		
		person.append("circle").attr("id", function(d) {
			return d.user_id;
		}).attr("class", "node").attr("transform", function(d) {
			return "translate(" + d.x + "," + d.y + ")";
		}).attr("r", function(d, i) {
			return Math.sqrt(d.count);
		});
	}
	
	initializeBarChart();
	initializeBubbleChart();
	
	function redraw() {
    svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
  }
  
  
  function getUrlVars(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
	}
	
	function initializeBarChart(){
		var chart = new CanvasJS.Chart("stepBarChart",
    {
      title:{
        text: "Influencer Analysis"    
      },
      animationEnabled: true,
      axisY: {
        title: "Number of nodes influenced"
      },
      width:800,
      height:500,
      legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
      },
      data: [

      {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Step of Transmission",
      }   
      ]
    });

    chart.render();
	}
	
	function initializeBubbleChart(){
		var chart = new CanvasJS.Chart("bubbleChart",
		    {
		      zoomEnabled: true,
		      animationEnabled: true,
		      title:{
		        text: "View without Position"                    
		      },
		      axisX: {
		        title:"Number of connections",
		        valueFormatString: "#0.#",
		        maximum: 17,
		        minimum: -.1,
		        gridThickness: 1,
			tickThickness: 1,
		        gridColor: "lightgrey",
		        tickColor: "lightgrey",
		        lineThickness: 0
		      },
		      axisY:{
		        title: "Probability",              
		        gridThickness: 1,
			tickThickness: 1,
		        gridColor: "lightgrey",
		        tickColor: "lightgrey",
		        lineThickness: 0,
		        maximum: 1,
		        interval: 0.1
		        
		      },
		      width:800,
		      height:500,		
		      data: [
		      {        
		        type: "bubble"
		      }]
		    });
		
		chart.render();
	}	
});