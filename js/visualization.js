function changeNode() {	
	var nodeId = document.getElementById("nodeList").value;
	var probData = document.getElementById("infoScope").value;
	var random = $('#random').attr("isRandom");
	var param = getUrlVars();
		
	resetNodes();
	resetProgressBar();
		
	if(nodeId && probData){
		$.ajax({
			dataType: "json",
			url: 'node_receivers.php?db=' + param.db + '&random=' + random,
			data: { id : nodeId, dataBase : probData },
			success: function(data) {										
				var numberOfPass = data.maxOrder;
				var maxCount = data.maxCount;
				var nodeArray = data.receive_order;
				var stepValue = data.stepValue;
				var colorOrderArray = getColorScheme(numberOfPass, $( "#spectrum" ).attr( "starterParameter"));	
				var allNodeData = new Array();
								
				$.each( nodeArray, function( id, nodeOrder ){
				  $('#' + nodeOrder.id).attr("order", nodeOrder.order);
				  $('#' + nodeOrder.id).attr("fillColor", colorOrderArray[nodeOrder.order]);
				  $('#' + nodeOrder.id).attr("probability", nodeOrder.prob);
				  $('#' + nodeOrder.id).attr("count", nodeOrder.count);
				  colorNode(nodeOrder.id, numberOfPass);
				  addBubbleChartData(allNodeData, nodeOrder.id, nodeOrder.order, colorOrderArray[nodeOrder.order], nodeOrder.prob, nodeOrder.count);
				});
				$('#activeNode').html(nodeArray.length);
				$('#connectionNo').html(maxCount);
				$('#dataTopic').html(probData);
				
				plotBarChat(createBarChartData(stepValue, colorOrderArray));
				plotBubbleChat(allNodeData, maxCount, 1);
			}
		});		
	}else{
		alert('Please choose influencer and category');
	}
}

function changeNodeTopK() {	
	var nodeIdList = document.getElementById("topKNodeArrayInput").value;	
	var probData = document.getElementById("infoScope").value;
	var random = $('#random').attr("isRandom");
	var param = getUrlVars();
		
	resetNodes();
	resetProgressBar();
		
	if(nodeIdList && probData){
		$.ajax({
			dataType: "json",
			url: 'node_receivers_topK.php?db=' + param.db + '&random=' + random,
			data: { id : nodeIdList, dataBase : probData },
			success: function(data) {				
				var numberOfPass = data.maxOrder;
				var maxCount = data.maxCount;
				var nodeArray = data.receive_order;
				var stepValue = data.stepValue;
				var colorOrderArray = getColorScheme(numberOfPass, $( "#spectrum" ).attr( "starterParameter"));	
				var allNodeData = new Array();
								
				$.each( nodeArray, function( id, nodeOrder ){
				  $('#' + nodeOrder.id).attr("order", nodeOrder.order);
				  $('#' + nodeOrder.id).attr("fillColor", colorOrderArray[nodeOrder.order]);
				  $('#' + nodeOrder.id).attr("probability", nodeOrder.prob);
				  $('#' + nodeOrder.id).attr("count", nodeOrder.count);
				  colorNode(nodeOrder.id, numberOfPass);
				  addBubbleChartData(allNodeData, nodeOrder.id, nodeOrder.order, colorOrderArray[nodeOrder.order], nodeOrder.prob, nodeOrder.count);
				});
				$('#activeNode').html(nodeArray.length);
				$('#connectionNo').html(maxCount);
				$('#dataTopic').html(probData);
				
				plotBarChat(createBarChartData(stepValue, colorOrderArray));
				plotBubbleChat(allNodeData, maxCount, 1);
			}
		});	
		return true;	
	}else{
		alert('Please choose influencer and category');
		return false;
	}
}



function resetNodes() {
	$('.node').css({
		'fill': '#666',
		'opacity': '0.1'
	});
	$('.person-arcs').css({
		'fill': 'none',
		'stroke': '#666',
		'opacity': '0.05',
		'display': 'none'
	});
}

function restartConnection(){
	$('#connection').attr("showConnection", "false");
	$('.person-arcs').hide();		
}

function showSpectrum(colorParameter){
	var number = 14;
	var starter = colorParameter * 150;
	var frequency = 0.345;
	var amplitude = 127;
	var center = 128;
	var colorString = "";
	for (var i = starter; i < number + starter; ++i) {
		red = Math.sin(frequency * i + 0) * amplitude + center;
		green = Math.sin(frequency * i + 2) * amplitude + center;
		blue = Math.sin(frequency * i + 4) * amplitude + center;
		colorString += '<div style="float:left; height:15px; width:20px; background:' +rgb2hex('rgb(' + Math.round(red) + ',' + Math.round(green) + ',' + Math.round(blue) + ')')+ '"></div>';		
	}
	colorString += '<div style="clear:both;"></div>';	
	$('#spectrum').html(colorString);
}

function getColorScheme(number, colorParameter) {
	var starter = colorParameter * 150;
	var frequency = 0.345;
	var amplitude = 127;
	var center = 128;	
	var colorOrder = new Array();
	for (var i = starter; i < number + starter; ++i) {
		red = Math.sin(frequency * i + 0) * amplitude + center;
		green = Math.sin(frequency * i + 2) * amplitude + center;
		blue = Math.sin(frequency * i + 4) * amplitude + center;
		
		colorOrder[Math.round(i - starter)] = rgb2hex('rgb(' + Math.round(red) + ',' + Math.round(green) + ',' + Math.round(blue) + ')');
	}
	return colorOrder;
}

function colorNode(nodeId, numberOfPass){	
	var color = $('#' + nodeId).attr("fillColor");
	var nodeOrder = $('#' + nodeId).attr("order");
	var probability = $('#' + nodeId).attr("probability");
	
	$('#' + nodeId).delay(300 * nodeOrder).css({
		'fill': color,
		'opacity': probability
	});
	$('.person-arcs', '#' + $('#' + nodeId).parent().attr('id')).css({
		'stroke': color,
		'opacity': (probability / 10)
	});	
	changeProgressBar(nodeOrder, numberOfPass);
}

function rgb2hex(rgb){
 rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 return (rgb && rgb.length === 4) ? "#" +
  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

function changeProgressBar(currentOrder, totalNumber){
	if(currentOrder != totalNumber){
		if($("#progressbar").is(":visible")){
			$( "#slider-range" ).hide();
			$( "#progressbar" ).show();
		}
		$( "#progressbar" ).progressbar({
	    value: Math.ceil((currentOrder / totalNumber) * 100)
	  });
	}else{
		$( "#progressbar" ).hide();
		$( "#slider-range" ).show();
		chooseRange(totalNumber);
		$('#stepShower').html( 0 + " - " + totalNumber );
	}
}

function resetProgressBar(){
	$( "#slider-range" ).hide();
	$( "#progressbar" ).show();	
}


function chooseRange(totalNumber){
  $( "#slider-range" ).slider({
    range: true,
    min: 0,
    max: totalNumber,
    values: [ 0, totalNumber ],
    slide: function( event, ui ) {
    	hideProgressNode(ui.values[0], ui.values[1]);
    	$('#stepShower').html( ui.values[ 0 ] + " - " + ui.values[1] );
    }
  });
}

function hideProgressNode(minStep, maxStep){
	$(".node").each(function(){
		var nodeId = $(this).attr("id");
		var order = $(this).attr("order");
		var probability = $(this).attr("probability");
		var showConnection = $('#connection').attr("showConnection");
		var filledColor = $(this).css('fill');
						
		if( (order > maxStep || order < minStep) && (filledColor != 'rgb(102, 102, 102)' || filledColor != '#666')){
				$(this).css({
					'fill': '#666',
					'opacity': 0.1
				});
				$('.person-arcs', '#' + $('#' + nodeId).parent().attr('id')).hide();
		}else{
					
			if($(this).attr('fillColor') && (filledColor == 'rgb(102, 102, 102)' || filledColor == '#666')){
				$(this).css({
					'fill': $(this).attr('fillColor'),
					'opacity': probability
				});
				if(showConnection == "true"){
					$('.person-arcs', '#' + $('#' + nodeId).parent().attr('id')).show();							
				}
			}else if(!$(this).attr('fillColor')){
				$('.person-arcs', '#' + $('#' + nodeId).parent().attr('id')).hide();
			}
		}		
	});
}

function changeConnection(showConnection){
	if(showConnection == "false"){
		$('.person-arcs').hide();
	}else if(showConnection == "true"){
		$('.person-arcs').show();		
		hideProgressNode($( "#slider-range" ).slider( "values", 0 ), $( "#slider-range" ).slider( "values", 1 ));
	}
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


/*Plot bar chat graph*/
function plotBarChat(dataPointsReceived){
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
        dataPoints: dataPointsReceived
      }   
      ]
    });

    chart.render();
}

function createBarChartData(stepValue, colorOrderArray){
	var dataPoints = new Array();	
	for(var i = 0; i < stepValue.length; i ++){
		dataPoints[i] = {"y": stepValue[i], "label": i, "color": colorOrderArray[i]};
	}
	return dataPoints;
}

function addBubbleChartData(allNodeData, id, order, color, prob, count){	
	var nodeInfo = { x: Number(count), y: Number(prob), z:Number(count),  name:id, color:color, order: order};
	allNodeData.push(nodeInfo);
}


/*Plot bubble chat graph*/
function plotBubbleChat(allNodeData, maxX, maxY){
	var chart = new CanvasJS.Chart("bubbleChart",
	    {
	      zoomEnabled: false,
	      animationEnabled: true,
	      title:{
	        text: "View without Position"                    
	      },
	      axisX: {
	        title:"Number of connections",
	        valueFormatString: "#0",
	        maximum: maxX,
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
	        maximum: maxY,
	        interval: (maxY / 10)
	        
	      },
	      width:800,
	      height:500,		
	      data: [
	      {        
	        type: "bubble",
	        fillOpacity: .7,     
	        toolTipContent: "<span style='\"'color: {color};'\"'><strong>{name}</strong></span><br/> <strong>Order</strong> {order} <br/> <strong>Probability</strong> {y}<br/> <strong>Connections</strong> {z}",
	        dataPoints: allNodeData
	      }
	      ]
	    });
	
	chart.render();	
}