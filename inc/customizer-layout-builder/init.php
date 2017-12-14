<?php

class Customify_Customizer_Builder_Panel {
    public $id = '';
    function __construct()
    {
        add_filter( 'customify/customizer/config', array( $this, '_customize' ) );
    }

    function get_rows_config(){ return array(); }
    function row_config(){ return array(); }
    function customize(){ return array(); }

    function get_items(){
        return Customify_Customizer_Layout_Builder()->get_builder_items( $this->id );
    }

    function _customize( $configs = array() ){
        if (! is_array( $configs ) ) {
            $configs = array();
        }
        $config = $this->customize();
        foreach( $this->get_rows_config() as $id => $name ) {

            $m = 'row_'.$id.'_config';
            if ( method_exists( $this, $m ) ) {
                $r = call_user_func_array( array( $this, $m ), array( $this->id.'_'.$id, $name ) );
                $config = array_merge( $config, $r );
            } else {
                if ( method_exists( $this, 'row_config' ) ) {
                    $config = array_merge( $config, $this->row_config( $this->id.'_'.$id, $name ) );
                }
            }
        }
        $items_config =  Customify_Customizer_Layout_Builder()->get_items_customize( $this->id ) ;
        if ( is_array( $items_config ) ) {
            $config = array_merge( $config,  $items_config );
        }

        return array_merge( $configs, $config );
    }
}


class Customify_Customizer_Layout_Builder {
    static $_instance;
    private $registered_items = array();
    private $registered_builders = array();
    function init(){
        $config_files = array(
            'header' => array(
                'panel',
                'html',
                'logo',
                'nav-icon',
                'primary-menu',
                'templates',
                'logo',
                'primary-menu',
                'nav-icon',
                'search',
                'button',
                'icon-list',
                'user',
                'social-icons',
                'languages',
            ),
            'footer' => array(
                'panel',
                'widgets',
                'templates'
            )
        );

        $path = get_template_directory();
        foreach( $config_files as $id => $files ) {
            foreach( $files as $f ) {
                $file =  $path."/inc/customizer-layout-builder/config/{$id}/{$f}.php";
                if ( file_exists( $file ) ) {
                    require_once $file;
                }
            }
        }

        do_action('customify/customize-builder/init' );

        if ( is_admin() ) {
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts' ) );
            add_action( 'customize_controls_print_footer_scripts', array( $this, 'template' ) );
        }
        add_action( 'wp_ajax_customify_builder_save_template', array( $this, 'ajax_save_template' ) );
    }

    function register_builder( $id, $class ){
        if ( ! isset( $id) ) {
            return false;
        }

        if( ! is_object( $class ) ) {
            if ( ! class_exists( $class ) ) {
                return false;
            }

            $class = new $class();
        }

        if ( ! $class instanceof Customify_Customizer_Builder_Panel ) {
            $name = get_class( $class );
            _doing_it_wrong( $name,  sprintf( __( 'Class <strong>%s</strong> do not extends class <strong>Customify_Customizer_Builder_Panel</strong>.', 'customify' ), $name ) ,'1.0.0' );
            return false;
        }

        $this->registered_builders[ $id ] = $class;
    }

    function register_item( $builder_id, $class ){
        if ( ! $builder_id ) {
            return false;
        }

        if ( is_object( $class ) ) {

        } else {
            if ( ! class_exists( $class ) ) {
                return false;
            }
            $class = new $class();
        }

        if ( ! isset( $this->registered_items[ $builder_id ] ) ) {
            $this->registered_items[ $builder_id ]  = array();
        }

        $this->registered_items[ $builder_id ][ $class->id ]  = $class;
        return true;

    }

    function get_builder_items( $builder_id ) {
        if ( ! $builder_id ) {
            return  apply_filters( 'customify/builder/'.$builder_id.'/items', array() );
        }
        if ( ! isset( $this->registered_items[ $builder_id ] ) ) {
            return  apply_filters( 'customify/builder/'.$builder_id.'/items', array() );
        }
        $items = array();
        foreach( $this->registered_items[ $builder_id ] as $name => $obj ) {
            if ( method_exists( $obj, 'item' ) ) {
                $item =  $obj->item();
                $items[ $item['id'] ] = $item;
            }
        }
        $items = apply_filters( 'customify/builder/'.$builder_id.'/items', $items );
        return $items;
    }

    function get_items_customize( $builder_id ){
        if ( ! $builder_id ) {
            return false;
        }
        if ( ! isset( $this->registered_items[ $builder_id ] ) ) {
            return false;
        }
        $items = array();
        foreach( $this->registered_items[ $builder_id ] as $name => $obj ) {
            if ( method_exists( $obj, 'customize' ) ) {
                $item =  $obj->customize();
                if ( is_array( $item ) ) {
                    $items = array_merge( $items, $item );
                }

            }
        }
        return $items;
    }

    function get_builder_item( $builder_id, $item_id ){
        if ( ! $builder_id ) {
            return false;
        }
        if ( ! isset( $this->registered_items[ $builder_id ] ) ) {
            return false;
        }

        if ( ! isset( $this->registered_items[ $builder_id ][ $item_id ] ) ) {
            return false;
        }

        return $this->registered_items[ $builder_id ][ $item_id ];
    }

    function ajax_save_template(){

        if ( ! current_user_can('edit_theme_options' ) ) {
            wp_send_json_error( __( 'Access denied', 'customify' ) );
        }

        $id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        $control = isset( $_POST['control'] ) ? sanitize_text_field( $_POST['control'] ) : '';
        $save_name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        if ( ! $save_name ) {
            $save_name = sprintf( __( 'Saved %s' ), date_i18n( 'Y-m-d H:i:s' ) );
        }
        $data = isset( $_POST['preview_data'] ) ? wp_unslash( $_POST['preview_data'] ) : array();
        $fn = false;
        if ( ! isset( $this->registered_builders[ $id ] ) ) {
            wp_send_json_error( __( 'No Support', 'customify' ) );
        } else {
            $fn = array( $this->registered_builders[ $id ] , '_customize' );
        }

        $theme_name = wp_get_theme()->get('Name');
        $option_name = $theme_name.'_saved_templates';

        $saved_templates = get_option( $option_name );
        if ( ! is_array( $saved_templates ) ) {
            $saved_templates = array();
        }

        if ( isset( $_POST['remove'] ) ) {
            $remove = sanitize_text_field( $_POST['remove'] );
            if ( isset( $saved_templates[ $remove ] ) ) {
                unset( $saved_templates[ $remove ] );
            }

            update_option( $option_name, $saved_templates );
            wp_send_json_success();
        }

        $config = call_user_func_array( $fn, array() );
        $new_template_data = array();

        foreach ( $config as $k => $field ) {
            if ( $field['type'] != 'panel' && $field['type'] != 'section' ) {
                $name = $field['name'];
                $value = isset( $data[ $name ] ) ? $data[ $name ] : '';

                if ( ! is_array( $value ) ) {
                    $value = json_decode( urldecode_deep( $value ), true );
                }

                $s = new Customify_Sanitize_Input( $field, $field );

                $value = $s->sanitize( $value, $field );
                $new_template_data[ $name ] = $value;
            }
        }


        if ( ! $save_name ) {
            $key_id = date_i18n( 'Y-m-d H:i:s', current_time('timestamp') );
            $save_name = sprintf( __( 'Saved %s', 'customify' ), $key_id );
        } else {
            $key_id = $save_name;
        }

        $saved_templates[ $key_id ] = array(
            'name' => $save_name,
            'image' => '',
            'data' => $new_template_data
        );

        update_option( $option_name, $saved_templates );
        $html = '<li class="saved_template" data-control-id="'.esc_attr( $control ).'" data-id="'.esc_attr( $key_id ).'" data-data="'.esc_attr( json_encode( $new_template_data ) ).'">'.esc_html( $save_name ).' <a href="#" class="load-tpl">'.__( 'Load', 'customify' ).'</a><a href="#" class="remove-tpl">'.__( 'Remove', 'customify' ).'</a></li>';
        wp_send_json_success( array( 'key_id' => $key_id, 'name' => $save_name, 'li' => $html ) );
        die();
    }

    function get_builders(){
        $builders = array();
        foreach( $this->registered_builders as $id => $builder ) {
            $config = $builder->get_config();
            $config['items'] = $this->get_builder_items( $id );
            $config['rows'] = $builder->get_rows_config();
            $builders[ $id ] = $config;
        }
        return $builders;
    }

    function scripts(){
        wp_enqueue_script( 'customify-layout-builder', get_template_directory_uri() . '/assets/js/customizer/builder.js', array( 'customize-controls', 'jquery-ui-resizable', 'jquery-ui-droppable', 'jquery-ui-draggable' ), false, true );
        wp_localize_script( 'customify-layout-builder',  'Customify_Layout_Builder',  array(
            'footer_moved_widgets_text' => __( 'Footer widgets moved', 'customify' ),
            'builders' => $this->get_builders(),
        ) );
    }

    static function get_instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance ;
    }

    function template(){
        ?>
        <script type="text/html" id="tmpl-customify--builder-panel">
            <div class="customify--customize-builder">
                <div class="customify--cb-inner">
                    <div class="customify--cb-header">
                        <div class="customify--cb-devices-switcher">
                        </div>
                        <div class="customify--cb-actions">
                            <a data-id="{{ data.id }}_templates" class="focus-section button button-secondary" href="#"><?php _e( 'Templates', 'customify' ); ?></a>
                            <a class="button button-secondary customify--panel-close" href="#">
                                <span class="close-text"><?php _e( 'Close', 'customify' ); ?></span>
                                <span class="panel-name-text">{{ data.title }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="customify--cb-body"></div>
                </div>
            </div>
        </script>


        <script type="text/html" id="tmpl-customify--cb-panel">
            <div class="customify--cp-rows">

                <# if ( ! _.isUndefined( data.rows.top ) ) { #>
                <div class="customify--row-top customify--cb-row" data-id="{{ data.id }}_top" >
                    <a class="customify--cb-row-settings" data-id="top"  href="#"></a>
                    <div class="customify--row-inner">
                        <div class="row--grid">
                            <?php for( $i = 1; $i<= 12; $i ++ ){
                                echo '<div></div>';
                            } ?>
                        </div>
                        <div class="customify--cb-items grid-stack gridster" data-id="top"></div>
                    </div>
                </div>
                <#  } #>

                <# if ( ! _.isUndefined( data.rows.main ) ) { #>
                <div class="customify--row-main customify--cb-row" data-id="{{ data.id }}_main" >
                    <a class="customify--cb-row-settings" data-id="main" href="#"></a>

                    <div class="customify--row-inner">
                        <div class="row--grid">
                            <?php for( $i = 1; $i<= 12; $i ++ ){
                                echo '<div></div>';
                            } ?>
                        </div>
                        <div class="customify--cb-items grid-stack gridster" data-id="main"></div>
                    </div>
                </div>
                <#  } #>


                <# if ( ! _.isUndefined( data.rows.bottom ) ) { #>
                <div class="customify--row-bottom customify--cb-row" data-id="{{ data.id }}_bottom" >
                    <a class="customify--cb-row-settings" data-id="bottom" href="#"></a>

                    <div class="customify--row-inner">
                        <div class="row--grid">
                            <?php for( $i = 1; $i<= 12; $i ++ ){
                                echo '<div></div>';
                            } ?>
                        </div>
                        <div class="customify--cb-items grid-stack gridster" data-id="bottom"></div>
                    </div>
                </div>
                <#  } #>

            </div>


            <# if ( data.device != 'desktop' ) { #>
                <# if ( ! _.isUndefined( data.rows.sidebar ) ) { #>
            <div class="customify--cp-sidebar">
                <div class="customify--row-bottom customify--cb-row" data-id="{{ data.id }}_sidebar">
                    <a class="customify--cb-row-settings" data-id="sidebar" href="#"></a>
                    <div class="customify--row-inner">
                        <div class="customify--cb-items customify--sidebar-items" data-id="sidebar"></div>
                    </div>
                </div>
            <div>
                <# } #>
            <# } #>

        </script>

        <script type="text/html" id="tmpl-customify--cb-item">
            <div class="grid-stack-item item-from-list for-s-{{ data.section }}"
                 title="{{ data.name }}"
                 data-id="{{ data.id }}"
                 data-section="{{ data.section }}"
                 data-control="{{ data.control }}"
                 data-gs-x="{{ data.x }}"
                 data-gs-y="{{ data.y }}"
                 data-gs-width="{{ data.width }}"
                 data-gs-height="1"
            >
                <div class="item-tooltip">{{ data.name }}</div>
                <div class="grid-stack-item-content">
                    <span class="customify--cb-item-name" data-section="{{ data.section }}">{{ data.name }}</span>
                    <span class="customify--cb-item-remove customify-cb-icon"></span>
                    <span class="customify--cb-item-setting customify-cb-icon" data-section="{{ data.section }}"></span>
                </div>
            </div>
        </script>
        <?php
    }

}

function Customify_Customizer_Layout_Builder(){
    return Customify_Customizer_Layout_Builder::get_instance();
}
Customify_Customizer_Layout_Builder()->init();



class Customify_Customizer_Layout_Builder_Frontend {
     static $_instance;
     private $control_id = 'header_builder_panel';
     private $id = 'header';
     private $render_items = array();
     private $rows = array();
     private $data = false;
     private $config_items = false;

     public function __construct()
     {

     }

     static function get_instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance ;
    }

    function set_id( $id ) {
         $this->id = $id;
        $this->data = null;
    }

    function set_control_id( $id ) {
        $this->control_id = $id;
        $this->data = null;
    }

    function set_config_items( $config_items ){
        $this->config_items = $config_items;
    }

     function get_settings(){
         if ( $this->data ) {
             return $this->data;
         }
         $data = get_theme_mod( $this->control_id );
         $data = wp_parse_args( $data, array(
             'desktop' => '',
             'tablet' => '',
             'mobile' => '',
         ) );

         foreach ( $data as $k => $v ) {
             if ( ! is_array( $v ) ) {
                 $v = array();
             }
             $data[ $k ] = $v;
         }

         $this->data = $data;

         return $data;
     }

     function get_row_settings( $row_id, $device = 'desktop' ){
        $data = $this->get_settings();
        if ( isset( $data[ $device ] ) ) {
            if ( isset( $data[ $device ][ $row_id ] ) ) {
                return ! empty( $data[ $device ][ $row_id ] ) ? $data[ $device ][ $row_id ] : false;
            }
        }
        return false;
     }

     function render_items( $list_items = array() ){
         $setting = $this->get_settings();
         $items = array();

         foreach ( $setting as $device => $device_settings ) {
            foreach ( $device_settings as $row_id => $row_items ) {
                if ( ! isset( $this->rows[ $row_id ] ) ) {
                    $this->rows[ $row_id ] = array();
                }

                if ( is_array( $row_items ) && count( $row_items ) ) {

                    $this->rows[ $row_id ][ $device ] = $device;

                    foreach ( $row_items as $item_index => $item ) {

                        $item = wp_parse_args( $item, array(
                            'x' => '',
                            'width' => '1',
                            'id' => '',
                        ) );

                        $item_config = isset( $this->config_items[ $item['id'] ] ) ? $this->config_items[ $item['id'] ] : array();

                        if ( ! isset( $items[ $item['id'] ] ) ) {
                            $items[ $item['id'] ] = array(
                                'render_content' => '',
                                'devices' => array(),
                                'rows' => array(),
                                'id' => $item['id']
                            );
                        }

                        if ( ! $items[ $item['id'] ] ['render_content'] ) {
                            ob_start();
                            $has_cb = false;
                            $object_item = Customify_Customizer_Layout_Builder()->get_builder_item( $this->id, $item['id'] );
                            // Call render in registered class
                            if ( $object_item ) {
                                if (  method_exists( $object_item,'render' ) ) {
                                    $return_render = call_user_func_array( array( $object_item, 'render' ), array( $item_config , $item));
                                    $has_cb = true;
                                }
                            }

                            // Call render by function if class do not exists
                            if ( ! $has_cb ) {
                                $id = str_replace('-', '_', $item['id']);
                                $fn = 'customify_builder_' . $id . '_item';
                                $return_render = false;
                                if (function_exists($fn)) {
                                    $return_render = call_user_func_array($fn, array($item_config, $item));
                                    $has_cb = true;
                                } else {
                                    $fn = 'customify_builder_' . $this->id . '_' . $id . '_item';
                                    if (function_exists($fn)) {
                                        $return_render = call_user_func_array($fn, array($item_config, $item));
                                        $has_cb = true;
                                    }
                                }
                            }

                            if ( ! $has_cb ) {
                                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                                    printf( __( 'Callback function <strong>%s</strong> do not exists.', 'customify' ), $fn );
                                }
                            }

                            $ob_render = ob_get_clean();

                            if ( ! $return_render ) {
                                if ( $ob_render ) {
                                    $return_render = $ob_render;
                                }
                            }

                            if ( $return_render ) {
                                $items[ $item['id'] ] ['render_content'] = $return_render;
                            }
                        }

                        $items[ $item['id'] ]['added'] = false;

                        $items[ $item['id'] ]['devices'][ $device ] = array(
                                'x' => $item['x'],
                                'width' => $item['width'],
                                'id' => $item['id'],
                                'row' => $row_id,

                        );
                        if( isset( $items[ $item['id'] ]['rows'][ $row_id ] ) ) {
                            $items[ $item['id'] ]['rows'][ $row_id ] = array(
                                $items[ $item['id'] ]['rows'][ $row_id ]
                            );

                            $items[ $item['id'] ]['rows'][ $row_id ][] = $device;

                        } else {
                            $items[ $item['id'] ]['rows'][ $row_id ] = $device;
                        }


                    }
                }

            }
         }

         $this->render_items = $items;

         return $items;
     }

    /**
     * Sort items by their position on the grid.
     * @access  private
     * @since   1.0.0
     * @return  array
     */
    private function _sort_items_by_position( $items = array() ) {
        $ordered_items = array();

        foreach ( $items as $key => $item ) {
            $ordered_items[ $key ] = $item['x'];
        }

        array_multisort( $ordered_items, SORT_ASC, $items );

        return $items;
    }

     function render_row( $items, $id = '', $device = 'desktop' ){
         $row_html     = '';
         $max_columns  = 12;
         $items = $this->_sort_items_by_position( $items );
         $last_item = false;
         $next_item = false;

         foreach ( $items as $index => $item ) {
             $content = $this->render_items[$item['id']]['render_content'];
             if ( isset( $items[ $index + 1 ] ) ) {
                 $next_item = $items[ $index + 1 ];
             } else {
                 $next_item = false;
             }

             $item_id = $item['id'];
             $x = intval( $item['x'] );
             $width = intval($item['width']);
             if ( ! $next_item ) {
                 if ( $x + $width < $max_columns ) {
                     $width += $max_columns - ( $x + $width );
                 }
             }
             $atts = array();
             $classes = array();
             if ( $this->id != 'footer' ) {
                 $classes[] = "customify-col-{$width}_md-{$width}_sm-{$width}";
             } else {
                 $classes[] = "customify-col-{$width}_md-{$width}_sm-12";
             }


             if ( $x > 0 ) {
                 if ( ! $last_item ) {
                     $atts[] = 'off-' . $x;
                 } else {
                    $o = intval( $last_item['width'] ) + intval( $last_item['x'] );
                    if ( $x - $o  > 0 ) {
                        $atts[] = 'off-' . ( $x - $o );
                    }
                 }
             }

             if ( $this->id == 'footer' ) {
                 $atts[] = '_sm-0';
             }


             $last_item = $item;

             $item_config = isset( $this->config_items[ $item_id ] ) ? $this->config_items[ $item_id ] : array();
             $classes[] = 'builder-item';
             $classes[] = 'builder-item--'.$item_id;

             if( is_customize_preview() ) {
                 $classes[] = ' builder-item-focus';
             }

             $classes = apply_filters('customify/builder/item-classes', $classes, $item, $item_config );

             $classes = join(' ', $classes ); // customify-grid-middle

             $content = str_replace( '__id__', $id, $content );
             $content = str_replace( '__device__', $device, $content );

             if( ! isset( $item_config['section'] ) ) {
                 $item_config['section'] = '';
             }
             
             echo '<div class="'.esc_attr( $classes ).'" data-section="'.$item_config['section'].'" data-item-id="' . esc_attr($item_id) . '" data-push-left="' . join(' ', $atts) . '">';
                echo '<div class="item--inner">';
                echo $content;

                 if( is_customize_preview() ) {
                     echo '<span class="item--preview-name">'.esc_html( $item_config['name'] ).'</span>';
                 }

                echo '</div>';
             echo '</div>';
         }
     }

     function render( $row_ids = array( 'top', 'main', 'bottom') ){
         $setting = $this->get_settings();
         $items = $this->render_items( );
         foreach ($row_ids as $row_id ) {
            if ( isset( $this->rows[ $row_id ] ) ) {
                $show_on_devices = $this->rows[$row_id];
                if (!empty($show_on_devices)) {
                    $classes = array();
                    $_id = sprintf('%1$s-%2$s', $this->id, $row_id);

                    $classes[] = $_id;
                    $classes[] = $this->id.'--row';
                    $desktop_items = $this->get_row_settings($row_id, 'desktop');
                    $mobile_items = $this->get_row_settings($row_id, 'mobile');
                    if ( ! empty( $desktop_items ) || ! empty( $mobile_items ) ) {

                        if ($this->id != 'footer') {
                            if (empty($desktop_items)) {
                                $classes[] = 'hide-on-desktop';
                            }
                            if (empty($mobile_items)) {
                                $classes[] = 'hide-on-mobile hide-on-tablet';
                            }
                        }

                        $row_layout = Customify_Customizer()->get_setting($this->id . '_' . $row_id . '_layout');
                        if ($row_layout) {
                            $classes[] = 'layout-' . sanitize_text_field( $row_layout );
                        }

                        $align_classes = 'customify-grid-top';
                        if ($this->id == 'header') {
                            $is_sticky = Customify_Customizer()->get_setting($this->id . '_' . $row_id . '_sticky');
                            if (absint($is_sticky) == 1) {
                                $classes[] = 'is-sticky';
                            }

                            $align_classes = 'customify-grid-middle';
                        }

                        $classes = apply_filters('customify/builder/row-classes', $classes, $row_id, $this );

                        ?>
                        <div id="cb-row--<?php echo esc_attr($_id); ?>" class="<?php echo esc_attr(join(' ', $classes)); ?>" data-row-id="<?php echo esc_attr($row_id); ?>"
                             data-show-on="<?php echo esc_attr(join(" ", $show_on_devices)); ?>">
                            <div class="customify-container">
                                <?php
                                if ($desktop_items) {
                                    $c = 'cb-row--desktop hide-on-mobile hide-on-tablet';
                                    if (empty($mobile_items)) {
                                        $c = '';
                                    }
                                    echo '<div class="customify-grid ' . esc_attr($c . ' ' . $align_classes) . '">';
                                    $this->render_row($desktop_items, $row_id, 'desktop');
                                    echo '</div>';
                                }

                                if ($mobile_items) {
                                    echo '<div class="cb-row--mobile hide-on-desktop customify-grid ' . esc_attr($align_classes) . '">';
                                    $this->render_row($mobile_items, $row_id, 'mobile');
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
            }

         } // end for each row_ids
     }

     function render_sidebar(){
         $id = 'sidebar';
         $mobile_items = $this->get_row_settings( $id, 'mobile');
         if ( ! is_array( $mobile_items ) ) {
             $mobile_items= array();
         }
         if ( ! empty( $mobile_items ) || is_customize_preview() ) {
             echo '<div id="mobile-header-panel" class="mobile-header-panel mobile-sidebar-panel">';
                 echo '<div id="mobile-header-panel-inner" class="mobile-header-panel-inner">';
                     echo $this->close_icon('close-panel' );
                     foreach( $mobile_items as $item ) {
                         $item_id = $item['id'];
                         $content = $this->render_items[$item['id']]['render_content'];
                         $item_config = isset( $this->config_items[ $item_id ] ) ? $this->config_items[ $item_id ] : array();

                         $classes = "builder-item-sidebar mobile-item--".$item_id;
                         if( is_customize_preview() ) {
                             $classes = 'builder-item-focus '.$classes;
                         }

                         $content = str_replace( '__id__', $id, $content );
                         $content = str_replace( '__device__', 'mobile', $content );
                         if ( ! isset( $item_config['section'] ) ) {
                             $item_config['section'] = '';
                         }

                         echo '<div class="'.esc_attr( $classes ).'" data-section="'.$item_config['section'].'" data-item-id="' . esc_attr($item_id) . '">';
                         echo '<div class="item--inner">';
                             echo $content;
                             if( is_customize_preview() ) {
                                 echo '<span class="item--preview-name">'.esc_html( $item_config['name'] ).'</span>';
                             }
                         echo '</div>';
                         echo '</div>';
                     }
                 echo '</div>';
             echo '</div>';
         }
     }

      function close_icon( $class = '' ){
         $close = '<a class="close '.esc_attr( $class ).'" href="#"><span class="screen-reader-text">'.__( 'Close', 'customify' ).'</span></a>';
         return $close;
    }
}


function Customify_Customizer_Layout_Builder_Frontend(){
    return Customify_Customizer_Layout_Builder_Frontend::get_instance();
}


/**
 * Display Header Layout
 */
function customify_customize_render_header(){

    echo '<header id="masthead" class="site-header">';
    if ( is_customize_preview() ) {
        ?>
        <span class="customize-partial-edit-shortcut customize-partial-edit-shortcut-header_panel"><button class="customize-partial-edit-shortcut-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path></svg></button></span>
        <?php
    }
    $list_items = Customify_Customizer_Layout_Builder()->get_builder_items( 'header' );
    Customify_Customizer_Layout_Builder_Frontend()->set_config_items( $list_items );
    Customify_Customizer_Layout_Builder_Frontend()->render();
    Customify_Customizer_Layout_Builder_Frontend()->render_sidebar();

    /*
    $theme_name = wp_get_theme()->get('Name');
    $option_name = $theme_name.'_saved_templates';
    ?>
    <pre class="debug"><?php // print_r( $b->render_items()  ); ?></pre>
    <pre class="debug"><?php print_r( get_theme_mod( 'header_builder_panel' ) ); ?></pre>
    <?php
    */
    echo '</header>';
}

/**
 * Display Footer Layout)
 */
function customify_customize_render_footer(){
    echo '<footer class="site-footer" id="site-footer">';
    Customify_Customizer_Layout_Builder_Frontend()->set_id( 'footer' );
    Customify_Customizer_Layout_Builder_Frontend()->set_control_id( 'footer_builder_panel' );
    $list_items = Customify_Customizer_Layout_Builder()->get_builder_items( 'footer' ) ;
    Customify_Customizer_Layout_Builder_Frontend()->set_config_items( $list_items );
    Customify_Customizer_Layout_Builder_Frontend()->render();
    echo '</footer>';
}