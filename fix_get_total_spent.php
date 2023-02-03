<?php
class SPROUT_order_total {
	private $orders = [];

	function __construct() {
		add_action( "woocommerce_before_order_object_save", [ $this, 'save_order' ], 1000, 2 );
		add_filter( "woocommerce_customer_get_total_spent_query", [ $this, 'intercept_query' ], 1000, 2 );
	}

	function intercept_query( $query, $customer ) {
		global $wpdb;
		$user_id = $customer->get_id();
        	$statuses = array_map( 'esc_sql', wc_get_is_paid_statuses() );
		$sql = "SELECT SUM(order_total) FROM wp_sprout_order_totals 
			WHERE user_id = %d
			AND order_status IN ( 'wc-" . implode( "','wc-", $statuses ) . "' )";
		$sql = $wpdb->prepare( $sql, $user_id );
		error_log( 'NEW: ' . $sql );
		return $sql;
	}
	
	function save_order( $instance, $data_store ) {
		$order_id = $instance->get_id();
		$user_id = $instance->get_user_id();
		$order_total = floatval( $instance->get_total() );
		$order_status = 'wc-' . $instance->get_status();

		$this->write_data( $order_id, $user_id, $order_total, $order_status );
	}

	function write_data( $order_id, $user_id, $order_total, $order_status ) {
		global $wpdb;

		$sql = "
			INSERT INTO {$wpdb->prefix}sprout_order_totals (order_id,user_id,order_total,order_status) 
			VALUES (%d,%d,%f,%s) 
			ON DUPLICATE KEY UPDATE 
				order_id = VALUES(order_id),
				user_id = VALUES(user_id),
				order_total = VALUES(order_total),
				order_status = VALUES(order_status)";

		$sql = $wpdb->prepare( $sql, $order_id, $user_id, $order_total, $order_status );
		$wpdb->query( $sql );
	}
}
new SPROUT_order_total;