<?php
// Execute deploy script
// Change the path to the deploy script as needed
$output = shell_exec('./deploylive.a2.asiaphoenix.sh');

// Send back execution output to the webhook originator.
echo $output;

// Return OK response
header("HTTP/1.1 200 OK");
