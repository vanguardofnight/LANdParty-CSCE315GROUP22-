<!DOCTYPE html>
<html>

<head>
<a href="http://projects.cse.tamu.edu/crevia1/"><img src="Logo.png" alt="Are you game?" width="393" height="150"></a>
<title>LANd Party- Find nearby games</title>
<style type="text/css">
body {background-color:gray}
p {color:red}
</style>
</head>
<hr>

<body>

<?php
echo "Hullo!";

require_once("facebook.php");

$config = array();
$config['appId'] = '136730719847805';
$config['secret'] = 'eb120b2c585d641cfbbe1b29d3650b08';
$config['fileUpload'] = false;

$facebook = new Facebook($config);

$params = array(
  'scope' => 'read_stream, friends_likes',
);

$loginUrl = $facebook ->getLoginUrl($params);

?>
</body>
</html>
