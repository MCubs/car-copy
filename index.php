<?php 
require_once("geo/geo.class.php");
require_once("geo/init.php")
?>
<!DOCTYPE html>
<html>
<head>
	<title>Geo</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$(function(){

			function parseResult(obj){
				var data = $.parseJSON(obj);
				if(data.success == 'false'){
					alert('cities not found');
				}
				var html = '<table><tbody>';
				for(var i=0; i<data.length; i++){
					html += '<tr><td>'+ data[i].city +'</td><td>'+ data[i].lat +'</td><td>'+ data[i].long +'</td></tr>';
				}
				html += '</tbody></table>';	
				return html;		
			}

			$(".coords").submit(function(e){
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: $(this).attr("action"),
					data: {
						'city': $(this).find("[name='city']").val()
					},
					success: function(response){
						var html = parseResult(response);
						$("#result").html(html);
					}
				});
			});

			$(".search").submit(function(e){
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: $(this).attr("action"),
					data: {
						'lat': $(this).find("[name='lat']").val(),
						'long': $(this).find("[name='long']").val(),
						'distance': $(this).find("[name='distance']").val()
					},
					success: function(response){
						var html = parseResult(response);
						$("#result").html(html);
					}
				});
			});

		});
	</script>
</head>
<body>

<form class="coords" method="POST" action="/geo/search/coords/">
	<a>Поиск координат по названию города(Части названия)</a>
	<input type="text" name="city" value="" placeholder="Город" required="required"/>
	<input type="submit" name="" value="Ok"/>
</form>

<form class="search" method="POST" action="/geo/search/cities/">
	<a>Поиск ближайших городов от указанных координат</a>
	<input type="text" name="lat" value="" placeholder="Широта" required="required"/>
	<input type="text" name="long" value="" placeholder="Долгота" required="required"/>
	<input type="text" name="distance" value="" placeholder="Радиус в метрах" required="required"/>
	<input type="submit" name="" value="Ok"/>
</form>

<div id="result"></div>

</body>
</html>