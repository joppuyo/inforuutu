//Set moment language for livestamp.js

//moment.locale("fi");

tweets = [];

$( document ).ready(function() {

	//Load web fonts

	//try{Typekit.load();}catch(e){}

	function mainLoop() {

		$.get( "data.php")
			.done(function(data) {
				$("body").html(data);
				showSlides();
			})
			.fail(function() {
				showSlides();
			});

	}

	function showSlides(){

			clearTwitterTimeout();

			timeout = 0;

			slideMax = $(".slide").length;

			$(".slide").each(function(index, value){

				timeout = timeout + $(value).data("delay") * 1000;

				setTimeout(function(){
					advanceSlide(index);
				}, timeout);

			});

			function advanceSlide(index){

				index = index + 1;

				if (index == slideMax) {
					mainLoop();
				}

				$( ".slide" ).hide();

				var currentSlide = $( "#"+index );

				currentSlide.show();

				slideType = currentSlide.data("type");

				if (slideType === "twitter") {
					twitter();
				}

				if (slideType === "instagram") {
					instagram();
				}

			}

	}

	function twitter(){

	    offset = -50;

	    timeout = 0;

	    $(".tweet").each( function(index, value){

	        timeout = timeout + 4000;

	        tweets.push(setTimeout(function(){

	            if ($(value).hasClass("tweet-large")) {
	                offset = offset - 50;
	            }

	            changeSlide(offset);

	            offset = offset - 50;

	        }, timeout));

	    });

	}

	function instagram() {

		var photosAmount = $(".photo").length;

		//Show first two photos immediately
		$("#photo-1").show();
		$("#photo-2").show();

		var i = 3;

		while (i <= photosAmount) {

			setTimeout(function(x) { return function() {

				var secondPhotoNumeral = x + 1;

				var photoOneId = "#" + "photo-" + x;
				var photoTwoId = "#" + "photo-" + secondPhotoNumeral;

				$(".photo").hide();

				$(photoOneId).show();
				$(photoTwoId).show();

			}; }(i), 2000*i);

			i = i + 2;
		}

	}

	function changeSlide(offset){

	    var cssRule = "translateY(" + offset + "%)";

	    $(".translate").css("transform", cssRule);

	}

	function clearTwitterTimeout(){
		for (var i = 0; i < tweets.length; i++) {
       		clearTimeout(tweets[i]);
    	}
	}

	mainLoop();

});