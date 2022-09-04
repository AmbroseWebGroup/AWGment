<?php

include_once( dirname(__FILE__) . "/get-handler.php" );
include_once( dirname(__FILE__) . "/post-handler.php" );

class APIRequest {

  private $method;
  private $request;
  private $params;
  public $deconstructed;

  public function __construct($_method, $_request, $_params) {
    $this->method = $_method;
    $this->request = $_request;
    $this->params = $_params;

    $this->deconstructed = $this->deconstruct();
  }

  public function getURL() {
    return $this->request;
  }

  public function getParams() {
    return $this->params;
  }

  public function extractTableName() {
    return $this->deconstructed[0];
  }

  private function deconstruct() {
    preg_match('/index\.php\/(?<api_call>.*)/', $this->request, $deconstructed);
    $deconstructed = explode( '/', $deconstructed["api_call"]);
    foreach ($deconstructed as &$part) {
      $part = preg_replace('/^(.*)\?.+/', '$1', $part);
    }
    $deconstructed = array_filter($deconstructed);
    return $deconstructed;
  }

  public function createHandler() {
    switch ($this->method) {
      case "GET":
        return new GetHandler($this);
      case "POST":
        return new PostHandler($this);
      default:
        kill_page("Unsupported request method.");
    }
  }
}