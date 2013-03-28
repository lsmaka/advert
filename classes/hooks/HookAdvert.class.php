<?php

class PluginAdvert_HookAdvert extends Hook {
	
	protected $iUserId = null;
	protected $iTopicId = null;
	protected $sBlogName = null;
	protected $sUserLogin = null;
	protected $sUserRate = null;
	
	//protected $aAdvertsPlaces = array();
	
	protected $aAdvertsSidebar = array();
	protected $aAdvertsHeader = null;
	protected $aAdvertsFotter = null;
	
	protected $aAdvertsTopic = array();
	
	protected $aAdvertsMaster = array();
	protected $aaAdverts = array(); // массив массивов обьектов
	protected $aaAdvertsAdmin = array();
	protected $aaAdvertsUser = array();

	
	public function RegisterHook() 
	{
		$this->AddHook('template_content_begin', 'header',__CLASS__,-200);
		$this->AddHook('template_content_end', 'fotter',__CLASS__,-200);
		$this->AddHook('template_topic_content_end', 'topic',__CLASS__,-200);
		
		$this->AddHook('template_menu_settings_settings_item', 'profile',__CLASS__,-100);
		$this->AddHook('init_action', 'EngineInitComplete',__CLASS__,-100);
		$this->AddHook('template_advert_version_info', 'Copyright');
	}
	public function topic($aParams)
	{
		$topic = $aParams['topic'];
		$bTopicList = $aParams['bTopicList'];
		
		if(!$bTopicList)
		{
			if($this->aAdvertsTopic)
			{
				$sBlockTopic = '';
				foreach($this->aAdvertsTopic as $sAdvertBlockType  => $aoAdvert)
				{
					// Сортировка массива обьектов по полю приоритета
					usort($aoAdvert,function($f1,$f2){
						if($f1->getAdvertBlockPriority() < $f2->getAdvertBlockPriority()) return -1;
						elseif($f1->getAdvertBlockPriority() > $f2->getAdvertBlockPriority()) return 1;
						else return 0;
					});
					//	
					if($this->aAdvertsMaster && isset($this->aAdvertsMaster[$sAdvertBlockType]))
					{
						unset($aoAdvert);
						$aoAdvert[] = $this->aAdvertsMaster[$sAdvertBlockType]; 
					}
					foreach($aoAdvert as $key => $oAdvert)		
					{	
						$this->PluginAdvert_Advert_AdvertView($oAdvert->getAdvertId());
						$this->Viewer_Assign('oAdvert',$oAdvert);			
						$sBlockTopic.=$this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__)."blocks/block.advert.tpl");
					}
				}
				return $sBlockTopic;
			}
		}
	}
	//
	public function header()
	{
		if($this->aAdvertsHeader)
		{
			$sBlockHeader = '';
			foreach($this->aAdvertsHeader as $sAdvertBlockType  => $aoAdvert)
			{
				// Сортировка массива обьектов по полю приоритета
				usort($aoAdvert,function($f1,$f2){
					if($f1->getAdvertBlockPriority() < $f2->getAdvertBlockPriority()) return -1;
					elseif($f1->getAdvertBlockPriority() > $f2->getAdvertBlockPriority()) return 1;
					else return 0;
				});
				foreach($aoAdvert as $key => $oAdvert)	
				{
					$this->PluginAdvert_Advert_AdvertView($oAdvert->getAdvertId());
					$this->Viewer_Assign('oAdvert',$oAdvert);
					$sBlockHeader.=$this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__)."blocks/block.advert.tpl");
				}
			}
			return $sBlockHeader;
		}
	}
//
	public function fotter()
	{
		if($this->aAdvertsFotter)
		{
			$sBlockFotter = '';
			foreach($this->aAdvertsFotter as $sAdvertBlockType  => $aoAdvert)
			{
				// Сортировка массива обьектов по полю приоритета
				usort($aoAdvert,function($f1,$f2){
					if($f1->getAdvertBlockPriority() < $f2->getAdvertBlockPriority()) return -1;
					elseif($f1->getAdvertBlockPriority() > $f2->getAdvertBlockPriority()) return 1;
					else return 0;
				});
				foreach($aoAdvert as $key => $oAdvert)	
				{
					$this->PluginAdvert_Advert_AdvertView($oAdvert->getAdvertId());
					$this->Viewer_Assign('oAdvert',$oAdvert);
					$sBlockFotter.=$this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__)."blocks/block.advert.tpl");
				}
			}
			return $sBlockFotter;
		}
	}
//	
	public function Copyright()
	{
		return 'Advert 1.0.0. <sup><font color="red">betta</font></sup>';
	}
	public function profile() 
	{
		if(Config::Get('plugin.advert.admin_mode') && $this->User_GetUserCurrent()->getUserLogin() == Config::Get('plugin.advert.defuser'))
		{
			return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject_advert_profile.tpl');
		}
		else if(!Config::Get('plugin.advert.admin_mode'))
		{
			return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject_advert_profile.tpl');
		}	
	}
	
	public function EngineInitComplete()
	{
		// Незапускать есть Action noi in Action List
		if(!in_array(Router::GetAction(), array_merge(Config::Get('plugin.advert.block_actions_user'), Config::Get('plugin.advert.block_actions_admin')) ) )
		{
			return;
		}
		
		if($this->AdvertGetBlocksData())
		{
			foreach($this->aAdvertsSidebar as $sAdvertBlockType => $aoAdvert)
			{
				// Сортировка массива обьектов по полю приоритета
				usort($aoAdvert,function($f1,$f2){
					if($f1->getAdvertBlockPriority() < $f2->getAdvertBlockPriority()) return -1;
					elseif($f1->getAdvertBlockPriority() > $f2->getAdvertBlockPriority()) return 1;
					else return 0;
				});
				//
				
				if($this->aAdvertsMaster && isset($this->aAdvertsMaster[$sAdvertBlockType]))
				{
					unset($aoAdvert);
					$aoAdvert[] = $this->aAdvertsMaster[$sAdvertBlockType]; 
				}
				foreach($aoAdvert as $key => $oAdvert)
				{
					$priority = Config::Get('plugin.advert.block_priority_'.$sAdvertBlockType);
					if(isset($priority))
					{
						// Вычисляем приоритет блока
						$priority = Config::Get('plugin.advert.block_priority_'.$sAdvertBlockType) - $key;
					}
					else
					{
						// Приоритет по умолчанию... 
						$priority = 0;
					}
					$this->PluginAdvert_Advert_AdvertView($oAdvert->getAdvertId());
					$this->Viewer_AddBlock('right', 'blocks/block.advert.tpl', array('plugin'=>'advert', 'oadvert'=>$oAdvert), $priority);	
				}		
			}
		}
	}
	//
	public function AdvertGetBlocksData()
	{
		// Загружаем все активные задания от пользователя по умолчанию
		$this->AdvertGetUserId();
		
		if($this->aaAdvertsAdmin = $this->PluginAdvert_Advert_GetBlockData(Config::Get('plugin.advert.defuser')) )
		{
			$this->aaAdverts[] = $this->aaAdvertsAdmin;
		}
		
		// Проверяем проходит ли пользователь по условиям рейтинга и заблокированных пользователей
		// 1. Если пользователь не пользователь по умолчанию в системе И
		// 2. Пользователь не заблокированн и его рейтинг больге установленного рейтинга ИЛИ
		// 3. Пользовательский рейтинг меньше установленного но пользователь есть в массиве разрешенных
		// ТО пропускаем (ищем в БД активные записи по этому пользователю)
		
		if ( $this->sUserLogin != Config::Get('plugin.advert.defuser') 
			&& ( (!in_array($this->sUserLogin, Config::Get('plugin.advert.disable_users')) 
			&& $this->sUserRate >= Config::Get('plugin.advert.access_rate')) 
			|| ($this->sUserRate < Config::Get('plugin.advert.access_rate') 
			&& in_array($this->sUserLogin, Config::Get('plugin.advert.allow_users'))) ) )
		{
			if($this->aaAdvertsUser = $this->PluginAdvert_Advert_GetBlockData($this->sUserLogin))
			{
				$this->aaAdverts[] = $this->aaAdvertsUser;
			}
		} 
		if(!$this->aaAdverts)
		{
			return false;
		}
		
		foreach($this->aaAdverts as $key => $aAdverts)
		{
			foreach($aAdverts as $key1 => $oAdvert)	
			{
				//Остановка показа по достижению условия - date & click & view
				$flagDate = $flagClick = $flagView = true;
				$flagDate = $this->__advert_check_data_deny('date', date("Y-m-d H:i:s"), $oAdvert->getAdvertDateToStop());
				$flagClick = $this->__advert_check_data_deny('click', $oAdvert->getAdvertClick(), $oAdvert->getAdvertClickCount());
				$flagView = $this->__advert_check_data_deny('view', $oAdvert->getAdvertView(), $oAdvert->getAdvertViewCount());
				//
				if(!$flagDate || !$flagClick || !$flagView)
				{
					$this->PluginAdvert_Advert_SetStop($oAdvert->getAdvertId(), $oAdvert->getAdvertUserOwnerLogin());
					continue;
				}
				if($oAdvert->getAdvertStatus() == 3)
				{
					$flagDateToStart = false;
					$flagDateToStart = $this->__advert_check_data_allow('startto', date("Y-m-d H:i:s"), $oAdvert->getAdvertDateToStart());
					if($flagDateToStart)
					{
						$sDataStart = date("Y-m-d H:i:s");
						$this->PluginAdvert_Advert_SetStart($oAdvert->getAdvertId(), $sDataStart, 1);
					}
					else
					{
						continue;
					}
				}	
				//
				$aRules = json_decode($oAdvert->getAdvertRules(), true);
				$aBlogs = isset($aRules['blogs']) ? explode(",", $aRules['blogs']) : array();
				$aTopics = isset($aRules['topics']) ? explode(",", $aRules['topics']) : array();
				$aUsers = isset($aRules['users']) ? explode(",", $aRules['users']) : array();	
				
				$aUsers=array_map('trim',$aUsers);
				$aTopics=array_map('trim',$aTopics);
				$aBlogs=array_map('trim',$aBlogs);
				
				if( ( array_key_exists(Router::GetAction(), $aRules['actions']) 
				&& $aRules['actions'][Router::GetAction()]) 
				|| ( $this->iTopicId && in_array($this->iTopicId, $aTopics) ) 
				|| ( $this->sBlogName && in_array($this->sBlogName, $aBlogs) ) 
				|| ( $this->sUserLogin && in_array($this->sUserLogin, $aUsers)) )
				{
					// Проверка перезаписи блока. 
					// Если getAdvertBlockRewrite true то показывать только административный блок
					if($oAdvert->getAdvertBlockRewrite() && $oAdvert->getAdvertUserOwnerLogin() == Config::Get('plugin.advert.defuser') )
					{
						$this->aAdvertsMaster[$oAdvert->getAdvertBlockPlace()] = $oAdvert;
					}
					
					if($oAdvert->getAdvertBlockPlace() == 'header')
					{
						$this->aAdvertsHeader[$oAdvert->getAdvertBlockPlace()][] = $oAdvert;
					}
					else if($oAdvert->getAdvertBlockPlace() == 'fotter')
					{
						$this->aAdvertsFotter[$oAdvert->getAdvertBlockPlace()][] = $oAdvert;
					}
					else if($oAdvert->getAdvertBlockPlace() == 'topic')
					{
						$this->aAdvertsTopic[$oAdvert->getAdvertBlockPlace()][] = $oAdvert;
					}
					else
					{
						$this->aAdvertsSidebar[$oAdvert->getAdvertBlockPlace()][] = $oAdvert;
					}
				}
			}
		}
		return true;
	}
	//
	public function AdvertGetUserId()
	{
		if (in_array(Router::GetAction(), Config::Get('plugin.advert.block_actions_user') ) )
		{
			$pattern = '/^(\d+)[.]html$/';
			if(Router::GetActionEvent() && preg_match($pattern, Router::GetActionEvent(), $matches))
			{
				$this->iTopicId = $matches[1];
				$this->iUserId = $this->getUserIdByTopicId($matches[1]);
			}
			else
			{
				// Для группы /blog/название_группы/ Имееют одинаковый URL 
				//if($this->User_GetUserByLogin(Router::GetActionEvent()))
				//{
				//	$this->iUserId = $this->User_GetUserByLogin(Router::GetActionEvent())->getId();
				//}
				//else
				//{
				//	$this->sBlogName = Router::GetActionEvent();
				//}	
				$this->sBlogName = Router::GetActionEvent();
				if(Router::GetParam(0) && preg_match($pattern, Router::GetParam(0), $matches))
				{
					$this->iTopicId = $matches[1];
					$this->iUserId = $this->getUserIdByTopicId($matches[1]);
				}	
				//	
			}
			if($this->iUserId)
			{
				$this->sUserLogin = $this->User_GetUserById($this->iUserId)->getLogin();
				$this->sUserRate = $this->User_GetUserById($this->iUserId)->getRating();
			}
		}
	}
	//
	public function getUserIdByTopicId($iTopicId)
	{
		if ($oTopic = $this->Topic_GetTopicById($iTopicId))
		{
			return $oTopic->getUserId();
		}    
		return false;	
	} 
	// Отложенный старт 
	public function __advert_check_data_allow($sType, $sA, $sB)
	{
		if($sType == 'startto')
		{
			if(strtotime($sA) > strtotime($sB))
			{
				return true;
			}
			return false;
		}
	}
	// Проверка остановки по дате, просмотрам и переходам
	public function __advert_check_data_deny($sType, $sA, $sB)
	{
		if($sType == 'date')
		{
			if(strtotime($sA) < strtotime($sB))
			{
				return true;
			}
			return false;
		}
		else if($sType == 'click')
		{
			if($sA > $sB || $sA == '0')
			{
				return true;
			}
			return false;			
		}
		else if($sType == 'view')
		{
			if($sA > $sB || $sA == '0')
			{
				return true;
			}
			return false;			
		}
	}

}
?>