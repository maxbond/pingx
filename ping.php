<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');

const NO_RESP_DELAY = -1;

$ip = $_GET['ip'] ?? null;

if (!$ip) {
    errorResponse('specify ip address');
}

$socket = @socket_create(AF_INET, SOCK_RAW,  getprotobyname('icmp'));

if ($socket === false) {
    errorResponse('can\'t open socket');
}

socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
socket_connect($socket, $ip, 0);


$startTime = hrtime(true);
$delay = NO_RESP_DELAY;
$package  = "\x08\x00\x19\x2f\x00\x00\x00\x00\x70\x69\x6e\x67";
socket_send($socket, $package, strlen($package), 0);
if (socket_read($socket, 255)) {
    $delay = (float) number_format((hrtime(true) - $startTime) / 1e+6, 2);
}
socket_close($socket);

if ($delay === NO_RESP_DELAY) {
    echo json_encode(['ok' => true, 'alive' => false]);
} else {
    echo json_encode(['ok' => true, 'alive' => true, 'delay' => $delay]);
}

/**
 * Error response
 * 
 * @param $message string
 */
function errorResponse(string $message)
{
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $message]);
    die();
}
