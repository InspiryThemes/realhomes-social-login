<?php

require_once get_template_directory() . '/common/partials/vendor/autoload.php';
$fb = new Facebook\Facebook([
	'app_id' => '256348282348141',
	'app_secret' => 'todo_api_secret',
	'default_graph_version' => 'v2.10',
	'default_access_token' => 'access_token'
	]);
  
  try {
	// Returns a `Facebook\Response` object
	$response = $fb->get('/me?fields=id,email,name,first_name,last_name,picture');
  } catch(Facebook\Exception\ResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
  } catch(Facebook\Exception\SDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
  }
  
  $user = $response->getGraphUser();
  ?>
  <pre>
  <?php
// echo ($user['first_name']) . '<br>';
// echo ($user['last_name']) . '<br>';
// echo ($user['email']) . '<br>';
// echo ($user['picture']['url']) . '<br><br><br><br>';
print_r($user); 
 ?>
  </pre>
  <?php
