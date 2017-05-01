<?php
include __DIR__."/firebase.php";

$first_time_user = 0;
$no_time_error = 0;
$bb = new ButlerBase();

date_default_timezone_set("America/Chicago");

$from = $_POST["From"];

$user = $bb->get_user($from);
if ($user == null) {
    $first_time_user = 1;
    $bb->create_user($from, "boss");
    $user = $bb->get_user($from);
}

$msg  = $_POST["Body"];

header('Content-Type: text/xml');

$matches = 0;
$result = array();

$timestamp;

if (preg_match("/Remind me to (.*) at (.*)/i", $msg ,$result)) {
    $matches = 1;
    $timestamp = strtotime($result[2]);
    if (!$timestamp) {
        $no_time_error = 1;
    }
    // add to db task
}

$watson_used = 0;
$output;

if (!$matches) {
    exec("python ../../src/watson.py '$msg'", $output);
    //var_dump($output);
    $matches = 1;
    $watson_used = 1;
}

$response = $output[0];
$intent = $output[1];
$time = $output[2];
$request = $output[3];


?>

<Response>
    <?php
    if ($intent == "emergency") {
        exec("python ../../src/send_call.py $from");
    }
    ?>
    <Message>
    <Body>
        <?php
        if ($matches) {
            if ($no_time_error) {
                echo "Sorry, I couldn't understand the time you requested.";
            } else {
                if (!$watson_used) $bb->add_task_to_user($from, $result[1], $timestamp);
                else {
                    echo "$response";
                    $t = strtotime("$time");
                    //$t = $t + 5 * 60 * 60;
                    //echo "$t";
                    if ($intent == "reminder") {
                        $bb->add_task_to_user($from, "Reminder: $request", strtotime("$t"));
                    }
                    else if ($intent == "question") {
                        $request = urlencode($request);
                        echo "https://www.google.com/search?q=$request&amp;btnI";
                    }
                }
                if (!$watson_used) echo "Okay! Will remind you to '{$result[1]}' at {$result[2]}";
            }
        } else {
            echo "Sorry, I can't understand you yet. Can you be more clear?";
        }
        ?>
        </Body>
    </Message>
</Response>
