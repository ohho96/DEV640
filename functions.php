<?php // Example 01: functions.php
  // Set execution time limit
  set_time_limit(60); // Increase to 60 seconds
  
  $host = '127.0.0.1:3306';    // Local MySQL
  $data = 'fantastic6';   // Database name
  $user = 'root';         // MySQL username
  $pass = '<Strong>012';             // MySQL password (empty by default)
  $chrs = 'utf8mb4';
  $attr = "mysql:host=$host;dbname=$data;charset=$chrs";
  $opts =
  [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    PDO::ATTR_TIMEOUT           => 10,    // Increased timeout
  ];
  
  $maxRetries = 1;  // Reduced retries to avoid timeout
  $retryCount = 0;
  
  while ($retryCount < $maxRetries) {
    try {
      $pdo = new PDO($attr, $user, $pass, $opts);
      break;
    }
    catch (PDOException $e) {
      $retryCount++;
      if ($retryCount == $maxRetries) {
        die("Connection failed: " . $e->getMessage() . "<br>Please check your MySQL credentials.");
      }
      usleep(100000); // Reduced wait time
    }
  }

  function createTable($name, $query)
  {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
  }

  function queryMysql($query)
  {
    global $pdo;
    return $pdo->query($query);
  }

  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }

  function sanitizeString($var)
  {
    global $pdo;

    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);

    $result = $pdo->quote($var);          // This adds single quotes
    return str_replace("'", "", $result); // So now remove them
  }

  function showProfile($user)
  {
    global $pdo;

    if (file_exists("$user.jpg"))
      echo "<img src='$user.jpg' style='float:left;'>";

    $result = $pdo->query("SELECT * FROM profiles WHERE user='$user'");

    while ($row = $result->fetch())
    {
      die(stripslashes($row['text']) . "<br style='clear:left;'><br>");
    }
    
    echo "<p>Nothing to see here, yet</p><br>";
  }
?>
