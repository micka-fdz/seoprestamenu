/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    SeoPresta <contact@seo-presta.com>
*  @copyright 2007-2015 SeoPresta
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SeoPresta
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).on('click', '#saveMenu', function (e) {
	updateMenu();
});
$(document).on('add_item_menu', function () {
	showSuccessMessage(success_add_item);
})
$(document).on('submit', '#formListCategoriesTree', function (e) {

	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function (data) {
			// console.log(data);
			$(document).trigger('add_item_menu');
			reloadMenu();
			$("#uncheck-all-categories-treeview").trigger('click');
			setMenuEvents();
		},
		error: function (data) {
			console.log(data);
		}

	})
	e.preventDefault();

});

// CMS

$(document).on('change', '#form_switch_language', function (e) {
	reloadMenu(); // reload menu on change langue
});

// update item ajax
$(document).on('submit', '#ajax_update_item', function (e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function (data) {
			if (data.success) {
				reloadMenu();
				$(".sweet-modal-close-link").click();
				swal(success_message, success_updated, "success");

			}


		},
		error: function (error) {
			console.log(error);
			$('div.panel').html(error.responseText);
		}
	});


})


// edit item
$(document).on('click', ".dd-item span.button-edit", function () {
	var id = $(this).attr('data-owner-id');
	var id_lang = getCurrentLang();
	$.get(ajax_url + "?action=getItemDetails&id=" + id + "&id_lang=" + id_lang + "&token=" + token_menu+"&id_shop="+current_shop, function (res) {
		// json result
		console.log(res);
		var sweet = $.sweetModal(JSON.parse(res));
	})
});

// remove items
$(document).on('click', '.trash-button', function (e) {
	if (!$(this).hasClass('disabled')) {

		e.preventDefault();

		var token = token_menu;
		var url = ajax_url;
		var ids = [];
		$(".dd input[type='checkbox']:checked").each(function (el) {
			ids.push($(this).val());
		});


		swal({
				title: delete_message,
				//text: "You will not be able to recover this imaginary file!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: confirm_delete_button,
				cancelButtonText: cancel_button,
				closeOnConfirm: false,
				closeOnCancel: true,
				showLoaderOnConfirm: true
			},
			function (isConfirm) {
				if (isConfirm) {

					$.ajax({
						type: "POST",
						url: url + "?action=removeItems",
						data: {
							'ids': ids,
							'token': token,
						},
						success: function (out) {
							// console.log(out);
							swal.close()
							showSuccessMessage(success_removed);
							reloadMenu();
						},
						error: function (error) {

							swal(cancel_message, cancel_removed, "error");
							$('div.panel').html(error.responseText);
						}
					})
				}
			});
	}


})
$(document).on('submit', '#formListCMSPages', function (e) {

	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function (data) {
			// addItemMenu(data);
			$(document).trigger('add_item_menu');
			reloadMenu();
			setMenuEvents();
		},
		error: function (data) {
			alert(data);
			console.log(data);
		}

	})
	e.preventDefault();

});

// Custom link

$(document).on('submit', '#formCustomLink, #ajax_search_product', function (e) {

	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function (data) {
			// addItemMenu(data);
			reloadMenu();
			setMenuEvents();
			reset_product_form();
			$(document).trigger('add_item_menu');
		},
		error: function (data) {
			alert(data);
			console.log(data);
		}

	})
	e.preventDefault();

});


$(document).on('click', '.custom-checkbox', function (e) {
	var id_parent = parseInt($(this).find('input').data('id_parent'));
	if ($(this).find('input').is(':checked')) {
		$(this).find('input').prop('checked', false);
		$(".custom-checkbox input[type='checkbox'][data-id_parent=" + $(this).find('input').val() + "]").prop('checked', false);
	} else {
		$(this).find('input').prop('checked', true);
		$(".custom-checkbox input[type='checkbox'][data-id_parent=" + $(this).find('input').val() + "]").prop('checked', true);
	}
	checkDeleteItems();

	e.preventDefault();
});



$(document).on('change', '.dd', function () {
	/* on change event */
	updateMenu();
	// reloadMenu();

});

var autoresize = function()
{
	if($(window).width() <= 992)
	{
		$(".autosize").css('width', '100%');
	}else{
		$(".autosize").css('width', $('#nestable').width() + 92);
	}
}

$(window).resize(function () {
	autoresize();
});

$(function () {
	autoresize();

	$('#product_ajax').typeahead({

		ajax: {
			url: ajax_url + "?action=searchProduct&token=" + token_menu,
			loadingClass: "loading-circle",
			preProcess: function (data) {
				return data;
			},
		},
		onSelect: function (element) {
			get_product(element.value);

		},
	});





	$(document).trigger('initModule');

	// widgets

	$('ul.menu-widgets li p.title').click(function (e) {
		e.preventDefault();

		var li = $(this).parent('li');
		if (!li.hasClass('active')) {
			$('ul.menu-widgets li div.content').hide();
			$('ul.menu-widgets li').removeClass('active');
			li.addClass('active');
			$('ul.menu-widgets li i.pull-right').removeClass('icon-chevron-up').addClass('icon-chevron-down');
			li.find('i.pull-right').removeClass('icon-chevron-down').addClass('icon-chevron-up');
			li.find('div.content').show();
		}


	})
	// nestable menu
	initMenu();
})