<?php
  header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  
  require 'auth.php';

  $name = $_POST["username"];

  // make sure this user actually exists
  if (!checkUser($name)) {
    $failure = <<<FAILURE
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bandmaster</title>

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
            Could not log in because: Username $name does not exist. 
          </p>
          <p>
            Please try again below.
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
FAILURE;

    echo $failure;
    return;
  }

  $password = $_POST["password"];
  // salt the password
  $password = "dB9" . $password . "87Xa";
  // Convert the password from UTF8 to UTF16 (little endian)
  $password = iconv('UTF-8', 'UTF-16LE', $password);
  // Encrypt it with the MD4 hash
  $MD4Hash = hash('md4', $password);

  // authenticate user
  if (!authenticate($name, $MD4Hash)) {
    $failure = <<<FAILURE
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bandmaster</title>

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
            Could not log in because: Invalid Username/Password. 
          </p>
          <p>
            Please try again below.
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
FAILURE;

    echo $failure;
    return;
  }

  // open up the settings json file for this user
  $file = file_get_contents($name . '/JSON/settings.json', true);
  $settings = json_decode($file, true);
  unset($file);
  
  // get band name
  $bandname = $settings[bandname];

  /*
    Successful login will show the admin page for the user
  */
  $html = <<<HTML

<!DOCTYPE html>
<html ng-app="bandmaster">
  <head>
    <title>$bandname Hub</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Links to include bootstrap and jQuery. -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css"/>
    <script>var username = "$name";</script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

    <!-- Link to load the AngularJS framework.-->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.27/angular.min.js"></script>

    <!-- JavaScript Includes -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    
    <!-- jQuery File Upload Dependencies -->
    <script src="assets/js/jquery.ui.widget.js"></script>
    <script src="assets/js/jquery.fileupload.js"></script>
    <script src="assets/js/jquery.knob.js"></script>

    <!-- External Javascript file containing custom functions and the AngularJS 
         model and controller that allow the page to function.-->
    <script src="assets/js/Bandmaster.js"></script>
    <script src="assets/js/uploadScript.js"></script>
    <script src="assets/js/dialog.js"></script>

    <!-- Link to CSS file used to stylize the page.-->
    <link href="assets/css/PKstyleV3.css" rel="stylesheet" type="text/css"> 
    <!-- The main CSS file -->
    <link href="assets/css/style.css" rel="stylesheet" />
  </head>
  <!-- Directive which says this page will use the bandmasterCtrl ng-controller. -->
  <body id="mainbody" ng-controller="bandmasterCtrl" ng-init='load()'>
    <!-- Bootstrap wrapper for the entire body.-->
    <div class="container-fluid">
      <!-- band name will be based off of json data.-->
      <h1 style="text-align: center;">$bandname</h1>
      
      <!-- LEFT pane. Div that contains the list of songs. An ng-class is applied to the different song groupings. -->
      <div class="col-md-4" id="setlist" >
        <p style="color:white; text-align: center;">Upload a Song</p>
        <!-- A container for the actual list of songs. -->
        <div id="tableDiv">
          <form id="upload" method="post" action="$name/upload.php"  enctype="multipart/form-data">
            <div id="drop">
              Drop
              <div id="green_circle"></div>
              <a>Browse</a>
              <div id="mainProgBar" class="progress hidden" >
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:{{uploadProg}}%">
                </div>
              </div>
              <input type="file" name="upl" multiple />
            </div><!-- end of drop div -->
          </form>
          <!-- As the download table has a third column, it is built separately and then hidden when
               not in use. This should DEFINITELY be reimplemented with AJAX -->
          <table>
            <!-- Table headers for the playlist songs names and lengths.-->
            <tr>
              <th class="numHeader">#</th>
              <th class="songHeader">{{tableName}}</th>
              <th></th>
              <th></th>
            </tr>
            <!-- Contains the song names and their index. -->
            <tr ng-class="{move: i.name === audioActive}" ng-repeat="i in myList" >
              <td>{{\$index + 1}}</td>
              <td class="songRow">
                <button ng-click="updateActive(i)">{{i.name}}</button>
              </td>
              <td>
                <button ng-click="deleteFile(i, \$index)">X</button>
              </td>
              <td>
                <span ng-click="startDownload(i.name)" class="downloads glyphicon glyphicon-floppy-save" ></span> 
              </td>
            </tr>
          </table><!-- End of play list table.-->
        </div><!-- End of table div -->
      </div><!-- End left pane-->

      <!-- MIDDLE pane. Container for the audio player. This includes the progress bar, play-head, song name and time.-->
      <div class="hubPane col-md-4">
        <div id="interaction">
          <audio id="my-audio" ng-src="{{audioActive|audioFilter}}"></audio>
          <div id="controls">
            <!-- custom play and pause buttons -->
            <input type="image" ng-src="{{playbackIcon}}" alt="UP" ng-click="togglePlayback()"/>
            <div id="nowPlaying"><span>&nbsp;{{nowPlaying}}&nbsp;</span></div>
            <div id="time">
              <span id="timeSpent">{{timeSpent| number:0|timeFilter}}</span>
              <span id="timeRemaining">-{{timeRemaining| number:0 |timeFilter}}</span>
            </div>
            <div id="progress">
              <span id="circle2"></span>
              <span id="bar"></span>
              <span id="circle1"></span>
            </div><!-- End progress bar-->
          </div><!-- End controls div-->
          <p style="color:white">#currently playing downloads</p>
        </div><!-- End interaction div-->
      </div><!-- end of middle pane -->

      <!-- RIGHT pane -->
      <div class="hubPane col-md-4">
        <p style="color:white">#calender</p>
        <div id="toDoList">
          <p style="color:white">To Do List</p>
          <table>
            <!-- Table headers for the playlist songs names and lengths.-->
            <!-- Contains the song names and their index. -->
            <tr ng-class="{activeTaskClass: i.task === activeTask}" ng-repeat="i in taskList" >
              <td><button ng-click="updateActiveTask(\$index, i)">{{i.task}}</button></td>
            </tr>
          </table><!-- End of play list table.-->
          <div id="newTaskButton">
            <input type="button" onclick="showNewTaskBox()" value="New Task">
            <input type="button" ng-click="removeActiveTask()" value="Remove Active Task">
          </div>
        </div><!-- End of toDoList div -->
        <!-- hidden pop up for adding a new task -->
        <form id="newTaskBox" class="hidden">
          New Task: <input id="taskInput" type="text" name="NewTask" value=""><br>
          <input type="button" ng-click="addTask()" value="Add Task">
          <input type="button" onclick="closeNewTaskBox()" value="Close">
        </form>
      </div><!-- end of right pane -->

    </div><!-- End wrapper -->
  </body>
</html>
HTML;
  
  echo $html;

?>
