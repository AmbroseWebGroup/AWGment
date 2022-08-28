<?php

function constructSelectQuery($_selectCols=[], $_tableName="users", $_filters=[], $_orderBy=[], $_limit=999) {
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