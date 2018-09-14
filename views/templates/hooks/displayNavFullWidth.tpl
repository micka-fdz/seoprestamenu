{* <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> *}
 <div class="container">
			<div class="row">
				<div id="seoprestamenu_navigation" class="col-xs-12 col-md-12 col-sm-12">
				<p class="hidden-md-up navigation-nav-title">
					{l s='Navigation' mod='seoprestamenu'}
					<span class="pull-right"><i class="fa fa-bars"></i></span>
				</p>
					<div class="site-navigation hidden-sm-down">
						<nav class="main-navigation default-navigation {*align_center*}">
							<ul class="menu-main-menu">
							{if $items|count > 0}
								{foreach from=$items item=item name=itemLoop}
									{include file='module:seoprestamenu/views/templates/hooks/item-front.tpl' start=false item=$item}
								{/foreach}
							{/if}
						</ul> <!-- end menu-main-menu -->
						</nav><!-- .main-navigation -->
					</div><!-- .site-navigation -->

					<!-- MOBILE MENU -->
					<div class="sm-menu slide-from-right hidden-md-up">
							<div class="nano">
								<div class="content">
									<div class="offcanvas_content_right">
										<div id="mobiles-menu-offcanvas">
											<nav class="mobile-navigation primary-navigation visible-xs visible-sm">
											<ul id="menu-main-menu-1">
												{if $items|count > 0}
													{foreach from=$items item=item name=itemLoop}
														{include file='module:seoprestamenu/views/templates/hooks/item-front.tpl' start=false item=$item}
													{/foreach}
												{/if}
											</ul>
											</nav>
										</div> <!--#mobiles-menu-offcanvas -->
									</div><!-- #offcanvas_content_right -->
								</div> <!--.content -->
							</div> <!-- .nano -->
						</div><!-- .sm-menu -->
					<!-- end mobile menu -->
					
			</div><!-- end #navigation -->
		</div><!-- end .row -->
</div> <!-- .container -->
