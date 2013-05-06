<!DOCTYPE html>
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
	<li class="current_page_item"><a href="index.php">Home, James.</a></li>
	<li><a href="facebook_1_pg.php">Arr, take me to your bookface</a></li>
	<li><a href="contact_us.php">Contact Us!</a></li>
      </ul>
    </div>
  </div> <!--End menu-wrapper-->

  <div id="two-column" class="container">

    <a href="http://facebook.com"><span style="display: block;">
    <div id="tbox1">
      <div class="box-style box-style02">
	<div class="content">
	  <p><img src="our_images/gaming.jpeg" alt="Find yourself?" align = left width=300px height=250px hspace = 10px vspace = 10px>
	    <h2>This snazzy link takes you to Facebook</h2>
	    (But doesn't log you in meaningfully yet)
	  </p>
	</div>
      </div>
    </div><!--End of tbox1-->
    </span></a>

    <a href="http://maps.google.com"><span style="display: block;">
    <div id="tbox2">
      <div class="box-style box-style01">
	<div class="content">
	  <p><img src="our_images/world.jpg" alt="Find the world" align = left width = 300px hspace = 10px vspace = 10px>
	    <h2>This is your IP<br>I'm a hackr MOFO</h2>
	    <?php 
	      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
	      echo $ip;
	    ?>
	    (Takes you too Google, because magic leads to more magic)
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
      <h2 class="title"><a href="facebook_1_pg.php">Find that which you seek here</a></h1>
      <div class = "entry">
         <p>Eventually</p>
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
