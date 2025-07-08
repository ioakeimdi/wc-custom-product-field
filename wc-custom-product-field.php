<?php
/*
	Plugin Name: Woocommerce Custom Product Field
	Description: Adds a custom product field in the product edit page and displays it on the single product page
	Version: 1.0.0
    Author: Ioakeim Diamantidis
    Requires at least: 6.2
    Requires PHP: 7.4
    WC requires at least: 9
*/

defined('ABSPATH') || exit;

// Init
require_once plugin_dir_path(__FILE__) . 'includes/class-wc-custom-product-field.php';

// Delete product custom field on plugin uninstall
register_uninstall_hook(__FILE__, array('WC_Custom_Product_Field', 'delete_custom_product_field'));
