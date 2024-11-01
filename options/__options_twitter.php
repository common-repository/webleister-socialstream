<?php
namespace wl_socialstream;
class Options_Twitter
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionname;

    public function __construct()
    {
        $this->optionname = LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_twitter';
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
            'wl_social_stream_twitter'
        );
    }
   

    /**
     * Register and add settings
     */
    public function PageInit()
    {        
        register_setting('wl_social_stream_option_group_twitter',$this->optionname,array( $this, 'Sanitize' )); 
        //Facebook
        add_settings_section(
           'setting_twitter_section_id', // ID
           'Twitter', // Title
           array( $this, 'PrintSectionTwitterInfo' ), // Callback
           'wl_social_stream_twitter' // Page
       );  
        add_settings_field(
            'twitter_api_key', // ID
            'API Key', // Title 
            array( $this, 'TwitterApiKeyCallback' ), // Callback
            'wl_social_stream_twitter', // Page
            'setting_twitter_section_id' // Section           
        ); 
        add_settings_field(
            'twitter_api_secret', // ID
            'API Secret', // Title 
            array( $this, 'TwitterApiSecretCallback' ), // Callback
            'wl_social_stream_twitter', // Page
            'setting_twitter_section_id' // Section           
        ); 
        add_settings_field(
            'twitter_access_token', // ID
            'Access Token', // Title 
            array( $this, 'TwitterAccessTokenCallback' ), // Callback
            'wl_social_stream_twitter', // Page
            'setting_twitter_section_id' // Section           
        );  
        add_settings_field(
            'twitter_access_token_secret', // ID
            'Access Token Secret', // Title 
            array( $this, 'TwitterAccessTokenSecretCallback' ), // Callback
            'wl_social_stream_twitter', // Page
            'setting_twitter_section_id' // Section           
        );  
        add_settings_field(
            'twitter_username', // ID
            'Username', // Title 
            array( $this, 'TwitterUsernameCallback' ), // Callback
            'wl_social_stream_twitter', // Page
            'setting_twitter_section_id' // Section           
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
        
        if( isset( $input['twitter_api_key'] ) )
            $new_input['twitter_api_key'] = sanitize_text_field( $input['twitter_api_key'] );

        if( isset( $input['twitter_api_secret'] ) )
            $new_input['twitter_api_secret'] = sanitize_text_field( $input['twitter_api_secret'] );

        if( isset( $input['twitter_access_token'] ) )
            $new_input['twitter_access_token'] = sanitize_text_field( $input['twitter_access_token'] );

        if( isset( $input['twitter_access_token_secret'] ) )
            $new_input['twitter_access_token_secret'] = sanitize_text_field( $input['twitter_access_token_secret'] );

        if( isset( $input['twitter_username'] ) )
            $new_input['twitter_username'] = sanitize_text_field( $input['twitter_username'] );

       
        return $new_input;
    }   
    /** 
     * Facebook Callbacks
     */
    public function PrintSectionTwitterInfo()
    {
        print __('Twitter Intro','wl-socialstream');
    }
    public function TwitterApiKeyCallback()
    {
        printf(
            '<input type="text" id="twitter_api_key" name="%s[twitter_api_key]" value="%s" />',$this->optionname,
            isset( $this->options['twitter_api_key'] ) ? esc_attr( $this->options['twitter_api_key']) : ''
        );
    }
    public function TwitterApiSecretCallback()
    {
        printf(
            '<input type="text" id="twitter_api_secret" name="%s[twitter_api_secret]" value="%s" />',$this->optionname,
            isset( $this->options['twitter_api_secret'] ) ? esc_attr( $this->options['twitter_api_secret']) : ''
        );
    }  
    public function TwitterAccessTokenCallback()
    {
        printf(
            '<input type="text" id="twitter_access_token" name="%s[twitter_access_token]" value="%s" />',$this->optionname,
            isset( $this->options['twitter_access_token'] ) ? esc_attr( $this->options['twitter_access_token']) : ''
        );
    }  
    public function TwitterAccessTokenSecretCallback()
    {
        printf(
            '<input type="text" id="twitter_access_token_secret" name="%s[twitter_access_token_secret]" value="%s" />',$this->optionname,
            isset( $this->options['twitter_access_token_secret'] ) ? esc_attr( $this->options['twitter_access_token_secret']) : ''
        );
    }  
    public function TwitterUsernameCallback()
    {
        printf(
            '<input type="text" id="twitter_username" name="%s[twitter_username]" value="%s" />',$this->optionname,
            isset( $this->options['twitter_username'] ) ? esc_attr( $this->options['twitter_username']) : ''
        );
    }  
    

}