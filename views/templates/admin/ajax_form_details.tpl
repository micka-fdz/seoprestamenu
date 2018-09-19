<form id="ajax_update_item" action="{$helperMenu->ajax_url|escape:'htmlall':'UTF-8'}" method="post">
    <input type="hidden" name="id_item" value="{$item->id}">
    <input type="hidden" name="token" value="{$helperMenu->token|escape:'htmlall':'UTF-8'}">
    <input type="hidden" name="action" value="updateItem">
    <input type="hidden" name="id_lang" value="{$id_lang}">
    <div class="form-group sweet-modal-prompt">
      <label for="">{l s='Label' mod='seoprestamenu'}</label>
      <input type="text" class="form-control" name="label" value="{if isset($item->label[$id_lang])}{$item->label[$id_lang]}{/if}">
    </div>

    <div class="form-group sweet-modal-prompt">
      <label for="">{l s='Url' mod='seoprestamenu'}</label>
      <input type="text" class="form-control" name="url" value="{if isset($item->url[$id_lang])}{$item->url[$id_lang]}{/if}">
    </div>
    {$item|dump}

    <div class="form-group sweet-modal-prompt">
      <label for="">{l s='Target' mod='seoprestamenu'}</label>
      <br>
      <select name="target" class="form-control">
        <option value="_self" {if isset($item->target) && $item->target == '_self'} selected {/if} >{l s='In the current tab' mod='seoprestamenu'}</option>
        <option value="_blank" {if isset($item->target) && $item->target == '_blank'} selected {/if} >{l s='In other tab' mod='seoprestamenu'}</option>
      </select>
    </div>
    {if $helperMenu->menu_model->getChilden($item->id,$id_lang)|count > 0}
    <div class="form-group sweet-modal-prompt">
      <label for="">{l s='Display in section' mod='seoprestamenu'}</label>
      <br>
      <input type="checkbox" class="form-control" name="display_sections" {if isset($item->display_sections[$id_lang]) && $item->display_sections[$id_lang]} checked {/if}value="1">
    </div>
    {/if}


    
    {hook h='displayAjaxFormMenu'}

    <div class="form-group sweet-modal-buttons">
      <label for="">&nbsp;</label>
      <br>
      <button type="submit" name="updateItem" class="button greenB">{l s='Update' mod='seoprestamenu'}</button>
    </div>
</form>
