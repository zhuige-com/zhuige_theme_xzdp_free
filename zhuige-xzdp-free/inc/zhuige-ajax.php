<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 追格小站点评主题
 */

/**
 * AjAX
 */
add_action('wp_ajax_nopriv_zhuige_theme_xzdp_event', 'zhuige_theme_xzdp_event');
add_action('wp_ajax_zhuige_theme_xzdp_event', 'zhuige_theme_xzdp_event');
function zhuige_theme_xzdp_event()
{
    $action = isset($_POST["zgaction"]) ? sanitize_text_field(wp_unslash($_POST["zgaction"])) : '';

    if ($action == 'get_products') { // 查询产品
        $offset = isset($_POST["offset"]) ? (int)($_POST["offset"]) : 0;
        $template = isset($_POST["template"]) ? sanitize_text_field($_POST["template"]) : 'index';
        $term_id = isset($_POST["temr_id"]) ? (int)($_POST["temr_id"]) : 0;
        $s = isset($_POST["s"]) ? sanitize_text_field($_POST["s"]) : '';
        $result = zhuige_theme_xzdp_get_products($offset, ['template' => $template, 'term_id' => $term_id, 's' => $s]);
        wp_send_json_success($result);
    } else if ($action == 'get_posts') { // 查询文章
        $offset = isset($_POST["offset"]) ? (int)($_POST["offset"]) : 0;
        $cat = isset($_POST["cat"]) ? (int)($_POST["cat"]) : '';
        $ss = isset($_POST["ss"]) ? sanitize_text_field($_POST["ss"]) : '';
        $result = zhuige_theme_xzdp_get_posts($offset, ['cat' => $cat, 'ss' => $ss]);
        wp_send_json_success($result);
    }

    die;
}

/**
 * 市场相关
 */
add_action('wp_ajax_nopriv_zhuige_market_event', 'zhuige_market_event');
add_action('wp_ajax_zhuige_market_event', 'zhuige_market_event');
function zhuige_market_event()
{
    $action = isset($_POST["zgaction"]) ? sanitize_text_field(wp_unslash($_POST["zgaction"])) : '';

    if ($action == 'get_list') { // 查询产品
        $cat = isset($_POST["cat"]) ? (int)($_POST["cat"]) : 0;
        $params = [];
        if ($cat) {
            $params['cat'] = $cat;
        }

        $free = isset($_POST["free"]) ? sanitize_text_field($_POST["free"]) : '';
        if ($free !== '') {
            $params['free'] = $free;
        }

        $init = isset($_POST["init"]) ? (int)($_POST["init"]) : 0;
        if ($init == 1) {
            $params['init'] = $init;
        }

        $response = wp_remote_post("https://www.zhuige.com/api/market/list", array(
            'method'      => 'POST',
            'body'        => $params
        ));

        if (is_wp_error($response) || $response['response']['code'] != 200) {
            wp_send_json_error();
        }

        $data = json_decode($response['body'], TRUE);
        $datadata = $data['data'];

        if ($data['code'] == 1) {
            wp_send_json_success($datadata);
        } else {
            wp_send_json_error();
        }
    }

    die;
}

/**
 * 首页弹框
 */
add_action('wp_ajax_nopriv_zhuige_home_pop_ad', 'zhuige_home_pop_ad');
add_action('wp_ajax_zhuige_home_pop_ad', 'zhuige_home_pop_ad');
function zhuige_home_pop_ad()
{
    $last_home_ad_pop_time = isset($_COOKIE['last_home_ad_pop_time']) ? $_COOKIE['last_home_ad_pop_time'] : false;
    if ($last_home_ad_pop_time && $last_home_ad_pop_time > time()) {
        wp_send_json_success(['pop' => 0]);
        die;
    }

    $home_ad_pop = zhuige_theme_xzdp_option('home_ad_pop');
    if ($home_ad_pop && $home_ad_pop['switch'] && $home_ad_pop['image'] && $home_ad_pop['image']['url']) {
        $data = [
            'pop' => 1,
            'image' => $home_ad_pop['image']['url'],
            'link' => $home_ad_pop['link'],
        ];

        $expire = time() + (int)$home_ad_pop['interval'] * 3600;
        setcookie('last_home_ad_pop_time', $expire, $expire);

        wp_send_json_success($data);
    } else {
        wp_send_json_success(['pop' => 0]);
    }

    die;
}