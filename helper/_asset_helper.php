<?php
namespace wl_socialstream;

class AssetHelper {

    /**
     * Enqueues Javascript to header or footer. Use named array!
     * 
     * Named values:
     *     id               string          optional    Unique identifier for script. If empty file is used.
     *     version          string|bool     optional    Version. If false, WP version is used.
     *     file             string          required    Filename without extension
     *     folder           string          required    Foldername for js file
     *     inFooter         bool(true)      optional    Add file to footer (true) or header (false)
     *     dataToBePassed   array           optional    Key/Value array which is added to js
     *     dataToBePassedId string          optional    Instance Name in JS
     *     depends          array           optional    String-Array with dependencies
     *     conditional      string          optional    Conditional comment (inside brackets)
     * 
     */    
    public static function LoadJs($params = array()) {
        // check required params
        $required = array(
            'file',
            'folder'
        );
        foreach ($required as $require) {
            if (!array_key_exists($require, $params)) throw new \Exception('[' . $require . '] is required.');
        }

        // get params
        $id = (array_key_exists('id', $params)? $params['id']:'');
        $version = (array_key_exists('version', $params)? $params['version']:false);
        $file = $params['file'];
        if (empty($id)) { $id = $file; }        
        $folder = $params['folder'];
        $inFooter =(array_key_exists('inFooter', $params)? $params['inFooter']:true); 
        $dataToBePassed =(array_key_exists('dataToBePassed', $params)? $params['dataToBePassed']:null);
        $dataToBePassedId = (array_key_exists('dataToBePassedId', $params)? $params['dataToBePassedId']:$id);
        $depends = (array_key_exists('depends', $params)?$params['depends']:array());
        $conditional = (array_key_exists('conditional', $params)? $params['conditional']:null);

        wp_register_script($id, $folder.$file.'.js', $depends, $version, $inFooter);
        wp_enqueue_script($id);
        if(is_array($dataToBePassed))
        {
            wp_localize_script( $id, $dataToBePassedId, $dataToBePassed );
        }

        if (!empty($conditional)) {
            wp_script_add_data($id, 'conditional', $conditional);
        }    
    }

    /**
     * Enqueues Stylesheets. Use named array!
     * 
     * Named values:
     *     id               string          optional    Unique identifier for stylesheet. If empty file is used.
     *     version          string|bool     optional    Version. If false, WP version is used.
     *     file             string          required    Filename without extension
     *     folder           string          required    Foldername for stylesheet file
     *     conditional      string          optional    Conditional comment (inside brackets)
     */    
    public static function LoadCss($params = array()) {
        // check required params
        $required = array(
            'file',
            'folder'
        );
        foreach ($required as $require) {
            if (!array_key_exists($require, $params)) throw new \Exception('[' . $require . '] is required.');
        }


        // get params
        $id = (array_key_exists('id', $params)? $params['id']:'');
        $version = (array_key_exists('version', $params)? $params['version']:false);
        $file = $params['file'];
        $folder = $params['folder'];
        $conditional = (array_key_exists('conditional', $params)? $params['conditional']:null);

        // process
        if (empty($id)) {
            $id = $file;
        }
        wp_register_style($id, $folder.$file.'.css', null, $version);
        wp_enqueue_style($id);

        if (!empty($conditional)) {
            wp_style_add_data($id, 'conditional', $conditional);
        }
    }

    /**
     * Enqueues web fonts. Use named array!
     * 
     * Named values:
     *     id               string          required    Unique identifier for font.
     *     version          string|bool     optional    Version. If false, WP version is used.
     *     url              string          required    url to webfont (absolute or relative)
     *     conditional      string          optional    Conditional comment (inside brackets)
     */    
    public static function LoadFont($params = array()) {
        // check required params
        $required = array(
            'id',
            'url'
        );
        foreach ($required as $require) {
            if (!array_key_exists($require, $params)) throw new \Exception('[' . $require . '] is required.');
        }

        // get params
        $id = $params['id'];     
        $version = (array_key_exists('version', $params)? $params['version']:false);
        $url = $params['url'];
        $conditional = (array_key_exists('conditional', $params)? $params['conditional']:null);

        // process
        wp_register_style($id, $url, null, $version);
        wp_enqueue_style($id);

        if (!empty($conditional)) {
            wp_style_add_data($id, 'conditional', $conditional);
        }
    }    
}
?>