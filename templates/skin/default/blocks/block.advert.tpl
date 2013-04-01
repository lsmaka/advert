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
	<center>
		<section class="block{$oAdvert->getAdvertBlockCss()}" id="block_advert_{$oAdvert->getAdvertBlockPlace()}">
			{$oAdvert->getAdvertDataText()|unescape:"html"}
		</section>
	</center>	
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


