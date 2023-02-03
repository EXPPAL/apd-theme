<?php

function subscribe_youtube($accessToken) {
    try {
        // $google_apis_key = carbon_get_theme_option('_google_apis_key');
        $client_id = carbon_get_theme_option('_google_oauth_client_id');
        $client_secret = carbon_get_theme_option('_google_oauth_client_secret');
        $youtube_channel_id = carbon_get_theme_option('_youtube_channel_id');

        // @see https://developers.google.com/youtube/v3/docs/subscriptions/insert
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);

        // Exchange authorization code for an access token.
        // $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
        $client->setAccessToken($accessToken);

        // Define service object for making API requests.
        $service = new Google_Service_YouTube($client);

        // Define the $subscription object, which will be uploaded as the request body.
        $subscription = new Google_Service_YouTube_Subscription();

        // Add 'snippet' object to the $subscription object.
        $subscriptionSnippet = new Google_Service_YouTube_SubscriptionSnippet();
        $resourceId = new Google_Service_YouTube_ResourceId();
        $resourceId->setChannelId($youtube_channel_id);
        $resourceId->setKind('youtube#channel');
        $subscriptionSnippet->setResourceId($resourceId);
        $subscription->setSnippet($subscriptionSnippet);

        $service->subscriptions->insert('snippet', $subscription);
        // $response = $service->subscriptions->insert('snippet', $subscription);
        // var_dump($response);

        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function apd_subscribe_youtube() {
    if (!is_user_logged_in()) {
        wp_send_json_error();
        return;
    }

    $is_used_youtube_subscribe_rewards = is_subscribed_youtube();
    if ($is_used_youtube_subscribe_rewards) {
        wp_send_json_error();
        return;
    }

    $token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';
    if (!$token) {
        wp_send_json_error();
        return;
    }

    $is_subscribed = subscribe_youtube($token);

    if (!$is_subscribed) {
        wp_send_json_error();
        return;
    }

    $current_user = wp_get_current_user();
    $reward_points = get_user_meta($current_user->ID, '_reward_points', true);
    $reward_points = $reward_points ? $reward_points : 0;
    $reward_points += 10;
    update_user_meta($current_user->ID, '_reward_points', $reward_points);
    update_user_meta($current_user->ID, '_youtube_subscribed', 'yes');
    wp_send_json_success();
}

add_action('wp_ajax_subscribe_youtube', 'apd_subscribe_youtube');
