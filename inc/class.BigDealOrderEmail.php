<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * A custom Order Completed WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */

class ApdBigDealOrderEmail extends WC_Email {

	public $meta_fields = array();

	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// set ID, this simply needs to be a unique name
		$this->id = 'wc_apd_bigdeal_order_completed';
		$this->customer_email = true;

		// this is the title in WooCommerce Email settings
		$this->title = 'Audio Plugin Big Deal Order Completed';

		// this is the description in WooCommerce email settings
		//$this->description = 'Audio Plugin Deals Order Completed Notification emails are sent when a customer places an order and it is completed';
		$this->description = __('Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.', 'woocommerce');

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = 'Your Order Completed.';
		$this->subject = 'Audio Plugin Big Deal Order Completed. {product_email_subject}';

		// Trigger on new completed orders
		add_action('woocommerce_order_status_completed_notification', array($this, 'trigger'),1000);

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		$this->meta_fields['company_name']			= $this->get_option('meta_field_company_name');
		$this->meta_fields['redeem_link']				= $this->get_option('meta_field_redeem_link');
		$this->meta_fields['email_subject']			= $this->get_option('meta_field_email_subject');
		$this->meta_fields['email_template']		= $this->get_option('meta_field_email_template');

	}

	public function is_subscription_order($order_id) {
		
		$subscription_product_id = 0;

		if ( class_exists('Apd_Elite_Subscription') ) {
			$subscription_product_id = Apd_Elite_Subscription::get_subscription_product_id();
		}
	
	
		$order = new WC_Order( $order_id );

		if ( $order && $subscription_product_id ) {
			$items = $order->get_items(); 
			$contains_subscription_product = false;

			foreach ( $items as $item_id => $item ) {
				$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

				if ( $product_id == $subscription_product_id ) {
					$contains_subscription_product = true;
					break;
				}
			}
			
			return $contains_subscription_product;
		}
		return false;
	}
	
	public function is_shop_or_deal_order($order_id) {
		$order_type = get_post_meta($order_id,'order_type',true);
		
		if ( $order_type == 'shop' || $order_type == 'deal' ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 0.1
	 * @param int $order_id
	 */
	public function trigger($order_id) {

		// bail if no order ID is present
		if (!$order_id) {
			return;
		}

		// check if this is the correct order type 
		if ( $this->is_shop_or_deal_order( $order_id ) ) {
			return;
		}
		
		
		if ( $this->is_subscription_order( $order_id ) ) {
			return;
		}

		// setup order object
		$this->object = new WC_Order($order_id);
		$this->recipient = $this->object->billing_email;
		
		if ( $this->get_option('subject') != '' ) {
			$this->subject = $this->get_option('subject');
		}
		
		if ( $this->get_option('heading') != '' ) {
			$this->heading = $this->get_option('heading');
		}

		$this->find['order-date'] = '{order_date}';
		$this->find['order-number'] = '{order_number}';

		$this->replace['order-date'] = date_i18n(wc_date_format(), strtotime($this->object->order_date));
		$this->replace['order-number'] = $this->object->get_order_number();

		if (!$this->is_enabled() || !$this->get_recipient()) {
			return;
		}

		do_action('woocommerce_generate_order_license_codes', $order_id, '','',''); // make License manager generate licenses
		do_action('woocommerce_update_item_gift_codes', $order_id ); // make this plugin update gift codes

		self::log('triggered for #' . $order_id);
		remove_filter( 'woocommerce_email_styles', 'apd_woocommerce_email_styles' );
		
		$emails_to_send = $this->get_content();

		self::log($emails_to_send);

		if( count($emails_to_send) ){
			foreach ( $emails_to_send as $email ) {
				self::set_order_email_template_content( $order_id, $email['body'], $email['product_id'] );
				
				// send email
				$this->send( $this->get_recipient(), $email['subject'], $email['body'], $this->get_headers(), $this->get_attachments() );
			}
		}

	}

	/**
	 * Compose and output emil template to test its content
	 *
	 * @param int $order_id
	 */
	public function test_email($order_id) {

		// bail if no order ID is present
		if (!$order_id) {
			return;
		}

		// check if this is the correct order type 
		if ( $this->is_shop_or_deal_order( $order_id ) ) {
			return;
		}

    // setup order object
		$this->object = new WC_Order($order_id);
		$this->recipient = $this->object->billing_email;
		
		//$this->heading = $this->process_shortcodes($this->heading);

		$this->find['order-date'] = '{order_date}';
		$this->find['order-number'] = '{order_number}';

		$this->replace['order-date'] = date_i18n(wc_date_format(), strtotime($this->object->order_date));
		$this->replace['order-number'] = $this->object->get_order_number();

		if (!$this->is_enabled() || !$this->get_recipient()) {
			return;
		}

		do_action('woocommerce_generate_order_license_codes', $order_id, '','',''); // make License manager generate licenses
		do_action('woocommerce_update_item_gift_codes', $order_id ); // make this plugin update gift codes

		self::log('triggered for #' . $order_id);
		remove_filter( 'woocommerce_email_styles', 'apd_woocommerce_email_styles' );
		
		$emails_to_send = $this->get_content();

		//self::log($emails_to_send);

		$results = array();
		
		if ( count($emails_to_send) ) {
			foreach ( $emails_to_send as $email ) {
				// output email
				$results[] = array( $this->get_recipient(), $email['subject'], $email['body'], $this->get_headers(), $this->get_attachments() );
			}
    }
		
		return $results;
	}

	/**
	 * get_content_html function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_html() {
		$email_heading = $this->get_heading();
		$email = $this;

		ob_start();
		/**
		 * @hooked WC_Emails::email_header() Output the email header
		 */
		do_action( 'woocommerce_email_header', $email_heading, $email );

		?>

		<div>
				{product_email_text}
		</div>

		<?php

		/**
		 * @hooked WC_Emails::email_footer() Output the email footer
		 */
		do_action( 'woocommerce_email_footer', $email );

		$html = ob_get_contents();
		ob_clean();
		return $this->process_shortcodes($html);
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		$email_heading = $this->get_heading();		
		ob_start();
		echo "= " . $email_heading . " =\n\n";

		echo sprintf( __( "Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:", 'woocommerce' ), get_option( 'blogname' ) ) . "\n\n";

		echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

		echo '{product_email_text}';

		echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

		echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
		$html = ob_get_contents();
		ob_clean();

		return $this->process_shortcodes($html);
	}

	public function process_shortcodes($html) {

		$order = new WC_Order( $this->object->id );

		$item = $order->get_items();
		
		$emails = [];
		foreach ($item as $product) {
			$product_id = $product['product_id'];
			
			if ( $this->is_big_deal_product($product) || $this->is_airdrop_product($product)  ) {
				
				$body = str_replace('{product_email_text}', $this->get_product_field('email_template', $product_id), $html);
				$subject = str_replace('{product_email_subject}', $this->get_product_field('email_subject', $product_id), $this->subject);
				
				$image = '<img class="header_logo" src="' . esc_url( get_template_directory_uri() ) . '/images/logo.png"/>';
				$body = str_replace( '[header_image]', $image, $body );
				
				$coupon_codes = $this->get_product_coupon_codes( $product->get_meta_data() );
				
				$search = array(
					'{product_name}',
					'{customer_name}',
					'{coupon_code}',
					'{gift_code}',
					'{company_name}',
					'{redeem_link}',
					'{url}',
				);
				
				$replace = array(
					$product['name'],
					$order->billing_first_name,
					strip_tags($coupon_codes),
					$product['gift_code'],
					$this->get_product_field('company_name', $product_id),
					$this->get_product_field('redeem_link', $product_id),
					$this->get_product_field('redeem_link', $product_id)
				);
				
				$body = str_replace($search, $replace, $body);
				$subject = str_replace($search, $replace, $subject);
				
				if ( $product->is_type('variable') ) {
					$price = $item->get_total();
					$points = number_format( $price * 2, 2, '.', '');
					$body = str_replace( '{donation}', $points, $body );
				}
				
				$emails[] = array(
					'subject'	=> $subject,
					'body'		=> $body
				);
			}	
		}
		
		return $emails;
	}
	
	public function get_product_coupon_codes( $item_meta ) {
		$codes = '';
		
		foreach ( $item_meta as $meta_item ) {
			if ( ( $meta_item->key == 'Coupon Code(s)' ) or ( $meta_item->key == 'License Code(s)' ) ) {
				$codes = $meta_item->value;

				if ( is_array( $codes ) ) {
						$codes = implode( ', ', $codes );
				}
			}
		}
		
		return $codes;
	}
	
	public function is_big_deal_product( $product ) {
		$is_big_deal = get_post_meta( $product['product_id'], '_product_big_deal', true );
		
		return ( $is_big_deal == 'yes' );
	}
    
	public function is_airdrop_product( $product ) {
		$is_airdrop = get_post_meta( $product['product_id'], '_product_air_drop', true );
		
		return ( $is_airdrop == 'yes' );
	}

	public function get_product_field($field_name, $product_id) {

		$value = '[unknown value in ' . $field_name. ']';

		if (isset($this->meta_fields[$field_name])) {
			$meta_key = $this->meta_fields[$field_name];
			if ($meta_key) {
				$value = get_post_meta($product_id, $meta_key, true);
			}
		}

		return $value;
	}

    /**
     * Initialize Settings Form Fields
     *
     * @since 0.1
     */
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'type' => 'checkbox',
								'label'   => 'Enable this email notification. <br> Avaliable shortcodes: '
									. '<br>{product_email_subject}, {product_email_text}'
									. '<br>{product_name}, {customer_name}, {coupon_code}, {gift_code}, {company_name}, {redeem_link}, {url}, {donation}',
                'default' => 'yes'
            ),
            'subject' => array(
                'title' => 'Subject',
                'type' => 'text',
                'description' => sprintf('This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject),
                'placeholder' => '',
                'default' => $this->subject
            ),
            'heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'description' => sprintf(__('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.'), $this->heading),
                'placeholder' => '',
                'default' => $this->heading
            ),
            'email_type' => array(
                'title' => 'Email type',
                'type' => 'select',
                'description' => 'Choose which format of email to send.',
                'default' => 'html',
                'class' => 'email_type',
                'options' => array(
                    'plain' => 'Plain text',
                    'html' => 'HTML', 'woocommerce',
                    //'multipart' => 'Multipart', 'woocommerce',
                )
            ),
            'meta_field_company_name' => array(
                'title' => 'Meta field where Company Name is stored',
                'type' => 'text',
                'description' => __('Product meta field used to obtan Company Name to insert into email'),
                'placeholder' => '',
                'default' => ''
            ),
            'meta_field_redeem_link' => array(
                'title' => 'Meta field where Redeem Link is stored',
                'type' => 'text',
                'description' => __('Product meta field used to obtan Redeem link  to insert into email'),
                'placeholder' => '',
                'default' => ''
            ),
						'meta_field_email_subject' => array(
								'title' => 'Meta field where product-specific email subject is stored.',
								'type' => 'text',
								'description' => __('Product meta field used to obtain Email subject. Default is "apd_deal_product_email_heading"'),
								'placeholder' => '',
								'default' => 'apd_deal_product_email_heading'
						),
						'meta_field_email_template' => array(
								'title' => 'Meta field where product-specific email template is stored.',
								'type' => 'text',
								'description' => __('Product meta field used to obtain Email Template. Default is "apd_deal_product_email_template"'),
								'placeholder' => '',
								'default' => 'apd_deal_product_email_template'
						)
        );
    }

    public static function set_order_email_template_content($order_id, $content) {
        update_post_meta($order_id, 'email_template_content_bigdeal', $content);
    }

    public static function log($data) {

        $filename = pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR .'log.txt';
        if (isset($_GET['aops_log_to_screen']) && $_GET['aops_log_to_screen'] == 1) {
            echo('log::<pre>' . print_r($data, 1) . '</pre>');
        }
        else {
            file_put_contents($filename, date("Y-m-d H:i:s") . " | " . print_r($data,1) . "\r\n\r\n", FILE_APPEND);
        }
    }
}