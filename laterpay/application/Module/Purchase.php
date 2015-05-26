<?php

/**
 * LaterPay Purchase class
 *
 * Plugin Name: LaterPay
 * Plugin URI: https://github.com/laterpay/laterpay-wordpress-plugin
 * Author URI: https://laterpay.net/
 */
class LaterPay_Module_Purchase extends LaterPay_Core_View implements LaterPay_Core_Event_SubscriberInterface {

    /**
     * @see LaterPay_Core_Event_SubscriberInterface::get_subscribed_events()
     */
    public static function get_subscribed_events() {
        return array(
            'laterpay_purchase_button' => array(
                array( 'on_purchase_button' ),
                array( 'is_purchasable', 100 ),
            ),
            'laterpay_check_user_access' => array(
                array( 'check_user_access' ),
            ),
        );
    }

    /**
     * Renders LaterPay purchase button
     *
     * @param LaterPay_Core_Event $event
     */
    public function on_purchase_button( LaterPay_Core_Event $event ) {
        if ( $event->has_argument( 'post' ) ) {
            $post = $event->get_argument( 'post' );
        } else {
            $post = get_post();
        }
        $view_args = array_merge( array(
                'post_id'                         => $post->ID,
                'link'                            => LaterPay_Helper_Post::get_laterpay_purchase_link( $post->ID ),
                'currency'                        => get_option( 'laterpay_currency' ),
                'price'                           => LaterPay_Helper_Pricing::get_post_price( $post->ID ),
                'purchase_button_is_hidden'       => LaterPay_Helper_View::purchase_button_is_hidden(),
                'attributes'                      => array(),
            ),
            $event->get_arguments()
        );

        //TODO: #612 add logger call
        $this->assign( 'laterpay', $view_args );
        $html = $this->get_text_view( 'frontend/partials/widget/purchase-button' );

        $event->set_result( $html )
            ->set_echo( true )
            ->set_arguments( $view_args );
    }

    /**
     * Check if user has access to the post
     *
     * @wp-hook laterpay_check_user_access
     * @param LaterPay_Core_Event $event
     *
     * @return bool $has_access
     */
    public function check_user_access( LaterPay_Core_Event $event ) {
        list( $has_access, $post_id ) = $event->get_arguments();
        $event->set_result( false );
        // get post
        if ( ! isset( $post_id ) ) {
            $post = get_post();
        } else {
            $post = get_post( $post_id );
        }

        if ( $post === null ) {
            $event->set_result( (bool) $has_access );
            return;
        }

        $user_has_unlimited_access = LaterPay_Helper_User::can( 'laterpay_has_full_access_to_content', $post );

        // user has unlimited access
        if ( $user_has_unlimited_access ) {
            $event->set_result( true );
            return;
        }

        // user has access to the post
        if ( LaterPay_Helper_Post::has_access_to_post( $post ) ) {
            $event->set_result( true );
            return;
        }

    }

    /**
     * Stops bubbling if content is not purchasable
     *
     * @param LaterPay_Core_Event $event
     */
    public function is_purchasable( LaterPay_Core_Event $event ) {
        if ( ! LaterPay_Helper_Pricing::is_purchasable() ) {
            $event->stop_propagation();
        }
    }
}
