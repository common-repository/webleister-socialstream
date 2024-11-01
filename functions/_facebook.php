<?php
namespace wl_socialstream;
/**
 * Functions to get Facebook POSTS and save them
 */
class Facebook {
    protected $access_token;
    protected $last_error;

    public function __construct($_access_token)
    {
        $this->access_token = $_access_token;
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

    private function GetFacebook($pageid)
    {       
        $url = sprintf('https://graph.facebook.com/%s/posts?access_token=%s',$pageid,$this->access_token);
        $json = file_get_contents($url);    
        $json = str_replace('&quot;', '"', $json);        
        $obj = json_decode($json);   
        $nextUrl ='';
        $data = $obj->data;         
        return $data;
    }    

    public function SaveEntries($pageid)
    {
        try{
            $items = $this->GetFacebook($pageid);  
            $not_delete_ids = array();
            if(is_array($items))
            {               
                foreach ($items as $item)
                {       
                    if(isset($item->message))
                    {
                        $socialid = $item->id;
                        $title = '';
                        $content = $item->message;                            
                        if(!empty($item->picture) && $item->type=='photo')
                        {
                            $image = $this->getBigImage($item->object_id);
                            if(!empty($image))
                            {
                            $content.= sprintf('<div class="img"><img class="img-responsive" src="%s"/></div>',$image);
                            }
                        }
                        if(!empty($item->link) && $item->type=='video' &&  (strpos($item->link,'http://youtu.be')!==false ||strpos($item->link,'youtube.com')!==false ))
                        {
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $item->link, $match)) {
                                $video_image= sprintf('https://img.youtube.com/vi/%s/0.jpg',$match[1]);
                                $content.= '<a href="'.$item->source.'" target="_blank" class="wl_video">'.sprintf('<img class="img-responsive" src="%s"/>',$video_image).'</a>';
                            }                          
                        }
                        $date =date('Y-m-d G:i',strtotime($item->created_time));
                        SocialStreamTable::InsertData($socialid,'Facebook',$title,$content,$date,1,true);
                        $not_delete_ids[]=$socialid;
                    }
                }               
            }
        }
        catch(\Exception $e){
            $this->last_error = $e->getMessage();
        }
        
    }

     private function getBigImage($objectid){
         $url = sprintf('https://graph.facebook.com/%s?access_token=%s',$objectid,$this->access_token);
         $json = file_get_contents($url);    
         $json = str_replace('&quot;', '"', $json);
        
         $obj = json_decode($json);   
         $retval = '';
         $currentWidth = 0;
         if(is_array($obj->images))
         {
             foreach($obj->images as $img)
             {
                if($img->width>$currentWidth)
                {
                    $currentWidth = $img->width;
                    $retval = $img->source;
                }
             }
         }
         return $retval;
    }
}
?>