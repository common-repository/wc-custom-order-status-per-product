<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCCOSPP_admin_menu')) {

    class OCCOSPP_admin_menu {

        protected static $OCCOSPP_instance;

        function OCCOSPP_submenu_page() {
           add_menu_page( 'WC Custom Order Status', 'WC Custom Order Status', 'manage_options', 'wc-custom-order-status', array($this, 'OCCOSPP_callback'));
        }

        function OCCOSPP_callback() {
            ?>    
                <div class="wrap">
                    <h2>WC Custom Order Status Settings</h2>
                </div>
                <div class="occospp-container">
                    <form method="post" >
                      <?php wp_nonce_field( 'occospp_nonce_action', 'occospp_nonce_field' ); ?>
                        <div id="wfc-tab-general" class="tab-content current">
                            <div class="occospp_cover_div">
                                <h2>Enable/Disable</h2>
                                Enable?
                                <input type="checkbox" name="occospp_enable" value="yes" <?php if(get_option('occospp_status_enable') == 'yes') { echo 'checked'; } ?>>
                            </div>
                            <div class="occospp_cover_div">
                                <h2>Date Format</h2>
                                <input type="text" name="occospp_date_format" value="<?php if(get_option('occospp_date_format') != '') {echo get_option('occospp_date_format'); } else { echo 'd-F-Y'; } ?>">
                                <div class="oc_date_format_help">
                                    <span class="description"><span class="ocfmhglgt">d</span> - The day of the month (from 01 to 31)</span>
                                    <span class="description"><span class="ocfmhglgt">D</span> - A textual representation of a day (three letters</span>
                                    <span class="description"><span class="ocfmhglgt">j</span> - The day of the month without leading zeros (1 to 31)</span>
                                    <span class="description"><span class="ocfmhglgt">l</span> - (lowercase 'L') - A full textual representation of a day</span>
                                    <span class="description"><span class="ocfmhglgt">m</span> - A numeric representation of a month (from 01 to 12)</span>
                                    <span class="description"><span class="ocfmhglgt">F</span> - A full textual representation of a month (January through December)</span>
                                    <span class="description"><span class="ocfmhglgt">M</span> - A short textual representation of a month (three letters)</span>
                                    <span class="description"><span class="ocfmhglgt">n</span> - A numeric representation of a month, without leading zeros (1 to 12)</span>
                                    <span class="description"><span class="ocfmhglgt">Y</span> - A four digit representation of a year</span>
                                    <span class="description"><span class="ocfmhglgt">y</span> - A two digit representation of a year</span>
                                </div>
                            </div>
                            <div class="occospp_cover_div">
                                <h2>Status Display Format</h2>
                                <textarea name="occospp_stdis_format" rows="4" cols="35" disabled=""><?php echo 'Status: {status}';  ?></textarea>
                                 <label class="ocfmhglgt_pro_link">Only available in pro version <a href="https://oceanwebguru.com/shop/custom-order-status-per-product-woocommerce/" target="_blank">link</a></label>
                                <div class="oc_date_format_help">
                                    <span class="description"><span class="ocfmhglgt">{status}</span> - to display status date</span>
                                    <span class="description"><span class="ocfmhglgt">{date}</span> - to display status update date</span>
                                    <span class="description"><span class="ocfmhglgt">{note}</span> - to display status note</span>
                                </div>

                            </div>
                            <div class="occospp_cover_div">
                              <h2>Statuses</h2>
                                <table class="occospp_status_table">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Status Color</th>
                                            <th>Status Note</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(!empty(get_option( 'occospp_statuses' ))) {
                                        $statuses_array = get_option( 'occospp_statuses' );
                                        
                                        foreach ($statuses_array as $key => $status) {
                                            ?>
                                            <tr>
                                            <td>
                                                <input type="text" name="status_name[]" value="<?php echo $status['status_name']; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="color-picker" data-alpha="true" name="status_color[]" />
                                                <label class="ocfmhglgt_pro_link">Only available in pro version <a href="https://oceanwebguru.com/shop/custom-order-status-per-product-woocommerce/" target="_blank">link</a></label>
                                            </td>
                                            <td>
                                                <textarea name="status_note[]" rows="3"><?php echo $status['status_note']; ?></textarea>
                                            </td>
                                            <td>
                                                <p class="occospp_remove_status">Remove Status</p>
                                            </td>
                                        </tr>
                                            <?php
                                            }    
                                    } else {
                                    ?>
                                        <tr>
                                            <td>
                                                <input type="text" name="status_name[]" >
                                            </td>
                                            <td>
                                                <input type="text" class="color-picker" data-alpha="true" name="status_color[]" >
                                                <label class="ocfmhglgt_pro_link">Only available in pro version <a href="https://oceanwebguru.com/shop/custom-order-status-per-product-woocommerce/" target="_blank">link</a></label>
                                            </td>
                                            <td>
                                                <textarea name="status_note[]" rows="3"></textarea>
                                            </td>
                                            <td>
                                                <p class="occospp_remove_status">Remove Status</p>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <a class="occospp_add_status">Add Status</a>
                            </div>
                          
                        <input type="hidden" name="action" value="occospp_save_option">
                        <input type="submit" value="Save changes" name="submit" class="button-primary" id="wfc-btn-space">
                    </form>  
                </div>
            <?php
        }

        function OCCOSPP_save_options(){
            if( current_user_can('administrator') ) { 
                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'occospp_save_option'){
                    if(!isset( $_POST['occospp_nonce_field'] ) || !wp_verify_nonce( $_POST['occospp_nonce_field'], 'occospp_nonce_action' )) 
                    {
                        echo 'Sorry, your nonce did not verify.';
                        exit;
                    }else {

                        if(!empty($_POST['status_name'])) {
                            $status_name = $this->recursive_sanitize_text_field($_POST['status_name']);
                            $status_color = $this->recursive_sanitize_text_field($_POST['status_color']);
                            $status_note = $this->recursive_sanitize_text_field($_POST['status_note']);
                            $statuses = array();

                            if(!empty($status_name)) {
                                foreach ($status_name as $key => $name) {
                                    if(!empty($name)) {
                                        $status['status_name'] = $name;
                                        $status['status_color'] = $status_color[$key];
                                        $status['status_note'] = $status_note[$key];
                                        
                                        $statuses[] = $status;
                                    }
                                }  
                            }

                            update_option('occospp_statuses', $statuses, 'yes');
                        } else {
							$statuses = array();
							update_option('occospp_statuses', $statuses, 'yes');                  	
                        }

                        

                        if(isset($_POST['occospp_enable'])) {
                            if($_POST['occospp_enable'] == 'yes') {
                                update_option('occospp_status_enable', 'yes', 'yes');
                            } else {
                                update_option('occospp_status_enable', 'no', 'yes');
                            }
                        } else {
                            update_option('occospp_status_enable', 'no', 'yes');
                        }
 
                        
                        if(isset($_POST['occospp_date_format'])) {
                            $occospp_date_format = sanitize_text_field($_POST['occospp_date_format']);
                            update_option('occospp_date_format', $occospp_date_format, 'yes');
                        }


                        if(isset($_POST['occospp_stdis_format'])) {
                            $occospp_stdis_format = sanitize_textarea_field($_POST['occospp_stdis_format']);
                            update_option('occospp_stdis_format', $occospp_stdis_format, 'yes');
                        }

                    }
                }
            }
        }

        function recursive_sanitize_text_field($array) {

            foreach ( $array as $key => $value ) {
                if ( is_array( $value ) ) {
                    $value = $this->recursive_sanitize_text_field($value);
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
            return $array;
        }

        function OCCOSPP_foot_script() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery(".occospp_add_status").click(function(){
                        jQuery(".occospp_status_table tbody").append('<tr><td><input type="text" name="status_name[]" ></td><td><input type="text" class="color-picker" data-alpha="true" name="status_color[]" value="" /><label class="ocfmhglgt_pro_link">Only available in pro version <a href="https://oceanwebguru.com/shop/custom-order-status-per-product-woocommerce/" target="_blank">link</a></label></td><td><textarea name="status_note[]" rows="3"></textarea></td><td><p class="occospp_remove_status">Remove Status</p></td></tr>');
                        jQuery('.color-picker').wpColorPicker();
                    });
                });
            </script>
            <?php
        }

        function OCCOSPP_set_status_per_item( $item_id, $item, $product ) {

            $occospp_status_enable = get_option('occospp_status_enable');
            
            if($occospp_status_enable == 'yes') {

                if(!empty(get_option( 'occospp_statuses' ))) {
                    $statuses_array = get_option( 'occospp_statuses' );
                    if(isset($statuses_array) && !empty($statuses_array)) {
                        if ( ! empty( $product ) && isset( $product ) ) {
                            $post_type = $product->post_type;
                            if ( ! empty( $post_type ) && ( $post_type === 'product' || $post_type === 'product_variation' ) ) {
                                $item_status = get_post_meta( $item_id, 'item_status', true );
                                ?>
                                <div class="wc-order-item-sku">
                                    <strong>Item Status:</strong>
                                    <select id="item_status_<?php echo $item_id; ?>" name="item_status_<?php echo $item_id; ?>">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($statuses_array as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['status_name']; ?>" <?php if($value['status_name'] == $item_status) { echo 'selected'; } ?>><?php echo $value['status_name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                        }
                    }
                }

            }

        }



        function OCCOSPP_save_order_status_backend( $post_id, $post, $update ) {
            $occospp_status_enable = get_option('occospp_status_enable');
            if ( $occospp_status_enable === 'yes' ) {
                if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                    return $post_id;
                }
                $oc_action    = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
                $oc_post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
                $order_item_id    = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

                if (isset( $order_item_id['order_item_id'] ) && ! empty( $order_item_id['order_item_id'] ) ) {
                    $order_item_id = $order_item_id['order_item_id'];
                } else {
                    $order_item_id = array();
                }

                if ( $oc_action === 'editpost' && $oc_post_type === 'shop_order' ) {
                    if ( isset( $order_item_id ) && is_array( $order_item_id ) ) {

                        foreach ( $order_item_id as $item_id ) {
                            $item_status     = get_post_meta( $item_id, 'item_status', true );
                            $item_statu_data = filter_input( INPUT_POST, 'item_status_' . $item_id, FILTER_SANITIZE_STRING );

                            if ( $item_status !== $item_statu_data ) {
                                update_post_meta( $item_id, 'item_status', $item_statu_data );
                                update_post_meta( $item_id, 'item_status_date', current_time( 'timestamp', 0 ) );
                            }
                        }
                    }
                }

            }
        }


        function OCCOSPP_support_and_rating_notice() {
            $screen = get_current_screen();
            if( 'wc-custom-order-status' == $screen->parent_base) {
                ?>
                <div class="occospp_mainnn_rantiong">
                    <div class="occospp_rateus_notice">
                        <div class="occospp_rtusnoti_left">
                            <h3>Rate Us</h3>
                            <label>If you like our plugin, </label>
                            <a target="_blank" href="https://wordpress.org/support/plugin/wc-custom-order-status-per-product/reviews/?filter=5">
                                <label>Please vote us</label>
                            </a>
                            <label>, so we can contribute more features for you.</label>
                        </div>
                        <div class="occospp_rtusnoti_right">
                            <img src="<?php echo OCCOSPP_PLUGIN_DIR;?>/images/review.png" class="occospp_review_icon">
                        </div>
                    </div>
                    <div class="occospp_support_notice">
                        <div class="occospp_rtusnoti_left">
                            <h3>Having Issues?</h3>
                            <label>You can contact us at</label>
                            <a target="_blank" href="https://oceanwebguru.com/contact-us/">
                                <label>Our Support Forum</label>
                            </a>
                        </div>
                        <div class="occospp_rtusnoti_right">
                            <img src="<?php echo OCCOSPP_PLUGIN_DIR;?>/images/support.png" class="occospp_review_icon">
                        </div>
                    </div>
                </div>
                <div class="occospp_donate_main">
                   <img src="<?php echo OCCOSPP_PLUGIN_DIR;?>/images/coffee.svg">
                   <h3>Buy me a Coffee !</h3>
                   <p>If you like this plugin, buy me a coffee and help support this plugin !</p>
                   <div class="occospp_donate_form">
                      <a class="button button-primary ocwg_donate_btn" href="https://www.paypal.com/paypalme/shayona163/" data-link="https://www.paypal.com/paypalme/shayona163/" target="_blank">Buy me a coffee !</a>
                   </div>
                </div>
                <?php
            }
        }
		

        function init() {
            add_action( 'admin_menu',  array($this, 'OCCOSPP_submenu_page'));
            add_action( 'init',  array($this, 'OCCOSPP_save_options'));
            add_action( 'admin_footer', array($this, 'OCCOSPP_foot_script') );
            add_action( 'woocommerce_after_order_itemmeta', array($this, 'OCCOSPP_set_status_per_item'), 3, 20 );
            add_action( 'save_post', array($this, 'OCCOSPP_save_order_status_backend'), 3, 20 );
            add_action( 'admin_notices', array($this, 'OCCOSPP_support_and_rating_notice' ));
        }


        public static function OCCOSPP_instance() {
            if (!isset(self::$OCCOSPP_instance)) {
                self::$OCCOSPP_instance = new self();
                self::$OCCOSPP_instance->init();
            }
            return self::$OCCOSPP_instance;
        }
    }
    OCCOSPP_admin_menu::OCCOSPP_instance();
}