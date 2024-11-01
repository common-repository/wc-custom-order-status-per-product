<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCCOSPP_front')) {

    class OCCOSPP_front {

        protected static $instance;
        function OCCOSPP_order_status_front_side( $item_id, $item, $order, $flag = false ) {
            
            $occospp_status_enable = get_option('occospp_status_enable');
            
            if ( $occospp_status_enable === 'yes' ) {

                $item_status      = get_post_meta( $item_id, 'item_status', true );
                $item_status_date = get_post_meta( $item_id, 'item_status_date', true );

                $statuses_array = get_option( 'occospp_statuses' );

                // $status_color = '';
                $status_note = '';

                foreach ($statuses_array as $key => $value) {
                    if(in_array($item_status, $value)) {
                        // $status_color = $value['status_color'];
                        $status_note = $value['status_note'];
                    }
                }

                $status_color_style = 'style="color: #000000"';

                // if($status_color != '') {
                //    $status_color_style = 'style="color: '.$status_color.';"';
                // }

                if($item_status != '' && $item_status_date != '') {
                    
                    if(get_option('occospp_date_format') != '') {
                        $date_format = get_option('occospp_date_format');
                    } else {
                        $date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                    }
                    
                    $itm_status_dt = date( $date_format, $item_status_date);
                    $item_status_display = wpautop(get_option('occospp_stdis_format','Status: {status}'));

                    $itm_status_span = "<span ".$status_color_style.">".$item_status."</span>";
                    $item_status_display = str_replace('{status}', $itm_status_span, $item_status_display);
                    $item_status_display = str_replace('{date}', $itm_status_dt, $item_status_display);
                    $item_status_display = str_replace('{note}', $status_note, $item_status_display);
                ?>
                    <span class="occospp_status_span"><?php echo $item_status_display; ?></span>
                <?php
                }

            }

        }

      
        


        function init() {
            add_action( 'woocommerce_order_item_meta_end', array($this, 'OCCOSPP_order_status_front_side'), 10, 4 );
        }


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }
    }
    OCCOSPP_front::instance();
}