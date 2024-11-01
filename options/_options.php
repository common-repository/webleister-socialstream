<?php
namespace wl_socialstream;
class Options
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $youTubeSettings;
    private $instagramSettings;
    private $facebookSettings;
    private $twitterSettings;
    private $wordpressSettings;
    private $optionname;
    /**
     * Start up
     */
    public function __construct()
    {

        $this->optionname = LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general';

        $this->youTubeSettings = new Options_YouTube();
        $this->instagramSettings = new Options_Instagram();
        $this->facebookSettings = new Options_Facebook();
        $this->twitterSettings = new Options_Twitter();
        $this->wordpressSettings = new Options_Wordpress();

        add_action( 'admin_menu', array( $this, 'AddPluginPage' ) );
        add_action( 'admin_init', array( $this, 'PageInit' ) );
    }

    /**
     * Add options page
     */
    public function AddPluginPage()
    {
        // This page will be under "Settings"
        add_submenu_page(
            'edit.php?post_type=wl_socialstream',
            'Settings Admin',
            __('Einstellungen','wl-socialstream'),
            'manage_options',
            'wl_social_stream_general',
             array( $this, 'CreateAdminPage' )
            );
        $this->youTubeSettings->AddPluginPage();
        $this->instagramSettings->AddPluginPage();
        $this->facebookSettings->AddPluginPage();
        $this->twitterSettings->AddPluginPage();
        $this->wordpressSettings->AddPluginPage();
    }



    /**
     * Options page callback
     */
    public function CreateAdminPage()
    {
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } else {
            $active_tab = 'general';
        }
        // Set class property

        $this->options = get_option( $this->optionname);
        $jsfolder = plugins_url(dirname(PLUGIN_BASENAME)).'/assets/js/';
        $dataToBePassed = array(
             'lang_notauthorized'   => __('Not authorized.','wl-socialstream') ,
             'lang_canceled'        => __('User cancelled login or did not fully authorize.','wl-socialstream'),
             'lang_notoken'         =>__('Could not retrieve Token please check your Configuration.','wl-socialstream')
          );
        AssetHelper::LoadJs(array(
            'folder'=>$jsfolder,
            'file'=>'admin',
            'depends'=>array('jquery'),
            'dataToBePassed' => $dataToBePassed,
            'dataToBePassedId' => 'wl_socialstream_admin'
            )
        );
?>
<div class="wrap wl_socialstream">
    <h2>Social Stream Settings</h2>
    <h2 class="nav-tab-wrapper">
        <a href="?post_type=wl_socialstream&page=wl_social_stream_general&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
        <?php
        if(isset($this->options['youtube']) && $this->options['youtube']){?>
        <a href="?post_type=wl_socialstream&page=wl_social_stream_general&tab=youtube" class="nav-tab <?php echo $active_tab == 'youtube' ? 'nav-tab-active' : ''; ?>">YouTube</a>
        <?php
        }
        if(isset($this->options['instagram']) &&$this->options['instagram']){?>
        <a href="?post_type=wl_socialstream&page=wl_social_stream_general&tab=instagram" class="nav-tab <?php echo $active_tab == 'instagram' ? 'nav-tab-active' : ''; ?>">Instagram</a>
        <?php
        }
        if(isset($this->options['facebook']) &&$this->options['facebook']){?>
        <a href="?post_type=wl_socialstream&page=wl_social_stream_general&tab=facebook" class="nav-tab <?php echo $active_tab == 'facebook' ? 'nav-tab-active' : ''; ?>">Facebook</a>
        <?php
        }
        if(isset($this->options['twitter']) &&$this->options['twitter']){?>
        <a href="?post_type=wl_socialstream&page=wl_social_stream_general&tab=twitter" class="nav-tab <?php echo $active_tab == 'twitter' ? 'nav-tab-active' : ''; ?>">Twitter</a>
        <?php
        }
        if(isset($this->options['wordpress']) &&$this->options['wordpress']){?>
        <a href="?post_type=wl_socialstream&page=wl_social_stream_general&tab=wordpress" class="nav-tab <?php echo $active_tab == 'wordpress' ? 'nav-tab-active' : ''; ?>">Wordpress</a>
        <?php }?>
    </h2>
    <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields('wl_social_stream_option_group_'.$active_tab);
        do_settings_sections( 'wl_social_stream_'.$active_tab);

        submit_button();

        ?>
    </form>
</div>
<?php
    }

    /**
     * Register and add settings
     */
    public function PageInit()
    {
        register_setting('wl_social_stream_option_group_general', $this->optionname,array( $this, 'Sanitize' ));
        add_settings_section(
            'setting_general_section_id', // ID
            'General', // Title
            array( $this, 'PrintSectionGeneralInfo' ), // Callback
            'wl_social_stream_general' // Page
        );
        add_settings_field(
           'youtube',
           'YouTube',
           array( $this, 'YoutubeCallback' ),
           'wl_social_stream_general',
           'setting_general_section_id'
       );
        add_settings_field(
           'instagram',
           'Instagram',
           array( $this, 'InstagramCallback' ),
           'wl_social_stream_general',
           'setting_general_section_id'
       );
        add_settings_field(
           'facebook',
           'Facebook',
           array( $this, 'FacebookCallback' ),
           'wl_social_stream_general',
           'setting_general_section_id'
       );
        add_settings_field(
           'twitter',
           'Twitter',
           array( $this, 'TwitterCallback' ),
           'wl_social_stream_general',
           'setting_general_section_id'
       );
        add_settings_field(
           'wordpress',
           'Wordpress',
           array( $this, 'WordpressCallback' ),
           'wl_social_stream_general',
           'setting_general_section_id'
       );
        add_settings_field(
         'max_skip',
         __('Anzahl Eintraege / Eintraege pro Load','wl-socialstream'),
         array( $this, 'MaxSkipCallback' ),
         'wl_social_stream_general',
         'setting_general_section_id'
     );
        add_settings_field(
          'infinite_scroll',
          'Infinite Scroll',
          array( $this, 'InfiniteScrollCallback' ),
          'wl_social_stream_general',
          'setting_general_section_id'
        );
        add_settings_field(
           'link_entries',
           __('Eintraege verlinken?','wl-socialstream'),
           array( $this, 'LinkEntriesCallback' ),
           'wl_social_stream_general',
           'setting_general_section_id'
       );
        add_settings_field(
         'use_css',
         'Webleister CSS',
         array( $this, 'UseCssCallback' ),
         'wl_social_stream_general',
         'setting_general_section_id'
     );

        $this->youTubeSettings->PageInit();
        $this->instagramSettings->PageInit();
        $this->facebookSettings->PageInit();
        $this->twitterSettings->PageInit();
        $this->wordpressSettings->PageInit();
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function Sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['youtube'] ) )
            $new_input['youtube'] = absint( $input['youtube'] );
        if( isset( $input['instagram'] ) )
            $new_input['instagram'] = absint( $input['instagram'] );
        if( isset( $input['facebook'] ) )
            $new_input['facebook'] = absint( $input['facebook'] );
        if( isset( $input['twitter'] ) )
            $new_input['twitter'] = absint( $input['twitter'] );
        if( isset( $input['wordpress'] ) )
            $new_input['wordpress'] = absint( $input['wordpress'] );
        if( isset( $input['infinite_scroll'] ) )
            $new_input['infinite_scroll'] = absint( $input['infinite_scroll'] );
        if( isset( $input['max_skip'] ) )
            $new_input['max_skip'] =  $input['max_skip'];
        if( isset( $input['use_css'] ) )
            $new_input['use_css'] = absint( $input['use_css'] );
        if( isset( $input['link_entries'] ) )
            $new_input['link_entries'] = absint( $input['link_entries'] );
        return $new_input;
    }
    /**
     * General Callbacks
     */
    public function PrintSectionGeneralInfo()
    {
        print '<p>'.__('General Intro','wl-socialstream').'</p><p><a id="load_socialstream" class="button button-primary">'.__('Daten laden','wl-socialstream').'</a><div class="result"></div></p>';
    }
    public function YoutubeCallback()
    {
        printf(
            '<input type="checkbox" id="youtube" name="%s[youtube]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['youtube'])?$this->options['youtube']:false, false)
        );
    }
    public function InstagramCallback()
    {
        printf(
            '<input type="checkbox" id="instagram" name="%s[instagram]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['instagram'])?$this->options['instagram']:false, false)
        );
    }
    public function FacebookCallback()
    {
        printf(
            '<input type="checkbox" id="facebook" name="%s[facebook]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['facebook'])?$this->options['facebook']:false, false)
        );
    }
    public function TwitterCallback()
    {
        printf(
            '<input type="checkbox" id="twitter" name="%s[twitter]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['twitter'])?$this->options['twitter']:false, false)
        );
    }
    public function WordpressCallback()
    {
        printf(
            '<input type="checkbox" id="wordpress" name="%s[wordpress]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['wordpress'])?$this->options['wordpress']:false, false)
        );
    }
    public function MaxSkipCallback()
    {
        printf(
            '<input type="number" id="max_skip" name="%s[max_skip]" value="%s"  />', $this->optionname,
            $this->options['max_skip']
        );
    }
    public function InfiniteScrollCallback()
    {
        printf(
            '<input type="checkbox" id="infinite_scroll" name="%s[infinite_scroll]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['infinite_scroll'])?$this->options['infinite_scroll']:false, false)
        );
    }
    public function LinkEntriesCallback()
    {
        printf(
            '<input type="checkbox" id="link_entries" name="%s[link_entries]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['link_entries'])?$this->options['link_entries']:false, false)
        );
    }
    public function UseCssCallback()
    {
        printf(
            '<input type="checkbox" id="use_css" name="%s[use_css]" value="1" %s />', $this->optionname,
             checked(1, isset($this->options['use_css'])?$this->options['use_css']:false, false)
        );
    }

}
if( is_admin() )
    $setting_page = new Options();