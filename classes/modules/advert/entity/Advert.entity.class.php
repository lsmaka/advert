<?php

class PluginAdvert_ModuleAdvert_EntityAdvert extends Entity
{
	// Get
        public function getAdvertId() {
                return $this->_aData['advert_id'];
        }      
        public function getAdvertUserOwnerId() {
                return $this->_aData['user_owner_id'];
        } 
        public function getAdvertUserOwnerLogin() {
                return $this->_aData['user_owner_login'];
        }         
        public function getAdvertStatus() {
                return $this->_aData['advert_status'];
        }         
        public function getAdvertTalkId() {
                return $this->_aData['advert_talk_id'];
        }         		
        public function getAdvertHint() {
                return $this->_aData['advert_hint'];
        } 
        public function getAdvertDataText() {
                return $this->_aData['advert_data_text'];
        }                
        public function getAdvertDataUrl() {
                return $this->_aData['advert_data_url'];
        }
        public function getAdvertDataImg() {
                return $this->_aData['advert_data_img'];
        }    
        public function getAdvertRules() {
                return $this->_aData['advert_rules'];
        }  
        public function getAdvertBlockType() {
                return $this->_aData['advert_block_type'];
        } 
        public function getAdvertBlockPlace() {
                return $this->_aData['advert_block_place'];
        } 		
        public function getAdvertBlockRewrite() {
                return $this->_aData['advert_block_rewrite'];
        }      
        public function getAdvertBlockPriority() {
                return $this->_aData['advert_block_priority'];
        }    
        public function getAdvertBlockCss() {
                return $this->_aData['advert_block_css'];
        }                       
        public function getAdvertView() {
                return $this->_aData['advert_view'];
        } 
        public function getAdvertViewCount() {
                return $this->_aData['advert_view_count'];
        }        
        public function getAdvertClick() {
                return $this->_aData['advert_click'];
        }  
        public function getAdvertClickCount() {
                return $this->_aData['advert_click_count'];
        }                                 
        public function getAdvertDateAdd() {
                return $this->_aData['advert_date_add'];
        }                
        public function getAdvertDateEdit() {
                return $this->_aData['advert_date_edit'];
        } 
        public function getAdvertDateStart() {
                return $this->_aData['advert_date_start'];
        }   
        public function getAdvertDateStop() {
                return $this->_aData['advert_date_stop'];
        }   
        public function getAdvertDateToStop() {
                return $this->_aData['advert_date_tostop'];
        }
        public function getAdvertDateToStart() {
                return $this->_aData['advert_date_tostart'];
        }
        public function getAdvertDateToStartFlag() {
                return $this->_aData['advert_date_tostart_flag'];
        }		
        public function getAdvertUserRating() {
                return $this->_aData['user_rating'];
        }                                                                   
        // Set 
                
        public function setAdvertId($data) {
                $this->_aData['advert_id']=$data;
        }      
        public function setAdvertUserOwnerId($data) {
                $this->_aData['user_owner_id']=$data;
        }
        public function setAdvertUserOwnerLogin($data) {
                $this->_aData['user_owner_login']=$data;
        }          
        public function setAdvertStatus($data) {
                $this->_aData['advert_status']=$data;
        }
		public function setAdvertTalkId($data) {
                $this->_aData['advert_talk_id']=$data;
        }    	
        public function setAdvertHint($data) {
                $this->_aData['advert_hint']=$data;
        } 
        public function setAdvertDataText($data) {
                $this->_aData['advert_data_text']=$data;
        }                
        public function setAdvertDataUrl($data) {
                $this->_aData['advert_data_url']=$data;
        }
        public function setAdvertDataImg($data) {
                $this->_aData['advert_data_img']=$data;
        }  
        public function setAdvertRules($data) {
                $this->_aData['advert_rules']=$data;
        } 
        public function setAdvertBlockType($data) {
                $this->_aData['advert_block_type']=$data;
        }  
        public function setAdvertBlockPlace($data) {
                $this->_aData['advert_block_place']=$data;
        }		
        public function setAdvertBlockRewrite($data) {
                $this->_aData['advert_block_rewrite']=$data;
        }     
        public function setAdvertBlockPriority($data) {
                $this->_aData['advert_block_priority']=$data;
        }    
        public function setAdvertBlockCss($data) {
                $this->_aData['advert_block_css']=$data;
        }                             
        public function setAdvertView($data) {
                $this->_aData['advert_view']=$data;
        }  
        public function setAdvertViewCount($data) {
                $this->_aData['advert_view_count']=$data;
        }        
        public function setAdvertClick($data) {
                $this->_aData['advert_click']=$data;
        }   
        public function setAdvertClickCount($data) {
                $this->_aData['advert_click_count']=$data;
        }                              
        public function setAdvertDateAdd($data) {
                $this->_aData['advert_date_add']=$data;
        }                
        public function setAdvertDateEdit($data) {
                $this->_aData['advert_date_edit']=$data;
        } 
        public function setAdvertDateStart($data) {
                $this->_aData['advert_date_start']=$data;
        }   
        public function setAdvertDateStop($data) {
                $this->_aData['advert_date_stop']=$data;
        } 
        public function setAdvertDateToStop($data) {
                $this->_aData['advert_date_tostop']=$data;
        } 
        public function setAdvertDateToStart($data) {
                $this->_aData['advert_date_tostart']=$data;
        }   
        public function setAdvertDateToStartFlag($data) {
                $this->_aData['advert_date_tostart_flag']=$data;
        }  		
        public function setAdvertUserRating() {
                $this->_aData['user_rating']=$data;
        }                                                               
}

?>
