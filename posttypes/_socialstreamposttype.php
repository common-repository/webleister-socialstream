<?php
namespace wl_socialstream;
/**
 * Creates post Type for SocialStream has Insert and Select methods
 */
class SocialStreamPostType  {

	public static function CreatePostType() {
        $args = array(
         'labels'	            =>	array(
                                         'name'              =>  'Social Stream',
                                         'singular_name'     =>  'Social Stream',
                                         'menu_name'         =>  'WL Social Stream',
                                     ),
         'description'           =>  'Social Stream',
         'public'		        =>	true,
         'exclude_from_search'   =>  false,
         'menu_position'         =>  60,
         'menu_icon'             =>  'dashicons-share',
         'supports'              =>  array('title','editor', 'revisions')
         );
        register_post_type( 'wl_socialstream', $args );

	}

    public static function Insert($socialid,$type,$title,$content,$date){

        $posts = get_posts(array(
                       'numberposts'	=> -1,
                       'post_type'		=> 'wl_socialstream',
                       'meta_key'       =>'wl_socialstream_socialid',
                       'meta_value'     => $socialid
                ));
        if($posts && !empty($posts)){
            $updateid = $posts[0]->ID;
            $socialitem = array(
                             'ID'                    => $updateid,
                             'post_status'           => 'publish',
                             'post_type'             => 'wl_socialstream',
                             'post_title'            => $title,
                             'post_content'          => $content,
                             'post_date'             => $date
                         );
            wp_update_post($socialitem);
            add_post_meta($updateid, 'wl_socialstream_type', $type);
            add_post_meta($updateid, 'wl_socialstream_socialid', $socialid);
        }else{
            $socialitem = array(
                             'post_status'           => 'publish',
                             'post_type'             => 'wl_socialstream',
                             'post_title'            => $title,
                             'post_content'          => $content,
                             'post_date'             => $date
                         );
            $newid = wp_insert_post($socialitem);
            add_post_meta($newid, 'wl_socialstream_type', $type);
            add_post_meta($newid, 'wl_socialstream_socialid', $socialid);
        }

    }

    public static function GetEntries($types,$limit=-1,$skip=-1){
        $args = array(
                        'numberposts'	=> $limit,
                        'post_type'		=> 'wl_socialstream',
                        'meta_query' => array(
                            array(
                                'key' => 'wl_socialstream_type',
                                'value' => $types,
                                'compare' => 'IN',
                            )
                        ),
                        'orderby'          => 'date',
                        'order'            => 'DESC'
                );
        if($skip>-1){
            $args['offset'] = $skip;
        }
        $posts = get_posts($args);

        $retval = array();
        if(!empty($posts))
        {
            foreach ( $posts as $result ) 
            {
                $content = $result->post_content;
                $type = get_post_meta( $result->ID,'wl_socialstream_type',true);
                $socialId = get_post_meta( $result->ID,'wl_socialstream_socialid',true);
                if( trim(strtolower($type)) == 'twitter')
                {
                    $content =URLHelper::ResolveUrls($content,true);
                }
                $retval[] = array(
                         'SocialID'=> $socialId,
                         'Type'=> $type,
                         'Title'=> trim(get_the_title($result->ID)),
                         'Content'=> $content,
                         'DateString'=>get_the_date('d.m.Y',$result->ID)
                     );
            }   
        }
        return $retval;
        
    }

}
add_action('init',array('\wl_socialstream\SocialStreamPostType', 'CreatePostType' ) );
?>