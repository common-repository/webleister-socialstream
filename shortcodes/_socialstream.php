<?php
namespace wl_socialstream;

class SocialStream {

	/**
     * Process the shortcode
     */
	public static function Timeline( $atts ) {
        $atts = shortcode_atts( array(
            'template'   => 'default',
            'infinite_scroll' => 'none',
            'max_entries' => -1
        ), $atts, 'wlsocialstream' );
        $generalSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general');

        $infiniteScroll = false;
        if(!empty($generalSettings['infinite_scroll']))
        {
            $infiniteScroll = $generalSettings['infinite_scroll'];
        }
        if($atts['infinite_scroll'] !='none')
        {
            $infiniteScroll =(bool)$atts['infinite_scroll'];

        }
        $max_entries = -1;
        if(!empty($generalSettings['max_skip']))
        {
            $max_entries=  $generalSettings['max_skip'];
        }
        if(is_numeric($atts['max_entries'])&& $atts['max_entries']>0)
        {
            $max_entries = $atts['max_entries'];
        }

        $retval = 'No Template';
        $template = TemplateHelper::GetTemplate(array('template'=>$atts['template'],'pluginname'=>PLUGIN_NAME));
        if(!empty($template))
        {

            if($infiniteScroll)
            {
                $entries =  array();
                $entries[] =  array(
                       'SocialID'=> '##socialid##',
                       'Type'=> '##type##',
                       'Title'=> '##title##',
                       'Content'=> '##content##',
                       'DateString'=>'##datestring##'
                   );
            }else{


                $types= array();

                if(!empty($generalSettings['twitter']) && $generalSettings['twitter']){
                    $types[] = 'Twitter';
                }
                if( !empty($generalSettings['facebook']) && $generalSettings['facebook']){
                    $types[] = 'Facebook';
                }
                if( !empty($generalSettings['instagram']) && $generalSettings['instagram']){
                    $types[] = 'Instagram';
                }
                if( !empty($generalSettings['wordpress']) && $generalSettings['wordpress']){
                    $types[] = 'Wordpress';
                }
                if( !empty($generalSettings['youtube']) &&$generalSettings['youtube']){
                    $types[] = 'YouTube';
                }

                if(is_numeric($max_entries))
                {
                    $entries = SocialStreamPostType::GetEntries($types,$max_entries);

                }else{
                    $entries = SocialStreamPostType::GetEntries($types);
                }
            }

            // load template content
            ob_start();
            include($template);
            $retval = ob_get_contents();
            ob_end_clean();
        }


        if($infiniteScroll)
        {
            if($max_entries < 0){
                $max_entries = 5; //Default Skip Value
            }
            $retval = '<div id="social_stream_template" style="display:none">'.$retval.'</div><div id="social_stream" data-skip="'.$max_entries.'" data-page="0"></div>';
            $jsfolder = plugins_url(dirname(PLUGIN_BASENAME)).'/assets/js/';
            $dataToBePassed = array(
              'folder'            => plugin_dir_url('') ,
              'invert_timeline' => (empty($generalSettings['use_css'])?false:true)
           );     
            wp_enqueue_script('jquery');
            AssetHelper::LoadJs(array(
                'file'=>'infinite',
                'folder'=>$jsfolder,
                'depends' => array('jquery'),
                'dataToBePassed' => $dataToBePassed,
                'dataToBePassedId' => 'wl_socialstream_infinite'
                ));
        }

        return $retval;
	}
    /**
     * Load CSS if option selected
     */
    public static function Css(){
        $generalSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general');
        if(!empty($generalSettings['use_css'])&&$generalSettings['use_css'])
        {
            $cssfolder = plugins_url(dirname(PLUGIN_BASENAME)).'/assets/css/';
            AssetHelper::LoadCss(array('file'=>'bootstrap.min','folder'=>$cssfolder));
            AssetHelper::LoadCss(array('file'=>'timeline','folder'=>$cssfolder));
        }
    }
}

add_shortcode( 'wlsocialstream', array( '\wl_socialstream\SocialStream', 'Timeline' ) );
add_action( 'wp_enqueue_scripts', array( '\wl_socialstream\SocialStream', 'Css' ) );
?>