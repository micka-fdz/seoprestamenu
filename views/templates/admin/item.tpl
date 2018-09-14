{$childrens = array()}
{$childrens = $helperMenu->menu_model->getChilden($item.id_item,$helperMenu->current_lang)}

<li class="dd-item" data-id="{$item.id_item|escape:'htmlall':'UTF-8'}" data-name="{$item.label|escape:'htmlall':'UTF-8'}" data-slug="{$item.label|escape:'htmlall':'UTF-8'}" data-new="0" data-deleted="0">
    <div class="dd-handle">
        <i class="material-icons">drag_indicator</i> 
        <span class="item-name">{$item.label|escape:'htmlall':'UTF-8'} </span>
       
    </div>
     <span class="button-edit pull-right"
                    data-parent="{$item.id_parent|escape:'htmlall':'UTF-8'}"
                    data-owner-id="{$item.id_item|escape:'htmlall':'UTF-8'}">
            {* <i class="icon icon-pencil" aria-hidden="true"></i> *}
            <i class="material-icons">edit</i>
        </span>
    <span class="button-delete  pull-right"
                data-parent="{$item.id_parent|escape:'htmlall':'UTF-8'}"
                data-owner-id="{$item.id_item|escape:'htmlall':'UTF-8'}">
        {* <i class="icon icon-times-circle-o" aria-hidden="true"></i>
         *}

         <span class="custom-checkbox">
            <input  name="to_delete[]" type="checkbox" data-id_parent="{$item.id_parent|escape:'htmlall':'UTF-8'}" value="{$item.id_item|escape:'htmlall':'UTF-8'}">
            <span><i class="material-icons rtl-no-flip checkbox-checked psgdpr_consent_icon"></i></span> 
        </span>
    </span>
    
{if $childrens|count > 0}
    <!-- Item3 children -->
    <ol class="dd-list has-children">
    {foreach from=$childrens item=c name=childrenLoop}
        {include file='./item.tpl' start=false item=$c}
    {/foreach}
    </ol>
{/if}
</li>
