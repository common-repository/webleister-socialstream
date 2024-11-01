<?php
namespace wl_socialstream;
class Options_YouTube
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionname;

    public function __construct()
    {
        $this->optionname = LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_youtube';
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
            'wl_social_stream_youtube',
            array($this,'create_page')
        );
    }
   
    
    /**
     * Register and add settings
     */
    public function PageInit()
    {        
 
        register_setting('wl_social_stream_option_group_youtube',$this->optionname,array( $this, 'Sanitize' )); 
        //YouTube
        add_settings_section(
            'setting_youtube_section_id', // ID
            'YouTube', // Title
            array( $this, 'PrintSectionYoutubeInfo' ), // Callback
            'wl_social_stream_youtube' // Page
        );  

        add_settings_field(
            'youtube_api', // ID
            'API Key', // Title 
            array( $this, 'YoutubeApiCallback' ), // Callback
            'wl_social_stream_youtube', // Page
            'setting_youtube_section_id' // Section           
        );      

        add_settings_field(
            'youtube_channel_id', 
            'Channel ID', 
            array( $this, 'YoutubeChannelIdCallback' ), 
            'wl_social_stream_youtube', 
            'setting_youtube_section_id'
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
        if( isset( $input['youtube_api'] ) )
            $new_input['youtube_api'] = sanitize_text_field( $input['youtube_api'] );

        if( isset( $input['youtube_channel_id'] ) )
            $new_input['youtube_channel_id'] = sanitize_text_field( $input['youtube_channel_id'] );      
        return $new_input;
    }   
    /** 
     * YouTube Callbacks
     */
    public function PrintSectionYoutubeInfo()
    {
        print __('YouTube Intro','wl-socialstream');
    }
    public function YoutubeApiCallback()
    {
        
        printf(
            '<input type="text" id="youtube_api" name="%s[youtube_api]" value="%s" />',$this->optionname,
            isset( $this->options['youtube_api'] ) ? esc_attr( $this->options['youtube_api']) : ''
        );
    }
    public function YoutubeChannelIdCallback()
    {
        printf(
            '<input type="text" id="youtube_channel_id" name="%s[youtube_channel_id]" value="%s" />',$this->optionname,
            isset( $this->options['youtube_channel_id'] ) ? esc_attr( $this->options['youtube_channel_id']) : ''
        );
        
    }   

}