<?php // Example 05: signup.php
  require_once 'header.php';

echo <<<_END
  <script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        $('#used').html('&nbsp;')
        return
      }

      $.post
      (
        'checkuser.php',
        { user : user.value },
        function(data)
        {
          $('#used').html(data)
        }
      )
    }

    function togglePassword() {
      var passwordInput = document.getElementById('password');
      var eyeIcon = document.getElementById('eye-icon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '👁️';
      } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '👁️‍🗨️';
      }
    }
  </script>  
_END;

  $error = $user = $pass = "";
  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if ($user == "" || $pass == "")
      $error = 'Not all fields were entered<br><br>';
    else
    {
      $result = queryMysql("SELECT * FROM members WHERE username='$user'");

      if ($result->rowCount())
        $error = 'That username already exists<br><br>';
      else
      {
        queryMysql("INSERT INTO members (username, password) VALUES('$user', '$pass')");
        echo <<<_END
        <div class='center'>
          <h2>Account Successfully Created!</h2>
          <p>You can now log in with your new account.</p>
          <a data-role='button' data-transition='slide' href='index.php?r=$randstr'>Go to Login</a>
        </div>
        <script>
          setTimeout(function() {
            window.location.replace('index.php?r=$randstr');
          }, 3000);
        </script>
        </div></body></html>
_END;
        exit();
      }
    }
  }

echo <<<_END
      <form method='post' action='signup.php?r=$randstr'>$error
      <div data-role='fieldcontain'>
        <label></label>
        Please enter your details to sign up
      </div>
      <div data-role='fieldcontain'>
        <label>Username</label>
        <input type='text' maxlength='16' name='user' value='$user'
          onBlur='checkUser(this)'>
        <label></label><div id='used'>&nbsp;</div>
      </div>
      <div data-role='fieldcontain' style="position: relative;">
        <label>Password</label>
        <input type='password' maxlength='16' name='pass' value='$pass' id='password'>
        <span id='eye-icon' onclick='togglePassword()' style='position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px;'>👁️‍🗨️</span>
      </div>
      <div data-role='fieldcontain'>
        <label></label>
        <input data-transition='slide' type='submit' value='Sign Up'>
      </div>
    </div>
  </body>
</html>
_END;
?>
