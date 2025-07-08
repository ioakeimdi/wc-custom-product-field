<?php
defined('ABSPATH') || exit;

class WC_Custom_Product_Field
{
    const FIELD_META = '_alternative_code';

    protected $field_label;

    /**
     * Constructor to initialize the plugin
     */
    public function __construct()
    {
        if (! function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            return;
        }

        $this->field_label = __('Alternative code');

        add_action('woocommerce_product_options_sku', array($this, 'woocommerce_product_extra_fields')); // Product extra field
        add_action('woocommerce_product_meta_end', array($this, 'display_the_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save'));
    }

    /**
     * Adds a custom field to the product edit page
     */
    public function woocommerce_product_extra_fields()
    {
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);

        $fields = array(
            'alternative_code' => array(
                'name' => $this->field_label,
                'meta' => self::FIELD_META,
            ),
        );

        foreach ($fields as $name => $field) {
            woocommerce_wp_text_input(
                array(
                    'id'          => esc_attr($name),
                    'label'       => esc_html($this->field_label),
                    'value'       => esc_attr($product->get_meta($field['meta'])),
                    'desc_tip'    => true,
                    'description' => esc_html__('This is the description of the custom field'),
                )
            );
        }
    }

    /*
     * Displays the custom field value on the single product page
     */
    public function display_the_field()
    {
        global $product;
        $field = $product->get_meta(self::FIELD_META);

        if (!$field) {
            return;
        }
?>
        <div class="product_meta wp-block-post-terms">
            <span><?php esc_html_e($this->field_label) ?>:</span>
            <span><?php esc_html_e($field) ?></span>
        </div>
<?php
    }

    /**
     * Saves the custom field value when the product is saved
     *
     * @param int $post_id The ID of the product being saved
     */
    public function save($post_id)
    {
        if (! isset($_POST['alternative_code'])) {
            return;
        }

        $product = wc_get_product($post_id);
        if (! $product) {
            return;
        }

        if (! empty($_POST['alternative_code'])) {
            $product->update_meta_data(self::FIELD_META, $_POST['alternative_code']);
        } else {
            $product->delete_meta_data(self::FIELD_META);
        }
        $product->save_meta_data();
    }

    public static function delete_custom_product_field()
    {
        delete_post_meta_by_key('_alternative_code');
    }
}

new WC_Custom_Product_Field();
