<?php
namespace wl_socialstream;
/**
 * Functions to get YouTube POSTS and save them
 */
class Youtube {
    protected $apiKey;
    protected $last_error;

    public function __construct($_apiKey)
    {
        $this->apiKey = $_apiKey;
        set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
           // error was suppressed with the @-operator
           if (0 === error_reporting()) {
               return false;
           }

           throw new \Exception($errstr);
       });
    }
    public function GetLastError(){
        return $this->last_error;
    }
    private function GetChannelVideos($channelId)
    {
        $url = sprintf('https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&channelId=%s&order=date&key=%s',$channelId,$this->apiKey);
        $json = file_get_contents($url);
        $obj = json_decode($json);
        return $obj;
    }

    public function SaveEntries($channelId)
    {
        try{
            $items = $this->GetChannelVideos($channelId);
            $not_delete_ids = array();
            if(is_array($items->items))
            {
                foreach ($items->items as $item)
                {
                    $socialid = $item->id->videoId;
                    $title = $item->snippet->title;
                    $description = $item->snippet->description;           
                    $content = '<p>'.$description.'</p>';
                    $content .= '<div class="youtube">'.URLHelper::GetYouTubeIframe($socialid).'</div>';
                    $date = $item->snippet->publishedAt;
                    SocialStreamPostType::Insert($socialid,'YouTube',$title,$content,$date);                   
                    $not_delete_ids[]=$socialid;
                }
            }
            
        }
        catch(\Exception $e){
            $this->last_error = $e->getMessage();
        }
        
    }
}
?>