{*
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script>
	var ajax_url="{$helperMenu->ajax_url}";
	var token_menu="{$helperMenu->token}";
	var delete_message=	"{l s='Do you want to delete it ?' mod='seoprestamenu'}";
	var cancel_button=	"{l s='Cancel' mod='seoprestamenu'}";
	var confirm_delete_button=	"{l s='Delete' mod='seoprestamenu'}";
	var success_message=	"{l s='Success' mod='seoprestamenu'}";
	var success_removed=	"{l s='Your item has been removed' mod='seoprestamenu'}";
	var success_updated=	"{l s='Your item has been updated' mod='seoprestamenu'}";
	var cancel_message=	"{l s='Cancel' mod='seoprestamenu'}";
	var cancel_removed=	"{l s='Operation canceled' mod='seoprestamenu'}";
	var success_menu_updated=	"{l s='Menu updated' mod='seoprestamenu'}";
	var success_add_item=	"{l s='Item added' mod='seoprestamenu'}";
	var current_shop = "{$current_shop}";
</script>

<div class="panel">
	<h3><i class="icon icon-gear"></i> {l s='Configure your menu' mod='seoprestamenu'}</h3>
	<div class="row">
		<div class="col-md-12 col-lg-6">
			<div class="col-md-12">
				<ul class="menu-widgets">
					<li class="active">
						<p class="title">{l s='Categories (alone)' mod='seoprestamenu'}  <i class="icon icon-chevron-down pull-right"></i></p>

						<div class="content" id="categories-list-widgets">

							<form class="" id="formListCategoriesTree" action="{$module_dir|escape:'htmlall':'UTF-8'}ajax.php" method="post">

								<input type="hidden" name="action" value="sendItemMenu">
								<input type="hidden" name="id_shop" value="{$current_shop}">
								<input type="hidden" name="token" value="{$helperMenu->token}">
								{$categoriesTree}
								<br>
								<button type="submit" class="btn btn-primary  pointer pull-right" name="addItemMenu" id="addItemMenu">{l s='Add item in menu' mod='seoprestamenu'}</button>

								
								<div class="clearfix"></div>
							</form>
						</div>
					</li>
					<li>
						<p class="title">{l s='CMS Pages' mod='seoprestamenu'} <i class="icon icon-chevron-right pull-right"></i></p>

						<div class="content">
							<form class="" id="formListCMSPages" action="{$module_dir|escape:'htmlall':'UTF-8'}ajax.php" method="post">

								<input type="hidden" name="action" value="sendCmsPageMenu">
								<input type="hidden" name="id_shop" value="{$current_shop}">
								<input type="hidden" name="token" value="{$helperMenu->token}">
								{foreach from=$cmsPages item=cms}
									<span class="custom-checkbox">
										<input  name="cms[]" type="checkbox" value="{$cms.id_cms|escape:'htmlall':'UTF-8'}">
										<span><i class="material-icons rtl-no-flip checkbox-checked psgdpr_consent_icon"></i></span> 
										{$cms.meta_title|escape:'htmlall':'UTF-8'}<br> <br>
									
									</span>
								{/foreach}
								<button type="submit" class="btn btn-primary  pointer pull-right " name="addCmsMenu" id="addCmsMenu">{l s='Add item in menu' mod='seoprestamenu'}</button>
								<div class="clearfix"></div>
							</form>
						</div>
					</li>

					<li>
						<p class="title">{l s='Custom links' mod='seoprestamenu'} <i class="icon icon-chevron-right pull-right"></i></p>

						<div class="content">

							<form class="" id="formCustomLink" action="{$module_dir|escape:'htmlall':'UTF-8'}ajax.php" method="post">

								<input type="hidden" name="action" value="sendCustomLink">
								<input type="hidden" name="id_shop" value="{$current_shop}">
								<input type="hidden" name="token" value="{$helperMenu->token}">

								<!-- custom link -->
								<div class="form-group">
									<label for="">{l s='Your link' mod='seoprestamenu'}</label>
									<input type="text" required name="link" placeholder="http:// or https://">
								</div>

								<!-- label -->
								<div class="form-group">
									<label for="">{l s='Label' mod='seoprestamenu'}</label>
									<input type="text" required name="label" placeholder="{l s='Your text' mod='seoprestamenu'}">
								</div>

								<!-- target -->
								<div class="form-group">
									<label for="">{l s='Target' mod='seoprestamenu'}</label>
									<select name="target">
										<option value="_self">{l s='In the current tab' mod='seoprestamenu'}</option>
										<option value="_blank">{l s='In other tab' mod='seoprestamenu'}</option>
									</select>
								</div>

								<!-- CSS class -->
								<div class="form-group">
									<label for="">{l s='CSS class' mod='seoprestamenu'}</label>
									<input type="text" name="cssclass" placeholder="{l s='Optional CSS class' mod='seoprestamenu'}">
								</div>

								<button type="submit" class="btn btn-primary  pointer pull-right " name="addCustomLink" id="addCustomLink">{l s='Add item in menu' mod='seoprestamenu'}</button>
								<div class="clearfix"></div>
							</form>
						</div>
					</li>
					<li>
						<p class="title">{l s='Products' mod='seoprestamenu'} <i class="icon icon-chevron-right pull-right"></i></p>

						<div class="content">

							<form id="ajax_search_product" method="POST" action="{$module_dir|escape:'htmlall':'UTF-8'}ajax.php">
								<input type="hidden" name="action" value="addProductMenu">
								<input type="hidden" name="id_shop" value="{$current_shop}">
								<input type="hidden" name="token" value="{$helperMenu->token|escape:'htmlall':'UTF-8'}">
								<input type="hidden" name="id_product" id="id_product" value="0">
								<div class="form-group">
									<label>{l s='search by name' mod='seoprestamenu'}</label>
									<input type="text" id="product_ajax" autocomplete="off" class="form-control" name="product_ajax">
								</div>

								<div class="hidden-fields hide">
									<div class="form-group">
										<label>{l s='label' mod='seoprestamenu'}</label>
										<input type="text" required class="form-control" name="label" id="label" >
									</div>

									{* <div class="form-group">
										<label>{l s='link' mod='seoprestamenu'}</label>
										<input type="link" required class="form-control" name="link" id="link" >
									</div> *}

									<!-- target -->
									<div class="form-group">
										<label for="">{l s='Target' mod='seoprestamenu'}</label>
										<select name="target">
											<option value="_self">{l s='In the current tab' mod='seoprestamenu'}</option>
											<option value="_blank">{l s='In other tab' mod='seoprestamenu'}</option>
										</select>
									</div>

									<!-- CSS class -->
									<div class="form-group">
										<label for="">{l s='CSS class' mod='seoprestamenu'}</label>
										<input type="text" name="cssclass" id="cssclass">
									</div>

									<button type="submit" class="btn btn-primary  pointer pull-right" name="addProductMenu" id="addProductMenu">{l s='Add product in menu' mod='seoprestamenu'}
									</button>
									<div class="clearfix"></div>

								</div> <!-- .hidden-fields -->

							</form>
						</div>
					</li>

					{hook h="displayCustomMenuWidget"}
				</ul>

			</div>

		</div>
		<div class="col-md-4 col-lg-6">
		<div class="autosize">
		
			<div class="pull-left">
				<label>{l s='Languages' mod='seoprestamenu'}</label>
				<select id="form_switch_language" class="form-control">
					{foreach from=$helperMenu->langs item=l}
					<option value="{$l.id_lang|escape:'htmlall':'UTF-8'}">{$l.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</div>
			<div class="pull-right">
				<label>&nbsp;</label>
				<div class="trash-button disabled"> 
					<i class="material-icons pull-left">delete</i> 
					<span class="pull-right">{l s='Remove item(s)' mod='seoprestamenu'}</span>
				</div>
			</div>
		</div><!-- end autosize -->

		<div>&nbsp;</div>
		<div class="col-md-12 menu-content">
				{include file="./menu.tpl" start=true}
		</div>
				{* <button id="saveMenu" class="btn btn-primary">{l s='Save menu' mod='seoprestamenu'}</button> *}
		</div>

		
	</div>



</div> <!-- end panel -->
