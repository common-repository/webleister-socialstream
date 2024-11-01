<?php
namespace wl_socialstream;
class Options_Instagram
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionname;

    public function __construct()
    {
        $this->optionname = LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_instagram';
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
            'wl_social_stream_instagram'
        );
    }
   

    /**
     * Register and add settings
     */
    public function PageInit()
    {        
        register_setting('wl_social_stream_option_group_instagram',$this->optionname,array( $this, 'Sanitize' )); 
        //Instagram
        add_settings_section(
           'setting_instagram_section_id', // ID
           'Instagram', // Title
           array( $this, 'PrintSectionInstagramInfo' ), // Callback
           'wl_social_stream_instagram' // Page
       );  

        add_settings_field(
            'instagram_access_token', // ID
            'Access Token', // Title 
            array( $this, 'InstagramAccessTokenCallback' ), // Callback
            'wl_social_stream_instagram', // Page
            'setting_instagram_section_id' // Section           
        );      

        add_settings_field(
            'instagram_user_id', 
            'User ID', 
            array( $this, 'InstagramUserIdCallback' ), 
            'wl_social_stream_instagram', 
            'setting_instagram_section_id'
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
        if( isset( $input['instagram_access_token'] ) )
            $new_input['instagram_access_token'] = sanitize_text_field( $input['instagram_access_token'] );

        if( isset( $input['instagram_user_id'] ) )
            $new_input['instagram_user_id'] = sanitize_text_field( $input['instagram_user_id'] );

           
        return $new_input;
    }   
    /** 
     * Instagram Callbacks
     */
    public function PrintSectionInstagramInfo()
    {
        print __('Instagram Intro','wl-socialstream');
    }
    public function InstagramAccessTokenCallback()
    {
        printf(
            '<input type="text" id="instagram_access_token" name="%s[instagram_access_token]" value="%s" />',$this->optionname,
            isset( $this->options['instagram_access_token'] ) ? esc_attr( $this->options['instagram_access_token']) : ''
        );
    }
    public function InstagramUserIdCallback()
    {
        printf(
            '<input type="text" id="instagram_user_id" name="%s[instagram_user_id]" value="%s" />',$this->optionname,
            isset( $this->options['instagram_user_id'] ) ? esc_attr( $this->options['instagram_user_id']) : ''
        );
    }   
    

}