<?php

include_once( dirname(__FILE__) . "/api_classes/api-request.php" );
include_once( dirname(__FILE__) . "/utility/helpers.php" );
include_once( dirname(__FILE__) . "/db/db.php" );

$db = new Database();

$request = new APIRequest($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"], $_GET);
$handler = $request->createHandler();

echo json_encode($handler->handle($db));