(function ($) {
    $.extend($.propHooks, {
        disabled: {
            set: function (elem, value) {
                elem.setAttribute("disabled", (value ? "" : "disabled"));
                return (elem["disabled"] = value);
            }
        }
    });
    $(document).ready(function () {
        $socialStream = $('.wl_socialstream');
        $loadButton = $('#load_socialstream');
        $fbaccesstoken_input = $('#facebook_access_token');
        if ($socialStream.length) {
            if ($loadButton.length) {
                $loadButton.on('click', function (e) {
                    e.preventDefault();
                    $link = $(this);
                    if (!$link.prop('disabled')) {
                        $link.attr('disabled', 'disabled');
                        $socialStream.find('.result').html('');
                        $.each(['youtube', 'twitter', 'instagram', 'facebook', 'wordpress'], function (index, value) {
                            var data = {
                                'action': 'social_stream_process',
                                'modul': value
                            };
                            if (!$socialStream.find('.result').length) {
                                $socialStream.append('<div class="result"></div>');
                            }
                            $socialStream.find('.result').append('<p id="result_' + value + '">' + value + ':&nbsp;<img src="/wp-admin/images/wpspin_light.gif" alt="Loading..." style="height:10px;"/></p>');
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: data,
                                success: function (response) {
                                    $socialStream.find('.result #result_' + value).html('<p>' + response + '</p>');
                                },
                                async: false
                            });


                        });
                        $link.removeAttr('disabled');
                    }

                });
            }
        }
        if ($fbaccesstoken_input.length) {
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) { return; }
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/de_DE/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            window.fbAsyncInit = function () {
                if ($('#facebook_access_token').val() == '') {
                    $fbaccesstoken_input.parents('.form-table').hide();
                    $fbaccesstoken_input.parents('.form-table').next('.submit').hide();
                    $('#fb_form').show();
                    $('#show_fb_token').hide();
                }
                function ValidateInputs() {
                    var retval = true;
                    if ($('#fb_app_id').val().length == 0) {
                        $('#fb_app_id').css('border-color', '#ff0000');
                        retval = false;
                    } else {
                        $('#fb_app_id').css('border-color', '#ddd');
                    }
                    if ($('#fb_app_secret').val().length == 0) {
                        $('#fb_app_secret').css('border-color', '#ff0000');
                        retval = false;
                    } else {
                        $('#fb_app_secret').css('border-color', '#ddd');
                    }
                    if ($('#fb_page_id').val().length == 0) {
                        $('#fb_page_id').css('border-color', '#ff0000');
                        retval = false;
                    } else {
                        $('#fb_page_id').css('border-color', '#ddd');
                    }
                    return retval;
                }
                $('#show_fb_token').on('click', function (e) {
                    $fbaccesstoken_input.parents('.form-table').hide();
                    $fbaccesstoken_input.parents('.form-table').next('.submit').hide();
                    $('#fb_form').show();
                    $(this).hide();
                });
                $('#generate_fb_token').on('click', function (e) {
                    e.preventDefault();
                    $('#generate_fb_token').attr('disabled', 'disabled');
                    $('#fb_output').html('');
                    if (ValidateInputs()) {
                        var appId = $('#fb_app_id').val();
                        var appSecret = $('#fb_app_secret').val();
                        var pageId = $('#fb_page_id').val();
                     
                        FB.init({
                            appId: appId,
                            xfbml: true,
                            version: 'v2.4'
                        });

                        var accessToken = '';
                        FB.getLoginStatus(function (response) {
                            if (response.status === 'connected') {
                                accessToken = response.authResponse.accessToken;
                            } else if (response.status === 'not_authorized') {
                                $('#fb_output').append(wl_socialstream_admin.lang_notauthorized+'<br/>');
                            } else {
                                // the user isn't logged in to Facebook.
                                FB.login(function (response) {
                                    if (response.authResponse) {
                                        accessToken = FB.getAuthResponse()['accessToken'];
                                    } else {
                                        $('#fb_output').append(wl_socialstream_admin.lang_canceled+'<br/>');
                                    }
                                }, { scope: '' });
                            }
                            if (accessToken != '') {
                                var data = {
                                    'action': 'facebook_get_token',
                                    'appid': appId,
                                    'appsecret': appSecret,
                                    'pageid': pageId,
                                    'access_token': accessToken
                                };

                                $.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: data,
                                    async: false
                                }).done(function (response) {
                                    if (response.error) {
                                        $('#fb_output').append(response.error);
                                    } else {
                                        $('#facebook_access_token').val(response.access_token);
                                        $('#facebook_page_id').val(pageId);
                                        $fbaccesstoken_input.parents('.form-table').show();
                                        $fbaccesstoken_input.parents('.form-table').next('.submit').show();
                                        $('#fb_form').hide();
                                        $('#show_fb_token').show();
                                    }

                                });

                            } else {
                                $('#fb_output').append(wl_socialstream_admin.lang_notoken + '<br/>');
                            }
                        }, true);
                    }
                    $('#generate_fb_token').removeAttr('disabled');
                });
            };
        }
    });
}(jQuery));