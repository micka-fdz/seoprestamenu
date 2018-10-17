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

var reset_product_form = function () {
		$('.hidden-fields').addClass('hide');
		$('#product_ajax').val('');
		$('#ajax_search_product #label').val('');
		$('#ajax_search_product #link').val('');
		$('#ajax_search_product #id_product').val('0');
	}
	var get_product = function (id) {
		$.get(ajax_url + "?action=getProductById&token=" + token_menu + "&id_product=" + id, function (res) {
			var json = JSON.parse(res);

			$('.hidden-fields').removeClass('hide');
			$('#ajax_search_product #label').val(json.name);
			// $('#ajax_search_product #link').val(json.custom_link);
			$('#ajax_search_product #id_product').val(json.id);

		});
	}

	var initMenu = function () {
		$('#nestable').nestable({
			maxDepth: 5,

		})
	}
	var setMenuEvents = function () {
		// $("#nestable .button-delete").on("click", deleteFromMenu);
		// $(document).on('click',"#nestable .button-delete", deleteFromMenu);
		$("#nestable .button-edit").on("click", prepareEdit);
	}

	var checkDeleteItems = function () {
		var nb = $(".dd input[type='checkbox']:checked").length;
		if (nb > 0) {
			$(".trash-button").removeClass('disabled');
		} else {
			$(".trash-button").addClass('disabled');
		}
	}

	var addItemMenu = function (html) {

		$('#menuTree').append(html);

	}


	var updateMenu = function () {
		var id_lang = $('#form_switch_language').val();
		var menu = $('.dd').nestable('serialize');

		$.post(ajax_url + "?action=updateMenu&id_lang=" + id_lang + "&token=" + token_menu +"&id_shop="+current_shop, {
			menu: menu
		}, function (res) {
			showSuccessMessage(success_menu_updated);
		}).error(function (error) {
			$('body').html(error.responseText)
		})


	}

	var getCurrentLang = function () {
		var id_lang = $('#form_switch_language').val();
		return parseInt(id_lang);
	}

	var reloadMenu = function () {

		var id_lang = $('#form_switch_language').val();
		$.get(ajax_url + "?action=reloadMenu&id_lang=" + id_lang + "&token=" + token_menu+"&id_shop="+current_shop, function (data) {
			$("#nestable").replaceWith(data);
			initMenu();
		})
	}