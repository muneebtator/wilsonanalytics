var app = new wilson();
app.initapp("GeoMe", "wilson.geome", ['datastore']);
	$.get("http://ipinfo.io", function(response) {
  		 app.datastore("CHECK", response.country, null, "geomelocations", website, function(data){
  		 	if(data.exist == false){
  		 		app.datastore("STORE", response.country, 1, "geomelocations", website, null);
  		 	}else{
  		 		app.datastore("GET", response.country, null, "geomelocations", website, function(data){
  		 			hits = data[0].data;
  		 			hits++;
  		 				app.datastore("UPDATE", response.country, hits, "geomelocations", website, null);
  		 		});
  		 	}
  		 });
	}, "jsonp");