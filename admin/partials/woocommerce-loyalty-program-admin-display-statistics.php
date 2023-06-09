<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Woocommerce_Loyalty_Program
 * @subpackage Woocommerce_Loyalty_Program/admin/partials
 */
?>

<div class="loyalty-program-wrapper-admin wrap">
    <h1 class="wp-heading-inline"><?php echo __( 'Statistic', 'woocommerce-loyalty-program' ); ?></h1>
</div>

<div class="statistic-wrapper loading">
    <div class="loader">
    <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="100px" height="100px" viewBox="-1.5 0 19 19" class="cf-icon-svg"><path d="M5.857 3.882v3.341a1.03 1.03 0 0 1-2.058 0v-.97a5.401 5.401 0 0 0-1.032 2.27 1.03 1.03 0 1 1-2.02-.395A7.462 7.462 0 0 1 2.235 4.91h-.748a1.03 1.03 0 1 1 0-2.058h3.34a1.03 1.03 0 0 1 1.03 1.03zm-3.25 9.237a1.028 1.028 0 0 1-1.358-.523 7.497 7.497 0 0 1-.37-1.036 1.03 1.03 0 1 1 1.983-.55 5.474 5.474 0 0 0 .269.751 1.029 1.029 0 0 1-.524 1.358zm2.905 2.439a1.028 1.028 0 0 1-1.42.322 7.522 7.522 0 0 1-.885-.652 1.03 1.03 0 0 1 1.34-1.563 5.435 5.435 0 0 0 .643.473 1.03 1.03 0 0 1 .322 1.42zm3.68.438a1.03 1.03 0 0 1-1.014 1.044h-.106a7.488 7.488 0 0 1-.811-.044 1.03 1.03 0 0 1 .224-2.046 5.41 5.41 0 0 0 .664.031h.014a1.03 1.03 0 0 1 1.03 1.015zm.034-12.847a1.03 1.03 0 0 1-1.029 1.01h-.033a1.03 1.03 0 0 1 .017-2.06h.017l.019.001a1.03 1.03 0 0 1 1.009 1.05zm3.236 11.25a1.029 1.029 0 0 1-.3 1.425 7.477 7.477 0 0 1-.797.453 1.03 1.03 0 1 1-.905-1.849 5.479 5.479 0 0 0 .578-.328 1.03 1.03 0 0 1 1.424.3zM10.475 3.504a1.029 1.029 0 0 1 1.41-.359l.018.011a1.03 1.03 0 1 1-1.06 1.764l-.01-.006a1.029 1.029 0 0 1-.358-1.41zm4.26 9.445a7.5 7.5 0 0 1-.315.56 1.03 1.03 0 1 1-1.749-1.086 5.01 5.01 0 0 0 .228-.405 1.03 1.03 0 1 1 1.836.93zm-1.959-6.052a1.03 1.03 0 0 1 1.79-1.016l.008.013a1.03 1.03 0 1 1-1.79 1.017zm2.764 2.487a9.327 9.327 0 0 1 0 .366 1.03 1.03 0 0 1-1.029 1.005h-.025A1.03 1.03 0 0 1 13.482 9.7a4.625 4.625 0 0 0 0-.266 1.03 1.03 0 0 1 1.003-1.055h.026a1.03 1.03 0 0 1 1.029 1.004z"/></svg>
    </div>
    <div class="top-statistic">
        <h3><?php echo __( 'General statistics', 'woocommerce-loyalty-program' ); ?></h3>
        <div class="content">
            <div class="nav">
                <ul>
                    <li><a href="#" class="statistic-period" data-period="all"><?php echo __( 'All period', 'woocommerce-loyalty-program' ); ?></a></li>
                    <li><a href="#" class="statistic-period" data-period="month"><?php echo __( 'Month', 'woocommerce-loyalty-program' ); ?></a></li>
                    <li><a href="#" class="statistic-period" data-period="week"><?php echo __( 'Week', 'woocommerce-loyalty-program' ); ?></a></li>
                </ul>
            </div>
            <div class="item">
                <span><?php echo __( 'total users', 'woocommerce-loyalty-program' ); ?>: </span><span id="all_users_count"></span>
            </div>
            <div class="item">
                <span><?php echo __( 'New users', 'woocommerce-loyalty-program' ); ?>: </span><span id="all_users_count_new"></span>
            </div>
            <div class="item">
                <span><?php echo __( 'All coupons', 'woocommerce-loyalty-program' ); ?>: </span><span id="all_coupon_count"></span>
            </div>
            <div class="item">
                <span><?php echo __( 'Activated coupons', 'woocommerce-loyalty-program' ); ?>: </span><span id="activated_coupons_count"></span>
            </div>
        </div>
    </div>
    <div class="body-statistic">
        <div class="pagination">
            <input type="number" id="users_page_loyalty" value="1">
            <a href="#" id="load_page">Load</a>
            <span><?php echo __( 'total pages', 'woocommerce-loyalty-program' ); ?>: <span id="pages">1</span></span>
        </div>
        <table id="users-with-coupons">
            <thead>
                <tr>
                    <th><?php echo __( 'User', 'woocommerce-loyalty-program' ); ?></th>
                    <th><?php echo __( 'Total Dates', 'woocommerce-loyalty-program' ); ?></th>
                    <th><?php echo __( 'Number of activated promo codes', 'woocommerce-loyalty-program' ); ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="bottom-statistic"></div>
</div>