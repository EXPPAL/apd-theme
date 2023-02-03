<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Page Content')
	->where('post_template', '=', 'page-templates/black-friday-2020.php')
	->add_fields(array(
		Field::make('date_time', 'datetime_start', 'Time Start'),
		Field::make('oembed', 'unlocked_video', 'Unlocked Video'),
		Field::make('textarea', 'unlocked_text', 'Unlocked Text'),
		Field::make('complex', 'deals', 'Deals')
			->set_layout('tabbed-vertical')
			->add_fields(array(
				Field::make('image', 'image', 'Image'),
				Field::make('image', 'image_slider', 'Slider Image'),
				Field::make('image', 'logo', 'Logo'),
				Field::make('oembed', 'promote_video', 'Promote Video'),
				Field::make('text', 'off_text', 'Off Text'),
				Field::make('text', 'title', 'Title'),
				Field::make('text', 'subtitle', 'Subtitle'),
				Field::make('textarea', 'text', 'Text'),
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
		Field::make('complex', 'checkout_product', 'Check Out')
			->set_layout('tabbed-vertical')
			->add_fields(array(
				Field::make('image', 'image', 'Image'),
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
		Field::make('rich_text', 'shop_brand_left', 'Shop Brands Left'),
		Field::make('rich_text', 'shop_brand_right', 'Shop Brands Right'),
		Field::make('rich_text', 'trending_now_left', 'Trending Now Left'),
		Field::make('rich_text', 'trending_now_right', 'Trending Now Right'),
	));