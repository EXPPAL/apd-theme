<?php

   /* Template Name: Test Download code */
    /* Template Post Type: page */


error_reporting(-1);
ini_set('display_errors', 'On');

echo 'test';


 $user  = wp_get_current_user();

 echo "<pre> User : ".print_r( $user->ID , TRUE )."</pre>";
global $woocommerce;
$downloads = wc_get_customer_available_downloads_test($user->ID);

echo "<pre> Data : ".print_r( $downloads , TRUE )."</pre>";

//$results = wc_get_customer_download_permissions(  $user->ID);

//echo "<pre> Data : ".print_r( $results , TRUE )."</pre>";



function wc_get_customer_available_downloads_test( $customer_id ) {
  $downloads   = array();
  $_product    = null;
  $order       = null;
  $file_number = 0;

  // Get results from valid orders only.
  $results = wc_get_customer_download_permissions( $customer_id );



  if ( $results ) {
    foreach ( $results as $result ) {
      $order_id = intval( $result->order_id );
  

      if ( ! $order || $order->get_id() !== $order_id ) {
        // New order.
        $order    = wc_get_order( $order_id );
        $_product = null;
      }

      // Make sure the order exists for this download.
      if ( ! $order ) {
        continue;
      }

      // Check if downloads are permitted.
      if ( ! $order->is_download_permitted() ) {
        continue;
      }
      echo "<pre> Data Permission : ".print_r( $order->is_download_permitted, TRUE )."</pre>";
      echo "<pre> Data : ".print_r(  $order_id, TRUE )."</pre>";
echo "<pre> Download Key : ".print_r( $result->download_id, TRUE )."</pre>";
      $product_id = intval( $result->product_id );

   
      if ( ! $_product || $_product->get_id() !== $product_id ) {
        // New product.
        $file_number = 0;
        $_product    = wc_get_product( $product_id );
      }
     // echo "<pre> File : ".print_r( $_product, TRUE )."</pre>";
      // Check product exists and has the file.
      if ( ! $_product || ! $_product->exists() || ! $_product->has_file( $result->download_id ) ) {
        continue;
      }

      $download_file = $_product->get_file( $result->download_id );
        echo "<pre> Files : ".print_r( $download_file , TRUE )."</pre>";
      // Download name will be 'Product Name' for products with a single downloadable file, and 'Product Name - File X' for products with multiple files.
      $download_name = apply_filters(
        'woocommerce_downloadable_product_name',
        $download_file['name'],
        $_product,
        $result->download_id,
        $file_number
      );



      $downloads[] = array(
        'download_url'        => add_query_arg(
          array(
            'download_file' => $product_id,
            'order'         => $result->order_key,
            'email'         => rawurlencode( $result->user_email ),
            'key'           => $result->download_id,
          ),
          home_url( '/' )
        ),
        'download_id'         => $result->download_id,
        'product_id'          => $_product->get_id(),
        'product_name'        => $_product->get_name(),
        'product_url'         => $_product->is_visible() ? $_product->get_permalink() : '', // Since 3.3.0.
        'download_name'       => $download_name,
        'order_id'            => $order->get_id(),
        'order_key'           => $order->get_order_key(),
        'downloads_remaining' => $result->downloads_remaining,
        'access_expires'      => $result->access_expires,
        'file'                => array(
          'name' => $download_file->get_name(),
          'file' => $download_file->get_file(),
        ),
      );

      $file_number++;
    }
  }

  return apply_filters( 'woocommerce_customer_available_downloads_test', $downloads, $customer_id );
}
?>
