<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


//$router->get('/auth/social', function(LinkThrow\LumenFacebookSdk\LumenFacebookSdk $fb) {
//    $login_link = $fb
//        ->getRedirectLoginHelper()
//        ->getLoginUrl('https://bit.ly/2OFGMhT', ['email']);
//
//    echo '<a href="' . $login_link . '">Log in with Facebook</a>';
//});
$router->get('/auth/social', function() {
    $fb = new Facebook\Facebook([
        'app_id' => '232053527445233',
        'app_secret' => '9e9c01400db518cc9fbba79bcdc25a4e',
        'default_graph_version' => 'v2.20',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://bit.ly/2OFGMhT', $permissions);

    echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
});

//$router->get('/auth/social/callback', function(LinkThrow\LumenFacebookSdk\LumenFacebookSdk $fb) {
//    try {
//        $token = $fb->getAccessTokenFromRedirect();
//
//        if ($token){
//            echo "jhakjsd";
//        }
//    } catch (Facebook\Exceptions\FacebookSDKException $e) {
//        // Failed to obtain access token
//        dd($e->getMessage());
//    }
//
//    // $token will be null if the user denied the request
//    if (! $token) {
//        // User denied the request
//        "token has not available";
//    } else {
//        var_dump($token);
//    }
//    echo "kajhdv";
//});
$router->get('/auth/social/callback', function() {
    $fb = new Facebook\Facebook([
        'app_id' => '232053527445233',
        'app_secret' => '9e9c01400db518cc9fbba79bcdc25a4e',
        'default_graph_version' => 'v2.20',
    ]);

    var_dump($fb);die;

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
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

// Logged in
    echo '<h3>Access Token</h3>';
    var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    echo '<h3>Metadata</h3>';
    var_dump($tokenMetadata);
});
