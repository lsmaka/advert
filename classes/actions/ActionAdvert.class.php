<?php

class PluginAdvert_ActionAdvert extends ActionPlugin {
	protected $sMenuSubItemSelect='advert';
	protected $sMenuArvertItemSelect = 'all';
	protected $oUserCurrent = null;
	protected $sUserCurrent = null;
	protected $sShowFieldsForAdmin = null;
	protected $sShowUserLoginForAdmin = null;
	protected $iShowByAdvertType = null;
	protected $aFilter = array();
    /**
     * Инициализация экшена
     */
    public function Init() {
        $this->SetDefaultEvent('settings');
    }
    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
    $this->AddEvent('settings','EventSettings');
	$this->AddEventPreg('/^ajax$/i','/^users$/','EventAutocompleterAjax');
	$this->AddEventPreg('/^ajax$/i','/^blogs$/','EventAutocompleterAjax');
	$this->AddEventPreg('/^ajax$/i','/^topics$/','EventAutocompleterAjax');	
	$this->AddEventPreg('/^ajax$/i','/^filter_advertid$/','EventAutocompleterAjax');
	$this->AddEventPreg('/^ajax$/i','/^filter_advertuser$/','EventAutocompleterAjax');	
	$this->AddEventPreg('/^ajax$/i','/^click$/','EventClickAjax');	
	$this->AddEventPreg('/^ajax$/i','/^blockTypeChange$/','EventBlockTypeChangeAjax');
    }
//
    protected function EventBlockTypeChangeAjax()
    {
		$this->Viewer_SetResponseAjax('json');
		$sUser = 'admin';
		if(getRequest('advertUserOwner') != Config::Get('plugin.advert.defuser'))
		{
			$sUser = 'user';
		}
		$aItems = $this->__advertGetConfigData($sUser, getRequest('advertBlockType'), 'css');
		$this->Viewer_AssignAjax('aItems',$aItems);
    }
	//	
    protected function EventClickAjax()
    {
		if($this->GetParam(0) == 'click')
		{
			$this->PluginAdvert_Advert_AdvertClick(getRequest('advertId'));
		}   
    }
	//	
    protected function EventAutocompleterAjax()
    {
    $this->Viewer_SetResponseAjax('json');
	$sUser = '';
	if($this->User_GetUserCurrent()->isAdministrator() || $this->User_GetUserCurrent()->getLogin() == Config::Get('plugin.advert.defuser'))
	{
		$sUser = '';
	}
	else
	{
		$sUser = $this->User_GetUserCurrent()->getId();
	}
	if (!($sValue=getRequest('value',null,'post')) or !is_string($sValue))
	{
		return ;
	}	
	$aItems=array();
		
	if($this->GetParam(0) == 'users')
	{
		$aUsers=$this->User_GetUsersByLoginLike($sValue,10);
		foreach ($aUsers as $oUser) 
		{
			$aItems[]=$oUser->getLogin();
		}	
	}
	else if($this->GetParam(0) == 'blogs')
	{
		$aItems = $this->PluginAdvert_Advert_GetBlogsByBlogNameLike($sValue,10);
	}
	else if($this->GetParam(0) == 'topics')
	{
		$aItems = $this->PluginAdvert_Advert_GetTopicsByTopicIdLike($sValue,10, $sUser);
	}
	else if($this->GetParam(0) == 'filter_advertid')
	{
		$aItems = '';
		if(preg_match("/^\d+$/",$sValue) || $sValue == '%%')
		{	
			$aItems = $this->PluginAdvert_Advert_FilterGetAdvertId($sValue,10, $sUser);
		}	
	}
	else if($this->GetParam(0) == 'filter_advertuser')
	{
		$aItems = $this->PluginAdvert_Advert_FilterGetAdvertUser($sValue,10);
	}	
	
    	$this->Viewer_AssignAjax('aItems',$aItems);
    	
    }
    protected function EventSettings() {

	//
	$this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__)."css/style_system.css");
	$this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__)."js/script_system.js");

	$this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__)."css/external/imgareaselect-default.css");
	$this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__)."js/external/jquery.imgareaselect.js");	
	//
	
	$this->oUserCurrent=$this->User_GetUserCurrent();
	$aAllowUsers = Config::Get('plugin.advert.allow_users');
	$aDisableUsers = Config::Get('plugin.advert.disable_users');
	$sDefUser = Config::Get('plugin.advert.defuser');
	$sRateAccess = Config::Get('plugin.advert.access_rate');
	$aActionsAdmin = Config::Get('plugin.advert.block_actions_admin');
	$aActionsUser = Config::Get('plugin.advert.block_actions_user');
	$aBlogPlacesAdmin = Config::Get('plugin.advert.block_places_admin');
	$aBlogPlacesUser = Config::Get('plugin.advert.block_places_user');
	$aBlogTypesAdmin = Config::Get('plugin.advert.block_types_admin');
	$aBlogTypesUser = Config::Get('plugin.advert.block_types_user');
	
	if (!$this->oUserCurrent ) {
		return $this->EventNotFound();
	}

	if(Config::Get('plugin.advert.admin_mode') && $this->User_GetUserCurrent()->getUserLogin() != Config::Get('plugin.advert.defuser'))
	{
		return $this->EventNotFound();
	}
	// если НЕ админ И НЕ не пользователь по умолчанию И рейтинг < def рейтинг И пользователь не в array allow users то непускаем
	// если НЕ админ И НЕ не пользователь по умолчанию И рейтинг >= def рейтинг И пользователь в array desable users то непускаем
	else if ( (!$this->oUserCurrent->isAdministrator() && $this->oUserCurrent->getLogin() != $sDefUser && $this->oUserCurrent->getRating() < $sRateAccess && !in_array($this->oUserCurrent->getLogin(), $aAllowUsers)) || (!$this->oUserCurrent->isAdministrator() && $this->oUserCurrent->getLogin() != $sDefUser && $this->oUserCurrent->getRating() >= $sRateAccess && in_array($this->oUserCurrent->getLogin(), $aDisableUsers)))
	{
		if(Config::Get('plugin.advert.help_page'))
		{
			Router::Location(Router::GetPath('page/'.Config::Get('plugin.advert.help_page')));
		}	
		else
		{			
			$this->Viewer_Assign('sUserCurrent',$this->oUserCurrent->getLogin());
			$this->Viewer_Assign('sUserCurrentRatin',$this->oUserCurrent->getRating());
			$this->Viewer_Assign('sSystemRating',$sRateAccess);
			$this->Viewer_Assign('aAllowUsers',$aAllowUsers);
			$this->Viewer_Assign('aDisableUsers',$aDisableUsers);
			$this->SetTemplateAction('error');
		}
		return false;
	}
	// попытка просмотреть статистику другого пользователя		
	if($this->GetParam(1) && $this->GetParam(1) != $this->oUserCurrent->getLogin() && !$this->oUserCurrent->isAdministrator())
	{
		Router::Location(Router::GetPath('advert'));
	}	
	//
		
	if ($this->GetParam(0) == 'add')
	{		
		if (isPost('submit_page_new'))
		{
			$this->Security_ValidateSendForm();
			
			$oAdvert = $this->SubmitNewData();
			$iAdvertId = $this->PluginAdvert_Advert_SubmitNewData($oAdvert);
			// нет id advert поэтому некуда делать # переход
			Router::Location(Router::GetPath('advert/settings/sort/'.$oAdvert->getAdvertUserOwnerLogin().'/notactive'));
		}
		else
		{			
			$this->sUserCurrent = $this->GetParam(1) ? $this->GetParam(1) : $this->oUserCurrent->getLogin() ;		

			$this->Viewer_Assign('aActions', $aActionsAdmin);
			$this->Viewer_Assign('aBlockPlaces', $aBlogPlacesAdmin);
			$this->Viewer_Assign('aBlockTypes', $this->__advertGetConfigData('admin', false, 'name'));
			$this->Viewer_Assign('aCss', $this->__advertGetConfigData('admin', 'text', 'css'));
			$this->Viewer_Assign('isAdministrator', true);
			
			if( ($this->oUserCurrent->isAdministrator() && $this->sUserCurrent != $sDefUser) || !$this->oUserCurrent->isAdministrator()) 
			{	
				$this->Viewer_Assign('aActions', $aActionsUser);
				$this->Viewer_Assign('aBlockPlaces', $aBlogPlacesUser);
				$this->Viewer_Assign('isAdministrator', false);
				$this->Viewer_Assign('aBlockTypes', $this->__advertGetConfigData('user', false, 'name'));
				$this->Viewer_Assign('aCss', $this->__advertGetConfigData('user', 'text', 'css'));				
			}	
			
			$this->Viewer_Assign('sUserCurrent', $this->sUserCurrent);
			
			$this->Viewer_Assign('sDateYearToStop',date("Y"));
			$this->Viewer_Assign('sDateMonthToStop',date("m"));
			$this->Viewer_Assign('sDateDayToStop',date("d"));
			$this->Viewer_Assign('sDateHourToStop',date("H"));
			$this->Viewer_Assign('sDateMinToStop',date("i"));
			$this->Viewer_Assign('sDateSecToStop',date("s"));
			
			$this->Viewer_Assign('sDateYearToStart',date("Y"));
			$this->Viewer_Assign('sDateMonthToStart',date("m"));
			$this->Viewer_Assign('sDateDayToStart',date("d"));
			$this->Viewer_Assign('sDateHourToStart',date("H"));
			$this->Viewer_Assign('sDateMinToStart',date("i"));
			$this->Viewer_Assign('sDateSecToStart',date("s"));			
			
			$this->SetTemplateAction('add');
			return true;
		}	
	}
	else if ($this->GetParam(0) == 'edit')
	{
		if (isPost('submit_page_edit'))
		{
			$this->Security_ValidateSendForm();
			
			$oAdvert = $this->SubmitEditData();
			$this->PluginAdvert_Advert_SubmitEditData($oAdvert);
			Router::Location(Router::GetPath('advert/settings/sort/'.$oAdvert->getAdvertUserOwnerLogin().'/notactive').'#'.$oAdvert->getAdvertId());
		}
		else
		{
		
			$this->sUserCurrent = $this->GetParam(1) ? $this->GetParam(1) : $this->oUserCurrent->getLogin() ;
	
			$oAdvert = $this->PluginAdvert_Advert_GetDataById($this->GetParam(2));
			$this->Viewer_Assign('oAdvert',$oAdvert);
			$aRules = json_decode($oAdvert->getAdvertRules(), true);

			if( ($this->oUserCurrent->isAdministrator() && $this->sUserCurrent != $sDefUser) || !$this->oUserCurrent->isAdministrator()) 
			{	
				$this->Viewer_Assign('isAdministrator', false);
				$this->Viewer_Assign('aActions', $aActionsUser);
				$this->Viewer_Assign('aBlockPlaces', $aBlogPlacesUser);
				//$this->Viewer_Assign('aBlockTypes', $aBlogTypesUser);
				$this->Viewer_Assign('aRulesAction',$aRules['actions']);
				$this->Viewer_Assign('sRulesTopics',$aRules['topics']);	
				//$this->Viewer_Assign('aCss', Config::Get('plugin.advert.block_css_user'));			
				//
				$this->Viewer_Assign('aBlockTypes', $this->__advertGetConfigData('user', false, 'name'));
				$this->Viewer_Assign('aCss', $this->__advertGetConfigData('user', $oAdvert->getAdvertBlockType(), 'css'));		
				
			}
			else
			{
				$this->Viewer_Assign('isAdministrator', true);
				$this->Viewer_Assign('aActions', $aActionsAdmin);
				$this->Viewer_Assign('aBlockPlaces', $aBlogPlacesAdmin);
				//$this->Viewer_Assign('aBlockTypes', $aBlogTypesAdmin);
				$this->Viewer_Assign('aRulesAction',$aRules['actions']);
				$this->Viewer_Assign('sRulesUsers',$aRules['users']);
				$this->Viewer_Assign('sRulesBlogs',$aRules['blogs']);
				$this->Viewer_Assign('sRulesTopics',$aRules['topics']);		
				//$this->Viewer_Assign('aCss', Config::Get('plugin.advert.block_css_admin'));		
				//
				$this->Viewer_Assign('aBlockTypes', $this->__advertGetConfigData('admin', false, 'name'));
				$this->Viewer_Assign('aCss', $this->__advertGetConfigData('admin', $oAdvert->getAdvertBlockType(), 'css'));								
			}

			
			$sDataToStopSrt = strtotime($oAdvert->getAdvertDateToStop());
			$sDataToStopArr = getdate($sDataToStopSrt);
			
			$this->Viewer_Assign('sDateYearToStop',$sDataToStopArr['year']);
			$this->Viewer_Assign('sDateMonthToStop',$sDataToStopArr['mon']);
			$this->Viewer_Assign('sDateDayToStop',$sDataToStopArr['mday']);
			$this->Viewer_Assign('sDateHourToStop',$sDataToStopArr['hours']);
			$this->Viewer_Assign('sDateMinToStop',$sDataToStopArr['minutes']);
			$this->Viewer_Assign('sDateSecToStop',$sDataToStopArr['seconds']);
			
			$sDataToStartSrt = strtotime($oAdvert->getAdvertDateToStart());
			$sDataToStartArr = getdate($sDataToStartSrt);
			
			$this->Viewer_Assign('sDateYearToStart',$sDataToStartArr['year']);
			$this->Viewer_Assign('sDateMonthToStart',$sDataToStartArr['mon']);
			$this->Viewer_Assign('sDateDayToStart',$sDataToStartArr['mday']);
			$this->Viewer_Assign('sDateHourToStart',$sDataToStartArr['hours']);
			$this->Viewer_Assign('sDateMinToStart',$sDataToStartArr['minutes']);
			$this->Viewer_Assign('sDateSecToStart',$sDataToStartArr['seconds']);			
			
			$this->SetTemplateAction('edit');
			return true;
		}
	}
	else if ($this->GetParam(0) == 'start')
	{
		$sDataStart = date("Y-m-d H:i:s");
		$sDataStop = date("Y-m-d H:i:s");
		
		$sStatus = 1;
		$sPathToRedirect = 'advert/settings/sort/'.$this->GetParam(1).'/active';
		if( $this->PluginAdvert_Advert_GetAdvertStratToStatus($this->GetParam(2)) )
		{
			$sStatus = 3;
			$sPathToRedirect = 'advert/settings/sort/'.$this->GetParam(1).'/delayedstart';			
		}	

		if(Config::Get('plugin.advert.moderation') && !$this->oUserCurrent->isAdministrator() && $this->oUserCurrent->getLogin() != $sDefUser && !in_array($this->oUserCurrent->getLogin(), $aAllowUsers))
		{
			$sStatus = 2;
			$sPathToRedirect = 'advert/settings/sort/'.$this->GetParam(1).'/moderation';
			
			$sAdvertPath = Router::GetPath('advert/settings/sort/'.$this->GetParam(1).'/all').'#'.$this->GetParam(2); 
			
				
			if($this->Talk_GetTalkById($iTalkId = $this->PluginAdvert_Advert_GetTalkId($this->GetParam(2))))
			{
				$sTalkBody = 'Повторая модерация <a href="'.$sAdvertPath.'">сообщения</a>.';
				
				$oCommentNew=Engine::GetEntity('Comment');
				$oCommentNew->setTargetId($iTalkId);
				$oCommentNew->setTargetType('talk');
				$oCommentNew->setUserId($this->oUserCurrent->getId());
				$oCommentNew->setText($sTalkBody);
				$oCommentNew->setDate(date("Y-m-d H:i:s"));
				$oCommentNew->setUserIp(func_getIp());
				$oCommentNew->setPid(null);
				$oCommentNew->setTextHash(md5($sTalkBody));
				$oCommentNew->setPublish(1);	

				$this->Comment_AddComment($oCommentNew);	
				$aUsersTalk=$this->Talk_GetUsersTalk($iTalkId, ModuleTalk::TALK_USER_ACTIVE);
				$oTalk=$this->Talk_GetTalkById($iTalkId);
				
				foreach ($aUsersTalk as $oUserTalk) 
				{
					if ($oUserTalk->getId()!=$oCommentNew->getUserId()) 
					{
						$this->Notify_SendTalkCommentNew($oUserTalk,$this->oUserCurrent,$oTalk,$oCommentNew);
					}
				}
				$this->Talk_increaseCountCommentNew($oTalk->getId(),$oCommentNew->getUserId());		
			}
			else
			{ 
				$sTalkSubj = 'Модерация рекламного сообщения [id = '.$this->GetParam(2).']';
				$sTalkBody = 'На модерацию поступило новое <a href="'.$sAdvertPath.'">сообщение</a>.';
				$oTalk = $this->Talk_SendTalk(
							$sTalkSubj,
							$sTalkBody,
							$this->oUserCurrent->getId(),
							array($this->User_GetUserByLogin($sDefUser)->getId()),
							true,false
							);	
				$this->PluginAdvert_Advert_SetTalkId($oTalk->getId(), $this->GetParam(2));				
			}
		}
		$oAdvert = $this->PluginAdvert_Advert_SetStart($this->GetParam(1), $this->GetParam(2), $sStatus);
		Router::Location(Router::GetPath($sPathToRedirect).'#'.$this->GetParam(2));
	}
	else if ($this->GetParam(0) == 'stop')
	{
		$oAdvert = $this->PluginAdvert_Advert_SetStop($this->GetParam(2), $this->GetParam(1));
		Router::Location(Router::GetPath('advert/settings/sort/'.$this->GetParam(1).'/notactive').'#'.$this->GetParam(2));
	}	
	else if ($this->GetParam(0) == 'del')
	{
		$oAdvert = $this->PluginAdvert_Advert_SetDel($this->GetParam(2), $this->GetParam(1));
		Router::Location(Router::GetPath('advert/settings/sort/'.$this->GetParam(1).'/all'));
	}		
	else if ($this->GetParam(0) == 'sort')
	{	
		$sMenuActionName = $this->GetParam(1);

		if($this->GetParam(2))
		{
			$sMenuActionName = $this->GetParam(2);
		}
		if($sMenuActionName == 'active')
		{
			$this->iShowByAdvertType = 1;
			$this->sMenuArvertItemSelect = 'active';
		}
		else if($sMenuActionName == 'notactive') 
		{
			$this->iShowByAdvertType = 0;
			$this->sMenuArvertItemSelect = 'notactive';
		}
		else if($sMenuActionName == 'all')
		{
			$this->iShowByAdvertType = null;
			$this->sMenuArvertItemSelect = 'all';
		}
		else if($sMenuActionName == 'moderation')
		{
			$this->iShowByAdvertType = 2;
			$this->sMenuArvertItemSelect = 'moderation';	
		}	
		else if($sMenuActionName == 'delayedstart')
		{
			$this->iShowByAdvertType = 3;
			$this->sMenuArvertItemSelect = 'delayedstart';	
		}		
	}
	else if ($this->GetParam(0) == 'preview')
	{
		$oAdvert = $this->PluginAdvert_Advert_GetDataById($this->GetParam(2));
		$this->Viewer_Assign('oAdvert',$oAdvert);		
		$this->SetTemplateAction('preview');
	}
	
	if($this->oUserCurrent->isAdministrator() || $this->oUserCurrent->getLogin() == $sDefUser)
	{
		$this->sShowFieldsForAdmin = true;
		$this->sUserCurrent = $this->GetParam(2) ? $this->GetParam(1) : null ;
	}
	else
	{
		$this->sUserCurrent = $this->oUserCurrent->getLogin();
	}	
	$this->Viewer_Assign('aBlockTypesAdmin', $this->__advertGetConfigData('admin', false, 'name'));
	$this->Viewer_Assign('aBlockTypesUser', $this->__advertGetConfigData('user', false, 'name'));
	$this->Viewer_Assign('aBlockStyleAdmin', $this->__advertGetConfigData('admin', false, 'css'));
	$this->Viewer_Assign('aBlockStyleUser', $this->__advertGetConfigData('user', false, 'css'));
	
	$this->Viewer_Assign('sDefUser', $sDefUser);
	$this->Viewer_Assign('sRateAccess', $sRateAccess);
	$this->Viewer_Assign('aAllowUsers', $aAllowUsers);
	$this->Viewer_Assign('aDisableUsers', $aDisableUsers);
	
	if($this->sUserCurrent)
	{
		$this->Viewer_Assign('sRateUser', $this->User_GetUserByLogin($this->sUserCurrent)->getRating());
	}
	
	if(Config::Get('plugin.advert.moderation'))
	{
		$this->Viewer_Assign('sModeration', $this->Lang_Get('plugin.advert.moderationOn'));
	}
	else
	{
		$this->Viewer_Assign('sModeration', $this->Lang_Get('plugin.advert.moderationOff'));
	}
	
	$this->Viewer_Assign('sShowFieldsForAdmin', $this->sShowFieldsForAdmin);
	

	
	$this->Viewer_Assign('sUserCurrent',$this->sUserCurrent);
	
	//$this->Viewer_Assign('sUserCurrent',$this->oUserCurrent->getLogin());
	$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);	
	$this->Viewer_Assign('sMenuArvertItemSelect',$this->sMenuArvertItemSelect);
	
	if (isPost('submit_filter'))
	{
		$this->aFilter['advert_id'] = $this->__advertCheckData(getRequest('advert_filter_block_id'));	
		$this->aFilter['advert_block_place'] = getRequest('advert_filter_blockplace');
		$this->aFilter['user_owner_login'] = $this->__advertCheckData(getRequest('advert_filter_user'));
		$this->sMenuArvertItemSelect = getRequest('sArvertItemSelect');
		if($this->sMenuArvertItemSelect == 'all') { $this->iShowByAdvertType = null;}
		if($this->sMenuArvertItemSelect == 'active') { $this->iShowByAdvertType = 1;}
		if($this->sMenuArvertItemSelect == 'notactive') { $this->iShowByAdvertType = 0;}
		if($this->sMenuArvertItemSelect == 'moderation') { $this->iShowByAdvertType = 2;}
		
		$this->Viewer_Assign('sMenuArvertItemSelect',$this->sMenuArvertItemSelect);
		
		//Router::Location(Router::GetPath('advert'));
	}	
	
	
	$oAdverts = $this->PluginAdvert_Advert_GetDataAll($this->sUserCurrent, $this->iShowByAdvertType, $this->aFilter);
	$aaRules = $this->setFormRules($oAdverts);
	$this->Viewer_Assign('aaRules',$aaRules);
	$this->Viewer_Assign('oAdverts',$oAdverts);
    }
	//
	protected function __advertCheckData($sData)
	{
		$aData = explode(",", $sData);
		foreach($aData as $key => $val)
		{
			if (empty($val))
			{
				unset($aData[$key]);
			}
		}
		$sData = implode(",", $aData);
		return $sData;
	}
	//
    protected function SubmitEditData() {
	
	$oAdvert=Engine::GetEntity('PluginAdvert_Advert');
	$oAdvert->setAdvertId(getRequest('advert_id'));
	$oAdvert->setAdvertUserOwnerLogin(getRequest('user_owner_login'));
	$oAdvert->setAdvertHint(htmlspecialchars(getRequest('advert_hint'))); 
	$oAdvert->setAdvertDataText(htmlspecialchars(getRequest('advert_data_text')));   
	$oAdvert->setAdvertDataUrl(htmlspecialchars(getRequest('advert_data_url')));  
	//$oAdvert->setAdvertDataImg(htmlspecialchars(getRequest('advert_data_img'))); 
	$sFilesName = $this->processUploadFiles();
	if($sFilesName)
	{
		$oAdvert->setAdvertDataImg($sFilesName);
	}
	else
	{
		$oAdvert->setAdvertDataImg(htmlspecialchars(getRequest('advert_data_img')));
	}	
	//$oAdvert->setAdvertDateStop(getRequest('advert_date_stop'));
	$oAdvert->setAdvertDateToStop(getRequest('advert_date_tostop_year').'-'.getRequest('advert_date_tostop_month').'-'.getRequest('advert_date_tostop_day').' '.getRequest('advert_date_tostop_hour').':'.getRequest('advert_date_tostop_min').':'.getRequest('advert_date_tostop_sec'));
	$oAdvert->setAdvertDateToStart(getRequest('advert_date_tostart_year').'-'.getRequest('advert_date_tostart_month').'-'.getRequest('advert_date_tostart_day').' '.getRequest('advert_date_tostart_hour').':'.getRequest('advert_date_tostart_min').':'.getRequest('advert_date_tostart_sec'));
	$oAdvert->setAdvertDateToStartFlag(getRequest('advert_date_tostart_flag')); 
	$oAdvert->setAdvertDateEdit(date("Y-m-d H:i:s")); 
	//$oAdvert->setAdvertDateStart('');
	$oAdvert->setAdvertDateStop(date("Y-m-d H:i:s"));
	$oAdvert->setAdvertStatus(0);
	$oAdvert->setAdvertTalkId(getRequest('advert_talk_id'));
	$oAdvert->setAdvertView(getRequest('advert_view'));
	$oAdvert->setAdvertClick(getRequest('advert_click'));
	//
	$oAdvert->setAdvertBlockType(getRequest('advert_block_type'));
	$oAdvert->setAdvertBlockPlace(getRequest('advert_block_place'));
	$oAdvert->setAdvertBlockRewrite(getRequest('advert_block_rewrite') || 0);
	$oAdvert->setAdvertBlockPriority(getRequest('advert_block_priority'));
	$oAdvert->setAdvertBlockCss(getRequest('advert_block_css'));
		
	//rules begin
	$oAdvert->setAdvertRules($this->getFormRules());
	//rules end
		
	return $oAdvert;
    }
    protected function SubmitNewData() {
	$oAdvert=Engine::GetEntity('PluginAdvert_Advert');
	$oAdvert->setAdvertUserOwnerId($this->User_GetUserByLogin(getRequest('user_owner_login'))->getId());
	$oAdvert->setAdvertUserOwnerLogin(getRequest('user_owner_login'));
	$oAdvert->setAdvertHint(htmlspecialchars(getRequest('advert_hint'))); 
	$oAdvert->setAdvertDataText(htmlspecialchars(getRequest('advert_data_text')));   
	$oAdvert->setAdvertDataUrl(htmlspecialchars(getRequest('advert_data_url')));  
	
	$sFilesName = $this->processUploadFiles();
	if($sFilesName)
	{
		$oAdvert->setAdvertDataImg($sFilesName);
	}
	else
	{
		$oAdvert->setAdvertDataImg(htmlspecialchars(getRequest('advert_data_img')));
	}
	
	$oAdvert->setAdvertDateToStop(getRequest('advert_date_tostop_year').'-'.getRequest('advert_date_tostop_month').'-'.getRequest('advert_date_tostop_day').' '.getRequest('advert_date_tostop_hour').':'.getRequest('advert_date_tostop_min').':'.getRequest('advert_date_tostop_sec'));
	$oAdvert->setAdvertDateToStart(getRequest('advert_date_tostart_year').'-'.getRequest('advert_date_tostart_month').'-'.getRequest('advert_date_tostart_day').' '.getRequest('advert_date_tostart_hour').':'.getRequest('advert_date_tostart_min').':'.getRequest('advert_date_tostart_sec'));
	$oAdvert->setAdvertDateToStartFlag(getRequest('advert_date_tostart_flag')); 
	$oAdvert->setAdvertDateAdd(date("Y-m-d H:i:s"));
	$oAdvert->setAdvertDateEdit(''); 
	$oAdvert->setAdvertDateStart('');
	$oAdvert->setAdvertDateStop('');
	$oAdvert->setAdvertStatus(0);
	$oAdvert->setAdvertTalkId(null);
	$oAdvert->setAdvertView(getRequest('advert_view'));
	$oAdvert->setAdvertClick(getRequest('advert_click'));
	//
	$oAdvert->setAdvertBlockType(getRequest('advert_block_type'));
	$oAdvert->setAdvertBlockPlace(getRequest('advert_block_place'));
	$oAdvert->setAdvertBlockRewrite(getRequest('advert_block_rewrite') || 0);
	$oAdvert->setAdvertBlockPriority(getRequest('advert_block_priority'));	
	$oAdvert->setAdvertBlockCss(getRequest('advert_block_css'));

	//rules begin
	$oAdvert->setAdvertRules($this->getFormRules());
	//rules end
	
	return $oAdvert;
	
    }
	// Обработка загруженного файла
	protected function processUploadFiles()
	{
		if(is_uploaded_file($_FILES["advert_userfile"]["tmp_name"]))
		{
			$sData = date("Y-m-d H:i:s");
			$sFileExt = 'png';
			$pattern = '/^.+[.](\w+)$/';
			if(preg_match($pattern, $_FILES["advert_userfile"]["name"], $matches))
			{
				$sFileExt = $matches[1];
			}	
			
			$sPathToAdvertFile = Config::Get('plugin.advert.path_to_files').'/'.getRequest('user_owner_login').'/';
			if(substr(Config::Get('plugin.advert.path_to_files'),-1) == '/')
			{
				$sPathToAdvertFile = Config::Get('plugin.advert.path_to_files').getRequest('user_owner_login').'/';	
			}
			
			$sFilesName = md5($_FILES["advert_userfile"]["name"].$sData).'.'.$sFileExt;
			
			if(!is_dir($sPathToAdvertFile))
			{
				mkdir($sPathToAdvertFile);
			}

			//
			if(move_uploaded_file($_FILES["advert_userfile"]["tmp_name"], $sPathToAdvertFile.$sFilesName))
			{
			//				
				if(getRequest('img_width') && getRequest('img_height') )
				{
					$img_dist = imagecreatetruecolor(getRequest('img_width'), getRequest('img_height'));
					
					$img_src = null;
					if($sFileExt == 'jpg' || $sFileExt == 'jpeg')
					{
						$img_src = imagecreatefromjpeg($sPathToAdvertFile.$sFilesName);
					}
					elseif($sFileExt == 'png')	
					{
						$img_src = imagecreatefrompng($sPathToAdvertFile.$sFilesName);
					}
					elseif($sFileExt == 'gif')
					{
						$img_src = imagecreatefromgif($sPathToAdvertFile.$sFilesName);
					}
					else
					{
						$img_src = imagecreatefrompng($sPathToAdvertFile.$sFilesName);
					}
					//
					if(getRequest('img_width_new') && getRequest('img_height_new'))
					{
						$img_src_resize = imagecreatetruecolor(getRequest('img_width_new'), getRequest('img_height_new'));
						imagecopyresampled($img_src_resize, $img_src, 0,0,0,0, getRequest('img_width_new'), getRequest('img_height_new'), getRequest('img_width_org'), getRequest('img_height_org') );
						imagecopyresampled($img_dist, $img_src_resize, 0,0, getRequest('img_x1'), getRequest('img_y1'), getRequest('img_width'), getRequest('img_height'), getRequest('img_width'), getRequest('img_height') );
					}
					else
					{
						imagecopyresampled($img_dist, $img_src, 0,0, getRequest('img_x1'), getRequest('img_y1'), getRequest('img_width'), getRequest('img_height'), getRequest('img_width'), getRequest('img_height') );
					}
					if($sFileExt == 'jpg' || $sFileExt == 'jpeg')
					{
						imagejpeg($img_dist, $sPathToAdvertFile.$sFilesName, 100);
					}
					elseif($sFileExt == 'png')	
					{
						imagepng($img_dist, $sPathToAdvertFile.$sFilesName, 100);
					}
					elseif($sFileExt == 'gif')
					{
						imagegif($img_dist, $sPathToAdvertFile.$sFilesName, 100);
					}				
					else
					{
						imagepng($img_dist, $sPathToAdvertFile.$sFilesName, 100);
					}
					imagedestroy($img_dist);
				}			
			// img crop end
				if(substr(Config::Get('path.root.web'),-1) == '/')
				{
					return Config::Get('path.root.web').$sPathToAdvertFile.$sFilesName;
				}
				else
				{
					return Config::Get('path.root.web').'/'.$sPathToAdvertFile.$sFilesName;
				}
			}
		}
		return false;
	}
	//
   	protected function getFormRules() 
   	{
		$aRules = array();
		$aActions = array();
		$aActionsConf = array();
		$sUser = getRequest('user_owner_login');
		$flag = true;
		
		if($this->User_GetUserByLogin($sUser)->isAdministrator() || $sUser == Config::Get('plugin.advert.defuser'))
		{
			$aActionsConf = Config::Get('plugin.advert.block_actions_admin');
		}
		else
		{
			$flag = false;
			$aActionsConf = Config::Get('plugin.advert.block_actions_user');
		}
		foreach($aActionsConf as $value)
		{
			$aActions[$value] = getRequest('advert_action_'.$value);
		}
		
		if($flag)
		{
			$aRules['actions'] = $aActions;
			$aRules['blogs'] = getRequest('advert_blogs');
			$aRules['users'] = getRequest('advert_users');
			$aRules['topics'] = getRequest('advert_topics');
		}
		else
		{
			$aRules['actions'] = $aActions;
			$aRules['topics'] = getRequest('advert_topics');		
		}
		return json_encode($aRules);	
   	}
	//
   	protected function setFormRules($oAdverts)
   	{
   		$aaRules = array();
   		if(isset($oAdverts))
   		{
	   		foreach($oAdverts as $oAdvert)
	   		{
				$aRules = json_decode($oAdvert->getAdvertRules(), true);
				$aaRules[$oAdvert->getAdvertId()] = $aRules;
	   		}
   		}
   		return $aaRules; 		
   	}
	//
	protected function __advertGetConfigData($_sOwner, $_sType, $_sOption)
	{
	
		$aResult = array();
		
		$aConfig = Config::Get('plugin.advert.block_types');
		
		foreach($aConfig as $sOwner => $aTypes)
		{
			if($sOwner == $_sOwner)
			{
				foreach($aTypes as $aType => $aOptions)
				{
					if(!$_sType && $_sOption)
					{
						$aResult[$aType] = $aOptions[$_sOption];
					}
					else
					{
						if($aType == $_sType)
						{
							$aResult = $aOptions[$_sOption];
						}
					}
				}
			}
		}
		//var_dump($aResult);
		return $aResult;
	}
}
?>
