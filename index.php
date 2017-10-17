<!DOCTYPE html>
<html>
<head>
<script src="jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<script>
var source;
var currentsentences;
var currentpage;
var sidelength = 3;
var sentencesPerPage = 25;
$(document).ready(function(){
	$("input:checkbox").change(refresh);
    $.get("getsentences.php", function(data, status){
        data = JSON.parse(data);
        source = data.sort(function() { return 0.5 - Math.random() });
        /*$(data).each(function(index){
        	var span = document.createElement("span");
        	index++;
        	span.innerHTML = index+". "+this.CH;
        	$(span).data("CH",index+". "+this.CH);
        	$(span).data("JP",index+". "+this.JP);
        	$(span).data("Status", "CH");
        	$(span).click(function(){
        		if($(this).data("Status")=="CH"){
        			$(span).data("Status", "JP");
        			span.innerHTML = $(this).data("JP");
        		}else if($(this).data("Status")=="JP"){
        			$(span).data("Status", "CH");
        			span.innerHTML = $(this).data("CH");
        		}
        	});
        	$("#sentences").append(span);
        	$("#sentences").append("<br>");
        });*/
        refresh();
    });
    function refresh(){
    	currentpage = 1;
    	currentsentences = source;
    	if(!$("#N1check")[0].checked){
    		currentsentences = jQuery.grep(currentsentences, function( n, i ) {
				return (n.level != "N1");
			});
    	}
    	if(!$("#N2check")[0].checked){
    		currentsentences = jQuery.grep(currentsentences, function( n, i ) {
				return (n.level != "N2");
			});
    	}
    	if(!$("#N3check")[0].checked){
    		currentsentences = jQuery.grep(currentsentences, function( n, i ) {
				return (n.level != "N3");
			});
    	}
    	if(!$("#N4check")[0].checked){
    		currentsentences = jQuery.grep(currentsentences, function( n, i ) {
				return (n.level != "N4");
			});
    	}
    	if(!$("#N5check")[0].checked){
    		currentsentences = jQuery.grep(currentsentences, function( n, i ) {
				return (n.level != "N5");
			});
    	}
    	if(currentsentences.length == 0){
    		$("#sentences").html("");
    		$("#pages").html("");
    	}else{
    		genSenAndPage();
    	}
    }
    function genSenAndPage(){
    	$("#sentences").html("");
    	$("#pages").html("");
    	var maxpage = Math.ceil(currentsentences.length / sentencesPerPage);
    	var startpage = currentpage;
		if(startpage - sidelength < 1){
			for(var i = sidelength; i > 0;i--){
				if(startpage - i > 0){
					startpage = startpage - i;
					break;
				}
			}
		}else{
			startpage = startpage - sidelength;
		}
		if(startpage + (2 * sidelength) > maxpage){
		    for(var i = (2 * sidelength) - maxpage + startpage;i > 0; i--){
		        if(startpage - i > 0){
		            startpage = startpage - i;
		            break;
		        }
		    }
		}
		var endpage;
		if(startpage + (2 * sidelength) > maxpage){
		    endpage = maxpage;
		}else{
		    endpage = startpage + (2 * sidelength);
		}
		for(var i = startpage;i <= endpage;i++){
			var span = document.createElement("span");
			span.innerHTML = i;
			if(i != currentpage){
				$(span).addClass("clickable");
				$(span).data("target", i);
				$(span).click(function(){
					currentpage = $(this).data("target");
					genSenAndPage();
				});
			}
			$("#pages").append(span);
		}

		for(var i = (currentpage-1) * sentencesPerPage;i < currentpage * sentencesPerPage;i++){
			var span = document.createElement("span");
			var index = i+1;
        	span.innerHTML = index+". "+currentsentences[i].CH;
        	$(span).data("CH",index+". "+currentsentences[i].CH);
        	$(span).data("JP",index+". "+currentsentences[i].JP);
        	$(span).data("Status", "CH");
        	$(span).click(function(){
        		if($(this).data("Status")=="CH"){
        			$(this).data("Status", "JP");
        			this.innerHTML = $(this).data("JP");
        		}else if($(this).data("Status")=="JP"){
        			$(this).data("Status", "CH");
        			this.innerHTML = $(this).data("CH");
        		}
        	});
        	$("#sentences").append(span);
        	$("#sentences").append("<br>");
		}
    }
});
</script>
</head>
<body>
	<div id="levels">
		<span><input type="checkbox" id="N1check" checked>N1</span>
		<span><input type="checkbox" id="N2check" checked>N2</span>
		<span><input type="checkbox" id="N3check" checked>N3</span>
		<span><input type="checkbox" id="N4check" checked>N4</span>
		<span><input type="checkbox" id="N5check" checked>N5</span>
	</div>
	<div id="sentences">
	</div>
	<div id="pages">

	</div>
</body>
</html>
