<?php
// Execute deploy script
$output = shell_exec('./deploy.sh');

// Send back execution output to the webhook originator.
echo $output;

// Return OK response
header("HTTP/1.1 200 OK");
