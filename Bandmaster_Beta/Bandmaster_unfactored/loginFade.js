//alert('dude');
function normalizeIt( _it, _array){
	
	if(_it === _array.length){
  		return 0;
  	}
  	else{
  		return _it;
  	}
}

function loopImages() {
	var imageArray = ["drummer.jpg", "sheetMusic.jpg","strat.jpg","GBBlur.jpg"];
 	var imageIterator = 0;
 	var backIterator = 1;

 	var test = $("#backImages").css('width');

  setInterval(function(){ 

  	imageIterator = normalizeIt(++imageIterator, imageArray);
  	backIterator = normalizeIt(++backIterator, imageArray);

  	var nextFront = imageArray[imageIterator];
  	var nextBack = imageArray[backIterator];

  	var backImages = document.getElementById('backImages');
  	var frontImages = document.getElementById('frontImages');

		$("#frontImages").fadeOut( 1500, function() {
		frontImages.style.backgroundImage="url('images/" + nextFront + "')";
		$("#frontImages").fadeIn( 0, function() {});
		backImages.style.backgroundImage="url('images/" + nextBack + "')";
		});

	}, 6000); //end set interval

} // end loopImages

$(document).ready(loopImages);


