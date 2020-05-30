<?php
session_start();

require_once __DIR__ . '/../facebook/autoload.php';

// $fb_app_keys = rsl_facebook_app_keys();
$fb = new Facebook\Facebook([
	'app_id'                => 'app_id',
	'app_secret'            => 'app_secret',
	'default_graph_version' => 'v2.10',
]);

  $helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}


  
  try {
	$accessToken = $helper->getAccessToken();
  } catch(Facebook\Exception\ResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
  } catch(Facebook\Exception\SDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
  }
  
  if (! isset($accessToken)) {
	if ($helper->getError()) {
	  header('HTTP/1.0 401 Unauthorized');
	  echo "Error: " . $helper->getError() . "\n";
	  echo "Error Code: " . $helper->getErrorCode() . "\n";
	  echo "Error Reason: " . $helper->getErrorReason() . "\n";
	  echo "Error Description: " . $helper->getErrorDescription() . "\n";
	} else {
	  header('HTTP/1.0 400 Bad Request');
	  echo 'Bad request';
	}
	exit;
  }
  
// Logged in.  
  $_SESSION['rsl_facebook_token'] = (string) $accessToken->getValue();
  
  // User is logged in with a long-lived access token.
  // You can redirect them to a members-only page.
  header('Location: https://rh.o');
  