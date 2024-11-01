<?php
namespace wl_socialstream;

class EntryHelper {
    /**
     * Enqueues Stylesheets. Use named array!
     * 
     * Named values:
     *     type                 string          required    Type of Item
     *     id                   string          required    ocial ID of Item
     *     content              string          optional    Link Titel
     *     enforce_generation   bool            optional    Ignores Global Settings for Link Generation     
     */    
    public static function GenerateLink($type,$id,$content='',$enforce_generation=false){
        $generalSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_general');
        $generateLink = $enforce_generation ? true : (empty($generalSettings['link_entries'])?false:$generalSettings['link_entries']);
        $retval = $content;
        $linktemplate = '<a href="%s" target="_blank">%s</a>';
        if($generateLink)
        {
            switch(strtolower($type)){
                case'youtube':
                    $retval = sprintf ($linktemplate,'https://youtu.be/'.$id,$content);                    
                    break;
                case 'twitter':
                    $twitterSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_twitter');
                    $retval = sprintf ($linktemplate,'https://twitter.com/'.$twitterSettings['twitter_username'].'/status/'.$id,$content);
                    break;
                case 'instagram':
                    $instagramSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_instagram');
                    $url = sprintf('https://api.instagram.com/v1/media/%s/?access_token=%s',$id,$instagramSettings['instagram_access_token']);
                    $json = file_get_contents($url);       
                    $obj = json_decode($json);                 
                    $retval = sprintf ($linktemplate,$obj->data->link,$content);
                    break;
                case 'facebook':
                    $fbids = explode('_',$id);   
                    $fbSettings = get_option(LanguageHelper::GetLanguageCode().'_wl_social_stream_option_name_facebook');
                    $retval = sprintf ($linktemplate,'https://www.facebook.com/'.$fbSettings['facebook_page_id'].'/posts/'.$fbids[1],$content);
                    break;
                case 'wordpress':
                    $pid = str_replace('Wordpress_','',$id);
                    $retval = sprintf ($linktemplate,get_permalink($pid),$content);
                    break;
            }
        }
        return $retval;
    }
}
?>