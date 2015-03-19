
function showAccountCreation(){
	//var creationBox = document.getElementById('create'); 
	//var loginBox = document.getElementById('login');

	$("#login").fadeOut( 0, function() {
		$("#create").fadeIn( 0, function(){});
	});

}

function cancelAccountCreation(){
	$("#create").fadeOut( 0, function() {
		$("#login").fadeIn( 0, function(){});
	});
}