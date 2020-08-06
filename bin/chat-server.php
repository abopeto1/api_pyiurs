#!/usr/bin/env php
<?

require dirname(__DIR__).'/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\ChatGlobal;


$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new ChatGlobal()
    )
  ), 5556);

$server->run();
