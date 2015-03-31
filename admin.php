<?php

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'login_assets/auth.php';

$name = $_POST["username"];
date_default_timezone_set('America/New_York');
$log = fopen("user_log.txt", "a");
fwrite($log, "\n\n" . date("m/d/Y @ g:i:sA") . " - User " . $name . " attemping to log in\n");

fwrite($log, date("m/d/Y @ g:i:sA") . " - User " . $name . " logged in successfully");
fclose($log);
// open up the settings json file for this user
$file = file_get_contents('./users/' . $name . '/JSON/settings.json', true);
$settings = json_decode($file, true);
unset($file);

/*
  Successful login will show the admin page for the user
 */
$html = <<<HTML
<!DOCTYPE html>
<html ng-app="bandmaster">
  <head>
    <title>Bandmaster Hub</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>var username = "users/$name";</script>
    <!-- Links to include bootstrap and jQuery -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- Link to load the AngularJS framework.-->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.27/angular.min.js"></script>
   
    <!-- jQuery File Upload Dependencies -->
    <script src="assets/js/jquery.ui.widget.js"></script>
    <script src="assets/js/jquery.fileupload.js"></script>
    <script src="assets/js/jquery.knob.js"></script>

     <!-- External Javascript file containing custom functions and the AngularJS 
         model and controller that allow the page to function -->   
    <script src="assets/js/Bandmaster.js"></script>
    <script src="assets/js/uploadScript.js"></script>
    <script src="assets/js/dialog.js"></script>

    <!-- CSS styling links -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>

  <!-- Directive which says this page will use the bandmasterCtrl ng-controller -->
  <body id="mainbody" ng-controller="bandmasterCtrl" ng-init='load("admin", "$name")'>
    <div id="top">
      <p id="alpha">Bandmaster - Administrator Dashboard Alpha<span id="logout"><a href="index.html">Logout</a></span></p>
    </div>
    <!-- Bootstrap wrapper for the entire body.-->
    <div class="container-fluid">

      <!-- band name will be based off of json data -->
      <div id="header">
        <h1 id="bandname">{{bandname}}</h1>
        <form id="changeNameBox" class="hidden">
          <input id="nameInput" type="text" name="nameInput" value="{{bandname}}"><br>
          <button ng-click="changeName()">Save</button>&nbsp;
          <button onclick="closeChangeName()">Cancel</button>
        </form>
      </div>
      <!-- panes are ordered "incorrect" in the html to make it work with bootstrap -->

      <!-- MIDDLE pane containing the audio player -->
      <div class="col-md-4 col-md-push-4 hubPane">
        <audio id="my-audio" ng-src="{{audioActive|audioFilter}}"></audio>
        <div id="controls">
          <!-- custom play and pause buttons -->
          <input type="image" ng-src="{{playbackIcon}}" alt="UP" ng-click="togglePlayback()">
          <p id="nowPlaying">&nbsp;{{nowPlaying}}&nbsp;</p>
          <div id="time">
            <span id="timeSpent">{{timeSpent| number:0|timeFilter}}</span>
            <span id="timeRemaining">-{{timeRemaining| number:0 |timeFilter}}</span>
          </div> <!-- end time div -->
          <div id="progress">
            <span id="circle2"></span>
            <span id="bar"></span>
            <span id="circle1"></span>
          </div><!-- end progress bar-->
        </div><!-- end controls div-->
        <div id="infoPanel">
          <!-- dynamic content goes here -->
        </div><!-- end infoPanel div -->
      </div> <!-- end of middle pane -->

      <!-- LEFT pane containing the song list -->
      <div class="col-md-4 col-md-pull-4" id="setlist">
        <form id="upload" method="post" action="users/$name/upload.php" enctype="multipart/form-data">
          <!-- File Upload section -->
          <div id="uploadDiv">
            <p id="uploadType">
              Upload a File&nbsp;&nbsp;
              <select id="uploadSelect" name="fileType">
                <option value="song">MP3 Song</option>
                <option value="lyrics">Lyrics</option>
                <option value="sheet">Sheet Music</option>
                <option value="tabs">Guitar Tabs</option>
              </select>
              <input name="songname" id="invisibleName">
            </p>
            <!-- drop zone div -->
            <div id="drop">
              <p id="uploadTitle">Drop an Audio File or Press Browse</p>
              <div id="green_circle"></div>
              <div id="uploadLeft">
                <a>Browse</a>
              </div>
              <div id="uploadRight">
                <div id="mainProgBar" class="progress hidden">
                  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:{{uploadProg}}%"></div>
                </div> <!-- end of mainProgBar div -->
              </div>
              <input type="file" name="upl" multiple>
            </div> <!-- end of drop div -->
          </div> <!-- end of file upload -->
        </form>
        <!-- jQuery tabs list -->
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
          <li class="active"><a href="#songs" data-toggle="tab">Songs</a></li>
          <li><a href="#sheetMusic" data-toggle="tab">Sheet Music</a></li>
          <li><a href="#downloads" data-toggle="tab">Downloads</a></li>
        </ul>
        <!-- jQuery tabs content -->
        <div id="my-tab-content" class="tab-content">
          <!-- songs -->
          <div class="tab-pane active" id="songs">
            <!-- table for song list -->
            <table class="leftTable">
              <tr>
                <th class="numHeader">#</th>
                <th>Songs</th>
                <th></th>
                <th></th>
              </tr>
              <tr ng-class="{activeSongClass: i.name === audioActive}" ng-repeat="i in myList" >
                <td>{{\$index + 1}}</td>
                <td>
                  <button id="songname" ng-click="updateActive(i)">{{i.name}}</button>
                </td>
                <td>
                  <button ng-click="deleteFile(i, \$index)">X</button>
                </td>
                <td>
                  <span ng-click="startDownload(i.name)" class="downloads glyphicon glyphicon-floppy-save" alt="Download Song"></span>
                </td>
              </tr>
            </table> <!-- end of song table -->
          </div> <!-- end of songs tab -->
          <!-- sheet music -->
          <div class="tab-pane" id="sheetMusic">
            SHEETMUSIC
          </div> <!-- end of sheet music tab -->
          <!-- downloads -->
          <div class="tab-pane" id="downloads">
            DOWNLOADS
          </div> <!-- end of downloads tab -->
        </div> <!-- end of jQuery tab content -->
      </div> <!-- end of left pane -->

      <!-- RIGHT pane containing task list and calendar-->
      <div class="col-md-4 hubPane">
        <div id="toDoList">
          <!-- table for task list -->
          <table id="taskTable">
            <tr>
              <th><h2>To Do List</h2></th>
            </tr>
            <tr ng-class="{activeTaskClass: i.task === activeTask}" ng-repeat="i in taskList" >
              <td> <button ng-click="updateActiveTask(\$index, i)">{{i.task}}</button></td>
            </tr>
          </table> <!-- end of task list table -->
          <!-- add/remove task section -->
          <div id="newTaskButton">
            <button onclick="showNewTaskBox()">Add New Task</button>&nbsp;
            <button ng-click="removeActiveTask()">Remove Selected Task</button>
          </div> <!-- end of add/remove task div -->
          <div id="newTaskBox" class="hidden">
            New Task: <input id="taskInput" type="text" name="NewTask" value=""><br>
            <button ng-click="addTask()">Add Task</button>&nbsp;
            <button onclick="closeNewTaskBox()">Close</button>
          </div> 
        </div> <!-- end of toDoList div -->
      </div> <!-- end of right pane -->

    </div> <!-- end of bootstrap container -->
  </body>
</html>

HTML;

echo $html;
?>
