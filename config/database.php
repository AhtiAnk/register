<?php
  define('DB_HOST', 'localhost');
  define('DB_USER', 'user');
  define('DB_PASS', 'SqL12345');
  define('DB_NAME', 'register_project');

  //Connection
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if($conn->connect_error) {
    die('Connection Failed' . $conn->connect_error);
  };
?>