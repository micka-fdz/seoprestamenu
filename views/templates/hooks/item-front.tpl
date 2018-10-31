{$childrens = array()}
{$childrens = $helperMenu->menu_model->getChilden($item.id_item,$helperMenu->current_lang)}

<li id="menu-item-{$item.id_item}" class="{if $item.display_sections}mega-menu{/if} {if $item.id_parent == 0}current-menu-parent{/if} {if $childrens|count > 0}menu-item-has-children{/if} {if $item.cssclass}{$item.cssclass}{/if}">
<a href="{$item.url}" {if $item.target != null}target="{$item.target}"{/if}>{$item.label}</a>

{if $childrens|count > 0}
    <ul class="sub-menu {if $item.display_sections}level-1{else}level-0{/if}">
      {foreach from=$childrens item=c name=childrenLoop}
          {include file='module:seoprestamenu/views/templates/hooks/item-front.tpl' start=false item=$c}
      {/foreach}
    </ul>
{/if}
</li> <!-- Closing -->
