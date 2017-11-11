<?php
	session_start();
	if(isset($_POST["password"]) && $_POST["password"] == "830619"){
		$_SESSION["verified"] = True;
	}
	if(!(isset($_SESSION["verified"]) && $_SESSION["verified"] == True)){
		echo '<form action="index.php" method="post"><input type="text" name="password"><br><input type="submit" value="submit"></form>';
		exit;
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>会話翻訳練習帳</title>
<script src="jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<script>
var source;
var currentsentences;
var currentpage;
var sidelength = 4;
var sentencesPerPage = 1;
$(document).ready(function(){
	$("#CHToJP").change(function(){
		if(this.checked){
			$("#JPToCH")[0].checked = false;
			refresh();
		}
	});
	$("#JPToCH").change(function(){
		if(this.checked){
			$("#CHToJP")[0].checked = false;
			refresh();
		}
	});
    $.get("gettaiwa.php", function(data, status){
        data = JSON.parse(data);
        source = [];
        for(var i = 0;i < data.length;i++){
        	if(i == 0){
        		source.push({
        			id: data[i].id,
        			sentences: [{CH: data[i].CH, JP: data[i].JP}]
        		});
        	}else{
        		if(source[source.length-1].id == data[i].id){
        			source[source.length-1].sentences.push({CH: data[i].CH, JP: data[i].JP});
        		}else{
        			source.push({
	        			id: data[i].id,
	        			sentences: [{CH: data[i].CH, JP: data[i].JP}]
	        		});
        		}
        	}
        }
        console.log(source);
        source = source.sort(function() { return 0.5 - Math.random() });
        console.log(source);
        refresh();
    });
    function refresh(){
    	currentpage = 1;
    	currentsentences = source;
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
		var taiwa = source[currentpage-1];
		var id = document.createElement("span");
		id.innerHTML = currentpage+".";
		$(id).data("id", taiwa.id);
		$(id).data("Status", "index");
		$(id).click(function(){
			if($(this).data("Status") == "index"){
				$(this).data("Status", "id");
				this.innerHTML = $(this).data("id");
			}else if($(this).data("Status") == "id"){
				$(this).data("Status", "index");
				this.innerHTML = currentpage+".";
			}
		});
		$("#sentences").append(id);
        $("#sentences").append("<br>");
		for(var i = 0;i < taiwa.sentences.length;i++){
			var span = document.createElement("span");
			if($("#CHToJP")[0].checked){
        		span.innerHTML = taiwa.sentences[i].CH;
	        	$(span).data("CH",taiwa.sentences[i].CH);
	        	$(span).data("JP",taiwa.sentences[i].JP);
	        	$(span).data("Status", "CH");
	        }
	        if($("#JPToCH")[0].checked){
	        	span.innerHTML = taiwa.sentences[i].JP;
	        	$(span).data("CH",taiwa.sentences[i].CH);
	        	$(span).data("JP",taiwa.sentences[i].JP);
	        	$(span).data("Status", "JP");
	        }
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
	<div id="translationtype">
		<span><input type="radio" id="CHToJP" checked>中翻日</span>
		<span><input type="radio" id="JPToCH">日翻中</span>
	</div>
	<div id="sentences">
	</div>
	<div id="pages">

	</div>
</body>
</html>
