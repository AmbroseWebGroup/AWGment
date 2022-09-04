<?php

abstract class RequestHandler {
  public function __construct($_request) {
    $this->request = $_request;
  }

  abstract public function handle($_db);
}