//$("#progressbar").is(":visible")

$(function(){
		//Adjust the dashboard
  $( "input[type=submit], a, button" ).button();
  $("#submitButton").button();
  $("#setTopK").button();
  $("#topKSubmit").button();
	$("#connection").buttonset();
	$("#nodeList").selectmenu();
	$("#infoScope").selectmenu();
	$( "#graphsResults" ).tabs();
	$( "#tabs" ).tabs();
	$( "#random" ).buttonset();
	
	
	$('#showConnection1').click(function(){
	  changeConnection('true');
	  $('#connection').attr("showConnection", 'true');
  });
  $('#showConnection2').click(function(){
	  changeConnection('false');
	  $('#connection').attr("showConnection", 'false');
  });
  
	$('#random1').click(function(){
	  $('#random').attr("isRandom", 'true');
  });
  $('#random2').click(function(){
	  $('#random').attr("isRandom", 'false');
  }); 
  
  
  $( "#setKDialog" ).dialog({
    autoOpen: false,
    width: 450,
    height: 400,
    show: {
      effect: "fade",
      duration: 500
    },
    hide: {
      effect: "fade",
      duration: 500
    },
    buttons: {
      "Pass Information From Top K": function() {
	      if(changeNodeTopK()){
	        $( this ).dialog( "close" );
        }
      }
    }
  });  
  $( "#setTopK" ).click(function() {
    $( "#setKDialog" ).dialog( "open" );
  });   
});

function setTopK(){
  var probData = document.getElementById("infoScope").value;
  var kNumber = document.getElementById("kNumber").value;
  var param = getUrlVars();
  
  if(probData && kNumber){
		$.ajax({
			dataType: "json",
			url: 'getTopK.php',
			data: { dataBase : probData, k : kNumber, db : param.db },
			success: function(data) {
				var topKNodeArray = new Array();		
				$("#topKResult").html("");	
				$("#topKResult").append( "<p>Top "+data.maxK+" influential nodes </p>" );								
				$.each(data.topKInform, function( id, nodeK ) {
				  $("#topKResult").append( 
				  "<p>"+ (id + 1) +".&nbsp;" + nodeK.id + 
				  " - Number of followers: " + nodeK.follower_count +
				  ", Probability: "+ nodeK.prob +
				  ", Top K Index: "+ nodeK.topKIndex +"</p>" );
				  topKNodeArray.push(nodeK.id);
				});
				$("#topKNodeArrayInput").val(topKNodeArray);
			}
		});				  
  }
  else{
	  alert('Please input both topic and K value');
  }
}	