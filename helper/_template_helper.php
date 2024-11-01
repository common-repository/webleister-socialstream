<?php
namespace wl_socialstream;

class TemplateHelper {

    /**
     * Search for given Template in Plugin/Theme Path. Use named array!
     * 
     * Named values:
     *     template             string          required    Name of Template to search for.
     *     pluginname           string|bool     optional    Name of plugin only used if called from plugin default: null.
     *     templateFolder       string          optional    Folder where to search default: templates
     * 
     */    
    public static function GetTemplate($params = array()) {
        // check required params
        $required = array(
            'template'
        );
        foreach ($required as $require) {
            if (!array_key_exists($require, $params)) throw new \Exception('[' . $require . '] is required.');
        }
        // get params
        $template = $params['template'];
        $pluginname = (array_key_exists('pluginname', $params)? $params['pluginname']:null);
        $templateFolder = (array_key_exists('templateFolder', $params)? $params['templateFolder']:'templates');     

        // process
        $themeDir = PathHelper::GetFilePathCorrected(get_template_directory());
        
        $themeTemplate = '';
        $pluginTemplate = '';

        if (!empty($pluginname)) {
            $pluginDir = PathHelper::GetFilePathCorrected(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.$pluginname);
            $themeTemplate = PathHelper::PathCombine($themeDir,DIRECTORY_SEPARATOR . 'webleister'.DIRECTORY_SEPARATOR.$pluginname.DIRECTORY_SEPARATOR.$template.'.php');
            $pluginTemplate = PathHelper::PathCombine($pluginDir,$templateFolder.DIRECTORY_SEPARATOR.$template.'.php');   
        }
        else {
            $themeTemplate = PathHelper::PathCombine($themeDir,DIRECTORY_SEPARATOR . $templateFolder.DIRECTORY_SEPARATOR.$template.'.php');    
        }
        if(file_exists($themeTemplate)){
            return $themeTemplate;
        }else if(file_exists($pluginTemplate)){
            return $pluginTemplate;
        }else{
            return '';
        }		
    }
    /**
     * Replace all replacer ##sample## with values
     * @param string $content String to search for
     * @param array $replacers Named array with key:name of replacer, value:value to be inserted 
     * @return string Replaced content
     */    
    public static function ProcessReplacers( $content, $replacers) {
        // replace vars
        if (!empty($content) && is_array($replacers)) {
            foreach ($replacers as $key => $value) {
                $content = str_ireplace('##' . $key . '##', $value, $content);
            }
        }
        
        return $content;
	}        
}
?>