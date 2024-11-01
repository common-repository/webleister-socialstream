<?php
namespace wl_socialstream;
/**
 * Functions to get Twitter POSTS and save them
 */
class Twitter {
    protected $apiKey;
    protected $apiSecret;
    protected $accessToken;
    protected $accessTokenSecret;
    protected $last_error;

    public function __construct($_apiKey,$_apiSecret,$_accessToken,$_accessTokenSecret)
    {
        $this->apiKey = $_apiKey;
        $this->apiSecret = $_apiSecret;
        $this->accessToken = $_accessToken;
        $this->accessTokenSecret = $_accessTokenSecret;
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
    private function GetTweets($username)
    {
        $settings = array(
            'oauth_access_token' => $this->accessToken,
            'oauth_access_token_secret' => $this->accessTokenSecret,
            'consumer_key' => $this->apiKey,
            'consumer_secret' => $this->apiSecret
        );
        $twitter = new TwitterAPIExchange($settings);
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $requestMethod = 'GET';
        $getfields = '?screen_name='.$username.'&count=200';
        $json = $twitter->setGetfield($getfields)->buildOauth($url, $requestMethod)->performRequest();         
        $obj = json_decode($json);
        $data = $obj;  
       
        return $obj;
    }    

    public function SaveEntries($username)
    {
        try{
            $items = $this->GetTweets($username);   
            $not_delete_ids = array();
            if(is_array($items))
            {
                foreach ($items as $item)
                {
                    $socialid = $item->id_str;
                    $title = '';
                    $content = $item->text;      
                    $date =date('Y-m-d G:i',strtotime($item->created_at));
                    SocialStreamPostType::Insert($socialid,'Twitter',$title,$content,$date);
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