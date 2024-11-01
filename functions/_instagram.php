<?php
namespace wl_socialstream;
/**
 * Functions to get instagram POSTS and save them
 */
class Instagram {
    protected $token;
    protected $last_error;

    public function __construct($_token)
    {
        $this->token = $_token;
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
    private function GetInstagram($userid)
    {       
        $url = sprintf('https://api.instagram.com/v1/users/%s/media/recent/?access_token=%s',$userid,$this->token);
        $json = file_get_contents($url);       
        $obj = json_decode($json);
        $nextUrl ='';
        
        $data = $obj->data;             
        return $data;
    }    

    public function SaveEntries($userid)
    {
        try{
            $items = $this->GetInstagram($userid);    
            $not_delete_ids = array();
       
            if(is_array($items))
            {
                foreach ($items as $item)
                {
                    if(isset($item->caption->id))
                    {
                        $socialid = $item->id;
                        $title = '';
                        $content = $item->caption->text.'<div class="img"><img class="img-responsive" src="'.$item->images->standard_resolution->url.'"/></div>';                          
                        $date =date('Y-m-d G:i',$item->caption->created_time);
                        SocialStreamPostType::Insert($socialid,'Instagram',$title,$content,$date);
                        $not_delete_ids[]=$socialid;
                    }
                }
            }
        }
        catch(\Exception $e){
            $this->last_error = $e->getMessage();
        }
    }
}
?>