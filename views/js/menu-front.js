/**
 * JS MENU
 */

$(document).ready(function ($) {

	// Off Canvas Navigation ---------------------------------------
	var window_width = $(window).innerWidth();

	var mobile_open = false;
	$('p.navigation-nav-title').click(function (e) {
		e.preventDefault();
		if (!mobile_open) {
			$('.sm-menu.slide-from-right').slideDown();
			mobile_open = true;
		} else {
			$('.sm-menu.slide-from-right').slideUp();
			mobile_open = false;
		}
	})

	// Submenu adjustments ---------------------------------------
	function submenu_adjustments() {
		$(".main-navigation > ul > .menu-item").mouseenter(function () {
			if ($(this).children(".sub-menu").length > 0) {
				var submenu = $(this).children(".sub-menu");
				var window_width = parseInt($(window).outerWidth());
				var submenu_width = parseInt(submenu.outerWidth());
				var submenu_offset_left = parseInt(submenu.offset().left);
				var submenu_adjust = window_width - submenu_width - submenu_offset_left;
				var dir = $('html').attr("dir");

				if (dir == "rtl") {
					if (submenu_adjust < 0) {
						submenu.css("right", submenu_adjust - 30 + "px");
						submenu.addClass("active");
					}
				} else {
					if (submenu_adjust < 0) {
						submenu.css("left", submenu_adjust - 30 + "px");
					}
				}
			}
		});
	}

	submenu_adjustments();



	// Mobile menu ---------------------------------------
	$(".mobile-navigation .menu-item-has-children").append('<div class="more"><i class="fa fa-plus-circle"></i></div>');

	$(".mobile-navigation").on("click", ".more", function (e) {
		e.stopPropagation();

		$(this).parent().toggleClass("current")
			.children(".sub-menu").toggleClass("open");

		$(this).html($(this).html() == '<i class="fa fa-plus-circle"></i>' ? '<i class="fa fa-minus-circle"></i>' : '<i class="fa fa-plus-circle"></i>');
	});

	$(".mobile-navigation").on("click", "a", function (e) {
		if (($(this).attr('href') === "#") || ($(this).attr('href') === "")) {
			$(this).parent().children(".sub-menu").toggleClass("open");
			$(this).parent().children(".more").html($(this).parent().children(".more").html() == '<i class="fa fa-plus-circle"></i>' ? '<i class="fa fa-minus-circle"></i>' : '<i class="fa fa-plus-circle"></i>');
		}
	});





});