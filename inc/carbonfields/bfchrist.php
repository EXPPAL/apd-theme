<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Page Content')
	->where('post_template', '=', 'page-templates/christ.php')
	->add_fields(array(
		Field::make('image', 'logo_img', 'Logo Image'),
		Field::make('date_time', 'datetime_start', 'Time Start'),
		Field::make('complex', 'new_deal', 'Deals')
			->set_layout('tabbed-vertical')
			->add_fields(array(
				Field::make('image', 'e_image', 'Empty Image'),
				Field::make('image', 'image', 'Image'),
				Field::make('image', 'd_image', 'Discount Image'),
				Field::make('image', 'h_image', 'Hover Image'),
				Field::make('association', 'product_id', 'Product')
					->set_min(1)
					->set_max(1)
					->set_types(array(
						array(
							'type' => 'post',
							'post_type' => 'product',
						)
					))
			)),
		Field::make('image', 'sub_logo', 'Sub Logo Image'),
		Field::make('image', 'sock_img', 'Sock Image'),
		Field::make('image', 'bell_img', 'Bell Image'),
		Field::make('image', 'vip_logo', 'VIP Logo'),
		Field::make('image', 'vip_title', 'VIP Title'),
		Field::make('text', 'vip_product', 'VIP Button Url'),
		Field::make('complex', 'checkout_products', 'Check Out')
			->set_layout('tabbed-vertical')
			->add_fields(array(
				Field::make('image', 'image', 'Image'),
				Field::make('image', 'd_image', 'Discount Image'),
				Field::make('text', 'product_id', 'Page Url')
			)),
		Field::make('oembed', 'video', 'Video'),
	));