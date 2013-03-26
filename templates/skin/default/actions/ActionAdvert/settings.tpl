{assign var="noSidebar" value='true'} 

{include file='header.tpl'}
{include file='menu.settings.tpl'}

<span class="advert_button_wrapper" >
	<a href="{router page='advert'}settings/add/{$sUserCurrent}"><span class="advert_button">{$aLang.plugin.advert.addbutton}</span></a>
</span>
	
{if $sModeration}
		<div class="advert_view_type">
			<span style="background: #FFCC99; color: black; padding: 5px;">{$sModeration}</span>
		</div>
{/if}


<br/><br/><br/>

{if $sShowFieldsForAdmin}
<span class="advert_button_wrapper" >
	<a href="{router page='advert'}"><span class="advert_button">{$aLang.plugin.advert.sortfullbutton}</span></a>
</span>

{/if}

{if $sUserCurrent}
	{assign var="pathmenu" value="/$sUserCurrent"} 
{else}
	{assign var="pathmenu" value=""} 
{/if}
<span class="{if $sMenuArvertItemSelect=='all'}advert_button_wrapper_active{else}advert_button_wrapper{/if}" >
		<a href="{router page='advert'}settings/sort{$pathmenu}/all/"><span class="advert_button {if $sMenuArvertItemSelect=='all'}advert_button_active{/if}">{$aLang.plugin.advert.sortallbutton}</span></a>
</span>

<span class="{if $sMenuArvertItemSelect=='active'}advert_button_wrapper_active{else}advert_button_wrapper{/if}" >
		<a href="{router page='advert'}settings/sort{$pathmenu}/active/"><span class="advert_button {if $sMenuArvertItemSelect=='active'}advert_button_active{/if}">{$aLang.plugin.advert.sortactivebutton}</span></a>
</span>

<span class="{if $sMenuArvertItemSelect=='notactive'}advert_button_wrapper_active{else}advert_button_wrapper{/if}" >
	<a href="{router page='advert'}settings/sort{$pathmenu}/notactive/"><span class="advert_button {if $sMenuArvertItemSelect=='notactive'}advert_button_active{/if}">{$aLang.plugin.advert.sortnotactivebutton}</span></a>
</span>

<span class="{if $sMenuArvertItemSelect=='delayedstart'}advert_button_wrapper_active{else}advert_button_wrapper{/if}" >
	<a href="{router page='advert'}settings/sort{$pathmenu}/delayedstart/"><span class="advert_button {if $sMenuArvertItemSelect=='delayedstart'}advert_button_active{/if}">Отложенный старт</span></a>
</span>

<span class="{if $sMenuArvertItemSelect=='moderation'}advert_button_wrapper_active{else}advert_button_wrapper{/if}" >
	<a href="{router page='advert'}settings/sort{$pathmenu}/moderation/"><span class="advert_button {if $sMenuArvertItemSelect=='moderation'}advert_button_active{/if}">{$aLang.plugin.advert.sortmoderationbutton}</span></a>
</span>

<br/><br/>

{if $sShowFieldsForAdmin}
	{assign var="aBlockPlaces" value=$oConfig->get('plugin.advert.block_places_admin')}
{else}
	{assign var="aBlockPlaces" value=$oConfig->get('plugin.advert.block_places_user')}
{/if}
<form action="{router page='advert'}" method="post" id="advert_filter" name="advert_filter">
	Номер #
	<span class="advert_button_wrapper" >
		<input type="text" class="input-text autocomplete-advert-filter-advertid" value="" name="advert_filter_block_id"/>
	</span>	
	{if $sShowFieldsForAdmin}
	Пользователь 
	<span class="advert_button_wrapper" >
		<input type="text" class="input-text autocomplete-advert-filter-advertuser" value="" name="advert_filter_user"/>
	</span>	
	{/if}	
	Место 
	<span class="advert_button_wrapper" >
		<select name="advert_filter_blockplace">
			<option value="">Не учитывать</option>	
			{foreach $aBlockPlaces key=place item=placeName}
					<option value="{$place}">{$placeName}</option>
			{/foreach}
		</select> 
	</span>

	<input type="hidden" name="sArvertItemSelect" value="{$sMenuArvertItemSelect}">
	<button type="submit" name="submit_filter">Показать</button>
</form>

{if $sUserCurrent}
<br/><br/>
Пороговое значение рейтинга : <b>{$sRateAccess}</b>, Рейтинг пользователя : <b>{$sRateUser}</b>, 
Наличие в базе allow : {if in_array($sUserCurrent, $aAllowUsers)}<b>Да</b>{else}<b>Нет</b>{/if},
Наличие в базе disable : {if in_array($sUserCurrent, $aDisableUsers)}<b>Да</b>{else}<b>Нет</b>{/if}

{/if}
<br/><br/>

{assign var="divbgcolor" value=0} 
{assign var="bgcolor" value='#dcdcdc'} 

{foreach from=$oAdverts item=oAdvert}

{if $oAdvert->getAdvertUserOwnerLogin() != $oConfig->get('plugin.advert.defuser')}
	{assign var="aActions" value=$oConfig->get('plugin.advert.block_actions_user')} 
	{assign var="aBlockPlaces" value=$oConfig->get('plugin.advert.block_places_user')}
	{assign var="aBlockTypes" value=$aBlockTypesUser}
	{assign var="aBlockStyle" value=$aBlockStyleUser}
{else}
	{assign var="aActions" value=$oConfig->get('plugin.advert.block_actions_admin')} 
	{assign var="aBlockPlaces" value=$oConfig->get('plugin.advert.block_places_admin')}
	{assign var="aBlockTypes" value=$aBlockTypesAdmin}
	{assign var="aBlockStyle" value=$aBlockStyleAdmin}
{/if}
				
<a name="{$oAdvert->getAdvertId()}"></a>

<div class="advert_table_div_header" style="background: {if $divbgcolor++ is div by 2}#f5f5f5{else}white{/if}; display: block;" id="advert_table_div_header_{$oAdvert->getAdvertId()}"> 
	<table border="0" width="100%" cellpadding="3" cellspacing="0">
		<tr align="center">
			<td width="60px">
				{if $oAdvert->getAdvertStatus()==0}
					<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/stoped.png'}" border="0" width="32" height="32" class="js-title-comment" title="Задание <br/>остановлено">
				{elseif $oAdvert->getAdvertStatus()==2}	
					<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/moderation.png'}" border="0" width="32" height="32" class="js-title-comment" title="Сообщение <br/> находиться <br/>на модерации">
				{elseif $oAdvert->getAdvertStatus()==1}
					<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/running.png'}" border="0" width="32" height="32" class="js-title-comment" title="Данное задание<br/> активно !">
				{elseif $oAdvert->getAdvertStatus()==3}
					<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/runafter.png'}" border="0" width="32" height="32" class="js-title-comment" title="Задание запуститься <br/>согласно расписанию">
				{/if}
			</td>	

			<td width="60px">
				<b>#{$oAdvert->getAdvertId()}</b>
			</td>
			<td width="100px">
				<span class="advert-info-{if $sDefUser == $oAdvert->getAdvertUserOwnerLogin()}admin">
					<a href="{router page='advert'}settings/sort/{$oAdvert->getAdvertUserOwnerLogin()}/all/">{$oAdvert->getAdvertUserOwnerLogin()}</a>
				</span>
				{elseif ( $oAdvert->getAdvertUserRating() < $sRateAccess && !in_array($oAdvert->getAdvertUserOwnerLogin(), $aAllowUsers)) || in_array($oAdvert->getAdvertUserOwnerLogin(), $aDisableUsers) }error">
					<a href="{router page='advert'}settings/sort/{$oAdvert->getAdvertUserOwnerLogin()}/all/">{$oAdvert->getAdvertUserOwnerLogin()}</a>
				</span>{else}normal">
					<a href="{router page='advert'}settings/sort/{$oAdvert->getAdvertUserOwnerLogin()}/all/">{$oAdvert->getAdvertUserOwnerLogin()}</a>
				</span>
				{/if}
				{*<a href="{router page='advert'}settings/sort/{$oAdvert->getAdvertUserOwnerLogin()}/all/">{$oAdvert->getAdvertUserOwnerLogin()}</a> *}		
			</td>
			<td width="100px">
				{$aBlockPlaces[$oAdvert->getAdvertBlockPlace()]}
			</td>
			<td width="100px">
				{$aBlockTypes[$oAdvert->getAdvertBlockType()]}
			</td>	
			<td width="100px">{$aBlockStyle[$oAdvert->getAdvertBlockType()][$oAdvert->getAdvertBlockCss()]}</td>
{**}
			<td width="150px" align="left">
				Просмотры
				{if $oAdvert->getAdvertView()}
					{$oAdvert->getAdvertView()}
				{else}
				N/A
				{/if} 
				&rarr; 
				{if $oAdvert->getAdvertViewCount()}
					{$oAdvert->getAdvertViewCount()}
				{else}
					N/A
				{/if}
				<br>
				Переходы
				{if $oAdvert->getAdvertClick()}
					{$oAdvert->getAdvertClick()}
				{else}
					N/A
				{/if} 
				&rarr; 
				{if $oAdvert->getAdvertClickCount()}
					{$oAdvert->getAdvertClickCount()}
				{else}
					N/A
				{/if}
			</td>

{**}				
			<td width="130px">
			<a href="{router page='advert'}settings/start/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/" onClick="return advert_conform_choise('start', {$oAdvert->getAdvertId()})">
				<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/ButtonPlay.png'}" border="0" title="Запустить">
			</a>

			<a href="{router page='advert'}settings/del/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/" onClick="return advert_conform_choise('del', {$oAdvert->getAdvertId()})">
				<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/ButtonDelete.png'}" border="0" title="Удалить"> 
			</a>
			
			<a href="{router page='advert'}settings/stop/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/" onClick="return advert_conform_choise('stop', {$oAdvert->getAdvertId()})">
				<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/ButtonPause.png'}" border="0" title="Остановить">
			</a>
			
			<a href="#{$oAdvert->getAdvertId()}" onclick="jQuery('#advert_table_div_content_{$oAdvert->getAdvertId()}').fadeToggle(); "> 
				<img src="{$aTemplateWebPathPlugin.advert|cat:'images/icons/ButtonSettings.png'}" border="0" title="Настройки">
			</a>
			</td>
		</tr>
	</table>
</div>

<div class="advert_table_div_content" style="background: {if $divbgcolor++ is div by 2}#f5f5f5{else}white{/if}; display: none;" id="advert_table_div_content_{$oAdvert->getAdvertId()}"> 

<table border="0" width="100%" cellpadding="3" cellspacing="0">
	<tr bgcolor="#cccccc">
		<td colspan="2">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="15%">
						<div class="advert_button_wrapper">
							<a href="{router page='advert'}settings/edit/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/"><div class="advert_button" onClick="return advert_conform_choise('edit', {$oAdvert->getAdvertId()})">{$aLang.plugin.advert.editbutton}</div></a>
						</div>
					</td>
					<td width="15%">	
						<div class="advert_button_wrapper" >
							<a href="{router page='advert'}settings/start/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/"><div class="advert_button" onClick="return advert_conform_choise('start', {$oAdvert->getAdvertId()})">{$aLang.plugin.advert.startbutton}</div></a>
						</div>
					</td>
					<td width="15%">	
						<div class="advert_button_wrapper" >
							<a href="{router page='advert'}settings/stop/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/"><div class="advert_button" onClick="return advert_conform_choise('stop', {$oAdvert->getAdvertId()})">{$aLang.plugin.advert.stopbutton}</div></a>
						</div>	
					</td>
					<td width="15%">	
						<div class="advert_button_wrapper" >
							<a href="{router page='advert'}settings/del/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/"><div class="advert_button" onClick="return advert_conform_choise('del', {$oAdvert->getAdvertId()})">{$aLang.plugin.advert.delbutton}</div></a>
						</div>	
					</td>
					<td width="15%">	
						<div class="advert_button_wrapper" >
							<a href="#" onClick="window.open('{router page='advert'}settings/preview/{$oAdvert->getAdvertUserOwnerLogin()}/{$oAdvert->getAdvertId()}/','mywindow','width=500,height=300,scrollbars=yes'); return false;"><div class="advert_button">Предпросмотр</div></a> 
						</div>						
					</td>	
					<td>
						<div class="advert_button_wrapper" >
							<a href="#{$oAdvert->getAdvertId()}" onclick="jQuery('#advert_table_div_content_{$oAdvert->getAdvertId()}').fadeToggle(); return false;"><span class="advert_button">Закрыть панель</span></a> 
						</div>					
					</td>
				</tr>
			</table>	
		</td>
	</tr>
	<tr>
		<td width="40%">
			<textarea rows="5" class="input-text input-width-full" disabled>{$oAdvert->getAdvertDataText()|escape:'html'}</textarea>
		</td>
		<td>
			<table border="1" width="100%" cellpadding="3" cellspacing="0">
				<tr>
					<td bgcolor="{$bgcolor}" width="80px">
						{$aLang.plugin.advert.table_hint}
					</td>
					<td>
						{$oAdvert->getAdvertHint()}
					</td>
				</tr>
				<tr>	
					<td bgcolor="{$bgcolor}">
						{$aLang.plugin.advert.table_img}
					</td>
					<td>
						{if $oAdvert->getAdvertDataImg()}
							{$oAdvert->getAdvertDataImg()|truncate:50:'...':true:true}
						{/if}
					</td>
				</tr>
				<tr>		
					<td bgcolor="{$bgcolor}">
						{$aLang.plugin.advert.table_url}
					</td>
					<td>
						{if $oAdvert->getAdvertDataUrl()}
						{$oAdvert->getAdvertDataUrl()|truncate:50:'...':true:true}
							<a href="{$oAdvert->getAdvertDataUrl()}" target="_blank">&raquo;&raquo;&raquo;</a>
						{/if}
					</td>
				</tr>
				
				<tr>	
					<td bgcolor="{$bgcolor}">Запустить</td>
					<td>
						{if $oAdvert->getAdvertDateToStartFlag()}
							{$oAdvert->getAdvertDateToStart()}
						{else}
							Запускается в ручном режиме
						{/if}		
					</td>
				</tr>					
				
			</table>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<table border="0" width="100%" cellpadding="3" cellspacing="3">
				<tr valign="top">
					<td width="30%">
						<table border="1" width="100%" cellpadding="3" cellspacing="0">
							<tr>
								<td colspan="2" bgcolor="{$bgcolor}" align="center">Настройки показа блока</td>
							</tr>
							<tr>
								<td width="50%">Место</td>
								<td>{$aBlockPlaces[$oAdvert->getAdvertBlockPlace()]}</td>
							</tr>	
							<tr>
								<td>Тип</td>
								<td>{$aBlockTypes[$oAdvert->getAdvertBlockType()]}</td>
							</tr>		
							<tr>
								<td>Стиль CSS</td>
								<td>{$aBlockStyle[$oAdvert->getAdvertBlockType()][$oAdvert->getAdvertBlockCss()]}</td>
							</tr>	
							
							{if $sShowFieldsForAdmin}
							<tr>
								<td>Перекрывать</td>
								<td>
									{if $oAdvert->getAdvertBlockRewrite()}
									Да
									{else}
									Нет
									{/if}
								</td>
							</tr>
							{/if}
							
							<tr>
								<td>Приоритет</td>
								<td>{$oAdvert->getAdvertBlockPriority()}</td>
							</tr>								
						</table>
					</td>
					<td width="30%">
						<table border="1" width="100%" cellpadding="3" cellspacing="0">
							<tr>
								<td colspan="2" bgcolor="{$bgcolor}" align="center">Ход истории</td>
							</tr>
			
							<tr>
								<td>{$aLang.plugin.advert.table_data_add}</td>
								<td>{$oAdvert->getAdvertDateAdd()}</td>
							</tr>	
							<tr>
								<td>{$aLang.plugin.advert.table_data_edit}</td>
								<td>{$oAdvert->getAdvertDateEdit()}</td>
							</tr>		
							<tr>
								<td>{$aLang.plugin.advert.table_data_start}</td>
								<td>{$oAdvert->getAdvertDateStart()}</td>
							</tr>
							<tr>
								<td>{$aLang.plugin.advert.table_data_stop}</td>
								<td>{$oAdvert->getAdvertDateStop()}</td>
							</tr>	
							<tr>
								<td>Переписка</td>
								<td>
									{if $oAdvert->getAdvertTalkId()}
										<a href="{$oConfig->get('path.root.web')}/talk/read/{$oAdvert->getAdvertTalkId()}/" target="_blank">
										Почитать
										</a>
									{else}
										N/A
									{/if}
								</td>
							</tr>								
						</table>
					</td>										
					<td width="30%">
						<table border="1" width="100%" cellpadding="3" cellspacing="0">
							<tr>
								<td colspan="2" bgcolor="{$bgcolor}" align="center">Настройки остановки блока</td>
							</tr>
							<tr>
								<td>Дата</td>
								<td>
									{if $oAdvert->getAdvertDateToStop()}
										{$oAdvert->getAdvertDateToStop()}
									{else}
										N/A
									{/if}
								</td>
							</tr>	
							<tr>
								<td>Просмотры</td>
								<td>
									{if $oAdvert->getAdvertView()}
										{$oAdvert->getAdvertView()}
									{else}
									N/A
									{/if} 
									&rarr; 
									{if $oAdvert->getAdvertViewCount()}
										{$oAdvert->getAdvertViewCount()}
									{else}
										N/A
									{/if}
								</td>
							</tr>		
							<tr>
								<td>Переходы</td>
								<td>
									{if $oAdvert->getAdvertClick()}
										{$oAdvert->getAdvertClick()}
									{else}
										N/A
									{/if} 
									&rarr; 
									{if $oAdvert->getAdvertClickCount()}
										{$oAdvert->getAdvertClickCount()}
									{else}
										N/A
									{/if}
								</td>
							</tr>								
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	{* *}
	<tr>	
		<td colspan="2">
			<table border="1" width="100%" CELLPADDING="5" CELLSPACING="0">

			<tr align="center">
				{foreach from=$aActions item=action}
					<td width="10%" bgcolor="#dcdcdc" class="advert_table_border">{$action}</td>
				{/foreach}	
			</tr>
			<tr align="center">
				{foreach from=$aActions item=action}
					{if $aaRules[$oAdvert->getAdvertId()]['actions'][$action] == 1}
						<td><input type="checkbox" name="advert_action_{$action}" value="1" checked disabled></td>
					{else}
						<td><input type="checkbox" name="advert_action_{$action}" value="1" disabled></td>
					{/if}
				{/foreach}	
			</tr>			
			
			{if $sShowFieldsForAdmin}
			<tr>
				<td colspan="{$aActions|@count}">
					Блоги &darr; <input type="text" class="input-text input-width-full" id="advert_blogs" name="advert_blogs" value="{$aaRules[$oAdvert->getAdvertId()]['blogs']}" disabled />
				</td>
			</tr>	
				
			<tr>
				<td colspan="{$aActions|@count}">
					Пользователи &darr; <input type="text" class="input-text input-width-full" id="advert_users" name="advert_users" value="{$aaRules[$oAdvert->getAdvertId()]['users']}" disabled/>
				</td>
			</tr>	
			{/if}
			
			<tr>
				<td colspan="{$aActions|@count}">
					Топики &darr; <input type="text" class="input-text input-width-full" id="advert_topics" name="advert_topics" value="{$aaRules[$oAdvert->getAdvertId()]['topics']}" disabled/>
				</td>
			</tr>										
			</table>
			
			
		</td>	
	</tr>	
	{* *}
</table>
</div>

{/foreach}

<br/>
<span class="advert_button_wrapper" >
	<a href="{router page='advert'}settings/add/{$sUserCurrent}"><span class="advert_button">{$aLang.plugin.advert.addbutton}</span></a>
</span>

<span style="float:right;">
	{hook run='advert_version_info'}
</span>

{include file='footer.tpl'}