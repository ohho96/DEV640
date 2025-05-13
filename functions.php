<?php
$host = 'localhost';
$data = 'robinsnest';
$user = 'robinsnest';
$pass = 'password';
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
try {
  $pdo = new PDO($attr, $user, $pass, $opts);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
function createTable($name, $query) {
  queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
}
function queryMysql($query) {
  global $pdo;
  return $pdo->query($query);
}
function destroySession() {
  $_SESSION = array();
  if (session_id() != "" || isset($_COOKIE[session_name()]))
    setcookie(session_name(), '', time()-2592000, '/');
  session_destroy();
}
function sanitizeString($var) {
  global $pdo;
  $var = strip_tags($var);
  $var = htmlentities($var);
  if (get_magic_quotes_gpc()) $var = stripslashes($var);
  $result = $pdo->quote($var);
  return str_replace("'", "", $result);
}
function showProfile($user) {
  global $pdo;
  if (file_exists("$user.jpg"))
    echo "<img src='$user.jpg' style='float:left;'>";
  $result = $pdo->query("SELECT * FROM profiles WHERE user='$user'");
  while ($row = $result->fetch()) {
    echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
  }
  echo "<p>Nothing to see here, yet</p><br>";
}
?>