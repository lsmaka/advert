<?php

class PluginAdvert_ModuleAdvert extends Module {

	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	// ѕолучени€ статуса отложенного старта
	public function GetAdvertStratToStatus($iAdvertId) {
		$data = $this->oMapper->GetAdvertStratToStatus($iAdvertId);
		return $data;
	}

	public function GetTalkId($iAdvertId) {
		$data = $this->oMapper->GetTalkId($iAdvertId);
		return $data;
	}	
	
	public function SetTalkId($iTalkId, $iAdvertId) {
	if ($this->oMapper->SetTalkId($iTalkId, $iAdvertId)) {
	
		return true;
	}
	return false;
	}	
//	
	public function AdvertClick($iAdvertId) {
	if ($this->oMapper->AdvertClick($iAdvertId)) {
	
		return true;
	}
	return false;
	}	
	public function AdvertView($iAdvertId) {
	if ($this->oMapper->AdvertView($iAdvertId)) {
	
		return true;
	}
	return false;
	}		
	public function GetDataAll($sUser, $iSort, $aFilter)
	{
		$data = $this->oMapper->GetDataAll($sUser, $iSort, $aFilter);
		return $data;
	}
	public function GetDataById($id)
	{
		$data = $this->oMapper->GetDataById($id);
		return $data;
	}
//
	public function GetBlockData($sUserLogin)
	{
		$cache_key = 'advert_'.$sUserLogin;
		if (false === ($data = $this->Cache_Get( $cache_key ))) 
		{
			$data = $this->oMapper->GetBlockData($sUserLogin);
			$this->Cache_Set( $data, $cache_key );
		}
		//		
		//$data = $this->oMapper->GetBlockData($sUserLogin);
		return $data;
	}
//
	public function SubmitEditData(PluginAdvert_ModuleAdvert_EntityAdvert $oAdvert) {
	if ($this->oMapper->SubmitEditData($oAdvert)) {
		$cache_key = 'advert_'.$oAdvert->getAdvertUserOwnerLogin();
		$this->Cache_Delete( $cache_key );	
		return true;
	}
	return false;
	}	
	public function SubmitNewData(PluginAdvert_ModuleAdvert_EntityAdvert $oAdvert) {
	if ($this->oMapper->SubmitNewData($oAdvert)) {
	
		return true;
	}
	return false;
	}
	public function SetStart($sUserLogin, $iAdvertId, $sStatus) {
	$sDataStart = date("Y-m-d H:i:s");
	if ($this->oMapper->SetStart($iAdvertId, $sDataStart, $sStatus)) {
		$cache_key = 'advert_'.$sUserLogin;
		$this->Cache_Delete( $cache_key );		
		return true;
	}
	return false;
	}
	//public function UnSetStart($sUserLogin, $sDataStop) {
	//if ($this->oMapper->UnSetStart($sUserLogin, $sDataStop)) {
	//	$cache_key = 'advert_'.$sUserLogin;
	//	$this->Cache_Delete( $cache_key );	
	//	return true;
	//}	
	//return false;
	//}
	public function SetStop($id, $sUserLogin) 
	{
		$sDataStop = date("Y-m-d H:i:s");
		if ($this->oMapper->SetStop($id, $sDataStop))
		{
			$cache_key = 'advert_'.$sUserLogin;
			$this->Cache_Delete( $cache_key );
			return true;
		}
		return false;
	}
	public function SetDel($id, $sUserLogin) {
	if ($this->oMapper->SetDel($id)) {
		$cache_key = 'advert_'.$sUserLogin;
		$this->Cache_Delete( $cache_key );	
		return true;
	}
	return false;
	}
	// avtocomplite
	public function GetBlogsByBlogNameLike($sBName, $iLimit)
	{
		$data = $this->oMapper->GetBlogsByBlogNameLike($sBName, $iLimit);
		return $data;
	}
	public function GetTopicsByTopicIdLike($sTId, $iLimit, $sUser)
	{
		$data = $this->oMapper->GetTopicsByTopicIdLike($sTId, $iLimit, $sUser);
		return $data;
	}	
	public function FilterGetAdvertId($sFilterAdvertId, $iLimit, $sUser)
	{
		$data = $this->oMapper->FilterGetAdvertId($sFilterAdvertId, $iLimit, $sUser);
		return $data;
	}
	public function FilterGetAdvertUser($sFilterAdvertUser, $iLimit)
	{
		$data = $this->oMapper->FilterGetAdvertUser($sFilterAdvertUser, $iLimit);
		return $data;
	}	
}
?>
