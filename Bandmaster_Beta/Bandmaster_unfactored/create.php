<?php

  require 'checkuser.php';

  /*
    createUser will create a new user account in the server
  */
  function createUser($username, $password) {

    // salt the password
    $password = "dB9" . $password . "87Xa";
    // Convert the password from UTF8 to UTF16 (little endian)
    $password = iconv('UTF-8', 'UTF-16LE', $password);
    // Encrypt it with the MD4 hash
    $MD4Hash = hash('md4', $password);
    
    // add entry into the DB
    $file = file_get_contents('users.json', true);
    $data = json_decode($file, true);
    unset($file);
    $data[] = array('username' => $username, 'password' => $MD4Hash);

    // sort the DB by usernames
    $sortArray = array();
    foreach($data as $user) {
      foreach($user as $key => $value) {
        if (!isset($sortArray[$key])) {
          $sortArray[$key] = array();
        }
        $sortArray[$key][] = $value;
      }
    }
    $orderby = "username";
    array_multisort($sortArray[$orderby], SORT_ASC, $data);

    // put the data back into the DB
    $result = json_encode($data);
    file_put_contents('users.json', $result);
    unset($result);
    $log = fopen("create_log.txt", "a");
    date_default_timezone_set('America/New_York');
    fwrite($log, "\n\n" . date("m/d/Y @ g:i:sA") . " - Creating account: " . $username . "\n");

    // create new directories for the new user
    // and log everything

    // ROOT
    $path = "./" . $username;
    mkdir($path);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $path . "\n");
    copy("./templates/index.html", $path . "/index.html");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/index.html to: " . $path . "/index.html\n");
    copy("./templates/functions.php", $path . "/functions.php");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/functions.php to: " . $path . "/functions.php\n");
    copy("./templates/upload.php", $path . "/upload.php");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/upload.php to: " . $path . "/upload.php\n");
    // UPLOADS
    $newdir = $path . "/uploads";
    mkdir($newdir);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $newdir . "\n");
    // JSON
    $newdir = $path . "/JSON";
    mkdir($newdir);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $newdir . "\n");
    copy("./templates/JSON/settings.json", $path . "/JSON/settings.json");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/JSON/settings.json to: " . $path . "/JSON/settings.json\n");
    copy("./templates/JSON/songs.json", $path . "/JSON/songs.json");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/JSON/songs.json to: " . $path . "/JSON/songs.json\n");
    copy("./templates/JSON/taskList.json", $path . "/JSON/taskList.json");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/JSON/taskList.json to: " . $path . "/JSON/taskList.json\n");
    // ASSETS
    $newdir = $path . "/assets";
    mkdir($newdir);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $newdir . "\n");
    // ASSETS/JS
    $newdir = $path . "/assets/js";
    mkdir($newdir);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $newdir . "\n");
    copy("./templates/assets/js/Bandmaster.js", $path . "/assets/js/Bandmaster.js");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/assets/js/Bandmaster.js to: " . $path . "/assets/js/Bandmaster.js\n");
    // ASSETS/CSS
    $newdir = $path . "/assets/css";
    mkdir($newdir);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $newdir . "\n");
    copy("./templates/assets/css/style.css", $path . "/assets/css/style.css");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying ./templates/assets/css/style.css to: " . $path . "/assets/css/style.css\n");
    // ASSETS/IMG
    $newdir = $path . "/assets/img";
    mkdir($newdir);
    fwrite($log, date("m/d/Y @ g:i:sA") . " - creating directory: " . $newdir . "\n");
    copy("./templates/assets/img/upArrow.png", $path . "/assets/img/upArrow.png");
    copy("./templates/assets/img/playIcon.png", $path . "/assets/img/playIcon.png");
    copy("./templates/assets/img/pauseIcon.png", $path . "/assets/img/pauseIcon.png");
    copy("./templates/assets/img/icons.png", $path . "/assets/img/icons.png");
    copy("./templates/assets/img/downArrow.png", $path . "/assets/img/downArrow.png");
    copy("./templates/assets/img/border-image.png", $path . "/assets/img/border-image.png");
    copy("./templates/assets/img/notepad.png", $path . "/assets/img/notepad.png");
    fwrite($log, date("m/d/Y @ g:i:sA") . " - copying images to: " . $path . "/assets/img");

    
    fclose($log);
    $success = <<<SUCCESS
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bandmaster</title>
    <!--
      create.php

      This file is shown when a user successfully creates an account.
    -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/frontstyle.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
  </head>

  <body>
    <div id="main" class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <h2>Account Creation Successful!</h2>
        </div>
      </div> <!-- end of header div -->
      
      <div class="row">
        <div class="col-xs-12">
          <p>
            Welcome to BandMaster $username. Your public Band Page url is located at <a href="$username">/bandmaster/$username</a>. 
          </p>
          <p>
            Login to your administrator page below.
          </p>
        </div>
      </div> 
      
      <div class="row">
        <div class="col-md-4 col-md-offset-1">
          <h3>Log In</h3>
          <form id="login" method="post" action="admin.php">
            <ul>
              <li>
                <label>Username</label>
                <input type="text" tabindex="3" placeholder="Enter your Username" name="username" id="login_username" required>
              </li>
              <li>
                <label>Password</label>
                <input type="password" tabindex="4" name="password" id="login_password" required>
              </li>
              <input type="submit" class="submit">
          </ul>
          </form>
        </div> <!-- end of login div -->
      </div> <!-- end of row -->

    </div> <!-- end of main div -->
  </body>
</html>
SUCCESS;

    echo $success;
  }
  
  // get the username
  $name = $_POST["username"];

  // check if the user exists or not. if not, create the account
  if (checkUser($name))
  {
    $failure = <<<FAILURE
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bandmaster</title>
    <!--
      create.php

      This file is shown when a username is already taken.
    -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/frontstyle.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
  </head>

  <body>
    <div id="main" class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <h2>Sorry about that!</h2>
        </div>
      </div> <!-- end of header div -->
      
      <div class="row">
        <div class="col-xs-12">
          <p>
            Could not create your account because: Username $name is already taken. 
          </p>
          <p>
            Please try again below.
          </p>
        </div>
      </div> 
      
      <div class="row">
        <div class="col-md-4 col-md-offset-2">
          <h3>Create an account</h3>
          <form id="create" method="post" action="create.php">
            <ul>
              <li>
                <label>Username</label>
                <input type="text" tabindex="1" placeholder="Enter a Username" name="username" id="create_username" required>
              </li>
              <li>
                <label>Password</label>
                <input type="password" tabindex="2" name="password" id="create_password" required>
              </li>
              <input type="submit" class="submit">
            </ul>
          </form>
        </div> <!-- end of create div-->
      </div> <!-- end of row -->

    </div> <!-- end of main div -->
  </body>
</html>
FAILURE;

    echo $failure;
  }
  else if ($name == "assets" || $name == "templates" || $name == "" || !$name || preg_match('/\s/',$name)) {
    
    $failure = <<<FAILURE
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bandmaster</title>
    <!--
      create.php

      This file is shown when a username is restricted.
    -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/frontstyle.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
  </head>

  <body>
    <div id="main" class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <h2>Sorry about that!</h2>
        </div>
      </div> <!-- end of header div -->
      
      <div class="row">
        <div class="col-xs-12">
          <p>
            Could not create your account because: Username $name is a restricted username.
          </p>
          <p>
            Please try again below.
          </p>
          <p>
            Hint: No spaces are allowed in usernames.
          </p>
        </div>
      </div> 
      
      <div class="row">
        <div class="col-md-4 col-md-offset-2">
          <h3>Create an account</h3>
          <form id="create" method="post" action="create.php">
            <ul>
              <li>
                <label>Username</label>
                <input type="text" tabindex="1" placeholder="Enter a Username" name="username" id="create_username" required>
              </li>
              <li>
                <label>Password</label>
                <input type="password" tabindex="2" name="password" id="create_password" required>
              </li>
              <input type="submit" class="submit">
            </ul>
          </form>
        </div> <!-- end of create div-->
      </div> <!-- end of row -->

    </div> <!-- end of main div -->
  </body>
</html>
FAILURE;

    echo $failure;
  }
  else
  {
    $pw = $_POST["password"];
    createUser($name, $pw);
  }
?>