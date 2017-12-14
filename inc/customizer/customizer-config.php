<?php
if ( ! function_exists( 'customify_customizer_config' ) ) {
    function customify_customizer_config( $configs ){

        $config = array(
            array(
                'name' => 'customify_panel',
                'type' => 'panel',
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Customify Panel', 'customify' ),
            ),

            array(
                'name' => 'customify_section',
                'type' => 'section',
                'panel' => 'customify_panel',
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Customify Section', 'customify' ),
                'description' => __( 'This is section description',  'customify' ),
            ),

            array(
                'name' => 'typography',
                'type' => 'group',
                'section'     => 'customify_section',
                'title'          => __( 'Typography', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'field_class' => 'customify-typography-control',
                'selector' => '#page',
                'css_format' => 'typography',
                'default' => array(

                ),
                'fields' => array(

                    array(
                        'name' => 'font',
                        'type' => 'font',
                        'label' => __( 'Font', 'customify' ),
                    ),

                    array(
                        'name' => 'text-align',
                        'type' => 'font_style',
                        'label' => __( 'Font Style', 'customify' ),
                    ),

                    array(
                        'name' => 'font_style',
                        'type' => 'text_align',
                        'label' => __( 'Text align', 'customify' ),
                    ),

                    array(
                        'name' => 'font_size',
                        'type' => 'slider',
                        'label' => __( 'Font Size', 'customify' ),
                        'device_settings' => true,
                    ),

                    array(
                        'name' => 'line_height',
                        'type' => 'slider',
                        'label' => __( 'Line Height', 'customify' ),
                        'device_settings' => true,
                    ),

                    array(
                        'name' => 'letter_spacing',
                        'type' => 'slider',
                        'label' => __( 'Letter Spacing', 'customify' ),
                        'min' => -10,
                        'max' => 10,
                    ),

                    array(
                        'name' => 'color',
                        'type' => 'color',
                        'label' => __( 'Color', 'customify' ),
                    ),

                )
            ),

            array(
                'name' => 'typography_h1',
                'type' => 'group',
                'section'     => 'customify_section',
                'title'          => __( 'Typography H1', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'field_class' => 'customify-typography-control',
                'selector' => '#page h1',
                'css_format' => 'typography',
                'default' => array(

                ),
                'fields' => array(

                    array(
                        'name' => 'font',
                        'type' => 'font',
                        'label' => __( 'Font', 'customify' ),
                    ),

                    array(
                        'name' => 'font_style',
                        'type' => 'font_style',
                        'label' => __( 'Font Style', 'customify' ),
                    ),

                    array(
                        'name' => 'font_size',
                        'type' => 'slider',
                        'label' => __( 'Font Size', 'customify' ),
                        'device_settings' => true,
                    ),

                    array(
                        'name' => 'line_height',
                        'type' => 'slider',
                        'label' => __( 'Line Height', 'customify' ),
                        'device_settings' => true,
                    ),

                    array(
                        'name' => 'letter_spacing',
                        'type' => 'slider',
                        'label' => __( 'Letter Spacing', 'customify' ),
                        'min' => -10,
                        'max' => 10,
                    ),

                    array(
                        'name' => 'color',
                        'type' => 'color',
                        'label' => __( 'Color', 'customify' ),
                    ),

                )
            ),

            array(
                'name' => 'background',
                'type' => 'group',
                'section'     => 'customify_section',
                'title'          => __( 'Background', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'live_title_field' => 'title',
                'field_class' => 'customify-background-control',
                'selector' => '#page',
                'css_format' => 'background',
                'device_settings' => true,
                'default' => array(

                ),
                'fields' => array(
                    array(
                        'name' => 'color',
                        'type' => 'color',
                        'label' => __( 'Color', 'customify' ),
                        'device_settings' => true,
                    ),
                    array(
                        'name' => 'image',
                        'type' => 'image',
                        'label' => __( 'Image', 'customify' ),
                    ),
                    array(
                        'name' => 'cover',
                        'type' => 'checkbox',
                        'required' => array( 'image', 'not_empty', ''),
                        'label' => __( 'Background cover', 'customify' ),
                        'checkbox_label' => __( 'Background cover', 'customify' ),
                    ),
                    array(
                        'name' => 'position',
                        'type' => 'select',
                        'label' => __( 'Background Position', 'customify' ),
                        'required' => array( 'image', 'not_empty', ''),
                        'choices' => array(
                            'default'       => __( 'Position', 'customify' ),
                            'center'        => __( 'Center', 'customify' ),
                            'top_left'      => __( 'Top Left', 'customify' ),
                            'top_right'     => __( 'Top Right', 'customify' ),
                            'top_center'    => __( 'Top Center', 'customify' ),
                            'bottom_left'   => __( 'Bottom Left', 'customify' ),
                            'bottom_center' => __( 'Bottom Center', 'customify' ),
                            'bottom_right'  => __( 'Bottom Right', 'customify' ),
                        ),
                    ),

                    array(
                        'name' => 'repeat',
                        'type' => 'select',
                        'label' => __( 'Background Repeat', 'customify' ),
                        'required' => array(
                            array('image', 'not_empty', ''),
                            // array('style', '!=', 'cover' ),
                        ),
                        'choices' => array(
                            'default' => __( 'Repeat', 'customify' ),
                            'no-repeat' => __( 'No-repeat', 'customify' ),
                            'repeat-x' => __( 'Repeat Horizontal', 'customify' ),
                            'repeat-y' => __( 'Repeat Vertical', 'customify' ),
                        ),
                    ),

                    array(
                        'name' => 'attachment',
                        'type' => 'select',
                        'label' => __( 'Background Attachment', 'customify' ),
                        'required' => array(
                            array('image', 'not_empty', ''),
                            array('cover', '!=', '1' ),
                        ),
                        'choices' => array(
                            'default' => __( 'Attachment', 'customify' ),
                            'scroll' => __( 'Scroll', 'customify' ),
                            'fixed' => __( 'Fixed', 'customify' )
                        ),
                    ),

                )
            ),

            array(
                'name' => 'background_p',
                'type' => 'group',
                'section'     => 'customify_section',
                'title'          => __( 'Background P', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'live_title_field' => 'title',
                'field_class' => 'customify-background-control',
                'selector' => '#page p',
                'css_format' => 'background',
                'device_settings' => false,
                'default' => array(

                ),
                'fields' => array(
                    array(
                        'name' => 'color',
                        'type' => 'color',
                        'label' => __( 'Color', 'customify' ),
                        'device_settings' => true,
                    ),
                    array(
                        'name' => 'image',
                        'type' => 'image',
                        'label' => __( 'Image', 'customify' ),
                    ),
                    array(
                        'name' => 'cover',
                        'type' => 'checkbox',
                        'required' => array( 'image', 'not_empty', ''),
                        'label' => __( 'Background cover', 'customify' ),
                        'checkbox_label' => __( 'Background cover', 'customify' ),
                    ),
                    array(
                        'name' => 'position',
                        'type' => 'select',
                        'label' => __( 'Background Position', 'customify' ),
                        'required' => array( 'image', 'not_empty', ''),
                        'choices' => array(
                            'default' => __( 'Default', 'customify' ),
                            'center' => __( 'Center', 'customify' ),
                            'top_left' => __( 'Top Left', 'customify' ),
                            'top_right' => __( 'Top Right', 'customify' ),
                            'top_center' => __( 'Top Center', 'customify' ),
                            'bottom_left' => __( 'Bottom Left', 'customify' ),
                            'bottom_center' => __( 'Bottom Center', 'customify' ),
                            'bottom_right' => __( 'Bottom Right', 'customify' ),
                        ),
                    ),

                    array(
                        'name' => 'repeat',
                        'type' => 'select',
                        'label' => __( 'Background Repeat', 'customify' ),
                        'required' => array(
                            array('image', 'not_empty', ''),
                            // array('style', '!=', 'cover' ),
                        ),
                        'choices' => array(
                            'default' => __( 'Default', 'customify' ),
                            'no-repeat' => __( 'No-repeat', 'customify' ),
                            'repeat-x' => __( 'Repeat Horizontal', 'customify' ),
                            'repeat-y' => __( 'Repeat Vertical', 'customify' ),
                        ),
                    ),

                    array(
                        'name' => 'attachment',
                        'type' => 'select',
                        'label' => __( 'Background Attachment', 'customify' ),
                        'required' => array(
                            array('image', 'not_empty', ''),
                            array('cover', '!=', '1' ),
                        ),
                        'choices' => array(
                            'default' => __( 'Default', 'customify' ),
                            'scroll' => __( 'Scroll', 'customify' ),
                            'fixed' => __( 'Fixed', 'customify' )
                        ),
                    ),

                )
            ),

            array(
                'name' => 'select',
                'type' => 'select',
                //'device_settings' => true,
                'default' => '',
                'section'     => 'customify_section',
                //'priority' => 22,
                'title'          => __( 'Select', 'customify' ),
                'description'    => __( 'Select 2 to show Dependence field',  'customify' ),
                'choices' => array(
                    '1' => __( 'One', 'customify' ),
                    '2' => __( 'Two', 'customify' ),
                    '3' => __( 'Three', 'customify' ),
                )
            ),

            array(
                'name' => 'dependence_text',
                'type' => 'text',
                'device_settings' => true,
                'default'           => null,
                'transport'			=> 'postMessage', // or refresh
                'section' => 'customify_section',
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Dependence Text Field', 'customify' ),
                'description'   => __( 'Show only select=2',  'customify' ),
                'required'      => array( 'select','==','2' )
            ),



            array(
                'name' => 'text',
                'type' => 'text',
                //'device_settings' => true,
                //'sanitize_callback' => '',
                'default'           => null,
                'section' => 'customify_section',
                //'priority' => 22,
                'theme_supports' => '',
                'device' => 'mobile',
                'title'          => __( 'Text', 'customify' ),
                'description' => __( 'This is description', 'customify' ),


                'selector' => '._test_text1',
                'render_callback' => ''
            ),

            array(
                'name' => 'text2',
                'type' => 'text',
                'device_settings' => true,
                'sanitize_callback' => '',
                'default'           => null,
                'section' => 'customify_section',
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Text 2 Inside the Text', 'customify' ),
                'description' => __( 'This is description', 'customify' ),

                //'selector' => '._test_text_2',
                //'render_callback' => '_test_2_render_callback'
            ),

            array(
                'name' => 'slider',
                'type' => 'slider',
                'device_settings' => true,
                'default' => '',
                'min' => -10,
                'max' => 100,
                'section' => 'customify_section',
                //'device' => 'mobile', // mobile || general
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Slider', 'customify' ),
                'description' => __( 'This is description',  'customify' ),
                'selector' => 'h4',
                'css_format' => 'font-size: {{value}}'
            ),

            array(
                'name' => 'css_ruler',
                'device_settings' => true,
                'type'      => 'css_ruler',
                'default'           => null,
                'transport'			=> 'postMessage', // or refresh
                'section'           => 'customify_section',
                'theme_supports' => '',
                'title'          => __( 'CSS Ruler', 'customify' ),
                'description'   => __( 'This is description',  'customify' ),
                'selector' => 'h4',
                'css_format' => array(
                    'top'    => 'padding-top: {{value}}',
                    'right'  => 'padding-right: {{value}}',
                    'bottom' => 'padding-bottom: {{value}}',
                    'left'   => 'padding-left: {{value}}',
                )
            ),

            array(
                'name' => 'icon',
                'type' => 'icon',
                'device_settings' => true,
                'default' => '',
                'section' => 'customify_section',
                //'device' => 'mobile', // mobile || general
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Icon', 'customify' ),
                'description' => __( 'This is description',  'customify' ),
            ),

            array(
                'name' => 'textarea',
                'type' => 'textarea',
                'device_settings' => true,
                'default' => '',
                'section' => 'customify_section',
                //'device' => 'mobile', // mobile || general
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Textarea', 'customify' ),
                'description' => __( 'This is description',  'customify' ),
            ),

            array(
                'name'  => 'checkbox',
                'type'  => 'checkbox',
                'device_settings' => true,
                'label'       => __( 'Checkbox', 'customify' ),
                'section'     => 'customify_section',
                'description' => __( 'This is description',  'customify' ),
                'setting_type' => 'checkbox',
                'checkbox_label' => __( 'This is checkbox label',  'customify' ),
            ),

            array(
                'name' => 'radio',
                'type' => 'radio',
                'device_settings' => true,
                'default' => '',
                'section'     => 'customify_section',
                //'priority' => 22,
                'title'          => __( 'Radio', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'choices' => array(
                    '1' => __( 'One', 'customify' ),
                    '2' => __( 'Two', 'customify' ),
                    '3' => __( 'Three', 'customify' ),
                )
            ),


            array(
                'name' => 'color',
                'device_settings' => true,
                'type' => 'color',
                'default'           => '#f5f5f5',
                'transport'			=> 'postMessage', // or refresh
                'section'           => 'customify_section',
                'theme_supports' => '',
                'title'          => __( 'Color', 'customify' ),
                'description'   => __( 'This is description',  'customify' ),
                'selector' => 'h4',
                'css_format' => 'color: {{value}}'
            ),

            array(
                'name' => 'multiple_dependence_text',
                'type' => 'text',
                'device_settings' => true,
                'default'           => null,
                'transport'			=> 'postMessage', // or refresh
                'section' => 'customify_section',
                //'priority' => 22,
                'theme_supports' => '',
                'title'          => __( 'Multiple Dependence Text Field', 'customify' ),
                'description'   => __( 'Show only select=3 and radio=2',  'customify' ),
                'required'      => array(
                    array( 'select','==','3' ),
                    array( 'radio','==','2' )
                )
            ),

            array(
                'name' => 'repeater',
                'type' => 'repeater',
                'section'     => 'customify_section',
                //'priority' => 22,
                'title'          => __( 'Repeater', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'live_title_field' => 'title',
                'limit' => 4,
                'limit_msg' => __( 'Just limit 4 item, Ability HTML here',  'customify' ),
                'default' => array(
                    array(
                        'title' => __( 'Title 1', 'customify' ),
                        'content' => __( 'Content 1', 'customify' ),
                    ),
                    array(
                        'title' => __( 'Title 2', 'customify' ),
                        'content' => __( 'Content 2', 'customify' ),
                    )
                ),
                'fields' => array(
                    array(
                        'name' => 'title',
                        'type' => 'text',
                        'label' => __( 'Title', 'customify' ),
                    ),
                    array(
                        'name' => 'slider',
                        'type' => 'slider',
                        'device_settings' => true,
                        'label' => __( 'Slider', 'customify' ),
                    ),
                    array(
                        'name' => 'image',
                        'type' => 'image',
                        'label' => __( 'Image', 'customify' ),
                    ),
                    array(
                        'name' => 'select',
                        'type' => 'select',
                        'label' => __( 'Select', 'customify' ),
                        'description' => __( 'Select 2 to show text area', 'customify' ),
                        'choices' => array(
                            1 => 1,
                            2 => 2,
                            3=> 3
                        )
                    ),
                    array(
                        'name' => 'content',
                        'type' => 'textarea',
                        'label' => __( 'Textarea', 'customify' ),
                        'required' =>  array( 'select','==','2' )
                    ),
                )
            ),

            array(
                'name' => 'group',
                'type' => 'group',
                'section'     => 'customify_section',
                //'priority' => 22,
                'title'          => __( 'Group', 'customify' ),
                'description'    => __( 'This is description',  'customify' ),
                'live_title_field' => 'title',
                'default' => array(
                    'title' => __( 'Title 1', 'customify' ),
                    'content' => __( 'Content 1', 'customify' ),
                ),
                'fields' => array(
                    array(
                        'name' => 'title',
                        'type' => 'text',
                        'device_settings' => true,
                        'label' => __( 'Title', 'customify' ),
                    ),
                    array(
                        'name' => 'image',
                        'type' => 'image',
                        'label' => __( 'Image', 'customify' ),
                    ),
                    array(
                        'name' => 'select',
                        'type' => 'select',
                        'label' => __( 'Select', 'customify' ),
                        'description' => __( 'Select 2 to show text area', 'customify' ),
                        'choices' => array(
                            1 => 1,
                            2 => 2,
                            3=> 3
                        )
                    ),
                    array(
                        'name' => 'content',
                        'type' => 'textarea',
                        'label' => __( 'Textarea', 'customify' ),
                        'required' =>  array( 'select','==','2' )
                    ),


                )
            ),

            array(
                'name' => 'text_align',
                'type' => 'text_align',
                'device_settings' => true,
                'default'           => null,
                'transport'			=> 'postMessage', // or refresh
                'section' => 'customify_section',
                'theme_supports' => '',
                'title'          => __( 'Text Align', 'customify' ),
            ),




        );

        return array_merge( $configs, $config );
    }
}

add_filter( 'customify/customizer/config', 'customify_customizer_config' );