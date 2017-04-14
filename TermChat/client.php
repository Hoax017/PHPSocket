<?php
error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";

/* Get the port for the WWW service. */
// $service_port = getservbyname('www', 'tcp');
$service_port = 9000;

/* Get the IP address for the target host. */
// $address = gethostbyname('127.0.0.1');
$address = "127.0.0.1";

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}
$Header = "GET / HTTP/1.1\nsec-websocket-version: 13\nupgrade: websocket\nconnection: upgrade\nsec-websocket-key: ".md5(time())."\nHost: 127.0.0.1";
$in = "$Header\n\n";

socket_write($socket, $in, strlen($in));
echo socket_read($socket, 2048);
$out = '';

echo "Ecrire au serveur:\n";
while($in = fgets(STDIN)){
	echo "Envoie...";
	$in = "$in\n";
	if (@socket_write($socket, $in, strlen($in)))
		echo "OK.\n";
	else
		echo "PASOK\n";
	echo "Reponse:";
	echo socket_read($socket, 2048)."\n";
	if ($in == 'quit' || $in == 'shutdown') {
		break;
	}
	echo "Ecrire au serveur:\n";
}

echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";
?>

