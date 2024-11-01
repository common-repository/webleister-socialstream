<?php
namespace wl_socialstream;
/**
 * Functions to get Wordpress POSTS and save them
 */
class Wordpress {

    protected $last_error;
    public function __construct(){
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


    public function SaveEntries($categories='',$tags='')
    {
        try{
            $category_check = explode(',',strtolower($categories));      
            $tag_check = explode(',',strtolower($tags));
            
            $not_delete_ids = array();

            $args = array (
	            'post_type'              => array( 'post' ),
	            'post_status'            => array( 'publish' ),
            );

            // The Query
            $query = new \WP_Query( $args );

            // The Loop
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $pid = get_the_ID();
                    $post_tags = wp_get_post_tags( $pid );
                    $post_categories = get_the_category( $pid );
                    $categorie_ids = array();
                    foreach ($post_categories as $cat)
                    {
                        $categorie_ids[]=strtolower($cat->name);
                    }
                    $tag_ids = array();
                    foreach ($post_tags as $tag)
                    {
                        $tag_ids[]=strtolower($tag->name);
                    }
                    
                    if((trim($categories)==false || count(array_diff($category_check,$categorie_ids)) < count($category_check)) && (trim($tags)==false ||  count(array_diff($tag_check,$tag_ids)) < count($tag_check)) )
                    {
                        $socialid ='Wordpress_'.$pid;
                        $title = get_the_title();  
                        
                        $content = get_the_excerpt();  
                        $img_id = get_post_thumbnail_id($pid);
                        if(!empty($img_id))
                        {
                            $thumb = wp_get_attachment_image_src( $img_id, 'medium' );
                            $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);
                            if(!empty($thumb))
                            {
                                $content.= sprintf('<div class="img"><img class="img-responsive" src="%s" alt="%s"/></div>',$thumb[0],$alt_text);
                            }
                        }                        
                        $date =get_the_date('Y-m-d G:i');                        
                        SocialStreamPostType::Insert($socialid,'Wordpress',$title,$content,$date);
                        $not_delete_ids[]=$socialid;
                    }
                }
            }
            wp_reset_postdata();           
        }
        catch(\Exception $e){
            $this->last_error = $e->getMessage();
        }
    }
   
}
?>