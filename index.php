<?php
  // require_once("facebook.php");
	// if(is_callable('curl_init'))
	// {
		// echo "curl exists";
	// }
	// else
	// {
		// echo "curl doesn't exist";
	// }
	// echo "hello world1";
	// $facebook = new Facebook(array(
  // 'appId'  => '136730719847805',
  // 'secret' => 'eb120b2c585d641cfbbe1b29d3650b08',
  // 'cookie' => true,
	// ));
	// include 'http_build_url';
	// echo http_build_url("http://user@www.example.com/pub/index.php?a=b#files",
		// array(
			// "scheme" => "http",
			// "host" => "maps.googleapis.com",
			// "path" => "/maps/api/place/textsearch/",
			// "query" => "xml?query=gamestores+near+austin&sensor=false&key=AIzaSyBdDviVXZl_XOlkbvuAm5KQ0wRcS1bVVeA"
			// ),
			// HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT
		// );
	
	
	
	require_once ("facebook.php");
	//require_once ("xmlTest.php");
	
 //echo "hello world2";
  $config = array(
  'appId' => '136730719847805',
  'secret' => 'eb120b2c585d641cfbbe1b29d3650b08',
  'fileUpload' => false, // optional
  );
  //echo "config array instantiated";
  // $facebook = new Facebook($config);
  // echo "facebook created";
  // $user_id = $facebook->getUser();
  // echo "got user ". $user_id;

  ?>
  <html>
	<head></head>
	<body>
	<div id="fb-root"></div>
		<script>
		  // Additional JS functions here
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : '136730719847805', // App ID
			  channelUrl : '//projects.cse.tamu.edu/chaveser/channel.html', // Channel File
			  status     : true, // check login status
			  cookie     : true, // enable cookies to allow the server to access the session
			  xfbml      : true  // parse XFBML
			});

					// Additional init code here
		 // Here we subscribe to the auth.authResponseChange JavaScript Event. This event is fired
		  // for any auth related change, such as login, logout or session refresh. This means that
		  // whenever someone who was previously logged out then logs in, the correct case below 
		  // will be handled. 
		  FB.Event.subscribe('auth.authResponseChange', function(response) {
			// Here we specify what we do with the response anytime this event occurs. 
			if (response.status === 'connected') {
			  // The response object is returned with a status field that lets us know what the current
			  // login status of the person is. In this case, we're handling the situation where they 
			  // have logged in to the app.
			  testAPI();
			} else if (response.status === 'not_authorized') {
			  // In this case, the person is logged into Facebook, but not into the app, so we call
			  // FB.login() to prompt them to do so. 
			  // In real-life usage, you wouldn't want to immediately prompt someone to login 
			  // like this, for two reasons:
			  // (1) JavaScript created popup windows are blocked by most browsers unless they 
			  // result from direct user interaction (such as a mouse click)
			  // (2) it is a bad experience to be continually prompted to login upon page load.
			  FB.login();
			} else {
			  // In this case, the person is not logged into Facebook, so we call the login() 
			  // function to prompt them to do so. Note that at this stage there is no indication
			  // of whether they are logged into the app. If they aren't then they'll see the Login
			  // Dialog right after they login to Facebook. 
			  // The same caveats as above apply to the FB.login() call here.
			  FB.login();
			}
		  });
		  };
		  

		  // Load the SDK Asynchronously
		  (function(d){
			 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));
		</script>
		
	<?php
  //$me = null;
  $user_id = $facebook->getUser();
  //echo $user_id;
	if ($user_id) {
		echo "attempting try";
		echo "<br />";
	  try {
		$fql = 'SELECT location FROM user WHERE uid = ' . $user_id;
		echo "fql created";
		echo "<br />";
		$ret_obj = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));
		echo "ret_obj created";
		echo "<br />";
		echo '<pre>Location: ' . $ret_obj[0]['name'] . '</pre>';						 
		$uid = $facebook->getUser();
		$me = $facebook->api('/me');
		} catch (FacebookApiException $e) {
		echo "failed try, going to catch\n";
		echo "<br />";
		$login_url = $facebook->getLoginUrl(); 
        echo 'Please <a href="' . $login_url . '">login 1.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
		}
	} else {
	// No user, so print a link for the user to login
      $login_url = $facebook->getLoginUrl();
      echo 'Please <a href="' . $login_url . '">login 2.</a>';
	  //$me = $facebook->api('/me');
	  echo "me = " . $me;
	}
 
//4. login or logout
 // if ($me) {
    // $logoutUrl = $facebook->getLogoutUrl();
// } else {
    // $loginUrl = $facebook->getLoginUrl();
// }
//require_once ("example.php");
// $exampleUserLocation = 'Houston';
// echo $exampleUserLocation;

// if(file_exists('locations.xml')){
	// $xml = new DOMDocument();
	// $xml->load("locations.xml");
	// $xml->formatOutput = true;
	// $xml_user = $xml->createElement("User");
	// $xml_location = $xml->createElement("Location");
	// $xml_user->appendChild( $xml_location );
	// $xml->appendChild( $xml_user );
	// $xml->save("locations.xml");
// }else{
	// $xml = new DOMDocument();
	// $xml->formatOutput = true;
	// $xml_user = $xml->createElement("User");
	// $xml_location = $xml->createElement("Location");
	// $xml_user->appendChild( $xml_location );
	// $xml->appendChild( $xml_user );
	// $xml->save("locations.xml");
    
// }

  ?>
	</body>
</html>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php if ($me): ?>
    <?php echo "Welcome, ".$me['first_name']. ".<br />"; ?>
    <a href="<?php echo $logoutUrl; ?>">
      <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif">
    </a>
    <?php else: ?>
      <a href="<?php echo $loginUrl; ?>">
        <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif">
      </a>
    <?php endif ?>
  <!-- <p> Run xmlTest <a href = "http://projects.cse.tamu.edu/chaveser/xmlTest.php">here</a><p> -->
 <p>You can get to php from <a href="http://projects.cse.tamu.edu/crevia1/facebook_1_pg.php">here</a><p>
  <iframe = src="http://www.facebook.com/plugins/like.php?href=http://students.cse.tamu.edu/crevia1/"
   scrolling = "no" frameborder="0"
   style="border::none; width:450px; height:80px"><iframe>
 </body>
</html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Google Maps AJAX + mySQL/PHP Example</title>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"
            type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[
    var map;
    var markers = [];
    var infoWindow;
    var locationSelect;

    function load() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(25, 100),
        zoom: 4,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
      infoWindow = new google.maps.InfoWindow();

      locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'click');
        }
      };
   }

   function searchLocations() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
   }

   function clearLocations() {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;

     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     option.innerHTML = "See all results:";
     locationSelect.appendChild(option);
   }

   function searchLocationsNear(center) {
     clearLocations();

     var radius = document.getElementById('radiusSelect').value;
     var searchUrl = 'phpsqlsearch_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("name");
         var address = markerNodes[i].getAttribute("address");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));

         createOption(name, distance, i);
         createMarker(latlng, name, address);
         bounds.extend(latlng);
       }
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'click');
       };
      });
    }

    function createMarker(latlng, name, address) {
      var html = "<b>" + name + "</b> <br/>" + address;
      var marker = new google.maps.Marker({
        map: map,
        position: latlng
      });
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      markers.push(marker);
    }

    function createOption(name, distance, num) {
      var option = document.createElement("option");
      option.value = num;
      option.innerHTML = name + "(" + distance.toFixed(1) + ")";
      locationSelect.appendChild(option);
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

    //]]>
  </script>
  </head>

  <body style="margin:0px; padding:0px;" onload="load()">
    <div>
     <input type="text" id="addressInput" size="10"/>
    <select id="radiusSelect">
      <option value="25" selected>25mi</option>
      <option value="100">100mi</option>
      <option value="200">200mi</option>
    </select>

    <input type="button" onclick="searchLocations()" value="Search"/>
    </div>
    <div><select id="locationSelect" style="width:100%;visibility:hidden"></select></div>
    <div id="map" style="width: 100%; height: 80%"></div>
  </body>
</html>

