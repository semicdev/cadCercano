<!DOCTYPE html>
<html>
<head>
	<title>Cadenamientos mas Cercanos en Carretera</title>
	<link rel="stylesheet" href="http://sctcloud.com.mx/tools/cdn/bootstrap/3.2.0/css/bootstrap.min.css">
	<style type="text/css">
		html, body, #map-canvas{	
			height: 100%;
			}
	</style>
</head>
<body class="row">
	<div id="map-canvas" class="col-lg-12"></div>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAeTbCOpuPIKT4i9n8iUQsBHNUt_MWjtog&sensor=false"></script>
	<script src="http://sctcloud.com.mx/tools/cdn/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
	<script src="map.js" type="text/javascript"></script>
	<script type="text/javascript">
		$.getJSON('getCarretera.php', {carretera: 'value1'}, function(json, textStatus) {
			var path=[]; 
			$.each(json, function(index, val) {
				 path.push(new google.maps.LatLng(val.latitud, val.longitud));
			     //setMarcador(new google.maps.LatLng(val.latitud, val.longitud), val.cadGeometrico.toString())
			});
			map.panTo(path[0]);
			var elemento = new google.maps.Polyline({
    				path: path,
    				strokeColor:'#0019FF' ,
    				strokeOpacity: 1,
    				strokeWeight: 4
  					});
			elemento.setMap(map);
			console.log(path);
		});
		var markers = [];
		google.maps.event.addListener(map, 'click', function(event) {
   			var marker = setMarcador(event.latLng, "x");
   			markers.push(marker);
   			if(markers.length < 3){
   				cadMasCercano(marker);
   			}else if(markers.length == 3){
   				calcularTriangulo(markers[2],markers[0],markers[1]);
   			}
    	});

		function setMarcador(latLng,title){
			return new google.maps.Marker({
      		position: latLng,
      		map: map,
      		title: title,
      		draggable:true
        	}); 			
		}
		function calcularTriangulo(pointA, pointB, pointC){
			var a,b,c;
					a =  distHaversine(pointA.getPosition(), pointB.getPosition())*1;
					
					b =  distHaversine(pointB.getPosition(),pointC.getPosition())*1;
					
					c =  distHaversine(pointC.getPosition(),pointA.getPosition())*1;
					


			//calculate semi perimetro	
			var semi_perimetro = (b + c + a)/2;
			//calculate area
			var	area = (semi_perimetro * (semi_perimetro-a) * (semi_perimetro-b) * (semi_perimetro-c));
				area = Math.sqrt(area);
			//calculate h
			var h = (2 * area) / b;
			//calculate x 
			var x = Math.sqrt((a * a) - (h * h));

			console.log("a= "+a);
			console.log("b= "+b);
            console.log("c= "+c);
            console.log("h= "+h);
            console.log("semi= "+semi_perimetro);
            console.log("area ="+ area);
			console.log("x= "+x);
			console.log("cadenamiento= "+(pointB.cadGeometrico+x));


		}

		function cadMasCercano(marker){
			$.getJSON('cadMasCercano.php', {lng: marker.getPosition().lng(), lat:marker.getPosition().lat() }, function(json, textStatus) {
					/*optional stuff to do after success */
					console.log(json);
					var cad = [];
					$.each(json, function(index, val) {
						 /* iterate through array or object */
						 var marker = setMarcador(new google.maps.LatLng(val.latitud, val.longitud), val.cadGeometrico.toString()+" "+cad.length);
						marker.cadGeometrico =  val.cadGeometrico;
						cad.push( marker );
					});

					var b, c;
					console.log(cad);
					b = (cad[0].cadGeometrico < cad[1].cadGeometrico)? cad[0] : cad[1]; 
					c = (cad[0].cadGeometrico < cad[1].cadGeometrico)? cad[1] : cad[0];  
					console.log(b.cadGeometrico + " < "+c.cadGeometrico);
					calcularTriangulo(marker, b, c);
					
			});
		}

	</script>
</body>
</html>