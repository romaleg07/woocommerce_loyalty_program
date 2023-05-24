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

<?php 
    if(!empty($_POST)) {
        if(isset($_POST['woocommerce_loyalty_plugin_enabled'])) {
            update_option( 'woocommerce_loyalty_plugin_enabled', true );
        } else {
            update_option( 'woocommerce_loyalty_plugin_enabled', false );
        }
        if(isset($_POST['woocommerce_loyalty_send_statistic_to_sendpulse'])) {
            update_option( 'woocommerce_loyalty_send_statistic_to_sendpulse', true );
        } else {
            update_option( 'woocommerce_loyalty_send_statistic_to_sendpulse', false );
        }

        if(isset($_POST['woocommerce_loyalty_program_api_url'])) {
            $new_woocommerce_loyalty_program_api_url = trim(($_POST['woocommerce_loyalty_program_api_url']));
            update_option( 'woocommerce_loyalty_program_api_url', $new_woocommerce_loyalty_program_api_url );
        } else {
            update_option( 'woocommerce_loyalty_program_api_url', '' );
        }
        if(isset($_POST['woocommerce_loyalty_grant_type'])) {
            $new_woocommerce_loyalty_grant_type = trim(($_POST['woocommerce_loyalty_grant_type']));
            update_option( 'woocommerce_loyalty_grant_type', $new_woocommerce_loyalty_grant_type );
        } else {
            update_option( 'woocommerce_loyalty_grant_type', '' );
        }
        if(isset($_POST['woocommerce_loyalty_client_id'])) {
            $new_woocommerce_loyalty_client_id = trim(($_POST['woocommerce_loyalty_client_id']));
            update_option( 'woocommerce_loyalty_client_id', $new_woocommerce_loyalty_client_id );
        } else {
            update_option( 'woocommerce_loyalty_client_id', '' );
        }
        if(isset($_POST['woocommerce_loyalty_client_secret'])) {
            $new_woocommerce_loyalty_client_secret = trim(($_POST['woocommerce_loyalty_client_secret']));
            update_option( 'woocommerce_loyalty_client_secret', $new_woocommerce_loyalty_client_secret );
        } else {
            update_option( 'woocommerce_loyalty_client_secret', '' );
        }
        if(isset($_POST['woocommerce_loyalty_default_percent_sale'])) {
            $new_woocommerce_loyalty_default_percent_sale = trim(($_POST['woocommerce_loyalty_default_percent_sale']));
            update_option( 'woocommerce_loyalty_default_percent_sale', $new_woocommerce_loyalty_default_percent_sale );
        } else {
            update_option( 'woocommerce_loyalty_default_percent_sale', '' );
        }
        if(isset($_POST['woocommerce_loyalty_id_address_book'])) {
            $new_woocommerce_loyalty_id_address_book = trim(($_POST['woocommerce_loyalty_id_address_book']));
            update_option( 'woocommerce_loyalty_id_address_book', $new_woocommerce_loyalty_id_address_book );
        } else {
            update_option( 'woocommerce_loyalty_id_address_book', '' );
        }
        
    }
?>


<div class="loyalty-program-wrapper-admin wrap">
    <h1 class="wp-heading-inline"><?php echo __( 'Settings', 'woocommerce-loyalty-program' ); ?></h1>
    <div class="settings-loyalty-wrapper">
        <div class="item-setting">
            <form action="#" method="post">
                <p class="form-row form-checkbox">
                    <label>
                        <input type="checkbox" class="checkbox" name="woocommerce_loyalty_plugin_enabled" id="woocommerce_loyalty_plugin_enabled" <?php if(get_option( 'woocommerce_loyalty_plugin_enabled' )) echo 'checked';?>> <span><?php echo __( 'Enable', 'woocommerce-loyalty-program' );?></span>
                    </label>
                </p>

                <p class="form-row form-row-last">
                    <label for="woocommerce_loyalty_program_api_url"><?php echo __( 'API URL', 'woocommerce-loyalty-program' );?></label>
                    <input type="text" class="input-text" name="woocommerce_loyalty_program_api_url" id="woocommerce_loyalty_program_api_url" value="<?php echo get_option( 'woocommerce_loyalty_program_api_url' );?>">
                </p>
                <p class="form-row form-row-last">
                    <label for="woocommerce_loyalty_grant_type"><?php echo __( 'Gradt Type', 'woocommerce-loyalty-program' );?></label>
                    <input type="text" class="input-text" name="woocommerce_loyalty_grant_type" id="woocommerce_loyalty_grant_type" value="<?php echo get_option( 'woocommerce_loyalty_grant_type' );?>">
                </p>
                <p class="form-row form-row-last">
                    <label for="woocommerce_loyalty_client_id"><?php echo __( 'Client ID', 'woocommerce-loyalty-program' );?></label>
                    <input type="text" class="input-text" name="woocommerce_loyalty_client_id" id="woocommerce_loyalty_client_id" value="<?php echo get_option( 'woocommerce_loyalty_client_id' );?>">
                </p>
                <p class="form-row form-row-last">
                    <label for="woocommerce_loyalty_client_secret"><?php echo __( 'Client Secret', 'woocommerce-loyalty-program' );?></label>
                    <input type="text" class="input-text" name="woocommerce_loyalty_client_secret" id="woocommerce_loyalty_client_secret" value="<?php echo get_option( 'woocommerce_loyalty_client_secret' );?>">
                </p>
                <p class="form-row form-row-last">
                    <label for="woocommerce_loyalty_default_percent_sale"><?php echo __( 'Default discount percentage', 'woocommerce-loyalty-program' );?></label>
                    <input type="text" class="input-text" name="woocommerce_loyalty_default_percent_sale" id="woocommerce_loyalty_default_percent_sale" value="<?php echo get_option( 'woocommerce_loyalty_default_percent_sale' );?>">
                </p>
                <p class="form-row form-row-last">
                    <label for="woocommerce_loyalty_id_address_book"><?php echo __( 'Id address book in SendPulse', 'woocommerce-loyalty-program' );?></label>
                    <input type="text" class="input-text" name="woocommerce_loyalty_id_address_book" id="woocommerce_loyalty_id_address_book" value="<?php echo get_option( 'woocommerce_loyalty_id_address_book' );?>">
                </p>

                <p class="form-row form-checkbox">
                    <label>
                        <input type="checkbox" class="checkbox" name="woocommerce_loyalty_send_statistic_to_sendpulse" id="woocommerce_loyalty_send_statistic_to_sendpulse" <?php if(get_option( 'woocommerce_loyalty_send_statistic_to_sendpulse' )) echo 'checked';?>> <span><?php echo __( 'Send statistic to SendPulse', 'woocommerce-loyalty-program' );?></span>
                    </label>
                </p>
            
                <p class="form-row form-checkbox">
                    <input type="submit" value="Save settings" />
                </p>
            </form>
           
        </div>
    </div>
</div>
