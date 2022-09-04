<?php

include_once( dirname(__FILE__) . "/request-handler.php" );

class GetHandler extends RequestHandler {
  public function __construct($_request) {
    parent::__construct($_request);
  }

  protected function generateSQL($_selectCols=[], $_tableName="users", $_filters=[], $_orderBy=[], $_limit=999) {
    $sql = "SELECT %s FROM %s WHERE %s ORDER BY %s LIMIT %s";
    $selectCols = sizeof($_selectCols)<1 ? "*" : "`".join("`,`", $_selectCols)."`";
    $filters = sizeof($_filters)<1 ? "1=1" : join(
      " AND ", array_map(
        function ($_val) {
          return sprintf("`%s` %s :%s", $_val[0], $_val[1], $_val[0]);
        }, $_filters
      )
    );
    $orderBy = sizeof($_orderBy)<1 ? "`id` ASC" : join(
      ", ", array_map(
        function ($_val) {
          return sprintf("`%s` %s", $_val[0], $_val[1]);
        }, $_orderBy
      )
    );

    return sprintf( $sql, $selectCols, $_tableName, $filters, $orderBy, $_limit );
  }

  protected function generateParams($_filters) {
    $params = [];
    foreach ($_filters as $filter) {
      $params[":".$filter[0]] = $filter[2];
    }
    return $params;
  }

  public function handle($_db) {
    $tableName = $this->request->extractTableName();
    $deconstructed = $this->request->deconstructed;
    $urlparams = $this->request->getParams();
    $filters = [];

    if (sizeof($deconstructed) === 1) {
      $selectCols = [];
    } else if (sizeof($deconstructed) === 2) {
      if ( isAllDigits($deconstructed[1]) ) {
        $selectCols = [];
        array_push($filters, ["id","=",$deconstructed[1]]);
      } else {
        $selectCols = explode("+", $deconstructed[1]);
      }
    } else if (sizeof($deconstructed) === 3) {
      $selectCols = explode("+", $deconstructed[1]);
      array_push($filters, ["id","=",$deconstructed[2]]);
    } else {
      kill_page("Request does not match with any correct formats");
    }

    if (isset($urlparams['filters'])) {
      $filters = array_merge(
        array_map(
          function ($_val) {
            return explode("|", $_val);
          }, explode(";", $urlparams['filters'])
        ), $filters
      );
    }
    $orderBy = isset($urlparams['order']) ? array_map(
      function ($_val) {
        return explode("|", $_val);
      }, explode(";", $urlparams['order'])
    ) : [];
    $limit = isset($urlparams['limit']) ? $urlparams['limit'] : 999;

    $sql = $this->generateSQL($selectCols, $tableName, $filters, $orderBy, $limit);
    $params = $this->generateParams($filters);
    return $_db->execute($sql, $params);
  }
}