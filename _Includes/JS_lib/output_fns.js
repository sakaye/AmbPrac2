$(document).ready(function() {
			var config = {
				 sensitivity: 7,
				 interval: 10,
				 over: hoverOver,
				 timeout: 200,
				 out: hoverOut
			};
			
			$("ul#topnav li").hoverIntent(config);
			
			$('#slider').nivoSlider({
				effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
				slices: 20, // For slice animations
				boxCols: 12, // For box animations
				boxRows: 5, // For box animations
				animSpeed: 500, // Slide transition speed
				pauseTime: 4000, // How long each slide will show
				startSlide: 0, // Set starting Slide (0 index)
				directionNav: true, // Next & Prev navigation
				directionNavHide: false, // Only show on hover
				controlNav: true, // 1,2,3... navigation
				controlNavThumbs: false, // Use thumbnails for Control Nav
				controlNavThumbsFromRel: false, // Use image rel for thumbs
				keyboardNav: true, // Use left & right arrows
				pauseOnHover: false, // Stop animation while hovering
				manualAdvance: false, // Force manual transitions
				captionOpacity: 1, // Universal caption opacity
				prevText: '', // Prev directionNav text
				nextText: '', // Next directionNav text
				randomStart: false // Start on a random slide
			});
			$("#new_postings_slides, #upcoming_events_slides").slides({
				preload: true,
				container: 'box_container',
				play: 10000,
				next: 'box_next',
				prev: 'box_prev',
				generatePagination: false				
			});
			$("#new_postings_slides").bind({
				mouseenter:function(){
					$(".arrows1").show();
				},
				mouseleave:function(){
					$(".arrows1").hide();
				}
			});
			$("#upcoming_events_slides").bind({
				mouseenter:function(){
					$(".arrows2").show();
				},
				mouseleave:function(){
					$(".arrows2").hide();
				}
			});
		});
		
//On Hover Over
function hoverOver(){
	$(this).find(".sub").stop().fadeTo('fast', 1).show(); //Find sub and fade it in
	$("a",this).addClass("hover");
	(function($) {
		//Function to calculate total width of all ul's
		jQuery.fn.calcSubWidth = function() {
			rowWidth = 0;
			//Calculate row
			$(this).find("ul").each(function() { //for each ul...
				rowWidth += $(this).width(); //Add each ul's width together
			});
		};
	})(jQuery); 

	if ( $(this).find(".row").length > 0 ) { //If row exists...

		var biggestRow = 0;	

		$(this).find(".row").each(function() {	//for each row...
			$(this).calcSubWidth(); //Call function to calculate width of all ul's
			//Find biggest row
			if(rowWidth > biggestRow) {
				biggestRow = rowWidth;
			}
		});

		$(this).find(".sub").css({'width' :biggestRow}); //Set width
		$(this).find(".row:last").css({'margin':'0'});  //Kill last row's margin

	} else { //If row does not exist...

		$(this).calcSubWidth();  //Call function to calculate width of all ul's
		$(this).find(".sub").css({'width' : rowWidth}); //Set Width

	}
}
//On Hover Out
function hoverOut(){
	$(this).find(".sub").stop().fadeTo('fast', 0, function() { //Fade to 0 opactiy
		$(this).hide();  //after fading, hide it
	});
	$("a",this).removeClass("hover");
}