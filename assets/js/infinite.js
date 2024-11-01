(function ($) {
    ajaxurl = '/wp-admin/admin-ajax.php';
    $(document).ready(function () {
        $social_stream = $('#social_stream');
        $social_stream_template = $('#social_stream_template');
        if ($social_stream.length) {
            $template = $($social_stream_template.find('.timeline').html());
            $template_container = $social_stream_template.find('.timeline').clone().children().remove().end();

            $social_stream.append($template_container);
            $social_stream.append('<div id="social_stream_loading" style="display:none"><img src="' + wl_socialstream_infinite.folder + 'wl-socialstream/assets/img/loader.gif" alt="Loading..." style="width:25%;margin:0 auto;display:block;"/></div>');
            ajaxInProgress = false;
            skip = $social_stream.attr('data-skip');
            invert_counter = 1;

            function element_in_scroll($elem) {
                var docViewTop = $(window).scrollTop();
                var docViewBottom = docViewTop + $(window).height();

                var elemTop = $elem.offset().top;
                var elemBottom = elemTop + $elem.height();

                return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
            }
            function get_items() {
                current_page = $social_stream.attr('data-page');
                $('#index_count').attr('value')
                ajaxInProgress = true;
                $('#social_stream_loading').show();
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'social_stream_infinite', page: current_page, skip: skip }
                }).done(function (data) {


                    $.each(data.data, function (i, item) {
                        $item = $template.clone();
                        if (wl_socialstream_infinite.invert_timeline && (invert_counter % 2 == 0)) {
                            $item.addClass('timeline-inverted');
                        }
                        $item.addClass(item.Type).removeClass('##type##');
                        $item.html($item.html().replace('##socialid##', item.SocialId));
                        $item.html($item.html().replace('##type##', item.Type));
                        $item.html($item.html().replace('##title##', item.Title));
                        $item.html($item.html().replace('##content##', item.Content));                        
                        $item.html($item.html().replace('##datestring##', item.DateString));
                        $item.html($item.html().replace('##date##', item.Date));
                        $item.html($item.html().replace('##enabled##', item.Enabled));
                        $item.html($item.html().replace('##edited##', item.Edited));

                        $social_stream.find('.timeline').append($item);                      
                        invert_counter++;
                    });


               
                    $social_stream.attr('data-page', data.page);

                    
                    ajaxInProgress = false;
                    $('#social_stream_loading').hide(); 
                  
                });

            }
            $(document).scroll(function (e) {
                if ($social_stream.find('.timeline >*:last-child').length && element_in_scroll($social_stream.find('.timeline >*:last-child')) && $social_stream.attr('data-page') !='end' && !ajaxInProgress) {
                    get_items();
                }
            });

            get_items();
        }

    });

}(jQuery));