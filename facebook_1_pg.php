<!DOCTYPE html>
<html>
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
