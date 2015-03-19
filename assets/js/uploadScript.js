/*
  uploadScript.js

  This file contains the code used to upload songs and
  other files through the administrator page. It utilizes
  a jQuery plugin called drop zone that allows users to
  drag and drop files onto a DOM element and the file
  will be uploaded to the server.
*/

$(function () {

    var ul = $('#upload ul');

    $('#drop a').click(function () {
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({
        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            $("#uploadMessage").remove();

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"' +
                    ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                    .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function () {

                if (tpl.hasClass('working')) {
                    jqXHR.abort();
                }

                tpl.fadeOut(function () {
                    tpl.remove();
                });

            });
            var filename = data.files[0].name;
            var ext = filename.substr(filename.length - 3);
            var val = $("#uploadSelect").val();

            // prevent unsupported files
            if (val == "song" && ext != "mp3") {
              $("#uploadType").append("<p id='uploadMessage' style='color: red;'>Song files must be MP3</p>");
              return;
            }
            else if (val != "song" && ext != "pdf") {
              $("#uploadType").append("<p id='uploadMessage' style='color: red;'>File must be PDF</p>");
              return;
            }
            // make sure a song is selected if they aren't upload a song
            if (val != "song") {
              //Grabs the main html body's angular scope. 
              var scope = angular.element(document.getElementById("mainbody")).scope();
              if (scope.activeSong == undefined || scope.activeSong == "") {
                $("#uploadType").append("<p id='uploadMessage' style='color: red;'>Must select a Song</p>");
                return;
              }

              var name = scope.activeSong.name;
              //data.post['name'] = name;
            }

            console.log("Beginning file upload: " + data.files[0].name);
            
            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit().done(function(res) {
              console.log(res);
              $("#green_circle").removeAttr('style');

              // if success, add song to angular model
              if (res == "songSuccess") {
                $("#uploadType").append("<p id='uploadMessage' style='color: green;'>Song uploaded successfully</p>");
                var scope = angular.element(document.getElementById("mainbody")).scope();
                scope.myList.push({name: data.files[0].name});
                scope.$apply();
                // remove success message after 5 seconds
                setTimeout(function(){
                  $("#uploadMessage").remove();; 
                }, 5000);
              }
              else if (res == "fileSuccess") {
                $("#uploadType").append("<p id='uploadMessage' style='color: green;'>File added successfully</p>");
                var scope = angular.element(document.getElementById("mainbody")).scope();
                scope.updateJSON();
                // remove success message after 5 seconds
                setTimeout(function(){
                  $("#uploadMessage").remove();; 
                }, 5000);
              }
              // if failed -- say why
              else if (res == "fileTooLarge") {
                $("#uploadType").append("<p id='uploadMessage' style='color: red;'>File was too large to upload</p>");
              }
              else if (res == "error") {
                $("#uploadType").append("<p id='uploadMessage' style='color: red;'>Error Uploading - try again</p>");
              }
            });
        },
        progress: function (e, data) {

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            //Grabs the main html body's angular scope. 
            var scope = angular.element(document.getElementById("mainbody")).scope();

            //Sends a progress update to the angular scope. 
            scope.updateProgress(progress);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if (progress === 100) {
                data.context.removeClass('working');
            }
        },
        fail: function (e, data) {
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

});