function login(userName, password){

  //console.log(userName.value);
  //console.log(password.value);

  $.ajax({
    url:"login_assets/login.php",
    data:{
      action: 'login',
      username: userName.value,
      password: password.value
    },
    type: "POST",
    dataType:"text",
    success: function(srvrMsg){
      console.log(srvrMsg);
      switch(srvrMsg){
        case 'DNE':{
        console.log('DNE');
        var msg = "Cannot find a record of this account.";
        $("#windowMessage").html(msg);
        break;
        }
        case 'INVALID':{
          console.log('invalid');
          var msg = "Invalid username/password. Please try again.";
          $("#windowMessage").html(msg);
          break;
        }
        case 'LOGIN':{
          console.log('login');
          $('body').append($('<form/>')
            .attr({'action': "admin.php", 'method': 'post', 'id' : 'dummyForm'})
            .append($('<input/>')
              .attr({'type': 'hidden', 'name': 'username', 'value': userName.value})
            )
            
           ).find('#dummyForm').submit();
          
        }
      }
      
    },
    error: function(srvrMsg){
      console.log('error');
    }  
  })
}

function makeAccount(userName, password){
  $.ajax({
    url:"login_assets/create.php",
    data:{
      action: 'startCreation',
      username: userName.value,
      password: password.value
    },
    type: "POST",
    dataType:"text",
    success: function(srvrMsg){
      console.log(srvrMsg);
      switch(srvrMsg){
        case 'SUCCESS':{
          console.log("Account Created");
          $.ajax({url: "login_assets/newAccountSuccess.html", success: function(result){
          $("#loginContainer").html(result)
          var newURL = '<a href="users/' + userName.value+ '">/bandmaster/users/' + userName.value + '</a>'
          $("#newPageLink").html(newURL);

          $('body').append($('<form/>')
            .attr({'action': "admin.php", 'method': 'post', 'id' : 'dummyForm'})
            .append($('<input/>')
              .attr({'type': 'hidden', 'name': 'username', 'value': userName.value})
            ));
          }});
          break;
        }
        case 'FAILURE':{
          console.log('Account exists');
            var msg = '\'' + userName.value +   '\' is already taken, please try again.'
            $("#windowMessage").html(msg);
          
          break;
        }
        case 'RESTRICTED':{
          console.log('Acount name restricted.');
          var msg = '\'' + userName.value +   '\' is a restricted name, please try again.'
            $("#windowMessage").html(msg);
          break;
        }
      }
    },
    error: function(srvrMsg){
      console.log(srvrMsg);
    } 
  })
}


function showAccountCreation(){
  $.ajax({url: "login_assets/accountCreation.html", success: function(result){
    $("#loginContainer").html(result);
  }})
}

function showLogin(){
  $.ajax({url: "login_assets/loginWindow.html", success: function(result){
    $("#loginContainer").html(result);
  }})
}


// Function to start a loop through a series of images for the login background.
// The function is called once when the login page is first loaded. The loop itself is set within
// the function
function loopImages() {

  //Array of image names.
  var imageArray = ["drummer.jpg", "sheetMusic.jpg", "studio1.jpg", "piano.jpg", "fretboard.jpg", "microphone.jpg", "strat.jpg", "GBBlur.jpg"];
  
  // Index of the starting image. This can be used to set the starting image as any point in the array.
  // If the starting index is out of bounds of the image array, it will be set to 0. 
  var imageIterator = 0;
 
  // Loop to travel through the images.
  setInterval(function(){ 

    // Increments the image index. If the index is out of bounds, it will be set to 0. 
    imageIterator = normalizeIt(++imageIterator, imageArray);
    
    // Grabs the next front and back image names.
    var nextFront = imageArray[imageIterator];
    var nextBack = imageArray[imageIterator+1];

    // Grabs the next front and back images.
    var backImages = document.getElementById('backImages');
    var frontImages = document.getElementById('frontImages');

    // Begins a fade out of the front image, reveal the back image. 
    $("#frontImages").fadeOut( 1500, function() {

      // When the front image has completely faded out, it is set to match the back image, 
      // though it is still hidden.
      frontImages.style.backgroundImage="url('login_assets/images/" + nextFront + "')";

      // The front image is set to instantly fade in. Because the front image now matches the back
      // this change is not observable. 
      $("#frontImages").fadeIn( 0, function() {

        // Changes the back image. 
        backImages.style.backgroundImage="url('login_assets/images/" + nextBack + "')";
      });

    });

  }, 4500); //end set interval

} // end loopImages

// Function to set the image array index to 0 if it goes out of bounds. 
// This allows a continuously incremented index to force a circular array. 
function normalizeIt( _it, _array){
  
  if(_it >= _array.length-1){
      return 0;
  } else{
    return _it;
  }
}

// Begins the image loop when the page is loaded.
$(document).ready(loopImages);
$(document).ready(showLogin);



