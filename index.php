<?php

use Psr\Http\Message\ResponseInterface;

require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . '/settings.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $recaptchaUserToken = $_POST['g-recaptcha-response'];

    $postData = [
        "event" => [
            'token' => $recaptchaUserToken,
            'expectedAction' => "login",
            'siteKey' => RECAPTCHA_SITE_KEY
        ]
    ];

    $googleResponse = httpPost(RECAPTCHA_URL, $postData);
    $responseArray =  json_decode($googleResponse->getBody()->getContents(), true);
    $riskAnalysis = $responseArray['riskAnalysis'];
    $score = $riskAnalysis['score'];

    if ($score >= 0.5)
    {
        die("Recaptcha PASSED. Now we would otherwise check username/password here.");
    }
    else
    {
        // recaptcha not verified, so need to show error response
        die("Recaptcha verification failed. Google thinks you are a bot.");
    }
}


/**
 * Helper method to send a POST request with the payload expressed in application/json format.
 * @param string $url - the URL to send the POST request to.
 * @param array $data - the array of data to send in the POST request
 * @return ResponseInterface
 */
function httpPost(string $url, array $data) : ResponseInterface
{
    $requestFactory = new \Slim\Psr7\Factory\RequestFactory();
    $httpClient = new \Http\Client\Curl\Client();

    // create the POST request
    $request = $requestFactory->createRequest('POST', $url);
    $request = $request->withHeader('Content-Type', 'application/json');
    $request->getBody()->write(json_encode($data));

    // send it and get the response
    return $httpClient->sendRequest($request);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>reCAPTCHA Demo</title>
        <meta name="description" content="A demo codebase for reCAPTCHA in PHP."/>
        <meta name="author" content="author" />
        <meta name="keywords" content="keywords" />
        <style type="text/css">.body { width: auto; }</style>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    </head>
    <body>

        <form id="login_form" method="post" action="/">
        
            <label for="username">Username:</label>
            <input type="text" name="username" />

            <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY; ?>" data-action="LOGIN"></div>
            <input type="submit">
        </form>
        
    </body>
</html>