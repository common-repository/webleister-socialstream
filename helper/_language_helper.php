<?php
namespace wl_socialstream;

class LanguageHelper {
    /**
     * Get Current Language Code from Wordpress
     */     
    public static function GetLanguageCode($lang = null) {
        if (empty($lang)) {
            global $sitepress; 
            if ($sitepress != null) {
                $lang = $sitepress->get_current_language();
            }
            else {
                $lang = get_locale();
            }
        }
        return self::GetShortLanguageCode($lang);
    }
    /**
     * Get only Language without Country from culture
     */  
    public static function GetShortLanguageCode($language) {
        if (!empty($language)) {
            $language = substr($language,0,2);
        }
        return $language;
    }
}
?>