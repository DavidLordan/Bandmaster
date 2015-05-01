# Bandmaster
GUI Programming 2 Team Project - Spring 2015 - David Lordan, Dean Marsinelli, Paul Karcher



<h3>Welcome to Bandmaster!</h3>

The purpose of Bandmaster is to allow musicians to easily collaborate with each other. Bandmaster
provides a means by which artists can upload and share their recordings, transcriptions, events, and
tasks. This is NOT intended to be used for band promotion, but is rather meant to give everyone involved in a
project convenient access to the same content. 

<h4> Live demos: </h4>
<p>Alpha: http://www.daves-data.com/Bandmaster/ </p>
<p>Beta: http://www.daves-data.com/BandMaster_beta</p>
<p>These are both still very much works in progress. The beta is being updated and tested daily, so please do not
	 be suprised if it is particularly unstable. Enjoy!</p>


<h4>File Structure</h4>
Within the assests folder there are subdirectories for CSS, images, and JavaScript. The CSS folder contains styling for the 
admin, public, and login pages. 

The images folder contains icons used for the built-in media player.

The JavaScript folder contains Bandmaster.js, our main JavaSript file for the entire application. Also, dialog.js used to edit
and display the to-do list. This folder also contains our jQuery dependencies. 

The templates folder contains all files which are to be copied over to user accounts. This includes JSON files which are used to
store user data, such as their personal settings, list of songs, and their task list. The templates folder also contains the same assets folder mentioned above, though with only the Bandmaster.js file in the JavaScript subdirectory. Additionally there are PHP files used for the uploading and removing of files and tasks. 


The PHP files in the repository's root directory are used for account creation, authentication, and login. Admin.php is used to control the entire admin page experience.
