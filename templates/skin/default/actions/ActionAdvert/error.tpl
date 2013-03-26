{assign var="noSidebar" value='true'} 

{include file='header.tpl'}
{include file='menu.settings.tpl'}

<h2 class="page-header">Отказанно в доступе</h2>

Вам отказанно в доступе к разделу <b>{$aLang.plugin.advert.profile_title}</b>. 
{if $sUserCurrentRatin<$sSystemRating}
	Ваш рейтинг ({$sUserCurrentRatin}) меньше необходимого ({$sSystemRating}).
{elseif in_array($sUserCurrent, $aDisableUsers)}
	Ваш аккаунт ({$sUserCurrent}) заблокирован.
{/if}

{include file='footer.tpl'}