<?php
namespace wl_socialstream;

class URLHelper {
    /**
     * Convert Urls to a tags
     * @param string $text String to search for
     * @param bool $twitter Replace also twitter Usernames and # with a tags default false  
     * @return bool Content with all Urls Replaced by a tags
     */    
	public static function ResolveUrls( $text,$twitter=false) {
        $text = preg_replace('/[^\"](https?:\/\/[^\s]+)/i',' <a href="$1" target="_blank">$1</a>',$text);
        if($twitter)
        {
            $text = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i','<a class="twitter_username" target="_blank" href="http://www.twitter.com/$1">@$1</a>',$text);
            $text = preg_replace('/#([a-z_0-9]+)/i', '<a class="twitter_hashtag" target="_blank" href="http://twitter.com/search/$1">$0</a>', $text);
        }
        return $text;
	}

    /**
     * Convert YouTube Video ID to iframe for youtube
     * @param string $video_id YouTube Video ID     
     * @return string YouTube iframe html Code
     */    
    public static function GetYouTubeIframe($video_id){    
        return '<iframe class="ytplayer" type="text/html" src="http://www.youtube.com/embed/'.$video_id.'" frameborder="0"></iframe>';
    }
    
}
?>