<?php
/**
* Plugin Name: Custom Order Status Per Product WooCommerce
* Description: This plugin allows create Custom Order Status Per Product WooCommerce plugin.
* Version: 1.0.1
* Copyright: 2020
* Text Domain: wc-custom-order-status-per-product
* Domain Path: /languages 
*/


if (!defined('ABSPATH')) {
    die('-1');
}
if (!defined('OCCOSPP_PLUGIN_NAME')) {
    define('OCCOSPP_PLUGIN_NAME', 'WC Custom Order Status Per Product');
}
if (!defined('OCCOSPP_PLUGIN_VERSION')) {
    define('OCCOSPP_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCCOSPP_PLUGIN_FILE')) {
    define('OCCOSPP_PLUGIN_FILE', __FILE__);
}
if (!defined('OCCOSPP_PLUGIN_DIR')) {
    define('OCCOSPP_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCCOSPP_DOMAIN')) {
    define('OCCOSPP_DOMAIN', 'wc-custom-order-status-per-product');
}
if (!defined('OCCOSPP_BASE_NAME')) {
    define('OCCOSPP_BASE_NAME', plugin_basename(OCCOSPP_PLUGIN_FILE));
}


if (!class_exists('OCCOSPP')) {

    class OCCOSPP {
        protected static $OCCOSPP_instance;
        function __construct() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            add_action('admin_init', array($this, 'OCCOSPP_check_plugin_state'));
        }


        function OCCOSPP_load_admin_script_style() {
            wp_enqueue_style( 'OCCOSPP_admin_css', OCCOSPP_PLUGIN_DIR . '/css/admin_style.css', false, '1.0.0' );
            wp_enqueue_script( 'OCCOSPP_admin_script', OCCOSPP_PLUGIN_DIR . '/js/back_script.js', false, '1.0.0' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker-alpha', OCCOSPP_PLUGIN_DIR . '/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '1.0.0', true );
        }


        function OCCOSPP_load_script_style() {
            wp_enqueue_style( 'OCCOSPP_front_css', OCCOSPP_PLUGIN_DIR . '/css/style.css', false, '1.0.0' );
           
            $translation_array_img = OCCOSPP_PLUGIN_DIR;
            wp_localize_script( 'OCCOSPP_front_js', 'object_front', array(
                            'ajax_url' => admin_url('admin-ajax.php'),
                            'object_name' => $translation_array_img,
            ) );   
        }




        function OCCOSPP_show_notice() {

            if ( get_transient( get_current_user_id() . 'occosppverror' ) ) {

                deactivate_plugins( plugin_basename( __FILE__ ) );

                delete_transient( get_current_user_id() . 'occospperror' );

                echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';

            }
        }


        function OCCOSPP_check_plugin_state(){
            if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
                set_transient( get_current_user_id() . 'occospperror', 'message' );
            }
        }



        function OCCOSPP_footer(){
            wp_enqueue_script( 'wc-add-to-cart-variation' );
            wp_enqueue_script('wc-single-product');
        }

        function OCCOSPP_plugin_row_meta( $links, $file ) {
            if (OCCOSPP_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  '<a href="https://oceanwebguru.com/custom-order-status-per-product-woocommerce/" target="_blank">Documentation</a> | <a href="https://oceanwebguru.com/contact-us/" target="_blank">Support</a> | <a href="https://wordpress.org/support/plugin/wc-custom-order-status-per-product/reviews/?filter=5" target="_blank"><img src="'.OCCOSPP_PLUGIN_DIR.'/images/star.png" class="OCCOSPP_rating_div"></a>',
                );

                return array_merge( $links, $row_meta );
            }

            return (array) $links;
        }


        function init() {
            add_action( 'admin_notices', array($this, 'OCCOSPP_show_notice'));
            add_action( 'admin_enqueue_scripts', array($this, 'OCCOSPP_load_admin_script_style'));
            add_action( 'wp_enqueue_scripts',  array($this, 'OCCOSPP_load_script_style'));
            add_filter( 'wp_footer', array( $this, 'OCCOSPP_footer' ), 10, 2 );
            add_filter( 'plugin_row_meta', array( $this, 'OCCOSPP_plugin_row_meta' ), 10, 2 );
        }
        


        function includes() {
            include_once('includes/oc-occospp-backend.php');
            include_once('includes/oc-occospp-kit.php');
            include_once('includes/oc-occospp-front.php');
        }


        public static function OCCOSPP_instance() {
            if (!isset(self::$OCCOSPP_instance)) {
                self::$OCCOSPP_instance = new self();
                self::$OCCOSPP_instance->init();
                self::$OCCOSPP_instance->includes();
            }
            return self::$OCCOSPP_instance;
        }
    }
    add_action('plugins_loaded', array('OCCOSPP', 'OCCOSPP_instance'));
}

add_action( 'plugins_loaded', 'OCCOSPP_load_textdomain' );
function OCCOSPP_load_textdomain() {
    load_plugin_textdomain( 'wc-custom-order-status-per-product', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_filter( 'load_textdomain_mofile', 'OCCOSPP_plugin_load_own_textdomain', 10, 2 );
function OCCOSPP_plugin_load_own_textdomain( $mofile, $domain ) {
    if ( 'wc-custom-order-status-per-product' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}