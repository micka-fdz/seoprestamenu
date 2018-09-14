// send item to AJAX and add in menu.


	var reset_product_form = function()
	{
		$('.hidden-fields').addClass('hide');
		$('#product_ajax').val('');
		$('#ajax_search_product #label').val('');
		$('#ajax_search_product #link').val('');
		$('#ajax_search_product #id_product').val('0');
	}
	var get_product = function(id)
	{
		$.get(ajax_url+"?action=getProductById&token="+token_menu+"&id_product="+id,function(res){
				var json = JSON.parse(res);

				$('.hidden-fields').removeClass('hide');
				$('#ajax_search_product #label').val(json.name);
				$('#ajax_search_product #link').val(json.custom_link);
				$('#ajax_search_product #id_product').val(json.id);

		});
	}

	var initMenu = function (){
		$('#nestable').nestable({
			maxDepth: 5,
			
      })
	}
	var setMenuEvents = function()
	{
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

	var addItemMenu = function(html)
	{

			// var html = '<li class="dd-item" data-id="12" data-name="ADD ITEM" data-slug="item-slug-12" data-new="1" data-deleted="0">';
			// 		html += '<div class="dd-handle">ADD ITEM</div>';
			// 		html += '<span class="button-delete btn btn-default btn-xs pull-right"';
			// 		html += '	data-owner-id="5">';
			// 		html += '	<i class="icon icon-times-circle-o" aria-hidden="true"></i>';
			// 		html += '</span>';
			// 		html += '<span class="button-edit btn btn-default btn-xs pull-right"';
			// 		html += '			data-owner-id="5">';
			// 		html += '	<i class="icon icon-pencil" aria-hidden="true"></i>';
			// 		html += '</span>';
			// 		html += '</li>';
		$('#menuTree').append(html);

	}

	
	var updateMenu = function()
	{
		var id_lang = $('#form_switch_language').val();
		var menu = $('.dd').nestable('serialize');
	
			
			$.post(ajax_url+"?action=updateMenu&id_lang="+id_lang+"&token="+token_menu, {menu: menu},function(res){
				showSuccessMessage(success_menu_updated);
	
			}).error(function(error){
				$('body').html(error.responseText)
				
				reloadMenu();
			})
		
		
	}

	var getCurrentLang = function()
	{
		var id_lang = $('#form_switch_language').val();
		return parseInt(id_lang);
	}

	var reloadMenu = function()
	{
		$(".menu-loader").removeClass('hide');
		var id_lang = $('#form_switch_language').val();
		$.get(ajax_url+"?action=reloadMenu&id_lang="+id_lang+"&token="+token_menu,function(data)
		{
			
			$(".menu-loader").addClass('hide');
			$("#nestable").replaceWith(data);
			initMenu();
		})
	}
