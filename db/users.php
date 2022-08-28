<?php

class Users {

  private $conn;

  public function __construct($_conn) {
    $this->conn = $_conn;
  }
}
