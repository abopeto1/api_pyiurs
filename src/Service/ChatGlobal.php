<?php

namespace App\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatGlobal implements MessageComponentInterface
{
  protected $clients;

  public function __construct(){
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn){
    $this->c->attach($conn);
    echo "New Connec ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg){
    $numRecV = count($this->clients) - 1;
    echo sprintf("Connection %d sem '%s' to %d \n",$from->resourceId,$msg,$numRecV );

    foreach ($this->clients as $c) {
      if($from !== $c){
        $c->send($msg);
      }
    }
  }

  public function onClose(ConnectionInterface $conn){

  }

  public function onError(ConnectionInterface $conn, \Exception $e){

  }
}
