<!DOCTYPE html>
<?php
session_start();
	//Load from XML
	$doc = new DOMDocument();
	$doc->load('locations.xml');

	$locations_array = $doc->getElementsByTagName("locations");
	foreach($locations_array as $locs){
		$locations = $locs->getElementsByTagName("location1");
		$location = $locations->item(0)->nodeValue;
	}
	function replace_whitespaces($string){
		$new_location = preg_replace('/\s+/', '+',$string);
		return $new_location;
	}
	$new_location = replace_whitespaces($location);
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/xml";
	$search_variable = "video+game+stores+near";
	$api_key = "&sensor=false&key=AIzaSyDhr8wxu4MLOEqivMJc-E16vGxM_qhtgZk";
	$new_url = $url . "?query=$search_variable$new_location&radius=50" . $api_key;
	//$_SESSION['new_url']=$new_url;

	$current_file = file_get_contents($new_url);
	file_put_contents('gamestores.xml', $current_file);

	$doc = new DOMDocument();
	$doc->load('gamestores.xml');

	$names_array = array();
	$addr_array = array();
	$lat_array = array();
	$lng_array = array();
	$results = $doc->getElementsByTagName("result");
	foreach($results as $res){
		$names = $res->getElementsByTagName("name");
		$name = $names->item(0)->nodeValue;

		$addresses = $res->getElementsByTagName("formatted_address");
		$address = $addresses->item(0)->nodeValue;

		$lats = $res->getElementsByTagName("lat");
		$lat = $lats->item(0)->nodeValue;

		$lngs = $res->getElementsByTagName("lng");
		$lng = $lngs->item(0)->nodeValue;

		$names_array [] = $name;
		$addr_array [] = $address;
		$lat_array [] = $lat;
		$lng_array [] = $lng;	
	}
	$center_lat = $lat_array[0];
	$center_lng = $lng_array[0];
	//echo $center_lat;
	//echo $center_lng;
	session_start();
	$_SESSION['cent_lat']=$center_lat;
	$_SESSION['cent_lng']=$center_lng;
	session_write_close();
	$markerDoc = new DOMDocument();
	$node = $markerDoc->createElement("markers");
	$parnode = $markerDoc->appendChild($node);
	$size = sizeof($names_array);

	for($i=0; $i<$size; $i++){
		$node = $markerDoc->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("name", $names_array[$i]);
		$newnode->setAttribute("address", $addr_array[$i]);
		$newnode->setAttribute("lat", $lat_array[$i]);
		$newnode->setAttribute("lng", $lng_array[$i]);
	}
	$markerDoc->save("markerDoc.xml");

//Draw map
$center_lat = $_SESSION['cent_lat'];
$center_lng = $_SESSION['cent_lng'];
?>

<html>

<head>
<title>LANd Party- Here are nearby games!</title>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(<?php echo $center_lat?>, <?php echo $center_lng?>),
        zoom: 13,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;
	<?php //echo "new_url: " . $new_url?>
      // Change this depending on the name of your PHP file
      downloadUrl("markerDoc.xml", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address;
          var icon = customIcons[name] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>
  </script>

</head>
<hr>

<body onload="load()">
<!--Navigation bar under image -->
<div id="wrapper">
  <div id="logo" class="container">
    <p><a href="http://projects.cse.tamu.edu/crevia1/"><img src="our_images/logo_dbg.gif" alt="Are you game?" width="400" height="300"></a></p>
  </div>
  <hr>
  <div id="menu-wrapper">

    <div id="menu" class="container">
      <ul>
	<li><a href="index.php">Home, James</a></li>
	<li class = "current_page_item"><a href="searchResults2.php">You're lookin at maps!</a></li>
	<li><a href="contact_us.php">Contact Us!</a></li>
      </ul>
    </div>
  </div>
</div> <!--End of menu-->

<div id="page" class="container">
  <div id = "content">
    <div class="post">
        <div id="map" style="width: 90%; height: 75%; max-width:1000px; max-height: 800px"></div>
	<p> The rest of the magic is here! </p>
	<iframe = src="http://www.facebook.com/plugins/like.php?href=http://projects.cse.tamu.edu/crevia1/"
   	  scrolling = "no" frameborder="0"
   	  style="border::none; width:450px; height:80px"></iframe>
    </div>
  </div>

</div>

<!-- signing the page -->
<div id="footer">
  <p>
    Created 1 April 2013, by Colton Revia, Erick Chaves, and Kristen Musolf.  Design template by <a href="http://www.freecsstemplates.org">FCT</a>, background taken from <a href="1-background.com/stars_1.htm">StarFields' backgrounds</a>.
  </p>
</div>


</body>
</html>
