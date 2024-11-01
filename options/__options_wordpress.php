<?php
namespace wl_socialstream;
class Options_Wordpress
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionname;

    public function __construct()
    {
        $this->optionname = LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_wordpress';
        $this->options = get_option($this->optionname);        
    }
    /**
     * Add options page
     */
    public function AddPluginPage()
    {
        // This page will be under "Settings"      
        add_submenu_page(
            'wl_social_stream_general',
            'Settings Admin', 
            'WL Social Stream', 
            'manage_options', 
            'wl_social_stream_wordpress'
        );
    }
   

    /**
     * Register and add settings
     */
    public function PageInit()
    {        
        register_setting('wl_social_stream_option_group_wordpress',$this->optionname,array( $this, 'Sanitize' )); 
        //Facebook
        add_settings_section(
           'setting_wordpress_section_id', // ID
           'Wordpress', // Title
           array( $this, 'PrintSectionWordpressInfo' ), // Callback
           'wl_social_stream_wordpress' // Page
       );  

        add_settings_field(
            'wordpress_categories', // ID
            __('Kategorien','wl-socialstream'), // Title 
            array( $this, 'WordpressCategoriesCallback' ), // Callback
            'wl_social_stream_wordpress', // Page
            'setting_wordpress_section_id' // Section           
        );
        add_settings_field(
            'wordpress_tags', // ID
            'Tags', // Title 
            array( $this, 'WordpressTagsCallback' ), // Callback
            'wl_social_stream_wordpress', // Page
            'setting_wordpress_section_id' // Section           
        );
                               
      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function Sanitize( $input )
    {
        $new_input = array();      
        
        if( isset( $input['wordpress_categories'] ) )
            $new_input['wordpress_categories'] = sanitize_text_field( $input['wordpress_categories'] );

        if( isset( $input['wordpress_tags'] ) )
            $new_input['wordpress_tags'] = sanitize_text_field( $input['wordpress_tags'] );
        
        return $new_input;
    }   
    /** 
     * Facebook Callbacks
     */
    public function PrintSectionWordpressInfo()
    {
        print __('Wordpress Intro','wl-socialstream');
    }
    public function WordpressCategoriesCallback()
    {
        printf(
            '<input type="text" id="wordpress_categories" name="%s[wordpress_categories]" value="%s" />',$this->optionname,
            isset( $this->options['wordpress_categories'] ) ? esc_attr( $this->options['wordpress_categories']) : ''
        );
    }
    public function WordpressTagsCallback()
    {
        printf(
            '<input type="text" id="wordpress_tags" name="%s[wordpress_tags]" value="%s" />',$this->optionname,
            isset( $this->options['wordpress_tags'] ) ? esc_attr( $this->options['wordpress_tags']) : ''
        );
    }      

}