<?php

include_once( dirname(__FILE__) . "/users.php");

class Database extends PDO {

  private $db_name;
  private $host;
  private $username;
  private $password;

  public $users;

  public function __construct() {
    $credentials = json_decode(file_get_contents(dirname(__FILE__)."/credentials.json"),false);

    $this->db_name = $credentials->db_name;
    $this->host = $credentials->host;
    $this->username = $credentials->username;
    $this->password = $credentials->password;

    parent::__construct('mysql:dbname='.$this->db_name.';host='.$this->host, $this->username, $this->password);

    $this->users = new Users($this);
  }

  public function execute($_sql, $_params) {
    $stmt = $this->prepare($_sql);
    $stmt->execute($_params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
