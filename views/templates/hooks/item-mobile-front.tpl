{$childrens = array()}
{$childrens = $helperMenu->menu_model->getChilden($item.id_item,$helperMenu->current_lang)}

<li id="menu-item-{$item.id_item}" class="mega-menu multi-column  {if $item.id_parent == 0}column_3{/if} menu-item menu-item-type-taxonomy menu-item-object-category {if $childrens|count > 0}menu-item-has-children{/if} menu-item-{$item.id_item}">
  <a href="{$item.url}" {if $item.target != null}target="{$item.target}"{/if}>{$item.label}</a>

{if $childrens|count > 0}
    <div class="more"><i class="fa fa-chevron-right"></i></div></li>
    <ul class="sub-menu">
      {foreach from=$childrens item=c name=childrenLoop}
          {include file='module:seoprestamenu/views/templates/hooks/item-front-mobile.tpl' start=false item=$c}
      {/foreach}
    </ul>
{/if}
</li> <!-- closing -->
