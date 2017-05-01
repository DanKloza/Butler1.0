<?php
    /* Make a call using Twilio. You can run this file 3 different ways:
     *
     * 1. Save it as call.php and at the command line, run 
     *        php call.php
     *
     * 2. Upload it to a web host and load mywebhost.com/call.php 
     *    in a web browser.
     *
     * 3. Download a local server like WAMP, MAMP or XAMPP. Point the web root 
     *    directory to the folder containing this file, and load 
     *    localhost:8888/call.php in a web browser.
     */

    // Step 1: Get the Twilio-PHP library from twilio.com/docs/libraries/php, 
    // following the instructions to install it with Composer.
    require_once "../vendor/autoload.php";
    use Twilio\Rest\Client;
    
    $sid = file_get_contents("/var/www/vhosts/Butler/api/src/account_sid.txt");
    $token = file_get_contents("/var/www/vhosts/Butler/api/src/auth_token.txt");
    echo $sid;
    echo $token;
    $client = new Client($sid, $token);

    try {
        $client->account->calls->create(
        "+18478090035",
        "+18722405562",
        array(
             "url" => "http://butler.andrijko.com/v1/callredirect.php",
             )
        );
    } catch (Exception $e) {
        echo $e->getMessage();
    }

