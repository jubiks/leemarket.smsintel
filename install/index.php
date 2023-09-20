<?
IncludeModuleLangFile(__FILE__);

Class leemarket_smsintel extends CModule
{
    var $MODULE_ID = "leemarket.smsintel";
    var $MODULE_GROUP_RIGHTS = "Y";
    public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
    
    function leemarket_smsintel()
    {
        $arModuleVersion = array();
        
        include($this->GetModInstPath()."/version.php");
    
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        
        $this->PARTNER_NAME = GetMessage("LEEMARKET_SMSINTEL_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("LEEMARKET_SMSINTEL_PARTNER_URI");
        
        $this->MODULE_NAME = GetMessage("LEEMARKET_SMSINTEL_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("LEEMARKET_SMSINTEL_MODULE_DESCRIPTION");
    }
    
    function GetModInstPath(){
        return dirname(__FILE__);
    }
    
    function DoInstall()
    {
        global $DB, $DOCUMENT_ROOT, $APPLICATION;      
        
        RegisterModule($this->MODULE_ID);
        
        //COption::SetOptionString($this->MODULE_ID,'VK_API_VERSION','5.54');
        
        RegisterModuleDependences('messageservice', 'onGetSmsSenders', $this->MODULE_ID, 'LeemarketSmsIntelTools', 'RegisterSmscService');
        
        $APPLICATION->IncludeAdminFile(GetMessage("LEEMARKET_SMSINTEL_INSTALL_MODULE"), $this->GetModInstPath()."/step.php");
    }
    
    function DoUninstall()
    {
        global $DB, $DOCUMENT_ROOT, $APPLICATION, $step;
        
        if($step == 2){
        
            UnRegisterModuleDependences('messageservice', 'onGetSmsSenders', $this->MODULE_ID, 'LeemarketSmsIntelTools', 'RegisterSmscService');
            
            if($_REQUEST["savedata"] != 'Y')
                COption::RemoveOption($this->MODULE_ID);
            
            UnRegisterModule($this->MODULE_ID);
            
            $APPLICATION->IncludeAdminFile(GetMessage("LEEMARKET_SMSINTEL_UNINSTALL_MODULE"), $this->GetModInstPath()."/unstep2.php");
            
        }else{
			$APPLICATION->IncludeAdminFile(GetMessage("LEEMARKET_SMSINTEL_UNINSTALL_MODULE"), $this->GetModInstPath()."/unstep1.php");
		}
    }
}
?>