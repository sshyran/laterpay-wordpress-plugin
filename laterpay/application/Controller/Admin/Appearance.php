<?php

/**
 * LaterPay appearance controller.
 *
 * Plugin Name: LaterPay
 * Plugin URI: https://github.com/laterpay/laterpay-wordpress-plugin
 * Author URI: https://laterpay.net/
 */
class LaterPay_Controller_Admin_Appearance extends LaterPay_Controller_Admin_Base {

    /**
     * @see LaterPay_Core_Event_SubscriberInterface::get_subscribed_events()
     */
    public static function get_subscribed_events() {
        return array(
            'wp_ajax_laterpay_appearance' => array(
                array( 'laterpay_on_admin_view', 200 ),
                array( 'laterpay_on_ajax_send_json', 300 ),
                array( 'process_ajax_requests' ),
                array( 'laterpay_on_ajax_user_can_activate_plugins', 200 ),
            ),
            'laterpay_admin_enqueue_scripts' => array(
                array( 'laterpay_on_admin_view', 200 ),
                array( 'laterpay_on_plugin_is_active', 200 ),
                array( 'add_custom_styles' )
            ),
        );
    }

    /**
     * Add appearance styles
     */
    public function add_custom_styles() {
        // apply colors config
        LaterPay_Helper_Appearance::add_overlay_styles( 'laterpay-admin' );
    }

    /**
     * @see LaterPay_Core_View::load_assets()
     */
    public function load_assets() {
        parent::load_assets();

        // load page-specific JS
        wp_register_script(
            'laterpay-backend-appearance',
            $this->config->js_url . '/laterpay-backend-appearance.js',
            array( 'jquery' ),
            $this->config->version,
            true
        );
        wp_enqueue_script( 'laterpay-backend-appearance' );

        wp_localize_script(
            'laterpay-backend-appearance',
            'lpVars',
            array(
                'overlaySettings'  => json_encode(
                    array(
                        'default' => LaterPay_Helper_Appearance::get_default_options(),
                        'current' => LaterPay_Helper_Appearance::get_current_options()
                    )
                ),
                'l10n_print_after' => 'lpVars.overlaySettings = JSON.parse(lpVars.overlaySettings)',
            )
        );
    }

    /**
     * @see LaterPay_Core_View::render_page()
     */
    public function render_page() {
        $this->load_assets();

        $menu = LaterPay_Helper_View::get_admin_menu();

        $view_args = array(
            'plugin_is_in_live_mode'              => $this->config->get( 'is_in_live_mode' ),
            'teaser_mode'                         => get_option( 'laterpay_teaser_mode', '2' ),
            'top_nav'                             => $this->get_menu(),
            'admin_menu'                          => add_query_arg( array( 'page' => $menu['account']['url'] ), admin_url( 'admin.php' ) ),
            'is_rating_enabled'                   => $this->config->get( 'ratings_enabled' ),
            'purchase_button_positioned_manually' => get_option( 'laterpay_purchase_button_positioned_manually' ),
            'time_passes_positioned_manually'     => get_option( 'laterpay_time_passes_positioned_manually' ),
            'hide_free_posts'                     => get_option( 'laterpay_hide_free_posts' ),
            'overlay'                             => LaterPay_Helper_Appearance::get_current_options(),
        );

        $this->assign( 'laterpay', $view_args );

        $this->render( 'backend/appearance' );
    }

    /**
     * Process Ajax requests from appearance tab.
     *
     * @param LaterPay_Core_Event $event
     * @throws LaterPay_Core_Exception_InvalidIncomingData
     * @throws LaterPay_Core_Exception_FormValidation
     *
     * @return void
     */
    public static function process_ajax_requests( LaterPay_Core_Event $event ) {
        $event->set_result(
            array(
                'success' => false,
                'message' => __( 'An error occurred when trying to save your settings. Please try again.', 'laterpay' )
            )
        );

        if ( ! isset( $_POST['form'] ) ) {
            // invalid request
            throw new LaterPay_Core_Exception_InvalidIncomingData( 'form' );
        }

        if ( function_exists( 'check_admin_referer' ) ) {
            check_admin_referer( 'laterpay_form' );
        }

        switch ( $_POST['form'] ) {
            // update presentation mode for paid content
            case 'paid_content_preview':
                $paid_content_preview_form = new LaterPay_Form_PaidContentPreview();

                if ( ! $paid_content_preview_form->is_valid( $_POST ) ) {
                    throw new LaterPay_Core_Exception_FormValidation( get_class( $paid_content_preview_form ), $paid_content_preview_form->get_errors() );
                }

                $result = update_option( 'laterpay_teaser_mode', $paid_content_preview_form->get_field_value( 'paid_content_preview' ) );

                if ( $result ) {
                    switch ( get_option( 'laterpay_teaser_mode' ) ) {
                        case '1':
                            $message = __( 'Visitors will now see the teaser content of paid posts plus an excerpt of the real content under an overlay.', 'laterpay' );
                            break;
                        case '2':
                            $message = __( 'Visitors will now see the teaser content of paid posts plus an excerpt of the real content under an overlay with all purchase options.', 'laterpay' );
                            break;
                        default:
                            $message = __( 'Visitors will now see only the teaser content of paid posts.', 'laterpay' );
                            break;
                    }

                    $event->set_result(
                        array(
                            'success' => true,
                            'message' => $message
                        )
                    );
                    return;
                }
                break;

            case 'overlay_settings':

                // handle additional settings save if present in request
                update_option( 'laterpay_overlay_header_title',      $_POST['header_title'] );
                update_option( 'laterpay_overlay_header_bg_color',   $_POST['header_background_color'] );
                update_option( 'laterpay_overlay_main_bg_color',     $_POST['background_color'] );
                update_option( 'laterpay_overlay_main_text_color',   $_POST['main_text_color'] );
                update_option( 'laterpay_overlay_description_color', $_POST['description_text_color'] );
                update_option( 'laterpay_overlay_button_bg_color',   $_POST['button_background_color'] );
                update_option( 'laterpay_overlay_button_text_color', $_POST['button_text_color'] );
                update_option( 'laterpay_overlay_link_main_color',   $_POST['link_main_color'] );
                update_option( 'laterpay_overlay_link_hover_color',  $_POST['link_hover_color'] );
                update_option( 'laterpay_overlay_show_footer',       (int) isset($_POST['show_footer']) );
                update_option( 'laterpay_overlay_footer_bg_color',   $_POST['footer_background_color'] );

                $event->set_result(
                    array(
                        'success' => true,
                        'message' => __( 'Purchase overlay settings saved successfully.', 'laterpay' )
                    )
                );

                break;

            // update rating functionality (on / off) for purchased items
            case 'ratings':
                $ratings_form = new LaterPay_Form_Rating();

                if ( ! $ratings_form->is_valid( $_POST ) ) {
                    throw new LaterPay_Core_Exception_FormValidation( get_class( $ratings_form ), $ratings_form->get_errors() );
                }

                $result = update_option( 'laterpay_ratings', ! ! $ratings_form->get_field_value( 'enable_ratings' ) );

                if ( $result ) {
                    if ( get_option( 'laterpay_ratings' ) ) {
                        $event->set_result(
                            array(
                                'success' => true,
                                'message' => __( 'Visitors can now rate the posts they have purchased.', 'laterpay' ),
                            )
                        );
                        return;
                    }

                    $event->set_result(
                        array(
                            'success' => true,
                            'message' => __( 'The rating of posts has been disabled.', 'laterpay' ),
                        )
                    );
                    return;
                }
                break;

            case 'purchase_button_position':
                $purchase_button_position_form = new LaterPay_Form_PurchaseButtonPosition( $_POST );

                if ( ! $purchase_button_position_form->is_valid() ) {
                    throw new LaterPay_Core_Exception_FormValidation( get_class( $purchase_button_position_form ), $purchase_button_position_form->get_errors() );
                }

                $result = update_option( 'laterpay_purchase_button_positioned_manually', ! ! $purchase_button_position_form->get_field_value( 'purchase_button_positioned_manually' ) );

                if ( $result ) {
                    if ( get_option( 'laterpay_purchase_button_positioned_manually' ) ) {
                        $event->set_result(
                            array(
                                'success' => true,
                                'message' => __( 'Purchase buttons are now rendered at a custom position.', 'laterpay' ),
                            )
                        );
                        return;
                    }

                    $event->set_result(
                        array(
                            'success' => true,
                            'message' => __( 'Purchase buttons are now rendered at their default position.', 'laterpay' ),
                        )
                    );
                    return;
                }
                break;

            case 'time_passes_position':
                $time_passes_position_form = new LaterPay_Form_TimePassPosition( $_POST );

                if ( ! $time_passes_position_form->is_valid() ) {
                    throw new LaterPay_Core_Exception_FormValidation( get_class( $time_passes_position_form ), $time_passes_position_form->get_errors() );
                }

                $result = update_option( 'laterpay_time_passes_positioned_manually', ! ! $time_passes_position_form->get_field_value( 'time_passes_positioned_manually' ) );

                if ( $result ) {
                    if ( get_option( 'laterpay_time_passes_positioned_manually' ) ) {
                        $event->set_result(
                            array(
                                'success' => true,
                                'message' => __( 'Time passes are now rendered at a custom position.', 'laterpay' ),
                            )
                        );
                        return;
                    }

                    $event->set_result(
                        array(
                            'success' => true,
                            'message' => __( 'Time passes are now rendered at their default position.', 'laterpay' ),
                        )
                    );
                    return;
                }
                break;

            case 'free_posts_visibility':
                $hide_free_posts_form = new LaterPay_Form_HideFreePosts( $_POST );

                if ( ! $hide_free_posts_form->is_valid() ) {
                    throw new LaterPay_Core_Exception_FormValidation( get_class( $hide_free_posts_form ), $hide_free_posts_form->get_errors() );
                }

                $result = update_option( 'laterpay_hide_free_posts', ! ! $hide_free_posts_form->get_field_value( 'hide_free_posts' ) );

                if ( $result ) {
                    if ( get_option( 'laterpay_hide_free_posts' ) ) {
                        $event->set_result(
                            array(
                                'success' => true,
                                'message' => __( 'Free posts with premium content now hided from the homepage.', 'laterpay' ),
                            )
                        );
                        return;
                    }

                    $event->set_result(
                        array(
                            'success' => true,
                            'message' => __( 'Free posts with premium content now hided from the homepage.', 'laterpay' ),
                        )
                    );
                    return;
                }
                break;

            default:
                break;
        }
    }

    /**
     * Render overlay
     *
     * @return string
     */
    public function render_overlay() {

        $config = laterpay_get_plugin_config();

        $additional_data = array(
            'currency' => $config->get( 'currency.code' ),
            'icons'    => $config->get_section( 'payment.icons' )
        );

        $this->assign( 'overlay', array_merge( LaterPay_Helper_Appearance::get_current_options(), $additional_data ) );

        return $this->get_text_view( 'backend/partials/purchase-overlay' );
    }
}
