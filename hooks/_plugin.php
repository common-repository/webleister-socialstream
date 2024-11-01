<?php
namespace wl_socialstream;

class Plugin {

    /**
     * On Plugin Activate start Scheduler
     */
    public static function Activate() {

        wp_schedule_event(time(), 'hourly', 'social_stream_scheduler');
    }
    /**
     * On Plugin Deactivate remove Scheduler
     */
    public static function Deactivate() {
        wp_clear_scheduled_hook('social_stream_scheduler');
    }
    /**
     * Scheduler Function to process requesting social posts
     */
    public static function SocialStreamProcessAll()
    {
        $generalSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general');
        if(isset($generalSettings['youtube']) && $generalSettings['youtube'])
        {
            $youtubeSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_youtube');
            $youTube = new Youtube($youtubeSettings['youtube_api']);
            $youTube->SaveEntries($youtubeSettings['youtube_channel_id']);
        }
        if(isset($generalSettings['twitter']) && $generalSettings['twitter'])
        {
            $twitterSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_twitter');
            $twitter = new Twitter($twitterSettings['twitter_api_key'],$twitterSettings['twitter_api_secret'],$twitterSettings['twitter_access_token'],$twitterSettings['twitter_access_token_secret']);
            $twitter->SaveEntries($twitterSettings['twitter_username']);
        }
        if(isset($generalSettings['instagram']) && $generalSettings['instagram'])
        {
            $instagramSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_instagram');
            $instagram = new Instagram($instagramSettings['instagram_access_token']);
            $instagram->SaveEntries($instagramSettings['instagram_user_id']);
        }
        if(isset($generalSettings['facebook']) && $generalSettings['facebook'])
        {
            $facebookSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_facebook');
            $facebook = new Facebook($facebookSettings['facebook_access_token']);
            $facebook->SaveEntries($facebookSettings['facebook_page_id']);
        }
        if(isset($generalSettings['wordpress']) && $generalSettings['wordpress'])
        {
            $wordpressSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_wordpress');
            $wordpress = new Wordpress();
            $wordpress->SaveEntries($wordpressSettings['wordpress_categories'],$wordpressSettings['wordpress_tags']);
        }
    }
    /**
     * Ajax Call from SocialStream Admin
     */
    public static function SocialStreamProcessCallback() {
        $modul = $_POST['modul'];
        $retval = 'Modul '.$modul. __(' wurde nicht gefunden','wl-socialstream');
        $generalSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general');

        if(isset($generalSettings[$modul]) && $generalSettings[$modul])
        {
            $error ='';
            switch($modul){
                case'youtube':
                    $youtubeSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_youtube');
                    if(!empty($youtubeSettings['youtube_api']) && !empty($youtubeSettings['youtube_channel_id']))
                    {
                        $youTube = new Youtube($youtubeSettings['youtube_api']);
                        $youTube->SaveEntries($youtubeSettings['youtube_channel_id']);
                        $error = $youTube->GetLastError();
                    }else{
                        $error = __('Bitte Youtube Konfiguration ueberpruefen.','wl-socialstream');
                    }
                    break;
                case 'twitter':
                    $twitterSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_twitter');
                    if(!empty($twitterSettings['twitter_api_key']) && !empty($twitterSettings['twitter_api_secret'])&& !empty($twitterSettings['twitter_access_token'])&& !empty($twitterSettings['twitter_access_token_secret'])&& !empty($twitterSettings['twitter_username']))
                    {
                        $twitter = new Twitter($twitterSettings['twitter_api_key'],$twitterSettings['twitter_api_secret'],$twitterSettings['twitter_access_token'],$twitterSettings['twitter_access_token_secret']);
                        $twitter->SaveEntries($twitterSettings['twitter_username']);
                        $error = $twitter->GetLastError();
                    }else{
                        $error = __('Bitte Twitter Konfiguration ueberpruefen.','wl-socialstream');
                    }
                    break;
                case 'instagram':
                    $instagramSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_instagram');
                    if(!empty($instagramSettings['instagram_access_token']) && !empty($instagramSettings['instagram_user_id']))
                    {
                        $instagram = new Instagram($instagramSettings['instagram_access_token']);
                        $instagram->SaveEntries($instagramSettings['instagram_user_id']);
                        $error = $instagram->GetLastError();
                    }else{
                        $error = __('Bitte Instagram Konfiguration ueberpruefen.','wl-socialstream');
                    }
                    break;
                case 'facebook':
                    $facebookSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_facebook');
                    if(!empty($facebookSettings['facebook_access_token']) && !empty($facebookSettings['facebook_page_id']))
                    {
                        $facebook = new Facebook($facebookSettings['facebook_access_token']);
                        $facebook->SaveEntries($facebookSettings['facebook_page_id']);
                        $error = $facebook->GetLastError();
                    }else{
                        $error = __('Bitte Facebook Konfiguration ueberpruefen.','wl-socialstream');
                    }
                    break;
                case 'wordpress':
                    $wordpressSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_wordpress');
                    $wordpress = new Wordpress();
                    $wordpress->SaveEntries($wordpressSettings['wordpress_categories'],$wordpressSettings['wordpress_tags']);
                    $error = $wordpress->GetLastError();
                    break;
            }
            if(empty($error))
            {
                $retval = ucfirst($modul). __(' wurde verarbeitet.','wl-socialstream');
            }else{
                $retval = ucfirst($modul). __(' Fehler: ','wl-socialstream').$error;
            }
        }else{
            $retval =ucfirst($modul). __(' ist Inaktiv.','wl-socialstream');
        }
        echo $retval;
        wp_die();
    }
    /**
     * Ajax call from Inifinite Scroll
     */
    public static function SocialStreamInfinite(){
        $page = $_POST['page'];
        $skip = $_POST['skip'];
        if($page==0)
        {
            $offset = -1;
        }else{
            $offset = $page * $skip;
        }
        $generalSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general');
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

        $entries = SocialStreamPostType::GetEntries($types,$skip,$offset);
        $nextPage = ($page+1);
        if(empty($entries) || count($entries) < $skip)
        {
            $nextPage = 'end';
        }
        if(is_array($entries))
        {
            foreach ($entries  as $key => $value) {
                $entries[$key]['Title'] = EntryHelper::GenerateLink($value['Type'],$value['SocialID'],$value['Title']);
                $entries[$key]['DateString'] = EntryHelper::GenerateLink($value['Type'],$value['SocialID'],$value['DateString']);
            }
        }
        $retval = array('page'=> $nextPage,'data'=>$entries);
        wp_send_json($retval);

    }
    /**
     * Ajax Call for getting Facebook Access token
     */
    public static function FacebookGetToken(){
        $appId = $_POST['appid'];
        $appSecret = $_POST['appsecret'];
        $pageId = $_POST['pageid'];
        $access_token = $_POST['access_token'];
        $retval = array();
        //Do Magic
        $fb = new \Facebook\Facebook([
          'app_id' => $appId,
          'app_secret' => $appSecret,
          'default_graph_version' => 'v2.4',
        ]);
        $params = array(
          'client_id' => $appId,
          'client_secret' => $appSecret,
          'grant_type' => 'fb_exchange_token',
          'fb_exchange_token' => $access_token,
        );
        $currentrequest = 'LongLive User Token';
        try
        {
            $result = $fb->post('/oauth/access_token',$params,$access_token);
            
            
            if(!$result->isError()){
                $resultArr = $result->getDecodedBody();
                $llat = $resultArr['access_token'];
                $currentrequest = 'Page ID';
                $result = $fb->get('/'.$pageId,$llat);
                $pageid = '';
                if(!$result->isError()){
                    $resultArr = $result->getDecodedBody();
                    $pageid = $resultArr['id'];
                }else{
                    $retval = array('error'=>$result->getThrownException()->getMessage(),'currentrequest'=>$currentrequest);
                }
                if(!empty($pageid))
                {
                    $currentrequest = 'Page Accounts';
                    $result = $fb->get('/me/accounts',$llat);
                    if(!$result->isError()){
                        $resultArr = $result->getDecodedBody();
                        if(is_array($resultArr['data'])){
                            $found = false;
                            foreach($resultArr['data'] as $page){
                                if($page['id']== $pageid){
                                    $retval = array('access_token'=>$page['access_token']);
                                    $found=true;
                                    break;
                                }
                            }
                            if(!$found){
                                $retval = array('error'=>__('You are not the owner of this page'));
                            }
                        } else{
                            $retval = array('error'=>__('You are not the owner of this page'));
                        }
                    }else{
                        $retval = array('error'=>$result->getThrownException()->getMessage(),'currentrequest'=>$currentrequest);
                    }
                }else{
                    $retval = array('error'=>__('No page found'));
                }
            }else{
                $retval = array('error'=>$result->getThrownException()->getMessage(),'currentrequest'=>$currentrequest);
            }
        }
        catch (\Exception $e)
        {
            $retval = array('error'=>print_r($e,true),'currentrequest'=>$currentrequest);
        }
        wp_send_json( $retval);
    }
}
register_activation_hook(PLUGIN,array('\wl_socialstream\Plugin', 'Activate' ) );
register_deactivation_hook(PLUGIN,array('\wl_socialstream\Plugin', 'Deactivate' ) );
add_action( 'wp_ajax_social_stream_process', array('\wl_socialstream\Plugin','SocialStreamProcessCallback') );
add_action( 'wp_ajax_facebook_get_token', array('\wl_socialstream\Plugin','FacebookGetToken') );
add_action( 'wp_ajax_nopriv_social_stream_infinite', array('\wl_socialstream\Plugin','SocialStreamInfinite') );
add_action('social_stream_scheduler', array('\wl_socialstream\Plugin','SocialStreamProcessAll'));
?>