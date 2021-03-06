<?php
if ( ! defined( 'ABSPATH' ) ) {
    // prevent direct access to this file
    exit;
}
?>

<div class="lp_paid-content">
    <div class="lp_full-content">
        <!-- <?php echo laterpay_sanitize_output( __( 'Preview a short excerpt from the paid post:', 'laterpay' ) ); ?> -->
        <?php echo laterpay_sanitized( $overlay['overlay_content'] ); ?>
        <br>
        <?php echo laterpay_sanitize_output( __( 'Thanks for reading this short excerpt from the paid post! Fancy buying it to read all of it?', 'laterpay' ) ); ?>
    </div>

    <?php
        $overlay_data = $overlay['data'];
        $input_id = 1;
    ?>
    <div class="lp_js_purchaseOverlay lp_purchase-overlay">
        <div class="lp_purchase-overlay__wrapper">
            <div class="lp_purchase-overlay__form">
                <section class="lp_purchase-overlay__header">
                    <?php echo laterpay_sanitize_output( $overlay['title'] ); ?>
                </section>
                <section class="lp_purchase-overlay__body">
                    <div class="lp_purchase-overlay__settings">
                        <?php if ( isset( $overlay_data['article'] ) ) : ?>
                        <div class="lp_purchase-overlay-option<?php if ( empty( $overlay_data['subscriptions']) && empty( $overlay_data['timepasses'] )):?> lp_purchase-overlay-option-single<?php endif;?>"
                             data-revenue="<?php echo $overlay_data['article']['revenue'];?>">
                            <div class="lp_purchase-overlay-option__button">
                                <input id="lp_purchaseOverlayOptionInput<?php echo $input_id; ?>" type="radio"
                                       class="lp_purchase-overlay-option__input" value="<?php echo laterpay_sanitize_output( $overlay_data['article']['url'] ); ?>"
                                       name="lp_purchase-overlay-option" checked>
                                <label for="lp_purchaseOverlayOptionInput<?php echo $input_id++; ?>" class="lp_purchase-overlay-option__label"></label>
                            </div>
                            <div class="lp_purchase-overlay-option__name">
                                <div class="lp_purchase-overlay-option__title">
                                    <?php echo laterpay_sanitize_output( __( 'This article', 'laterpay' ) ); ?>
                                </div>
                                <div class="lp_purchase-overlay-option__description">
                                    <?php echo laterpay_sanitize_output( $overlay_data['article']['title'] ); ?>
                                </div>
                            </div>
                            <div class="lp_purchase-overlay-option__cost">
                                <div class="lp_purchase-overlay-option__price">
                                    <?php echo laterpay_sanitize_output( $overlay_data['article']['price'] ); ?>
                                </div>
                                <div class="lp_purchase-overlay-option__currency">
                                    <?php echo laterpay_sanitize_output( $overlay['currency'] ); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ( isset( $overlay_data['timepasses'] ) ) : ?>
                            <?php foreach ( $overlay_data['timepasses'] as $timepass ) : ?>
                                <div class="lp_purchase-overlay-option lp_js_timePass"
                                     data-pass-id="<?php echo laterpay_sanitize_output( $timepass['id'] ); ?>"
                                     data-revenue="<?php echo $timepass['revenue'];?>">
                                    <div class="lp_purchase-overlay-option__button">
                                        <input id="lp_purchaseOverlayOptionInput<?php echo $input_id; ?>" type="radio" 
                                               class="lp_purchase-overlay-option__input" value="<?php echo laterpay_sanitize_output( $timepass['url'] ); ?>" 
                                               name="lp_purchase-overlay-option">
                                        <label for="lp_purchaseOverlayOptionInput<?php echo $input_id++; ?>" class="lp_purchase-overlay-option__label"></label>
                                    </div>
                                    <div class="lp_purchase-overlay-option__name">
                                        <div class="lp_purchase-overlay-option__title">
                                            <?php echo laterpay_sanitize_output( $timepass['title'] ); ?>
                                        </div>
                                        <div class="lp_purchase-overlay-option__description">
                                            <?php echo laterpay_sanitize_output( $timepass['description'] ); ?>
                                        </div>
                                    </div>
                                    <div class="lp_purchase-overlay-option__cost">
                                        <div class="lp_purchase-overlay-option__price lp_js_timePassPrice">
                                            <?php echo laterpay_sanitize_output( $timepass['price'] ); ?>
                                        </div>
                                        <div class="lp_purchase-overlay-option__currency">
                                            <?php echo laterpay_sanitize_output( $overlay['currency'] ); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ( isset( $overlay_data['subscriptions'] ) ) : ?>
                            <?php foreach ( $overlay_data['subscriptions'] as $subscription ) : ?>
                                <div class="lp_purchase-overlay-option" data-revenue="<?php echo $subscription['revenue'];?>">
                                    <div class="lp_purchase-overlay-option__button">
                                        <input id="lp_purchaseOverlayOptionInput<?php echo $input_id; ?>" type="radio"
                                               class="lp_purchase-overlay-option__input" value="<?php echo laterpay_sanitize_output( $subscription['url'] ); ?>" name="lp_purchase-overlay-option">
                                        <label for="lp_purchaseOverlayOptionInput<?php echo $input_id++; ?>" class="lp_purchase-overlay-option__label"></label>
                                    </div>
                                    <div class="lp_purchase-overlay-option__name">
                                        <div class="lp_purchase-overlay-option__title">
                                            <?php echo laterpay_sanitize_output( $subscription['title'] ); ?>
                                        </div>
                                        <div class="lp_purchase-overlay-option__description">
                                            <?php echo laterpay_sanitize_output( $subscription['description'] ); ?>
                                        </div>
                                    </div>
                                    <div class="lp_purchase-overlay-option__cost">
                                        <div class="lp_purchase-overlay-option__price">
                                            <?php echo laterpay_sanitize_output( $subscription['price'] ); ?>
                                        </div>
                                        <div class="lp_purchase-overlay-option__currency">
                                            <?php echo laterpay_sanitize_output( $overlay['currency'] ); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="lp_purchase-overlay__voucher lp_hidden">
                        <div>
                            <input type="text" class="lp_purchase-overlay__voucher-input lp_js_voucherCodeInput" placeholder="<?php echo laterpay_sanitize_output( __('Enter Voucher Code', 'laterpay' ) ); ?>">
                        </div>
                            <div class="lp_purchase-overlay__message-container lp_js_purchaseOverlayMessageContainer"></div>
                    </div>
                    <div class="lp_purchase-overlay__buttons">
                        <div>
                            <a class="lp_js_overlayPurchase lp_purchase-overlay__submit" data-purchase-action="buy"
                               data-preview-post-as-visitor="<?php echo laterpay_sanitize_output( $overlay['is_preview'] ); ?>" href="#">
                                <span data-icon="b"></span>
                                <span data-buy-label="true" class="lp_purchase-overlay__submit-text"><?php echo laterpay_sanitize_output( $overlay['submit_text'] ); ?></span>
                                <span data-voucher-label="true" class="lp_hidden"><?php echo laterpay_sanitize_output( __( 'Redeem Voucher Code', 'laterpay' ) ); ?></span>
                            </a>
                        </div>
                        <div class="lp_purchase-overlay__notification">
                            <div class="lp_js_notificationButtons">
                                <a href="<?php echo laterpay_sanitize_output( $overlay['identify_url'] ); ?>"><?php echo laterpay_sanitize_output( $overlay['notification_text'] ); ?></a> | <a href="#" class="lp_js_redeemVoucher"><?php echo laterpay_sanitize_output( __( 'Redeem voucher', 'laterpay' ) ); ?></a>
                            </div>
                            <div class="lp_js_notificationCancel lp_hidden">
                                <a href="#" class="lp_js_voucherCancel"><?php echo laterpay_sanitize_output( __( 'Cancel', 'laterpay' ) ); ?></a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="lp_purchase-overlay__footer" <?php if ( $overlay['footer'] !== '1' ) { echo 'style="display:none;"'; } ?>>
                    <ul class="lp_purchase-overlay-payments-list">
                        <?php foreach ( $overlay['icons'] as $icon ) : ?>
                            <li class="lp_purchase-overlay-payments-item">
                                <i class="lp_purchase-overlay-icon lp_purchase-overlay-icon-<?php echo $icon; ?>"></i>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            </div>
            <div class="lp_purchase-overlay__copy">
                <?php echo laterpay_sanitize_output( __( 'Powered by', 'laterpay' ) ); ?>
                <span data-icon="a"></span>
            </div>
        </div>
    </div>
</div>
