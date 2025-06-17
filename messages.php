<?php // Example 11: messages.php
  require_once 'header.php';
  
  if (!$loggedin) die("</div></body></html>");

  if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
  else                      $view = $user;

  if (isset($_POST['text']))
  {
    $text = sanitizeString($_POST['text']);

    if ($text != "")
    {
      $pm   = substr(sanitizeString($_POST['pm']),0,1);
      $time = time();
      queryMysql("INSERT INTO messages VALUES(NULL, '$user', '$view', '$pm', $time, '$text')");
    }
  }

  if ($view != "")
  {
    if ($view == $user) $name1 = $name2 = "Your";
    else
    {
      $name1 = "<a href='members.php?view=$view&r=$randstr'>$view</a>'s";
      $name2 = "$view's";
    }

    echo "<h3>$name1 Messages</h3>";
    
    echo <<<_END
      <form method='post' action='messages.php?view=$view&r=$randstr'>
        <fieldset data-role="controlgroup" data-type="horizontal">
          <legend>Type here to leave a message</legend>
          <input type='radio' name='pm' id='public' value='0' checked='checked'>
          <label for="public">Public</label>
          <input type='radio' name='pm' id='private' value='1'>
          <label for="private">Private</label>
        </fieldset>
      <textarea name='text'></textarea>
      <input data-transition='slide' type='submit' value='Post Message'>
    </form><br>
_END;

    date_default_timezone_set('UTC');

    if (isset($_GET['erase']))
    {
      $erase = sanitizeString($_GET['erase']);
      queryMysql("DELETE FROM messages WHERE id='$erase' AND recip='$user'");
    }
    
    // First get all public messages
    $query = "SELECT * FROM messages WHERE pm=0 ORDER BY time DESC";
    $result = queryMysql($query);
    $num = $result->rowCount();

    if ($num > 0)
    {
      while ($row = $result->fetch())
      {
        echo date('M jS \'y g:ia:', $row['time']) . " ";
        echo "<a href='messages.php?view=" . $row['auth'] . "&r=$randstr'>" . $row['auth']. "</a> ";
        if ($row['pm'] == 0)
          echo "wrote: &quot;" . html_entity_decode($row['message'], ENT_QUOTES | ENT_HTML5, 'UTF-8') . "&quot; ";
        if ($row['recip'] == $user)
          echo "[<a href='messages.php?view=$view&erase=" . $row['id'] . "&r=$randstr'>erase</a>]";
        echo "<br>";
      }
    }

    // Then get private messages where user is involved
    $query = "SELECT * FROM messages WHERE pm=1 AND (auth='$user' OR recip='$user') ORDER BY time DESC";
    $result = queryMysql($query);
    $num = $result->rowCount();

    if ($num > 0)
    {
      while ($row = $result->fetch())
      {
        echo date('M jS \'y g:ia:', $row['time']) . " ";
        echo "<a href='messages.php?view=" . $row['auth'] . "&r=$randstr'>" . $row['auth']. "</a> ";
        echo "whispered: <span class='whisper'>&quot;" . html_entity_decode($row['message'], ENT_QUOTES | ENT_HTML5, 'UTF-8'). "&quot;</span> ";
        if ($row['recip'] == $user)
          echo "[<a href='messages.php?view=$view&erase=" . $row['id'] . "&r=$randstr'>erase</a>]";
        echo "<br>";
      }
    }

    if ($num == 0)
    {
      echo "<br><span class='info'>No messages yet</span><br><br>";
    }
  }

  echo "<br><a data-role='button' href='messages.php?view=$view&r=$randstr'>Refresh messages</a>";
?>

    </div><br>
  </body>
</html>
