{$aHtmlHeadFiles.css}
<div style="padding: 10px;">

{if !$oAdvert}
	{assign var="oAdvert" value=$params.oadvert} 
{/if}	
{assign var="BlockClass" value=$oAdvert->getAdvertBlockCss()} 

{if $BlockClass == '-def'}
	{assign var="BlockClass" value=''} 
{/if}

{if $oAdvert->getAdvertBlockType() == 'banner'}
	<section class="block{$oAdvert->getAdvertBlockCss()}" id="block_advert_{$oAdvert->getAdvertBlockPlace()}">
		<div class="block-content{$BlockClass}">
			{if $oAdvert->getAdvertDataUrl() && $oAdvert->getAdvertDataImg()}
				<center>
				<a href="{$oAdvert->getAdvertDataUrl()}" rel="nofollow" target="_blank" onClick="advert_click('{$oAdvert->getAdvertId()}');">
					<img src="{$oAdvert->getAdvertDataImg()}" border="0">
				</a>
				</center>
			{else if $oAdvert->getAdvertDataUrl() && !$oAdvert->getAdvertDataImg()}
				<a href="{$oAdvert->getAdvertDataUrl()}" rel="nofollow" target="_blank" onClick="advert_click('{$oAdvert->getAdvertId()}');">
					Подробнее...
				</a>
			{else if !$oAdvert->getAdvertDataUrl() && $oAdvert->getAdvertDataImg()}
				<center>
					<img src="{$oAdvert->getAdvertDataImg()}" border="0">
				</center>
			{/if}
		</div>
	</section>
{elseif $oAdvert->getAdvertBlockType() == 'code'}
	<section class="block{$oAdvert->getAdvertBlockCss()}" id="block_advert_{$oAdvert->getAdvertBlockPlace()}">
		{$oAdvert->getAdvertDataText()}
	</section>	
{elseif $oAdvert->getAdvertBlockType() == 'text'}
	<section class="block{$BlockClass}" id="block_advert_{$oAdvert->getAdvertBlockPlace()}">
		<header class="block-header{$BlockClass}">
			<h3>
					{$oAdvert->getAdvertHint()}
			</h3>
		</header>
		
		<div class="block-content{$BlockClass}">
			{if $oAdvert->getAdvertDataText()}
				{$oAdvert->getAdvertDataText()}
				<br/><br/>
			{/if}
			
			{if $oAdvert->getAdvertDataUrl() && $oAdvert->getAdvertDataImg()}
				<center>
				<a href="{$oAdvert->getAdvertDataUrl()}" rel="nofollow" target="_blank" onClick="advert_click('{$oAdvert->getAdvertId()}');">
					<img src="{$oAdvert->getAdvertDataImg()}" border="0">
				</a>
				</center>
			{else if $oAdvert->getAdvertDataUrl() && !$oAdvert->getAdvertDataImg()}
				<a href="{$oAdvert->getAdvertDataUrl()}" rel="nofollow" target="_blank" onClick="advert_click('{$oAdvert->getAdvertId()}');">
					Подробнее...
				</a>
			{else if !$oAdvert->getAdvertDataUrl() && $oAdvert->getAdvertDataImg()}
				<center>
					<img src="{$oAdvert->getAdvertDataImg()}" border="0">
				</center>
			{/if}
		</div>	
	</section>

{/if}
<br/>
<div style="font-size: 10px; color: #333; background: #f5f5f5; border: 1px dashed #cccccc; padding: 5px;">
Внимание ! Отображаемая информация может не соотетствовать тому что вы увидите на страницах сайта. 
Главная цель этого preview показать используемые стили для вашего блока. После запуска всегда проверяйте корректность 
отображения блока на сайте !
</div>
</div>

