<?php

class Apd_Elite_Subscription {
	
	const OPTIONS_KEY = 'apd_elite_subscription_settings';
	const STATUS_KEY = 'apd_elite_subscription_status';
	
	private $subscription_status = array();
	
	private $settings = array();
	
	private $setting_fields = array(
		'subscription_product_id',
		'email_template',
		'email_subject',
		'first_email_template',
		'first_email_subject'
	);
	
	public function __construct() {
		
		add_action('admin_menu', array( $this, 'add_setting_page' ), 10);
		add_action('woocommerce_order_status_changed', array( $this, 'apd_create_subscription_coupons' ) , 10, 3 );
		
		// apply_filters( "gettext_{$domain}", $translation, $text, $domain );
		add_filter("gettext_woocommerce-subscriptions", array( $this, 'update_subscription_translation' ), 10, 3  );
	}
	

	public function update_subscription_translation( $translation, $text, $domain ) {
		if ( $text == 'First renewal: %s') {
			$translation = 'Next payment: %s';
		}
		
		return $translation;
	}
	
	public function get_settings() {
		$this->settings = get_option( self::OPTIONS_KEY );
	}
	
	public function save_settings() {
		update_option( self::OPTIONS_KEY, $this->settings  );
	}

	public static function get_subscription_product_id() {
		$options = get_option( self::OPTIONS_KEY );
		return $options['subscription_product_id'];
	}
	
	/*
  public static function mass_send_monthly_coupons() {
		
		if ( ! self::is_current_month_coupons_sent() ) {
			$users = self::get_all_subscribed_users();

			$email_template = Apd_Elite_Subscription::get_email_template();
			
			foreach ( $users as $user ) {
				self::send_coupon( $user, $email_template );
			}
		}
		
		self::set_current_month_coupons_sent();
  }
	*/
	
	public static function is_current_month_coupons_sent() {
		$this->subscription_status = get_option( self::STATUS_KEY );
		
		$month = date('n');
		
		if ( isset( $this->subscription_status[ $month ] ) ) {
			return true;
		}
		
		return false;
	}
	
	public static function set_current_month_coupons_sent() {
		$subscription_status = (array) get_option( self::STATUS_KEY );
		
		$month = date('n');
		
		$subscription_status[$month] = true;
		
		update_option( self::STATUS_KEY, $subscription_status );
	}
  
  public static function get_all_subscribed_users() {
		$args = array(
			'meta_key' => 'monthly_subscription_data',
			'meta_value' => 'monthly_subscription_active',
			'meta_compare' => 'LIKE'
		);
		
    return get_users($args);
  }
  
  public static function subscribe_user( $user, $billing_email ) {
		
		$coupon_ids = self::create_coupons_for_user( $user->data->user_email, $billing_email );
		
		apd_log( ' subscribe_user $coupon_ids ::: ' . $billing_email . print_r($coupon_ids ,1) );
		
		$subscription_data = array(
			'start_date' => date('U'),
			'status'	=> 'monthly_subscription_active',
			'coupons_sent' => array(), // to fill later every month
			'coupon_ids'	=> $coupon_ids,
			'billing_email' => $billing_email
		);
		
    update_user_meta( $user->ID, 'monthly_subscription_data', $subscription_data, true );
  }
  
  public static function unsubscribe_user( $user_id ) {
    remove_user_meta( $user_id, 'monthly_subscription_data');
  }
	
	
  public static function get_user_subscription_data( $user_id ) {
    return get_user_meta( $user_id, 'monthly_subscription_data' , true );
  }
	
	/**
	 * Generates as string to use as a coupon code. Must be unique
	 * 
	 * @param string $email
	 * @param integer $month
	 * @return string
	 */
	private static function generate_coupon_code( $email, $month ) {
		
		$symbols = range('A', 'Z');
		
		for ( $i = 1; $i < 9; $i++) {
			$symbols[] = $i;
		}
		
		$code_seed_from_email = substr($email, 0, 3 );
		
		if ( strlen( $email ) > 5 ) {
			$code_seed_from_month = substr($email, 3, 2 ) . $month;
		}
		else {
			$code_seed_from_month = $month . $month . substr($email, 0, 3 );
		}
		
		$code_seed_from_rand = rand(10000,99999);
		
		$code_blocks = array(
			self::generate_code_block( $symbols, $code_seed_from_email ),
			self::generate_code_block( $symbols, $code_seed_from_month ),
			self::generate_code_block( $symbols, $code_seed_from_rand )
		);
		
		shuffle( $code_blocks );
		
		$code = 'APD-' . implode('-', $code_blocks );
		
		return $code;
	}
	
	private static function generate_code_block( $symbols, $code_seed ) {
		
		$out = '';
		
		$length = count($symbols);
		$letters = str_split( $code_seed, 1 );
		
		shuffle( $letters );
		
		foreach ( $letters as $letter ) {
			$position =  ord($letter) % $length;
			$out .= $symbols[$position]; // . '(' . $position . ')' . $letter . '^' . ord($letter) ;
		}
		
		return $out;
	}
	
	public static function create_coupons_for_user( $user_email, $billing_email ) {
		
		$coupon_ids = array();
		
		if ( ! class_exists( 'WP_Site_Health' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
		}

		$coupons = self::prepare_coupons_for_user( $user_email, $billing_email );
		
		foreach ( $coupons as $month => $coupon ) {
			$result = self::create_coupon( $coupon );
			
			if ( is_wp_error($result) ) {
				
			}
			else  {
				$coupon_id = $result->save();
				$coupon_ids[$month] = $coupon_id;			
			}
		}
		
		return $coupon_ids;
	}
	
	public static function create_coupon( $coupon_data ) {
		
		$coupon    = new WC_Coupon();
	
		// Handle all writable props.
		foreach ( $coupon_data as $key => $value ) {

			if ( ! is_null( $value ) ) {
				switch ( $key ) {
					case 'code':
						$coupon_code  = wc_format_coupon_code( $value );
						$id_from_code = wc_get_coupon_id_by_code( $coupon_code, 0 );

						if ( $id_from_code ) {
							return new WP_Error( 'woocommerce_rest_coupon_code_already_exists', __( 'The coupon code already exists', 'woocommerce' ), array( 'status' => 400 ) );
						}

						$coupon->set_code( $coupon_code );
						break;
					case 'meta_data':
						if ( is_array( $value ) ) {
							foreach ( $value as $meta ) {
								$coupon->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
							}
						}
						break;
					case 'description':
						$coupon->set_description( wp_filter_post_kses( $value ) );
						break;
					default:
						if ( is_callable( array( $coupon, "set_{$key}" ) ) ) {
							$coupon->{"set_{$key}"}( $value );
						}
						break;
				}
			}
		}
		
		return $coupon;
		
	}
	
	/**
	 * Prepares info for 12 coupons, one for each month. 
	 * Every coupon should be valid until the last day of its month.
	 * 
	 * @param string $billing_email
	 * @return array
	 */
	public static function prepare_coupons_for_user( $user_email, $billing_email ) {
		
		$date = new DateTime();
		
		$coupons = array();
		
		for ( $i = 0; $i < 12; $i++ ) {
		
			$date->modify('last day of');
			
			$month = intval($date->format('n'));
			$coupons[ $month ] = self::prepare_coupon_info( $user_email, $billing_email, $date );	
			$date->modify('+1 day');
		}
		
		return $coupons;
	}
	
	/**
	 * Prepares info about single coupon
	 * 
	 * @param string $email
	 * @param DateTime $date
	 * @return array
	 */
	private static function prepare_coupon_info( $user_email, $billing_email, DateTime $date ) {
	
		$month_names = array(
			'Zero', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
		);

		$month = intval($date->format('n'));

		$coupon_info = array(
			"email_restrictions"		=> array( $user_email, $billing_email),
			"code"									=> self::generate_coupon_code( $user_email, $month ),
			"description"						=> "Monthly coupon for $user_email for " . $month_names[$month] . ' ' . $date->format('Y'),
			"discount_type"					=> "fixed_cart",
			"amount"								=> "20",
			"date_expires"					=> $date->format('Y-m-d'),
			"individual_use"				=> true,
			"usage_limit"						=> 1,
			"usage_limit_per_user"	=> 1
		);
	

		return $coupon_info;
	}
	

	/**
	 * Prepare coupons when a subscription is purchased
	 */
	public function apd_create_subscription_coupons( $order_id, $old_status, $new_status ) {

		if ( $new_status == 'completed' ) {

			$order = new WC_Order( $order_id );

			if ( $order ) {
				
				$this->get_settings();
					
				$billing_email = $order->get_billing_email(); 
				$user = $order->get_user();

				$items = $order->get_items(); 
				$contains_subscription_product = false;

				foreach ( $items as $item_id => $item ) {
					$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

					if ( $product_id == $this->settings['subscription_product_id'] ) {
						$contains_subscription_product = true;
						break;
					}
				}

				if ( $contains_subscription_product ) {
					self::subscribe_user( $user, $billing_email );
					$this->send_immediate_email( $billing_email, $user->ID );
					apd_log( '$contains_subscription_product ::: ' . $order_id . print_r($this->settings,1) );
				}
			}
		}
	}
	
	public function send_test_email( $email_address, $user_id ) {
		$user = get_user_by('ID', $user_id );
		
		if ( $user ) {
			
			$this->get_settings();

			//$email = $user->data->user_email;
			
			$coupon_data = $this->get_user_subscription_data( $user_id );

			//echo('$user->data-<pre>' . print_r($user->data, 1) . '</pre>');
			
			$month = date('n');
			$coupon_code = $this->get_coupon_code_for_month( $coupon_data, $month );

			if ( $coupon_code ) {
				
				$template_data = array( 
					'user_name'		=> $user->data->display_name, 
					'month'				=> $month,
					'coupon_code'	=> $coupon_code
				);
				
				$email_subject = $this->prepare_template( $this->settings['email_subject'], $template_data );
				$email_template = $this->prepare_template( $this->settings['email_template'], $template_data );
				
				if ( wp_mail( $email_address, $email_subject, $email_template ) ) {
					$result = 'Mail sent to ' . $email_address;
				}
				else {
					$result = 'Failed to send mail';
				}
			}
			else {
				$result = 'Coupon code not found for month ' . $month;
			}
		}
		else {
			$result = "User not found ( id = $user_id )";
		}
		
		return $result;
	}

	public function send_actual_coupons_for_month() {
		
		$month = date('n');
		$year = date('Y');
			
		$users = self::find_subscription_users();
		
		if ( is_array( $users ) ) {
			
			$this->get_settings();
			
			$num = 0;
			foreach( $users as $user_data ) {
			
				$coupon_code = false;
				
				$user = get_user_by('id', $user_data['ID'] );
				if ( $user ) {
					$coupon_code = $this->get_coupon_code_for_month( $user_data['subscription_data'], $month );
					
					if ( $coupon_code ) {
						if ( $this->send_coupon_email( $user->user_email, $user, $month, $coupon_code ) ) {
							$num++;
						}
					}
					else {
						apd_log( 'failed to send ' . $month . ' to ' . $user_data['ID'] . '  ::: ' . $coupon_code . print_r($user_data['subscription_data'] ,1) );
					}
				}
				else {
						apd_log( 'failed to find user ' . $user_data['ID'] . '  ::: ' . $coupon_code . print_r($user_data['subscription_data'] ,1) );
				}
				
			}
			
			$result = ' Found ' . count($users) . " users with coupons. Sent " . $num . " coupons to users" ;
			
		}
		else {
			$result = ' Error in query or params ';
		}
		
		return $result;
	}

	public function send_test_coupons_for_month( $email, $month, $year ) {
		
		$users = self::find_subscription_users();
		
		if ( is_array( $users ) ) {
			
			
			$this->get_settings();
			
			$i = 0;
			$num = 0;
			foreach( $users as $user_data ) {
				$i++;
			
				$user = get_user_by('id', $user_data['ID'] );
				if ( $user ) {
					$coupon_code = $this->get_coupon_code_for_month( $user_data['subscription_data'], $month );
					
					if ( $coupon_code ) {
						if ( $this->send_coupon_email( $email, $user, $month, $coupon_code ) ) {
							$num++;
						}
					}
				}
				
				if ( $i > 2 ) break;
			}
			
			$result = ' Found ' . count($users) . " users with coupons. Sent " . $num . " coupons to $email " ;
			
		}
		else {
			$result = ' Error in query or params ';
		}
		
		return $result;
	}
	
	
	public function log_coupon_email( $email_address, $user, $month, $coupon_code ) {
	
		$template_data = array( 
			'user_name'		=> $user->data->display_name, 
			'month'				=> $month,
			'coupon_code'	=> $coupon_code
		);

		$email_subject = $this->prepare_template( $this->settings['email_subject'], $template_data );
		$email_template = $this->prepare_template( $this->settings['email_template'], $template_data );

		apd_log( array( 'address' => $email_address, 'subject' => $email_subject, 'body' => $email_template ) );
			
		return true;
	}
	
	
	
	
	public function send_coupon_email( $email_address, $user, $month, $coupon_code ) {
	
		$template_data = array( 
			'user_name'		=> $user->data->display_name, 
			'month'				=> $month,
			'coupon_code'	=> $coupon_code
		);

		$email_subject = $this->prepare_template( $this->settings['email_subject'], $template_data );
		$email_template = $this->prepare_template( $this->settings['email_template'], $template_data );

		
		apd_log( array( 'address' => $email_address, 'subject' => $email_subject, 'body' => $email_template ) );
		return wp_mail( $email_address, $email_subject, $email_template );
	}
	
	
	public static function find_subscription_users() {
		
		$subscription_users = array();
		
		global $wpdb;
		$wp = $wpdb->prefix;

		// date_expires	1698706800
		$query_sql = "SELECT u.ID, um.meta_value as 'subscription_data' from {$wp}users AS u "
						. " LEFT JOIN `{$wp}usermeta` AS um on u.`ID` = um.`user_id`"
						. " WHERE um.meta_key = 'monthly_subscription_data' AND um.meta_value != '' ";		// select only users with filled-in coupon data

		$rows = $wpdb->get_results( $query_sql, ARRAY_A );

		if ( is_array( $rows ) ) {
			foreach ( $rows as $row ) {
				$row['subscription_data'] = unserialize($row['subscription_data']);
				$subscription_users[] = $row;
			}

			return $subscription_users;
		}
		
		
		return false;
	}
	
	public static function find_coupons_for_month( $month_number, $year ) {
		
		if ( $month_number > 0 && $month_number < 13 ) {
			
			$coupons_for_month = array();
				
			$month_names = array(
				'Zero', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
			);
		
			$name_start = 'Monthly coupon for';
			$name_end = 'for ' . $month_names[ $month_number ] . ' ' . $year;
		
			// Example of full coupon name: "Monthly coupon for czigrovic@yahoo.com for November 2023"
    
			global $wpdb;
			$wp = $wpdb->prefix;

			// date_expires	1698706800
			$query_sql = "SELECT p.ID, p.post_title, pm.meta_value from {$wp}posts AS p "
							. " LEFT JOIN `{$wp}postmeta` AS pm on p.`ID` = pm.`post_id`"
							. " WHERE pm.meta_key = 'date_expires' AND pm.meta_value > 0 "		// select only coupons with  filled-in expiration date
							. " AND p.post_type = 'shop_coupon' AND p.post_status = 'publish' " 	// select only active coupons 
							. " AND p.post_excerpt LIKE '" . $name_start . "%' AND p.post_excerpt LIKE '%" . $name_end . "' "; // search coupons with proper names
			
			$rows = $wpdb->get_results( $query_sql, ARRAY_A );

			if ( is_array( $rows ) ) {
				foreach ( $rows as $row ) {
						$coupons_for_month[] = $row;
				}
				
				return $coupons_for_month;
			}
		}
		
		return false;
	}
	
	public function send_immediate_email( $email_address, $user_id ) {
		$user = get_user_by('ID', $user_id );
		
		if ( $user ) {
			
			$this->get_settings();

			//$email = $user->data->user_email;
			
			$coupon_data = $this->get_user_subscription_data( $user_id );

			//echo('$user->data-<pre>' . print_r($user->data, 1) . '</pre>');
			
			$month = date('n'); 
			$coupon_code = $this->get_coupon_code_for_month( $coupon_data, $month );

			if ( $coupon_code ) {
				
				$template_data = array( 
					'user_name'		=> $user->data->display_name, 
					'month'				=> $month,
					'coupon_code'	=> $coupon_code
				);
				
				$email_subject = $this->prepare_template( $this->settings['first_email_subject'], $template_data );
				$email_template = $this->prepare_template( $this->settings['first_email_template'], $template_data );

				
				//echo('$email, $email_subject, $email_template<pre>' . print_r([$email, $email_subject, $email_template, $template_data], 1) . '</pre>');
			
				if ( wp_mail( $email_address, $email_subject, $email_template ) ) {
					$result = 'Mail sent to ' . $email_address;
				}
				else {
					$result = 'Failed to send mail';
				}
			}
			else {
				$result = 'Coupon code not found for month ' . $month;
			}
		}
		else {
			$result = "User not found ( id = $user_id )";
		}
		
		return $result;
	}


	
	public function get_coupon_code_for_month( $coupon_data, $month ) {
		
		$coupon_code = false; 
		
	
		apd_log( 'get_coupon_code_for_month  ' . $month . ' --' . print_r($coupon_data['coupon_ids'][$month] ,1) );
		
		if ( isset($coupon_data['coupon_ids'][$month]) ) {
			$coupon_id = $coupon_data['coupon_ids'][$month];
			
			global $wpdb;
			$wp = $wpdb->prefix;

			$query_sql = $wpdb->prepare("SELECT * FROM `{$wp}posts` AS p WHERE p.ID = %d ", array($coupon_id ) );

			$db_result = $wpdb->get_row( $query_sql, ARRAY_A );

			if ($db_result) {
				$coupon_code = strtoupper( $db_result['post_title'] );
			}
		}
		
		return $coupon_code;
	}
	
	public function prepare_template( $template, $template_data ) {	
		$month_names = array(
			'Zero', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
		);
		
		$template = str_replace('[coupon_code]', $template_data['coupon_code'], $template );
		$template = str_replace('[user_name]', $template_data['user_name'], $template);
		$template = str_replace('[month_name]', $month_names[$template_data['month']], $template );
		$template = str_replace('[current_year]', date('Y'), $template );
		
		return $template;
	}
	
	public function add_setting_page() { 

		add_options_page(
			__('Send monthly coupons'), 
			__('Send monthly coupons'), 
			'manage_options',
			'mass_send_coupons-setting-page',
			array( $this, 'mass_send_coupons_setting_page' )
		);
	}


	/**
	 * Render plugin page to the Tools
	 */
	public function mass_send_coupons_setting_page() { 

		$sending_result = false;  
		
		if ( isset($_POST['action']) ) {

			
			
			if ( $_POST['action'] == 'save_settings' ) {
				foreach ( $this->setting_fields as $field ) {
					if (array_key_exists($field, $_POST) && $_POST[$field]) {
						$this->settings[$field] = wp_unslash($_POST[$field]);
					}
				}

				//echo('$this->settings<pre>' . print_r($this->settings, 1) . '</pre>');
				$this->save_settings();

				$message = __('Settings Saved.', 'auto-upload-images');
			}

			if ( $_POST['action'] == 'send_test_email' ) {
				$message = $this->send_test_email( $_POST['test_email'], $_POST['test_user_id'] );
			}
			
			if ( $_POST['action'] == 'send_test_immediate_email' ) {
				$message = $this->send_immediate_email( $_POST['test_email'], $_POST['test_user_id'] );
			}
			
			if ( $_POST['action'] == 'send_test_coupons_for_month' ) {
				$message = $this->send_test_coupons_for_month( $_POST['test_email'], $_POST['test_month'], $_POST['test_year'],);
			}
			
			if ( $_POST['action'] == 'send_actual_coupons_for_month' ) {
				$message = $this->send_actual_coupons_for_month();
			}
			
		}
		
		$this->get_settings();

		
		?>
			<div class="wrap">
				<h1><?php echo __("Send monthly coupons"); ?></h1>

				<?php if (isset($message)) : ?>
				<div id="setting-error-settings_updated" class="updated settings-error">
						<p><strong><?php echo $message; ?></strong></p>
				</div>
				<?php endif; ?>

				<form method="POST">

					<input type="hidden" name="action" value="save_settings" />

					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="subscription_product_id"><?php echo __('Subscription product ID'); ?></label></th>
								<td>
									<input class="regular-text" id="subscription_product_id" name="subscription_product_id" type="number" min="0" max="" value="<?php echo $this->settings['subscription_product_id']; ?>">
									<p class="description"><?php echo __('Enter ID for the subscription that triggers coupon generation when purchased by a user'); ?></p>
								</td>
							</tr>
							
							<tr>
								<th><label for="first_email_subject"><?php echo __('Email Subject for the immediate email'); ?></label></th>
								<td>
									<input class="regular-text" id="first_email_subject" name="first_email_subject" type="text" value="<?php echo esc_attr($this->settings['first_email_subject']); ?>" />

									<p class="description" ><?php echo __('This email is sent immediately after subscribing. Available shortcodes: [user_name], [month_name], [current_year] '); ?></p>
								</td>
							</tr>
							<tr>
							<tr>
								<th><label for="first_email_template"><?php echo __('Immediate Email template'); ?></label></th>
								<td>
									<textarea rows="20" cols="60" id="first_email_template" name="first_email_template" ><?php echo esc_textarea($this->settings['first_email_template']); ?></textarea>

									<p class="description" ><?php echo __('Available shortcodes: [user_name], [month_name], [coupon_code], [current_year] '); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="email_subject"><?php echo __('Monthly Email Subject'); ?></label></th>
								<td>
									<input class="regular-text" id="email_subject" name="email_subject" type="text" value="<?php echo esc_attr($this->settings['email_subject']); ?>" />

									<p class="description" ><?php echo __('This email is sent every month. Available shortcodes: [user_name], [month_name], [current_year] '); ?></p>
								</td>
							</tr>
							<tr>
							<tr>
								<th><label for="email_template"><?php echo __('Monthly Email template'); ?></label></th>
								<td>
									<textarea rows="20" cols="60" id="email_template" name="email_template" ><?php echo esc_textarea($this->settings['email_template']); ?></textarea>

									<p class="description" ><?php echo __('Available shortcodes: [user_name], [month_name], [coupon_code], [current_year] '); ?></p>
								</td>
							</tr>
						
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" id="apd-save-settings" class="button button-primary" value="<?php echo __('Save settings'); ?>" ?>
					</p>

				</form>

				<h2><?php echo __("Send test immediate email"); ?></h2>

				<form method="POST">

					<input type="hidden" name="action" value="send_test_immediate_email" />
					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="test-user-id"><?php echo __('Test user ID'); ?></label></th>
								<td>
									<input class="regular-text" id="test-user-id" name="test_user_id" type="number" min="0" max="" value="0">
									<p class="description" id="dt-age-description"><?php echo __('Who\'s data to use for an example coupon email using the current template'); ?></p>
								</td>
							</tr>
							<tr>
							<tr>
								<th><label for="test-email"><?php echo __('Email address to send to'); ?></label></th>
								<td>
									<input class="regular-text" id="test-email" name="test_email" type="email" value="">
								</td>
							</tr>
							<tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" id="apd-save-settings" class="button button-primary" value="<?php echo __('Send test immediate email'); ?>" ?>
					</p>

				</form>


				<h2><?php echo __("Send test monthly email"); ?></h2>

				<form method="POST">

					<input type="hidden" name="action" value="send_test_email" />
					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="test-user-id"><?php echo __('Test user ID'); ?></label></th>
								<td>
									<input class="regular-text" id="test-user-id" name="test_user_id" type="number" min="0" max="" value="0">
									<p class="description" id="dt-age-description"><?php echo __('Who\'s data to use for an example coupon email using the current template'); ?></p>
								</td>
							</tr>
							<tr>
							<tr>
								<th><label for="test-email"><?php echo __('Email address to send to'); ?></label></th>
								<td>
									<input class="regular-text" id="test-email" name="test_email" type="email" value="">
								</td>
							</tr>
							<tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" id="apd-save-settings" class="button button-primary" value="<?php echo __('Send test monthly email'); ?>" ?>
					</p>

				</form>

				
				<h2><?php echo __("Send 3 monthly coupons to test address"); ?></h2>

				<form method="POST">

					<input type="hidden" name="action" value="send_test_coupons_for_month" />
					
					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="test-month"><?php echo __('Month'); ?></label></th>
								<td>
									<input class="regular-text" id="test-month" name="test_month" type="number" min="1" max="12" value="1">
									<p class="description" id="dt-age-description"><?php echo __('Enter month number. 1 is for January, 2 for February, etc'); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="test-year"><?php echo __('Year'); ?></label></th>
								<td>
									<input class="regular-text" id="test-year" name="test_year" type="number" min="2022" max="3033" value="2023">
									<p class="description" id="dt-age-description"><?php echo __('Enter year'); ?></p>
								</td>
							</tr>
							<tr>
							<tr>
								<th><label for="test-email"><?php echo __('Email address to send to'); ?></label></th>
								<td>
									<input class="regular-text" id="test-email" name="test_email" type="email" value="">
								</td>
							</tr>
							<tr>
						</tbody>
					</table>
						
					<p class="submit">
						<input type="submit" id="apd-save-settings" class="button button-primary" value="<?php echo __('Send test monthly coupons'); ?>" ?>
					</p>

				</form>

				
				<h2 style="color:red">Send ALL monthly coupons for the current month</h2>

				<form method="POST">

					<input type="hidden" name="action" value="send_actual_coupons_for_month" />
					
					<p class="submit">
						<input type="submit" id="apd-save-settings" class="button button-primary" value="<?php echo __('Send monthly coupons'); ?>" ?>
					</p>

				</form>
			</div>


		<?php 

	}
}


$elite_plugin = new Apd_Elite_Subscription();

/*
if ( isset($_GET['moo949393']) ) {
	
	$user = get_user_by('ID',1);
	$billing_email = 'test@test.cc';
	Apd_Elite_Subscription::subscribe_user($user, $billing_email);
	
}
*/