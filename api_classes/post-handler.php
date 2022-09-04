<?php

include_once( dirname(__FILE__) . "/request-handler.php" );

class PostHandler extends RequestHandler {
  public function __construct($_request) {
    parent::__construct($_request);
  }

  protected function generateSQL() {
  }

  protected function generateParams() {
  }

  public function handle($_db) {
    $tableName = $this->request->extractTableName();

    $sql = $this->generateSQL();
    $params = $this->generateParams();
    return $_db->execute($sql, $params);
  }
}