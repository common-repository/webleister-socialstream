<?php
namespace wl_socialstream;

class LanguageHook {
    
	/**
     * Load Language Files
     */
	public static function LoadTextDomain() {
        $path   = dirname(PLUGIN_BASENAME) .'/languages/';
        $loaded = load_plugin_textdomain(PLUGIN_NAME, false, $path );  
        if ( ! $loaded )
        {
            error_log("Localization: File not found: $path");                      
        }
	}  
    /**
     * Fallback for Language File if not found
     * @param mixed $mofile 
     * @param mixed $domain 
     * @return mixed
     */
    public static function LoadFallback($mofile, $domain) {        
        if(!file_exists($mofile)){
            $mofile = dirname( $mofile ) . '/' . $domain . '.mo';
        }
        return $mofile;
    }
    
}
add_action('plugins_loaded',  array( '\wl_socialstream\LanguageHook', 'LoadTextDomain' ) );
add_filter( 'load_textdomain_mofile', array( '\wl_socialstream\LanguageHook', 'LoadFallback' ), 100, 2 );

?>