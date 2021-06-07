html2element:(function( $ ) {
	'use strict';
	

// Featured Slider
// ====================
$(document).ready(function(){
	var items = mainscript.itemslength;
	$(".king-featured").owlCarousel({
		nav:true,
		margin:0,
		center:true,
		loop: true,
		autoplay: true,
		items: items,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:items
			},
			1000:{
				items:items
			}
		},
		navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>']
	});
});
// Editor's Choice
// ====================
$(document).ready(function(){
	var miniitems = mainscript.miniitemslength;
	$(".king-featured-small").owlCarousel({
		margin:5,
		loop: true,
		autoplay: true,
		dots:false,
		items: miniitems,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:miniitems
			},
			1000:{
				items:miniitems
			}
		}
	});
});
// Header search active
// ====================
$(document).ready(function(){

	$(".header-search-form").click(function(event){
		event.stopPropagation();
		$("div.king-search").addClass("active");
	});

	$(document).on("click", function () {

		$("div.king-search").removeClass("active");
	});


});
// Sticky ad in sidebar
// ====================
$(document).ready(function(){
	$(".sidebar-ad").stick_in_parent({
		parent: "#primary",
		offset_top: 80
	});
	$(".sticky-header-03").stick_in_parent({
		parent: "#page"
	});
});
// bootstrap loading state
// ====================
$(document).ready(function(){
	$("#king-submitbutton").click(function() {
		var $btn = $(this).find('#king-submitbutton');
		$btn.button('loading');
	});
});

// Header search active
// ====================
$(document).ready(function(){
	$('#searchv2-button').on('click', function(event) {
		event.preventDefault();
		$('#live-search').addClass('active');
		setTimeout(function(){
			$(".live-header-search-field").focus();
		},700);
	});
	
	$('#live-search, #live-search .king-close').on('click keyup', function(event) {
		if (event.target == this || event.target.className == 'king-close' || event.keyCode == 27) {
			$(this).removeClass('active');
		}
	});  
});

  // Infinite Scroll
  // ====================
  $(document).ready(function() {
  	var mas = mainscript.enablemas;
  	if (mas) {
  		var container = document.querySelector('.king-grid-07 .site-main-top #main, .king-grid-03 .site-main-top #main, .king-grid-10 .site-main-top #main');
  		var msnry = new Masonry( container, {
  			columnWidth: '.grid-sizer',
  			itemSelector: '.king-post-item',
  			percentPosition: true,
	  		fitWidth: true,
  		});
  	}
  	var ias = $.ias({
  		container:  "#king-pagination-01",
  		item:       ".king-post-item",
  		pagination: "#king-pagination-01 .posts-navigation",
  		next:       "#king-pagination-01 .nav-previous a"
  	});
  	if (mas) {
  		ias.on('render', function(items) {
  			$(items).css({ opacity: 0 });
  		});

  		ias.on('rendered', function(items) {
  			msnry.appended(items);
  		});
  	}
  	var inumber = mainscript.infinitenumber;

	ias.extension(new IASSpinnerExtension({
			html: '<div class="switch-loader"><span class="loader"></span></div>'      // override text when no pages left
		}));            // shows a spinner (a.k.a. loader)
		ias.extension(new IASTriggerExtension({offset: inumber, text: 'Load More'})); // shows a trigger after page 3
		ias.extension(new IASNoneLeftExtension({
			html: '<div class="load-nomore"><span>There are no more pages left to load.</span></div>'      // override text when no pages left
		}));
});

})( jQuery );

