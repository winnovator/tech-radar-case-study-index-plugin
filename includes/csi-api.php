<?php

//API endpoints
register_rest_route('csi-plugin/v1', '/admin-csi/overview', array(
    'methods' => 'GET',
    'callback' => [new AdminCaseStudyIndexRouter(), 'getAllSubData'],
    'permission_callback' => function () {
        return current_user_can('manage_options');
    }
));

register_rest_route('csi-plugin/v1', '/admin-csi/info', array(
    'methods' => 'POST',
    'callback' => [new AdminCaseStudyIndexInfoRouter(), 'postCsiDataSubmit'],
    'permission_callback' => function () {
        return current_user_can('manage_options');
    }
));

register_rest_route('csi-plugin/v1', '/public-csi/overview', array(
    'methods' => 'GET',
    'callback' => [new PublicCaseStudyIndexRouter(), 'getPublicCsiData'],
    'permission_callback' => '__return_true'
));

register_rest_route('csi-plugin/v1', '/public-csi/sub/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => [new PublicCaseStudyIndexRouter(), 'getSinglePublicCsiData'],
    'permission_callback' => '__return_true'
));

register_rest_route('csi-plugin/v1', '/public-csi/sbi/all', array(
    'methods' => 'GET',
    'callback' => [new PublicCaseStudyIndexRouter(), 'getAllSbiData'],
    'permission_callback' => '__return_true'
));