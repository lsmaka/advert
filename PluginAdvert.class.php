<?php

if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginAdvert extends Plugin {

    public $aDelegates = array();

    protected $aInherits=array();

    // Активация плагина
    public function Activate() {
        
        $this->ExportSQL(dirname(__FILE__).'/install.sql');
        return true;
    }

    // Деактивация плагина
    public function Deactivate(){
 
        //$this->ExportSQL(dirname(__FILE__).'/deinstall.sql'); 

        return true;
    }


    // Инициализация плагина
    public function Init() {
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__)."css/style_system.css");
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__)."css/style_user.css");
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__)."js/script.js");
    }
}
?>
