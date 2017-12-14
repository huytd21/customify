<?php

class Customify_Builder_Item_User
{
    public $id = 'user';

    function item()
    {
        return array(
            'name' => __( 'User', 'customify' ),
            'id' => 'user',
            'col' => 0,
            'width' => '4',
            'section' => 'header_user' // Customizer section to focus when click settings
        );
    }

    function customize(){
        $section = 'header_user';
        $prefix = 'header_user_';
        $fn = array( $this, 'render' );
        $selector = '.header-user-item';

        return array(
            array(
                'name' => $section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => __( 'User', 'customify' ),
            ),

            array(
                'name' => $prefix.'heading_1',
                'type' => 'heading',
                'section' => $section,
                'title' => __( 'When user not logged in', 'customify' ),
            ),

            array(
                'name' => $prefix.'show_login',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'default' => 1,
                'title' => __( 'Show login', 'customify' ),
                'checkbox_label'  => __( 'Show login', 'customify' ),
            ),

            array(
                'name' => $prefix.'login',
                'type' => 'group',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'title'   => __( 'Login item', 'customify' ),
                'required'  => array( $prefix.'show_login','==','1' ),
                'default' => array(
                    'label' =>  __( 'Login', 'customify' ),
                    'url' => '#'
                ),
                'fields' => array(
                    array(
                        'name' => 'label',
                        'type' => 'text',
                        'label' => __( 'Label', 'customify' ),
                    ),
                    array(
                        'name' => 'icon',
                        'type' => 'icon',
                        'label' => __( 'Icon', 'customify' ),
                    ),
                    array(
                        'name' => 'url',
                        'type' => 'text',
                        'label' => __( 'Custom Login URL', 'customify' ),
                    ),
                )
            ),


            array(
                'name' => $prefix.'show_signup',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'title'   => __( 'Show login', 'customify' ),
                'default' => 1,
                'checkbox_label' => __( 'Show Sign Up', 'customify' ),
            ),

            array(
                'name' => $prefix.'signup',
                'type' => 'group',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'title'   => __( 'Sign Up Settings', 'customify' ),
                'required'  => array( $prefix.'show_signup','==','1' ),
                'default' => array(
                    'label' =>  __( 'Sign Up', 'customify' ),
                    'url' => '#'
                ),
                'fields' => array(
                    array(
                        'name' => 'label',
                        'type' => 'text',
                        'label' => __( 'Label', 'customify' ),
                    ),
                    array(
                        'name' => 'icon',
                        'type' => 'icon',
                        'label' => __( 'Icon', 'customify' ),
                    ),
                    array(
                        'name' => 'url',
                        'type' => 'text',
                        'label' => __( 'Custom Sign Up URL', 'customify' ),
                    ),
                )
            ),


            array(
                'name' => $prefix.'heading_3',
                'type' => 'heading',
                'section' => $section,
                'theme_supports' => '',
                'title' => __( 'When user logged in', 'customify' ),
            ),

            array(
                'name' => $prefix.'profile_url',
                'type' => 'text',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'title'   => __( 'Profile URL', 'customify' ),
            ),

            array(
                'name' => $prefix.'show_avatar',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'default' => 1,
                'title'   => __( 'Show Avatar', 'customify' ),
                'checkbox_label'   => __( 'Show Avatar', 'customify' ),
            ),

            array(
                'name' => $prefix.'avatar_size',
                'type' => 'slider',
                'default' => 35,
                'max' => 150,
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'required'  => array( $prefix.'show_avatar','==','1' ),
                'title'   => __( 'Avatar Size', 'customify' ),
            ),


            array(
                'name' => $prefix.'show_username',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'default' => 1,
                'title'   => __( 'Show User Name', 'customify' ),
                'checkbox_label'   => __( 'Show User Name', 'customify' ),
            ),

            array(
                'name' => $prefix.'logged_in_items',
                'type' => 'repeater',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'title'   => __( 'Dropdown Items', 'customify' ),
                'fields' => array(
                    array(
                        'name' => 'label',
                        'type' => 'text',
                        'label' => __( 'Label', 'customify' ),
                    ),
                    array(
                        'name' => 'icon',
                        'type' => 'icon',
                        'label' => __( 'Icon', 'customify' ),
                    ),
                    array(
                        'name' => 'url',
                        'type' => 'text',
                        'label' => __( 'URL', 'customify' ),
                    ),
                )
            ),

            array(
                'name' => $prefix.'text_align',
                'type' => 'text_align_no_justify',
                'section' => $section,
                'device_settings' => true,
                'selector' => '.builder-item--user',
                'css_format' => 'text-align: {{value}};',
                'title'   => __( 'Align', 'customify' ),
            ),

        );
    }


    function render(){
        $classes = array();
        $classes[] = is_user_logged_in() ? 'user-logged-in' : 'not-logged-in';

        echo '<div class="header-user-item '.esc_attr( join(' ', $classes ) ).'">';

        if ( is_user_logged_in() ) {

            $url = Customify_Customizer()->get_setting('header_user_profile_url' );
            $show_username = Customify_Customizer()->get_setting('header_user_show_username' );
            $show_avatar = Customify_Customizer()->get_setting('header_user_show_avatar' );
            $size = Customify_Customizer()->get_setting('header_user_avatar_size' );
            $size = wp_parse_args( $size, array(
                'value' => ''
            ) );
            $size = absint( $size['value'] );
            if ( ! $size ) {
                $size = 35;
            }

            if ( ! $url ) {
                $url = get_edit_profile_url();
            }

            $user = wp_get_current_user();
            $avatar = get_avatar( $user->user_email, $size );

            if ( $url ) {
                echo '<a class="profile-url '.( $show_avatar ? 'show-avatar' : 'no-avatar' ).'" href="'.esc_url( $url ).'">';
            }
            if ( $show_avatar && $avatar ) {
                echo '<span class="user-avatar">'.$avatar.'</span>';
            }
            if ( $show_username ) {
                echo '<span class="user-display-name">';
                if ( $user->display_name ) {
                    echo $user->display_name;
                } elseif( $user->user_login ) {
                    echo $user->display_name;
                } else {
                    echo $user->user_email;
                }
            }
            echo '<span>';

            if ( $url ) {
                echo '</a>';
            }

            $items = Customify_Customizer()->get_setting('header_user_logged_in_items' );
            if ( ! empty( $items ) ) {
                echo '<ul class="user-dropdown-items">';
                foreach(  $items as $item ) {
                    $item = wp_parse_args( $item, array(
                        'label' => '',
                        'icon' => array(),
                        'url' => '',

                    ) );

                    $icon = wp_parse_args( $item['icon'], array(
                        'type' => '',
                        'icon' => '',
                    ) );

                    echo '<li>';
                    if ( $item['url'] ) {
                        echo '<a href="'.esc_attr( $item['url'] ).'">';
                    }
                    if ( $icon['icon'] ) {
                        echo '<i class="'.esc_attr( $icon['icon'] ).'"></i>';
                    }
                    echo  $item['label'];
                    if ( $item['url'] ) {
                        echo '</a>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            }
        } else {

            $show_login = Customify_Customizer()->get_setting('header_user_show_login');
            if ( $show_login ) {
                $login_settings = Customify_Customizer()->get_setting('header_user_login');
                $login_settings = wp_parse_args( $login_settings, array(
                    'label' => '',
                    'icon' => '',
                    'url' => '',
                ) );
                $login_settings['icon'] = Customify_Customizer()->setup_icon( $login_settings['icon'] );

                if ( $login_settings['label'] ) {
                    if ( $login_settings['url'] ) {
                        echo '<a class="user-login-item user-link" href="'.esc_url( $login_settings['url'] ).'">';
                    } else {
                        echo '<span class="user-login-item">';
                    }
                    if ( $login_settings['icon']['icon'] ) {
                        echo '<i class="'.esc_attr( $login_settings['icon']['icon'] ).'"></i>';
                    }
                    echo esc_html( $login_settings['label'] );
                    if ( $login_settings['url'] ) {
                        echo '</a>';
                    } else {
                        echo '</span>';
                    }
                }
            }

            $show_signup = Customify_Customizer()->get_setting('header_user_show_signup');

            if ( $show_signup ) {
                $signup_settings = Customify_Customizer()->get_setting('header_user_signup');
                $signup_settings = wp_parse_args( $signup_settings, array(
                    'label' => '',
                    'icon' => '',
                    'url' => '',
                ) );

                $signup_settings['icon'] = Customify_Customizer()->setup_icon( $signup_settings['icon'] );

                if ( $signup_settings['label'] ) {

                    if ( $signup_settings['url'] ) {
                        echo '<a class="user-singup-item user-link" href="'.esc_url( $signup_settings['url'] ).'">';
                    } else {
                        echo '<span class="user-singup-item">';
                    }
                    if ( $signup_settings['icon']['icon'] ) {
                        echo '<i class="'.esc_attr( $signup_settings['icon']['icon'] ).'"></i>';
                    }
                    echo esc_html( $signup_settings['label'] );
                    if ( $signup_settings['url'] ) {
                        echo '</a>';
                    } else {
                        echo '</span>';
                    }
                }
            }
        }

        echo '</div>';
    }

}

Customify_Customizer_Layout_Builder()->register_item('header', new Customify_Builder_Item_User() );