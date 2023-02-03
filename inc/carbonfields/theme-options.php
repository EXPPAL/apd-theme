<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

$basic_options_container = Container::make('theme_options', 'Theme Options')
    ->add_fields(array(
        Field::make('text', '_youtube_channel_id', 'YouTube channel ID'),
        Field::make('text', '_google_apis_key', 'Google APIs Key'),
        Field::make('text', '_google_oauth_client_id', 'Google OAuth 2.0 Client ID'),
        Field::make('text', '_google_oauth_client_secret', 'Google OAuth 2.0 Client Secret'),
    ))
    ->add_tab('Shop Carousels', array(
        Field::make('association', '_shop_carousels_product_exclusions', 'Exclude products from shop carousels')
            ->set_types(array(
                array(
                    'type' => 'post',
                    'post_type' => 'product',
                )
            )),
    ));

Container::make('theme_options', __('Rewards Settings'))
    ->set_page_parent($basic_options_container) // reference to a top level container
    ->add_fields(array(
        Field::make('select', '_disable_rewards_and_offer_base_price', 'Disable rewards and offer EVERYTHING at base price')
            ->add_options(array(
                'no' => 'No',
                'yes' => 'Yes',
            )),
        Field::make('association', '_disable_rewards_and_offer_base_price_for_custom_products', 'Disable rewards and offer base price for custom products')
            ->set_types(array(
                array(
                    'type' => 'post',
                    'post_type' => 'product',
                )
            )),
        Field::make('select', '_active_global_reward_percentage', 'Activate custom rewards earn')
            ->add_options(array(
                'yes' => 'Yes',
                'no' => 'No',
            )),
        Field::make('text', '_global_reward_percentage', 'Rewards earn (in percentage)')
            ->set_conditional_logic(array(
                'relation' => 'AND', // Optional, defaults to "AND"
                array(
                    'field' => '_active_global_reward_percentage',
                    'value' => 'yes', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                    'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                )
            )),
    ));
