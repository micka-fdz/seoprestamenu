{if isset($start) && $start}
<div class="dd nestable" id="nestable">
	<ol class="dd-list" id="menuTree">
{/if}
{foreach from=$items item=item name=itemLoop}

	{include file='./item.tpl' start=false item=$item}
{foreachelse}
		<p>{l s='No item in menu' mod='seoprestamenu'}
{/foreach}

{if isset($start) && $start}
	</ol>
</div>
{/if}
