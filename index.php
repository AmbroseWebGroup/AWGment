<?php

include_once("./db/db.php");
include_once("./utility/helpers.php");
include_once("./utility/statement_constructors.php");

header("Content-Type: application/json");

$db = new Database();

preg_match('/index\.php\/(?<api_call>.*)/', $_SERVER["REQUEST_URI"], $api_call);
$api_call = explode( '/', $api_call["api_call"]);
foreach ($api_call as &$part) {
  $part = preg_replace('/^(.*)\?.+/', '$1', $part);
}
$api_call = array_filter($api_call);

$requestHandlers = [
  "GET" => function ($_db, $_api_call) {
    $tableName = "users";
    $filters = [];

    if (sizeof($_api_call) === 1) {
      $selectCols = [];
    } else if (sizeof($_api_call) === 2) {
      if ( preg_match('/^\d+$/', $_api_call[1]) === 1) {
        $selectCols = [];
        array_push($filters, ["id","=",$_api_call[1]]);
      } else {
        $selectCols = explode("+", $_api_call[1]);
      }
    } else if (sizeof($_api_call) === 3) {
      $selectCols = explode("+", $_api_call[1]);
      array_push($filters, ["id","=",$_api_call[2]]);
    } else {
      kill_page("Request does not match with any correct formats");
    }

    if (isset($_GET['filters'])) {
      $filters = array_merge(
        array_map(
          function ($_val) {
            return explode("|", $_val);
          }, explode(";", $_GET['filters'])
        ), $filters
      );
    }
    $orderBy = isset($_GET['order']) ? array_map(
      function ($_val) {
        return explode("|", $_val);
      }, explode(";", $_GET['order'])
    ) : [];
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 999;

    $sql = constructSelectQuery($selectCols, "users", $filters, $orderBy, $limit);
    $params = [];
    foreach ($filters as $filter) {
      $params[":".$filter[0]] = $filter[2];
    }
    return $_db->execute($sql, $params);
  },
  "POST" => function ($_db, $_api_call) {
    return;
  }
];

if (!in_array($_SERVER["REQUEST_METHOD"], array_keys($requestHandlers))) {
  kill_page("Unsupported request type");
}

echo json_encode($requestHandlers[$_SERVER["REQUEST_METHOD"]]($db, $api_call));
