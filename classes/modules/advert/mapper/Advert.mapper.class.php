<?php

class PluginAdvert_ModuleAdvert_MapperAdvert extends Mapper
{
	public function GetBlockData($sUserLogin) {
		$sql = "SELECT * FROM ".Config::Get('plugin.advert.table.page')." WHERE advert_status IN (1,3) AND user_owner_login = ? ";
		if ($aRows=$this->oDb->select($sql,$sUserLogin)) 
		{
			foreach ($aRows as $aPage) 
			{
				$aPages[] = Engine::GetEntity('PluginAdvert_Advert', $aPage);
			}
			return $aPages;
		}
		return false;		
	}	
		
	public function GetDataAll($sUser, $iSort, $aFilter) {
		$sWhere = '';
		if($sUser && isset($iSort))
		{
			$sWhere = " AND t1.user_owner_login = '$sUser' AND t1.advert_status = $iSort ";
		}
		else if($sUser && !isset($iSort))
		{
			$sWhere = " AND  t1.user_owner_login = '$sUser' ";
		}
		else if(!$sUser && isset($iSort))
		{
			$sWhere = " AND t1.advert_status = $iSort ";
		}
		//
		foreach($aFilter as $key => $val)
		{
			if($aFilter[$key])
			{
				if($key == 'advert_block_place')
				{
					$sWhere .= " AND t1.$key = '$val' ";
				}
				else if($key == 'user_owner_login')
				{
					$sWhere .= " AND t1.$key = '$val' ";
				}
				else
				{
					$sWhere .= " AND t1.$key IN ($val) ";
				}	
			}
		}
		//
		$sql = "SELECT t1.*, t2.user_rating FROM ".Config::Get('plugin.advert.table.page')." t1, ".Config::Get('db.table.prefix')."user t2 WHERE t1.user_owner_login = t2.user_login $sWhere ORDER BY t1.advert_id DESC";
		$aPages = array();
		if ($aRows = $this->oDb->select($sql)) {
			foreach ($aRows as $aPage) {
				$aPages[] = Engine::GetEntity('PluginAdvert_Advert', $aPage);
			}
			return $aPages;
		}
		return null;
	}

	public function GetDataById($id) {
		$sql = "SELECT * FROM ".Config::Get('plugin.advert.table.page')." WHERE advert_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$id)) {
			return Engine::GetEntity('PluginAdvert_Advert',$aRow);
		}
		return null;		
	}

	public function SubmitNewData(PluginAdvert_ModuleAdvert_EntityAdvert $oAdvert) {
		$sql = "INSERT INTO ".Config::Get('plugin.advert.table.page')." 
			(advert_hint,
			advert_data_text,
			advert_data_url,
			advert_data_img,
			advert_rules,
			advert_block_type,
			advert_block_place,
			advert_block_rewrite,
			advert_block_priority,
			advert_block_css,
			advert_view,
			advert_click,
			advert_date_stop,
			advert_date_tostop,
			advert_date_tostart,
			advert_date_tostart_flag,
			advert_date_edit,
			advert_date_start,
			advert_date_add,
			advert_status,
			advert_talk_id,
			user_owner_id,
			user_owner_login
			)
			VALUES(?, ?, ?,	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
		if ($this->oDb->query($sql,$oAdvert->getAdvertHint(),$oAdvert->getAdvertDataText(),$oAdvert->getAdvertDataUrl(),$oAdvert->getAdvertDataImg(),$oAdvert->getAdvertRules(),$oAdvert->getAdvertBlockType(),$oAdvert->getAdvertBlockPlace(),$oAdvert->getAdvertBlockRewrite(),$oAdvert->getAdvertBlockPriority(),$oAdvert->getAdvertBlockCss(),$oAdvert->getAdvertView(),$oAdvert->getAdvertClick(),$oAdvert->getAdvertDateStop(),$oAdvert->getAdvertDateToStop(),$oAdvert->getAdvertDateToStart(),$oAdvert->getAdvertDateToStartFlag(),$oAdvert->getAdvertDateEdit(), $oAdvert->getAdvertDateStart(), $oAdvert->getAdvertDateAdd(), $oAdvert->getAdvertStatus(), $oAdvert->getAdvertTalkId(), $oAdvert->getAdvertUserOwnerId(), $oAdvert->getAdvertUserOwnerLogin()))
		{
			return true;
		}
		return false;
	}

	public function SubmitEditData(PluginAdvert_ModuleAdvert_EntityAdvert $oAdvert) {
		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
			SET 
			advert_hint = ? ,
			advert_data_text = ? ,
			advert_data_url = ? ,
			advert_data_img = ? ,
			advert_rules = ?,
			advert_block_type = ?,
			advert_block_place = ?,
			advert_block_rewrite = ?,
			advert_block_priority = ?,
			advert_block_css = ?,
			advert_view = ?,
			advert_click = ?,
			advert_date_stop = ?,
			advert_date_tostop = ?,
			advert_date_tostart = ?,
			advert_date_tostart_flag = ?,
			advert_date_edit = ?,
			advert_status = ?,
			advert_talk_id = ?	
			WHERE advert_id = ?d
		";
		if ($this->oDb->query($sql,$oAdvert->getAdvertHint(),$oAdvert->getAdvertDataText(),$oAdvert->getAdvertDataUrl(),$oAdvert->getAdvertDataImg(),$oAdvert->getAdvertRules(),$oAdvert->getAdvertBlockType(),$oAdvert->getAdvertBlockPlace(),$oAdvert->getAdvertBlockRewrite(),$oAdvert->getAdvertBlockPriority(),$oAdvert->getAdvertBlockCss(),$oAdvert->getAdvertView(),$oAdvert->getAdvertClick(),$oAdvert->getAdvertDateStop(),$oAdvert->getAdvertDateToStop(),$oAdvert->getAdvertDateToStart(),$oAdvert->getAdvertDateToStartFlag(),$oAdvert->getAdvertDateEdit(), $oAdvert->getAdvertStatus(), $oAdvert->getAdvertTalkId(), $oAdvert->getAdvertId()))
		{
			return true;
		}
		return false;
	}

	public function SetDel($id) {
		$sql = "DELETE FROM ".Config::Get('plugin.advert.table.page')." WHERE advert_id = ? ";
		if ($this->oDb->selectRow($sql,$id)) {
			return true;
		}
		return false;
	}
	
	public function SetStart($id, $sStart, $sStatus) {
		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
			SET 
			advert_status = ?d,
			advert_date_start = ? 
			WHERE advert_id = ?d
		";
		if ($this->oDb->query($sql, $sStatus, $sStart, $id))
		{
			return true;
		}
		return false;
	}
//	public function UnSetStart($sUserLogin, $sDataStop) {
//		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
//			SET 
//			advert_status = ?d, 
//			advert_date_stop = ?
//			WHERE user_owner_login = ? AND advert_status = 1
//		";
//		if ($this->oDb->query($sql,'0',$sDataStop, $sUserLogin))
//		{
//			return true;
//		}
//		return false;
//	}
	public function SetStop($id, $sDataStop) {
		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
			SET 
			advert_status = ?, 
			advert_date_stop = ? 
			WHERE advert_id = ?d
		";
		if ($this->oDb->query($sql,'0', $sDataStop, $id))
		{
			return true;
		}
		return false;
	}
	// avtocomplite
	public function GetBlogsByBlogNameLike($sBName,$iLimit) {
		$sql = "SELECT
				blog_url
			FROM
				".Config::Get('db.table.blog')."
			WHERE
				blog_url IS NOT NULL
				and
				blog_url LIKE ?
			LIMIT 0, ?d
				";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,'%'.$sBName.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_url'];
			}
		}
		return $aReturn;
	}
	public function GetTopicsByTopicIdLike($sTId, $iLimit, $sUser) {
		$where = '';
		if($sUser)
		{
			$where = " AND user_id = $sUser ";
		}
		$sql = "SELECT
				topic_id
			FROM
				".Config::Get('db.table.topic')."
			WHERE
				topic_id LIKE ? $where
			LIMIT 0, ?d
				";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,'%'.$sTId.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['topic_id'];
			}
		}
		return $aReturn;
	}
	public function AdvertClick($iAdvertId) {
		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
			SET 
			advert_click_count = advert_click_count + 1
			WHERE advert_id = ?d
		";
		if ($this->oDb->query($sql, $iAdvertId))
		{
			return true;
		}
		return false;
	}
	public function AdvertView($iAdvertId) {
		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
			SET 
			advert_view_count = advert_view_count + 1
			WHERE advert_id = ?d
		";
		if ($this->oDb->query($sql, $iAdvertId))
		{
			return true;
		}
		return false;
	}
//
	public function FilterGetAdvertId($sFilterAdvertId, $iLimit, $sUser) {
		$where = '';
		if($sUser)
		{
			$where = " AND user_owner_id = '$sUser' ";
		}
		$sql = "SELECT
				advert_id
			FROM
				".Config::Get('plugin.advert.table.page')."
			WHERE
				advert_id LIKE ? $where
			LIMIT 0, ?d
				";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,'%'.$sFilterAdvertId.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['advert_id'];
			}
		}
		if($aReturn)
		{
			return $aReturn;
		}
		return false;	
	}	
//
	public function FilterGetAdvertUser($sFilterAdvertUser, $iLimit) {
		$sql = "SELECT
				user_owner_login
			FROM
				".Config::Get('plugin.advert.table.page')."
			WHERE
				user_owner_login LIKE ?
			GROUP BY user_owner_login	
			LIMIT 0, ?d
				";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,'%'.$sFilterAdvertUser.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_owner_login'];
			}
		}
		return $aReturn;
	}
// Get & Set Talk Id
	public function GetTalkId($iAdvertId) {
		$sql = "SELECT advert_talk_id FROM ".Config::Get('plugin.advert.table.page')." WHERE advert_id = ?";
		if ($aRow=$this->oDb->selectRow($sql, $iAdvertId)) 
		{
			if($aRow['advert_talk_id'])
			{
				return $aRow['advert_talk_id'];
			}
		}	
		return false;		
	}
	public function SetTalkId($iTalkId, $iAdvertId) {
		$sql = "UPDATE ".Config::Get('plugin.advert.table.page')." 
			SET 
			advert_talk_id = ?d
			WHERE advert_id = ?d
		";
		if ($aRow=$this->oDb->selectRow($sql, $iTalkId, $iAdvertId)) {
			return true;
		}
		return false;		
	}	
// ѕолучание статуса отложенного старта
	public function GetAdvertStratToStatus($iAdvertId) {
		$sql = "SELECT advert_date_tostart_flag FROM ".Config::Get('plugin.advert.table.page')." WHERE advert_id = ?";
		if ($aRow=$this->oDb->selectRow($sql, $iAdvertId)) 
		{
			if($aRow['advert_date_tostart_flag'])
			{
				return $aRow['advert_date_tostart_flag'];
			}
		}	
		return false;		
	}	
}

?>
