<?
Class LeemarketSmsIntelTools {
    function RegisterSmscService() {
        if(!\CModule::IncludeModule("leemarket.smsintel")) return [];
        
        return [
            new \Leemarket\Smsintel\Sms(),
        ];
    }
}