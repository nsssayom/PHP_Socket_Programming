<?php

set_time_limit(0);

$ip = gethostbyname('localhost');
$port = 1935;

if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
    echo "socket_create() Fail to create:".socket_strerror($sock)."\n";
}
else{
    echo "Socket creation successful\n";
}

while (($ret = socket_bind($sock,$ip,$port)) < 0) {
    echo "socket_bind() Fail to bind:".socket_strerror($ret)."\n";
    echo "Failed to bind socket with port: $port\n";
    $port++;
}

    echo "Socket Binding success. Port: $port\n";

if(($ret = socket_listen($sock,4)) < 0) {
    echo "socket_listen() Fail to listen:".socket_strerror($ret)."\n";
}

$clients = array($sock);

while (true){
    $read = $clients;
    $write = null;
    $except = null;

    if (socket_select($read, $write, $except, 0) < 1){
        continue;
    }
    
    if (in_array($sock, $read))
    {
        $clients[] = $newsock = socket_accept($sock);
        socket_write($newsock, "There are ".(count($clients) - 1)." client(s) connected to the server\n");
        socket_getpeername($newsock, $ip, $port);
        echo "New client connected: {$ip}\n";
        $key = array_search($sock, $read);
        unset($read[$key]);
    }


foreach ($read as $read_sock){
        // read until newline or 1024 bytes
        // socket_read while show errors when the client is disconnected, so silence the error messages
        $data = @socket_read($read_sock, 4096, PHP_BINARY_READ);
        // check if the client is disconnected
        if ($data === false)
        {
            // remove client for $clients array
            $key = array_search($read_sock, $clients);
            unset($clients[$key]);
            echo "client disconnected.\n";
            continue;
        }
        $data = trim($data);
        if (!empty($data))
        {
            echo " send {$data}\n";
            // do sth..
            // send some message to listening socket
            ////socket_write($read_sock, $send_data);
            // send this to all the clients in the $clients array (except the first one, which is a listening socket)
            ////foreach ($clients as $send_sock)
            ////{
                ////if ($send_sock == $sock)
                    ////continue;
                ////socket_write($send_sock, $data);
            ////} // end of broadcast foreach
        }
    } // end of reading foreach
}
// close the listening socket
socket_close($sock);


?>