<?php
require_once ("facebook.php");
//require('GoogleGeocode.php');
  
  session_start();
  $_SESSION = array();
  $config = array(
  'appId' => '136730719847805',
  'secret' => 'eb120b2c585d641cfbbe1b29d3650b08',
  'fileUpload' => true, // optional
  //'cookie' => true,
  );

  $facebook = new Facebook($config);
  $login_params = array(
		'scope' => 'user_location, user_about_me',
		'redirect_uri' => 'http://projects.cse.tamu.edu/crevia1',
		'display' =>  'page'
  );
  
  $login_status_params = array(
	'ok_session' => 'http://projects.cse.tamu.edu/crevia1',
	'no_user' => $facebook->getLoginUrl($login_params),
	'no_session' => $facebook->getLoginUrl($login_params)
  );
  $user_id = $facebook->getUser();
  $access_token = $facebook->getAccessToken;
  
  $logout_params = array(
		'next' =>  'http://projects.cse.tamu.edu/chaveser/'
  );
 if ($user_id) {
    $logoutUrl = $facebook->getLogoutUrl($logout_params);
} else {
    $loginUrl = $facebook->getLoginUrl($login_params);
}
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
			
			FB.Event.subscribe('auth.login', function(){
				window.location.reload();
			});
			
					// Additional init code here
		 // Here we subscribe to the auth.authResponseChange JavaScript Event. This event is fired
		  // for any auth related change, such as login, logout or session refresh. This means that
		  // whenever someone who was previously logged out then logs in, the correct case below 
		  // will be handled. 
		  //window.location.reload();
		  FB.Event.subscribe('auth.authResponseChange', function(response) {
			
			// Here we specify what we do with the response anytime this event occurs. 
			if (response.status === 'connected') {
			  // The response object is returned with a status field that lets us know what the current
			  // login status of the person is. In this case, we're handling the situation where they 
			  // have logged in to the app.
			  //testAPI();
			 
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
		// Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Good to see you, ' + response.name + '.');
    });
  }
</script>
</body>
</html>
		
	<?php
  
	if ($user_id) {

	  try {
		$fql = 'SELECT name FROM user WHERE uid = '. $user_id;
		$ret_obj = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));
		$user_name = $ret_obj[0]['name'];
		//echo '<pre>Name: ' . $user_name . '</pre>';	
		$fql2 = 'SELECT current_location FROM user WHERE uid = '. $user_id;	
		$ret_obj2 = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql2,
                                 ));		
		$user_location = $ret_obj2[0]['current_location']['name'];
		//echo '<pre>Location: ' . $user_location . '</pre>';
		} catch (FacebookApiException $e) {
		  echo "<br />";
		  echo "Message: " . $e->getMessage();
		  echo "<br />";
		  echo "<br />";
		  echo $e->getTraceAsString();
		  echo "<br />";
		  echo "<br />";
        	  echo 'Please <a href="' . $login_url . '">login 1.</a>';
        	  error_log($e->getType());
        	  error_log($e->getMessage());
		}
	} else {
	// No user, so print a link for the user to login
      $login_url = $facebook->getLoginUrl($login_params);
	}
	//Save to XML
	$locations_array = array();
	$locations_array [] = array(
		'name' => $user_name,
		'location' => $user_location,
	);	

	$doc = new DOMDocument();
	$doc ->formatOutput = true;
	$preserveWhiteSpace = true;
	$r = $doc->createElement( "locations" );
	$doc-> appendChild($r);

	foreach($locations_array as $locs)
	{
	$b = $doc->createElement("User");
	$name = $doc->createElement("name");
	$name -> appendChild($doc->createTextNode($locs['name']));
	$b->appendChild($name);
	$r->appendChild($b);

	$b = $doc->createElement("location1");
	$location = $doc->createElement("location2");
	$location -> appendChild($doc->createTextNode($locs['location']));
	$b->appendChild($location);
	$r->appendChild($b);

	}
	$doc->save("locations.xml");

	//Load from XML
  ?>
	</body>
</html>

<!DOCTYPE html>
<!--<html xmlns:fb="http://www.facebook.com/2008/fbml">-->
<html>

<head>
<title>LANd Party- Find nearby games</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen">

</head>

<body>
<!--Navigation bar under image -->
<div id="wrapper">
  <div id="logo" class="container">
    <p><a href="http://projects.cse.tamu.edu/crevia1/"><img src="our_images/logo_dbg.gif" alt="Are you game?" width="400" height="300"></a></p>
  </div>
  <hr>
  <div id="menu-wrapper">

    <div id="menu" class="container">
      <ul>
	<li class="current_page_item"><a href="index.php">Welcome Home!</a></li>
	<li><a href="searchResults2.php">To the Map!</a></li>
	<li><a href="contact_us.php">Contact Us!</a></li>
      </ul>
    </div>
  </div> <!--End menu-wrapper-->

  <div id="two-column" class="container">
	<!--Log in link-->
    <a href="<?php if (!$user_id): echo $loginUrl; else: echo 'www.facebook.com'; endif ?>"><span style="display: block;">
    <div id="tbox1">
      <div class="box-style box-style02">
	<div class="content">
	  <p>
	  <img src="our_images/gaming.jpeg" alt="Find yourself?" align = left width=300px height=250px hspace = 10px vspace = 10px>
	    <h2>This snazzy link takes you to Facebook</h2>
	    (After you log in it brings you back, leaves you there when you log out)
	    <img src="our_images/logo_facebook.png" width = 20px; height = 20px>
	  </p>
	</div>
      </div>
    </div><!--End of tbox1-->
    </span></a>

    <a href="searchResults2.php"><span style="display: block;">
    <div id="tbox2">
      <div class="box-style box-style01">
	<div class="content">
	  <p>
          <?php if ($user_id): 
	    echo '
		<img src="our_images/world.jpg" alt="Find the world" align = left width = 300px hspace = 10px vspace = 10px>
		<h2>Welcome, check here to see game stores in your area!</h2>
		Cool huh?
	    ';
	    else:
	    echo '
		<img src="our_images/world.jpg" alt="Find the world" align = left width = 300px hspace = 10px vspace = 10px>
	        <h2>This is your IP<br>I am a hackr MOFO</h2>
	    ';
	    endif
	  ?>
	    <?php //Grabs IP address 
	      //$ip=$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
	      //echo $ip;
	    ?>
	  </p>
	</div>
      </div>
    </div><!--End of tbox2-->
    </span></a>

  </div><!--End two-column container-->

</div><!--End wrapper-->

<div id="page" class="container">
  <div id = "content">
    <div class="post">
      <h2 class="title"><a href="searchResults2.php">Find that which you seek here</a></h1>
      <div class = "entry">
         <p>Finally!</p>
      </div>
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
