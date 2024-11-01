<?php
namespace wl_socialstream;
class Options_Facebook
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionname;

    public function __construct()
    {
        $this->optionname = LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_facebook';
        $this->options = get_option( $this->optionname);        
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
            'wl_social_stream_facebook'
        );
    }
   

    /**
     * Register and add settings
     */
    public function PageInit()
    {        
        register_setting('wl_social_stream_option_group_facebook', $this->optionname,array( $this, 'Sanitize' )); 
        //Facebook
        add_settings_section(
           'setting_facebook_section_id', // ID
           'Facebook', // Title
           array( $this, 'PrintSectionFacebookInfo' ), // Callback
           'wl_social_stream_facebook' // Page
       );  

        add_settings_field(
            'facebook_access_token', // ID
            'Access Token', // Title 
            array( $this, 'FacebookAccessTokenCallback' ), // Callback
            'wl_social_stream_facebook', // Page
            'setting_facebook_section_id' // Section           
        );      

        add_settings_field(
            'facebook_page_id', 
            'Page ID', 
            array( $this, 'FacebookPageIdCallback' ), 
            'wl_social_stream_facebook', 
            'setting_facebook_section_id'
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
        
        if( isset( $input['facebook_access_token'] ) )
            $new_input['facebook_access_token'] = sanitize_text_field( $input['facebook_access_token'] );

        if( isset( $input['facebook_page_id'] ) )
            $new_input['facebook_page_id'] = sanitize_text_field( $input['facebook_page_id'] );

       
        return $new_input;
    }   
    /** 
     * Facebook Callbacks
     */
    public function PrintSectionFacebookInfo()
    {
        $retval = '<br/><a href="#" class="button button-primary" id="show_fb_token">'.__('Access Token Generieren','wl-socialstream').'</a>';
        $retval .= '<div id="fb_form" style="display:none;"><p>';
        $retval .= '<label for="fb_app_id" style="width:90px;display:inline-block;">App ID </label>';
        $retval .= '<input type="text" id="fb_app_id" name="fb_app_id" /><br/>';
        $retval .= '<label for="fb_app_secret" style="width:90px;display:inline-block;">App Secret </label>';
        $retval .= '<input type="text" id="fb_app_secret" name="fb_app_secret" /><br/>';
        $retval .= '<label for="fb_page_id" style="width:90px;display:inline-block;">Page ID </label>';
        $retval .= '<input type="text" id="fb_page_id" name="fb_page_id" /></p>';
        $retval .= '<p><a href="#" class="button button-primary" id="generate_fb_token">'.__('Access Token Generieren','wl-socialstream').'</a></p>';
        $retval .= '<div id="fb_output"></div>';
        $retval .= '</div>';
        $retval = __('Facebook Intro','wl-socialstream').$retval;
        print $retval;
    }
    public function FacebookAccessTokenCallback()
    {
        printf(
            '<input type="text" id="facebook_access_token" name="%s[facebook_access_token]" value="%s" />', $this->optionname,
            isset( $this->options['facebook_access_token'] ) ? esc_attr( $this->options['facebook_access_token']) : ''
        );
    }
    public function FacebookPageIdCallback()
    {
        printf(
            '<input type="text" id="facebook_page_id" name="%s[facebook_page_id]" value="%s" />', $this->optionname,
            isset( $this->options['facebook_page_id'] ) ? esc_attr( $this->options['facebook_page_id']) : ''
        );
    }   
   

}