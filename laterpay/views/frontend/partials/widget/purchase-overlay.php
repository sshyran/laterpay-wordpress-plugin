<?php
if ( ! defined( 'ABSPATH' ) ) {
    // prevent direct access to this file
    exit;
}
?>

<div class="lp_paid-content">
    <div class="lp_full-content">
        <!-- <?php echo laterpay_sanitize_output( __( 'Preview a short excerpt from the paid post:', 'laterpay' ) ); ?> -->
        <?php echo laterpay_sanitized( LaterPay_Helper_String::truncate( $laterpay['content'], LaterPay_Helper_String::determine_number_of_words( $laterpay['content'] ), array( 'html' => true, 'words' => true, ) ) ); ?>
        <br>
        <?php echo laterpay_sanitize_output( __( 'Thanks for reading this short excerpt from the paid post! Fancy buying it to read all of it?', 'laterpay' ) ); ?>
    </div>

    <?php $overlay_content = $laterpay['overlay_content']; ?>
    <div class="lp_overlay-text">
        <div class="lp_benefits">
            <header class="lp_benefits__header">
                <h2 class="lp_benefits__title">
                    <?php echo laterpay_sanitize_output( $overlay_content['title'] ); ?>
                </h2>
            </header>
            <ul class="lp_benefits__list">
                <?php foreach ( $overlay_content['benefits'] as $benefit ) : ?>
                    <li class="lp_benefits__list-item <?php echo esc_attr( $benefit['class'] ); ?>">
                        <h3 class="lp_benefit__title">
                            <?php echo laterpay_sanitize_output( $benefit['title'] ); ?>
                        </h3>
                        <p class="lp_benefit__text">
                            <?php echo laterpay_sanitize_output( $benefit['text'] ); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="lp_benefits__action">
                <?php echo laterpay_sanitized( $overlay_content['action'] ); ?>
            </div>
            <div class="lp_powered-by">
                powered by<span data-icon="a"></span>beta
            </div>
        </div>
    </div>

</div>
