<?php

// Require all variables to have a value
function requireVariables($_vars) {
  foreach ($_vars as $var) {
    if (empty($var)) {
      kill_page("Missing a parameter");
    }
  }
  return true;
}

// Require atleast one variable to have a value
function requireVariable($_vars) {
  foreach ($_vars as $var) {
    if (!empty($var)) {
      return true;
    }
  }
  kill_page("Missing a parameter");
}

function kill_page($_message) {
  http_response_code(400);
  die(json_encode([$_message]));
}