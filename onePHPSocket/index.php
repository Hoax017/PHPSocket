
<?php
error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";

/* Get the port for the WWW service. */
// $service_port = getservbyname('www', 'tcp');
$service_port = 10001;

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

$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: 127.0.0.1\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

echo "Sending HTTP HEAD request...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Ecrire au serveur:\n";
while($in = fgets(STDIN)){
	echo "Envoie...";
	if(@socket_write($socket, $in."\n", strlen($in) + 1))
		echo "OK.\n";
	else
		echo "ERROR.\n";
	echo "Reponse:";
	echo socket_read($socket, 2048);
	if ($in == 'quit' || $in == 'shutdown') {
		break;
	}
	echo "Ecrire au serveur:\n";
}

echo "Sending shutdown...";
socket_write($socket, "shutdown", strlen("shutdown"));
echo "OK.\n";
echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";
?>
