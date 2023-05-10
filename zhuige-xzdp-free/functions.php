<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 追格小站点评主题
 * 文档：https://www.zhuige.com/product/xzdp.html
 */

require_once TEMPLATEPATH . '/inc/codestar-framework/codestar-framework.php';
require_once TEMPLATEPATH . '/inc/admin-options.php';
require_once TEMPLATEPATH . '/inc/zhuige-market.php';
require_once TEMPLATEPATH . '/inc/zhuige-ajax.php';
require_once TEMPLATEPATH . '/inc/zhuige-dashboard.php';
require_once TEMPLATEPATH . '/inc/zhuige-post-type.php';

// ----

remove_filter('template_redirect', 'redirect_canonical');
add_action('init',  function () {
    add_rewrite_rule('^link-go$', 'index.php?zhuige_page=link-go', 'top');

    add_rewrite_rule('^news$', 'index.php?zhuige_page=news', 'top');
    add_rewrite_rule('^news/search/([^/]*)$', 'index.php?zhuige_page=news&search=$matches[1]', 'top');

    add_rewrite_rule('^prd/([0-9]+)\\.html$', 'index.php?post_type=zhuige_product&p=$matches[1]', 'top');
    add_rewrite_rule('^prd/cat/([^/]+)/?$', 'index.php?post_type=zhuige_product&zhuige_product_cat=$matches[1]', 'top');
    add_rewrite_rule('^prd/tag/([^/]+)/?$', 'index.php?post_type=zhuige_product&zhuige_product_tag=$matches[1]', 'top');

    // update_option('rewrite_rules', '');
});

add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'zhuige_page';

    $query_vars[] = 'search';

    return $query_vars;
});

add_action('template_include', function ($template) {
    $zhuige_page = get_query_var('zhuige_page');
    if ($zhuige_page == false || $zhuige_page == '') {
        return $template;
    }

    if ($zhuige_page == 'news') {
        return get_template_directory() . '/archive.php';
    } else if (file_exists(get_template_directory() . '/template/' . $zhuige_page . '.php')) {
        return get_template_directory() . '/template/' . $zhuige_page . '.php';
    }
});

add_filter('post_type_link', 'zhuige_theme_xzdp_sites_link', 1, 3);
function zhuige_theme_xzdp_sites_link($link, $post = null)
{
    if ($post && $post->post_type == 'zhuige_product') {
        return home_url('prd/' . $post->ID . '.html');
    } else {
        return $link;
    }
}

function zhuige_theme_xzdp_category_link($url, $term, $taxonomy)
{
    if ('zhuige_product_cat' == $taxonomy) {
        return home_url('prd/cat/' . $term->slug);
    } else if ('zhuige_product_tag' == $taxonomy) {
        return home_url('prd/tag/' . $term->slug);
    } else {
        return $url;
    }
}
add_filter('term_link', 'zhuige_theme_xzdp_category_link', 10, 3);
// ---

/**
 * 非管理员隐藏后台入口
 */
if (!current_user_can('manage_options')) {
    add_filter('show_admin_bar', '__return_false');
}
function zhuige_theme_is_show_admin_bar()
{
    $user_id = get_current_user_id();
    if (!$user_id) {
        return false;
    }

    if (!current_user_can('manage_options')) {
        return false;
    }

    if (get_user_meta($user_id, 'show_admin_bar_front', true) == 'false') {
        return false;
    }

    return true;
}

/* wp编辑器增加字体和字体大小设置 */
function MBT_add_editor_buttons($buttons)
{
    $buttons[] = 'fontselect';
    $buttons[] = 'fontsizeselect';
    $buttons[] = 'cleanup';
    $buttons[] = 'styleselect';
    $buttons[] = 'del';
    $buttons[] = 'sub';
    $buttons[] = 'sup';
    $buttons[] = 'copy';
    $buttons[] = 'paste';
    $buttons[] = 'cut';
    $buttons[] = 'image';
    $buttons[] = 'anchor';
    $buttons[] = 'backcolor';
    $buttons[] = 'wp_page';
    $buttons[] = 'charmap';
    return $buttons;
}
add_filter("mce_buttons_2", "MBT_add_editor_buttons");

// 切换经典小工具
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');

/**
 * 移除图片的宽高属性
 */
add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);
function remove_width_attribute($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
}

/**
 * 开启特色图功能
 */
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
}

// 在init action处注册脚本，可以与其它逻辑代码放在一起
function zhuige_theme_xzdp_init()
{
    $url = get_template_directory_uri();

    // 注册脚本
    wp_register_script('lib-script', $url . '/js/lib/lb.js', [], '0.1');
    wp_register_script('lib-swiper', $url . '/js/lib/swiper.min.js', [], '5.4.5');
    wp_register_script('lib-layer', $url . '/js/layer/layer.js', ['jquery'], '1.0', false);
    wp_register_script('zhuige-footer-script', $url . '/js/zhuige.footer.js', ['jquery'], '0.1', true);
    wp_register_script('zhuige-single-script', $url . '/js/zhuige.single.js', ['lib-layer'], '0.1', true);

    // 其它需要在init action处运行的脚本
}
add_action('init', 'zhuige_theme_xzdp_init');


function zhuige_theme_xzdp_scripts()
{
    //全局加载js脚本
    wp_enqueue_script('jquery');
    wp_enqueue_script('lib-script');
    wp_enqueue_script('lib-layer');
    wp_enqueue_script('zhuige-footer-script');

    if (is_singular('zhuige_product')) {
        wp_enqueue_script('lib-swiper');
    }

    if (is_single()) {
        wp_enqueue_script('zhuige-single-script');
    }
}
add_action('wp_enqueue_scripts', 'zhuige_theme_xzdp_scripts');

/**
 *  清除谷歌字体 
 */
function jiangqie_remove_open_sans_from_wp_core()
{
    wp_deregister_style('open-sans');
    wp_register_style('open-sans', false);
    wp_enqueue_style('open-sans', '');
}
add_action('init', 'jiangqie_remove_open_sans_from_wp_core');

/**
 * 清除wp_head无用内容 
 */
function remove_dns_prefetch($hints, $relation_type)
{
    if ('dns-prefetch' === $relation_type) {
        return array_diff(wp_dependencies_unique_hosts(), $hints);
    }
    return $hints;
}
function zhuige_theme_xzdp_remove_laji()
{
    remove_action('wp_head', 'wp_generator'); //移除WordPress版本
    remove_action('wp_head', 'rsd_link'); //移除离线编辑器开放接口
    remove_action('wp_head', 'wlwmanifest_link'); //移除离线编辑器开放接口
    remove_action('wp_head', 'index_rel_link'); //去除本页唯一链接信息
    remove_action('wp_head', 'feed_links', 2); //移除feed
    remove_action('wp_head', 'feed_links_extra', 3); //移除feed
    remove_action('wp_head', 'rest_output_link_wp_head', 10); //移除wp-json链
    remove_action('wp_head', 'print_emoji_detection_script', 7); //头部的JS代码
    remove_action('wp_head', 'wp_print_styles', 8); //emoji载入css
    remove_action('wp_head', 'rel_canonical'); //rel=canonical
    add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2); //头部加载DNS预获取（dns-prefetch）
}
add_action('init', 'zhuige_theme_xzdp_remove_laji');


function zhuige_theme_xzdp_setup()
{
    //关键字
    add_action('wp_head', 'zhuige_theme_xzdp_seo_keywords');

    //页面描述 
    add_action('wp_head', 'zhuige_theme_xzdp_seo_description');

    //网站图标
    add_action('wp_head', 'zhuige_theme_xzdp_favicon');
}
add_action('after_setup_theme', 'zhuige_theme_xzdp_setup');

add_action('admin_init', 'zhuige_theme_xzdp_on_admin_init');
add_action('admin_menu', 'zhuige_theme_xzdp_add_admin_menu', 20);
function zhuige_theme_xzdp_add_admin_menu()
{
    add_submenu_page('zhuige-theme-xzdp', '', '安装文档', 'manage_options', 'zhuige_theme_xzdp_setup', 'zhuige_theme_xzdp_handle_external_redirects');
    add_submenu_page('zhuige-theme-xzdp', '', '更多产品', 'manage_options', 'zhuige_theme_xzdp_upgrade', 'zhuige_theme_xzdp_handle_external_redirects');
}

function zhuige_theme_xzdp_on_admin_init()
{
    zhuige_theme_xzdp_handle_external_redirects();
}

function zhuige_theme_xzdp_handle_external_redirects()
{
    if (empty($_GET['page'])) {
        return;
    }

    if ('zhuige_theme_xzdp_setup' === $_GET['page']) {
        wp_redirect('https://www.zhuige.com/product/xzdp.html');
        die;
    }

    if ('zhuige_theme_xzdp_upgrade' === $_GET['page']) {
        wp_redirect('https://www.zhuige.com/product.html?cat=23');
        die;
    }
}

function zhuige_theme_xzdp_sanitize_user($username, $raw_username, $strict)
{
    if (!$strict)
        return $username;

    return sanitize_user(stripslashes($raw_username), false);
}
add_filter('sanitize_user', 'zhuige_theme_xzdp_sanitize_user', 10, 3);

/**
 * 缩略图
 */
function zhuige_theme_xzdp_thumbnail_src()
{
    global $post;
    return zhuige_theme_xzdp_thumbnail_src_d($post->ID, $post->post_content);
}

function zhuige_theme_xzdp_thumbnail_src_d($post_id, $post_content)
{
    $post_thumbnail_src = '';
    if (has_post_thumbnail($post_id)) {    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $post_thumbnail_src = $thumbnail_src[0];
    } else {
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
        if ($matches && isset($matches[1]) && isset($matches[1][0])) {
            $post_thumbnail_src = $matches[1][0];   //获取该图片 src
        }
    };
    return $post_thumbnail_src;
}


/**
 * 美化时间
 */
function zhuige_theme_xzdp_time_ago($ptime)
{
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return '刚刚';
    $interval = array(
        12 * 30 * 24 * 60 * 60  =>  '年前 (' . wp_date('Y-m-d', $ptime) . ')',
        30 * 24 * 60 * 60       =>  '个月前 (' . wp_date('m-d', $ptime) . ')',
        7 * 24 * 60 * 60        =>  '周前 (' . wp_date('m-d', $ptime) . ')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

/**
 * 设置项的值
 */
$zhuige_theme_xzdp_options = null;
if (!function_exists('zhuige_theme_xzdp_option')) {
    function zhuige_theme_xzdp_option($key, $default = '')
    {
        global $zhuige_theme_xzdp_options;
        if (!$zhuige_theme_xzdp_options) {
            $zhuige_theme_xzdp_options = get_option('zhuige-theme-xzdp');
        }

        if (isset($zhuige_theme_xzdp_options[$key])) {
            return $zhuige_theme_xzdp_options[$key];
        }

        return $default;
    }
}


/**
 * 设置文章浏览量
 */
function zhuige_theme_xzdp_inc_post_view($post_id)
{
    $view_count = (int) get_post_meta($post_id, 'view_count', true);
    if (!update_post_meta($post_id, 'view_count', ($view_count + 1))) {
        add_post_meta($post_id, 'view_count', 1, true);
    }
}

/**
 * 获取浏览数
 */
function zhuige_theme_xzdp_get_post_view($post_id)
{
    $view_count = get_post_meta($post_id, "view_count", true);
    if (!$view_count) {
        $view_count = 0;
    }
    return $view_count;
}

/**
 * 摘要
 */
function zhuige_theme_xzdp_excerpt($post, $length = 50)
{
    if ($post->post_excerpt) {
        return html_entity_decode(wp_trim_words($post->post_excerpt, $length, '...'));
    } else {
        return html_entity_decode(wp_trim_words($post->post_content, $length, '...'));
    }
}

/**
 * 面包屑导航
 */
function zhuige_theme_xzdp_breadcrumbs()
{
    $delimiter = '<em> > </em>'; // 分隔符
    $before = '<span class="current">'; // 在当前链接前插入
    $after = '</span>'; // 在当前链接后插入
    if (!is_home() && !is_front_page() || is_paged()) {
        echo '<div class="base-list-nav" itemscope="">' . __('', 'cmp');
        global $post;
        $homeLink = home_url() . '/';
        echo '<a itemprop="breadcrumb" href="' . $homeLink . '">' . __('首页', 'cmp') . '</a> ' . $delimiter . ' ';
        if (is_404()) { // 404 页面
            echo $before;
            _e('404', 'cmp');
            echo $after;
        } else if (is_category()) { // 分类 存档
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) {
                $cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
                echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
            }
            echo $before . '' . single_cat_title('', FALSE) . '' . $after;
        } elseif (is_day()) { // 天 存档
            echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) { // 月 存档
            echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) { // 年 存档
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) { // 文章
            if (get_post_type() != 'post') { // 自定义文章类型
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else { // 文章 post
                $cat = get_the_category();
                $cat = $cat[0];
                $cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
                // echo '<a itemprop="breadcrumb" href="/news/cat/' . $cat->term_id . '">' . $cat->name . '</a>' . $delimiter . ' ';
                echo $before . '正文' . $after;
            }
        } elseif (is_attachment()) { // 附件
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif (is_page() && !$post->post_parent) { // 页面
            echo $before . get_the_title() . $after;
        } elseif (is_page() && $post->post_parent) { // 父级页面
            $parent_id = $post->post_parent;
            $breadcrumbs = [];
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif (is_search()) { // 搜索结果
            printf(__('搜索：%s', 'cmp'), get_search_query());
        } elseif (is_tag()) { //标签 存档
            echo $before;
            printf(__('标签：%s', 'cmp'), single_tag_title('', FALSE));
            echo $after;
        } elseif (is_author()) { // 作者存档
            global $author;
            $userdata = get_userdata($author);
            echo $before;
            printf(__('作者：%s', 'cmp'), $userdata->display_name);
            echo $after;
        } elseif (!is_single() && !is_page() && get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        }

        if (get_query_var('paged')) { // 分页
            if (is_category() || is_day() || is_month() || is_year()  || is_tag() || is_author())
                echo sprintf(__('( Page %s )', 'cmp'), get_query_var('paged'));
        }
        echo '</div>';
    }
}


/* ---- SEO start ---- */
//标题
function zhuige_theme_xzdp_seo_title()
{
    $seo_home = zhuige_theme_xzdp_option('seo_home');
    $site_title = get_bloginfo('name');
    if (is_array($seo_home) && $seo_home['title']) {
        $site_title = $seo_home['title'];
    }

    $title = $site_title;
    if (is_home()) {
        $zhuige_page = get_query_var('zhuige_page');
        if ($zhuige_page) {
            if ($zhuige_page == 'news') {
                $news_title = $site_title;
                $seo_news = zhuige_theme_xzdp_option('seo_news');
                if (is_array($seo_news) && !empty($seo_news['title'])) {
                    $news_title = $seo_news['title'];
                }

                $search = get_query_var('search');
                if ($search) {
                    $title = '搜索：' . urldecode($search) . '_' . $news_title;
                } else {
                    $title = $news_title;
                }
            }
        }
    } else if (is_search()) {
        global $s;
        $title = '搜索：' . $s . '_' . $site_title;
    } else if (is_tax('zhuige_product_cat') || is_tax('zhuige_product_tag') || is_category() || is_tag()) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        $title = $query_obj->name . '_' . $site_title;
    } else if (is_single()) {
        global $post;
        $title = $post->post_title . '_' . $site_title;
    } else if (is_page()) {
        global $post;
        $title = $post->post_title . '_' . $site_title;
    }

    global $page, $paged;
    if ($paged >= 2 || $page >= 2) {
        $title .= ' - ' . sprintf('第%s页', max($paged, $page));
    }

    echo $title;
}

//关键字
function zhuige_theme_xzdp_seo_keywords()
{
    $keywords = '';
    $seo_home = zhuige_theme_xzdp_option('seo_home');
    if (is_array($seo_home) && !empty($seo_home['keywords'])) {
        $keywords = $seo_home['keywords'];
    }

    if (is_home()) {
        $zhuige_page = get_query_var('zhuige_page');
        if ($zhuige_page) {
            if ($zhuige_page == 'news') {
                $seo_news = zhuige_theme_xzdp_option('seo_news');
                if (is_array($seo_news) && !empty($seo_news['keywords'])) {
                    $keywords = $seo_news['keywords'];
                }

                $search = get_query_var('search');
                if ($search) {
                    $keywords = urldecode($search) . ',' . $keywords;
                }
            }
        }
    } else if (is_search()) {
        global $s;
        $keywords = $s . ',' . $keywords;
    } else if (is_tax('zhuige_product_cat')) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        $options = get_term_meta($query_obj->term_id, 'zhuige_product_cat_options', true);
        $keywords = (is_array($options) && !empty($options['keywords']) ? $options['keywords'] : $query_obj->name);
    } else if (is_tax('zhuige_product_tag')) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        $options = get_term_meta($query_obj->term_id, 'zhuige_product_tag_options', true);
        $keywords = (is_array($options) && !empty($options['keywords']) ? $options['keywords'] : $query_obj->name);
    } else if (is_category()) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        $options = get_term_meta($query_obj->term_id, 'zhuige_category_options', true);
        $keywords = (is_array($options) && !empty($options['keywords']) ? $options['keywords'] : $query_obj->name);
    } else if (is_tag()) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        $options = get_term_meta($query_obj->term_id, 'zhuige_post_tag_options', true);
        $keywords = (is_array($options) && !empty($options['keywords']) ? $options['keywords'] : $query_obj->name);
    } else if (is_single()) {
        global $post;
        $tags = [];
        if ($post->post_type == 'post') {
            $terms = get_the_tags($post->ID);
            if ($terms) {
                foreach ($terms as $tag) {
                    $tags[] = $tag->name;
                }
            }

            $cats = get_the_category($post->ID);
            foreach ($cats as $category) {
                $tags[] = $category->cat_name;
            }

            if (!empty($tags)) {
                $keywords = implode(',', $tags);
            }
        } else if ($post->post_type == 'zhuige_product') {
            $terms = get_the_terms($post, 'zhuige_product_tag');
            if (is_array($terms)) {
                foreach ($terms as $term) {
                    $tags[] = $term->name;
                }
            }

            $terms = get_the_terms($post, 'zhuige_product_cat');
            if (is_array($terms)) {
                foreach ($terms as $term) {
                    $tags[] = $term->name;
                }
            }

            if (!empty($tags)) {
                $keywords = implode(',', $tags);
            }
        }
    }

    if ($keywords) {
        echo "<meta name=\"keywords\" content=\"$keywords\">\n";
    }
}

//描述
function zhuige_theme_xzdp_seo_description()
{
    $description = get_bloginfo('description');
    $seo_home = zhuige_theme_xzdp_option('seo_home');
    if (is_array($seo_home) && !empty($seo_home['description'])) {
        $description = $seo_home['description'];
    }

    if (is_home()) {
        $zhuige_page = get_query_var('zhuige_page');
        if ($zhuige_page == 'news') {
            $seo_news = zhuige_theme_xzdp_option('seo_news');
            if (is_array($seo_news) && !empty($seo_news['description'])) {
                $description = $seo_news['description'];
            }

            $search = get_query_var('search');
            if ($search) {
                $description = '在' . get_bloginfo('name') . '搜索：' . urldecode($search) . ' 的结果';
            }
        }
    } else if (is_search()) {
        global $s;
        $description = '在' . get_bloginfo('name') . '搜索：' . $s . ' 的结果';
    } else if (is_tax('zhuige_product_cat')) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        if ($query_obj->description) {
            $description = $query_obj->description;
        } else {
            $description = '分类：' . $query_obj->name . ' 下的产品';
        }
    } else if (is_tax('zhuige_product_tag')) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        if ($query_obj->description) {
            $description = $query_obj->description;
        } else {
            $description = '标签：' . $query_obj->name . ' 下的产品';
        }
    } else if (is_category()) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        if ($query_obj->description) {
            $description = $query_obj->description;
        } else {
            $description = '分类：' . $query_obj->name . ' 下的文章';
        }
    } else if (is_tag()) {
        global $wp_query;
        $query_obj = $wp_query->get_queried_object();
        if ($query_obj->description) {
            $description = $query_obj->description;
        } else {
            $description = '标签：' . $query_obj->name . ' 下的文章';
        }
    } else if (is_single()) {
        global $post;
        $description =  html_entity_decode(wp_trim_words($post->post_content, 120, '...'));
    } else if (is_page()) {
        global $post;
        $description =  html_entity_decode(wp_trim_words($post->post_content, 120, '...'));
    }

    if ($description) {
        $description = mb_substr($description, 0, 220, 'utf-8');
        echo "<meta name=\"description\" content=\"$description\">\n";
    }
}
/* ---- SEO end ---- */

/**
 * 站点LOGO
 */
function zhuige_theme_xzdp_logo()
{
    $logo = zhuige_theme_xzdp_option('site_logo');
    if ($logo && $logo['url']) {
        echo '<img alt="picture loss" src="' . $logo['url'] . '" alt="' . get_bloginfo('name') . '" />';
    } else {
        echo '<img alt="picture loss" src="' . get_stylesheet_directory_uri() . '/images/default_logo.png' . '" alt="' . get_bloginfo('name') . '" />';
    }
}

/**
 * favicon
 */
function zhuige_theme_xzdp_favicon()
{
    $favicon = zhuige_theme_xzdp_option('site_favicon');
    if ($favicon && $favicon['url']) {
        echo '<link rel="shortcut icon" type="image/x-icon" href="' . $favicon['url'] . '" />';
    } else {
        echo '';
    }
}

/**
 * 评论样式
 */
function zhuige_theme_xzdp_comment_list($comment, $args, $depth)
{
?>
    <div class="zhuige-comment-line zhuige-comment-line-depth-<?php echo $depth ?>">
        <div class="pt-10 pb-10 d-flex align-items-center justify-content-between">
            <div class="zhuige-base-list">
                <div class="zhuige-list-img">
                    <a href="javascript:void(0)">
                        <?php echo zhuige_user_avatar($comment->user_id); ?>
                    </a>
                </div>
                <div class="zhuige-list-text">
                    <h6>
                        <?php
                        if ($comment->user_id) {
                            $nickname = get_user_meta($comment->user_id, 'nickname', true);
                        } else {
                            $nickname = $comment->comment_author;
                        }
                        ?>
                        <a href="<?php echo $user_site ?>" title="<?php echo $nickname ?>" target="_blank">
                            <text><?php echo $nickname ?></text>
                        </a>
                        <cite><?php echo zhuige_theme_xzdp_time_ago(get_comment_time('Y-m-d H:i:s', true)) ?></cite>
                    </h6>
                    <p>
                        <?php echo get_comment_text() ?>
                    </p>
                </div>
            </div>
            <div class="zhuige-comment-opt d-flex">
                <?php if ($depth < $args['max_depth']) { ?>
                    <!-- <a href="javascript:void(0)" data-comment_id="<?php echo $comment->comment_ID ?>" data-nickname="<?php echo get_user_meta($comment->user_id, 'nickname', true) ?>" class="zhuige-comment-btn-reply" title="回复">回复</a> -->
                <?php
                    echo comment_reply_link(array_merge($args, array('respond_id' => 'product-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'])));
                }
                ?>
            </div>
        </div>
    </div>
<?php
}

/**
 * 追格头像
 */
function zhuige_user_avatar($user_id)
{
    $avatar = get_user_meta($user_id, 'zhuige_user_avatar', true);
    if (empty($avatar)) {
        $avatar = get_stylesheet_directory_uri() . '/images/default_avatar.jpg';
    }

    return '<img alt="picture loss" src="' . $avatar . '" />';
}

/**
 * 格式化文章列表项
 */
function zhuige_theme_xzdp_format_post($post, $require_thumb = false)
{
    $item = [
        'id' => $post->ID,
        'title' => $post->post_title,
        'link' => get_permalink($post->ID)
    ];

    $thumb = zhuige_theme_xzdp_thumbnail_src_d($post->ID, $post->post_content);
    if ($require_thumb && empty($thumb)) {
        $thumb = get_stylesheet_directory_uri() . '/images/placeholder.png';;
    }
    $item["thumb"] = $thumb;

    $item["excerpt"] = zhuige_theme_xzdp_excerpt($post, zhuige_theme_xzdp_option('news_excerpt_length', 120));

    $item['view_count'] = zhuige_theme_xzdp_get_post_view($post->ID);

    $item['time'] = zhuige_theme_xzdp_time_ago($post->post_date_gmt);

    return $item;
}

/**
 * 格式化产品列表项
 */
function zhuige_theme_xzdp_format_product($post)
{
    $product = [
        'id' => $post->ID,
        'title' => $post->post_title,
        'link' => get_permalink($post->ID)
    ];

    $product["excerpt"] = zhuige_theme_xzdp_excerpt($post, zhuige_theme_xzdp_option('home_excerpt_length', 120));

    $options = get_post_meta($post->ID, 'zhuige_product_options', true);

    $product['logo'] = $options['logo']['url'];

    $terms = get_the_terms($post, 'zhuige_product_tag');
    $tags = [];
    if (is_array($terms)) {
        foreach ($terms as $term) {
            $tags[] = [
                'name' => $term->name,
                'link' =>  get_term_link($term->term_id)
            ];
        }
    }
    $product['tags'] = $tags;

    $product['view_count'] = zhuige_theme_xzdp_get_post_view($post->ID);

    return $product;
}

/**
 * 获取当前的URL
 */
function zhuige_theme_xzdp_url()
{
    $pageURL = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * 用户签名
 */
function zhuige_theme_xzdp_user_sign($user_id)
{
    $sign = get_user_meta($user_id, 'zhuige_theme_xzdp_sign', true);
    if (empty($sign)) {
        $sign = '这个用户有点懒，什么都没写~';
    }
    return $sign;
}

/**
 * 截取url中的模块 第一个/和第二个/之间的认为是模块
 */
function zhuige_url_module($url)
{
    $index = stripos($url, '://');
    if ($index > -1) {
        $url = substr($url, $index + strlen('://'));
    }
    // echo $url;
    $index = stripos($url, '/');
    if ($index > -1) {
        $url = substr($url, $index + strlen('/'));
    }
    // echo $url;
    $index = stripos($url, '/');
    if ($index > -1) {
        $url = substr($url, 0, $index);
    }
    // echo $url;
    $index = stripos($url, '?');
    if ($index > -1) {
        $url = substr($url, 0, $index);
    }
    // echo $url;
    return $url;
}

/**
 * 获取产品列表
 */
function zhuige_theme_xzdp_get_products($offset, $params)
{
    $query = new WP_Query();
    $posts_per_page = 10;
    $args = [
        'post_type' => ['zhuige_product'],
        'offset' => $offset,
        'posts_per_page' => $posts_per_page,
        'orderby' => 'date',
        'post_status' => 'publish'
    ];

    if ($params['template'] == 'zhuige_product_cat') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'zhuige_product_cat',
                'field'    => 'term_id',
                'terms'    => $params['term_id'],
            ]
        ];
    } else if ($params['template'] == 'zhuige_product_tag') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'zhuige_product_tag',
                'field'    => 'term_id',
                'terms'    => $params['term_id'],
            ]
        ];
    } else if ($params['template'] == 'search') {
        $args['s'] = $params['s'];
    }

    $result = $query->query($args);
    $content = '';
    foreach ($result as $post) {
        $product = zhuige_theme_xzdp_format_product($post);

        // 产品/热门/近期列表
        $content .= '<div class="zhugi-prd-bg pl-20 pr-20 slide-in">';
        $content .= '<div class="zhuige-prd-list zhuige-prd-for-ajax-count">';

        $content .= '<div class="zhuige-prd-opt">';
        $content .= '<a href="' . $product['link'] . '" target="_blank" title="热度">';
        $content .= '<p><i class="fa fa-fire-alt"></i></p>';
        $content .= '<p><text>' . $product['view_count'] . '</text></p>';
        $content .= '</a>';
        $content .= '</div>';

        // 列表基础
        $content .= '<div class="zhuige-base-list">';
        $content .= '<div class="zhuige-list-img">';
        $content .= '<a href="' . $product['link'] . '" target="_blank">';
        $content .= '<img alt="' . $product['title'] . '" src="' . $product['logo'] . '">';
        $content .= '</a>';
        $content .= '</div>';
        $content .= '<div class="zhuige-list-text">';
        $content .= '<h6>';
        $content .= '<a target="_blank" href="' . $product['link'] . '" title="' . $product['title'] . '">';
        $content .= '<text>' . $product['title'] . '</text>';
        $content .= '</a>';

        $content .= '</cite>';

        $content .= '</h6>';
        $content .= '<div>';
        $content .= '<a href="' . $product['link'] . '" title="' . $product['title'] . '">';
        $content .= '<cite>' . $product['excerpt'] . '</cite>';
        $content .= '</a>';
        $content .= '</div>';
        $content .= '<p class="pt-10">';

        $tag_count = 0;
        foreach ($product['tags'] as $tag) {
            $content .= '<a href="' . $tag['link'] . '" title="' . $tag['name'] . '">' . $tag['name'] . '</a>';
            $tag_count++;
            if ($tag_count >= 4) {
                break;
            }
        }

        $content .= '</p>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
    }

    return ['content' => $content, 'more' => (count($result) == $posts_per_page), 'args' => $args];
}

/**
 * 格式化文章
 */
function zhuige_theme_xzdp_post_string($post, $show_sticky = false)
{
    $content = '';
    $item = zhuige_theme_xzdp_format_post($post);
    // 列表基础
    if (is_sticky($post->ID)) {
        $content .= '<div class="zhuige-base-list mb-20 slide-in">';
    } else {
        $content .= '<div class="zhuige-base-list mb-20 zhuige-post-for-ajax-count slide-in">';
    }

    if ($item['thumb']) {
        $content .= '<div class="zhuige-list-img">';

        $categories = get_the_category($post->ID);

        $content .= '<a href="' . get_category_link($categories[0]->cat_ID) . '" class="zhuige-list-type" title="">' . $categories[0]->cat_name . '</a>';
        $content .= '<a href="' . $item['link'] . '" target="_blank">';
        $content .= '<img alt="" src="' . $item['thumb'] . '">';
        $content .= '</a>';
        $content .= '</div>';
    }
    $content .= '<div class="zhuige-list-text">';
    $content .= '<h6>';
    $content .= '<a href="' . $item['link'] . '" title="' . $item['title'] . '">';

    if ($show_sticky && is_sticky($post->ID)) {
        $content .= '<span>置顶</span>';
    }

    $content .= '<text>' . $item['title'] . '</text>';
    $content .= '</a>';
    $content .= '</h6>';
    $content .= '<div class="overFlow-n mt-10 mb-10">';
    $content .= '<a href="' . $item['link'] . '" title="' . $item['title'] . '">';
    $content .= $item['excerpt'];
    $content .= '</a>';
    $content .= '</div>';
    $content .= '<p class="pt-10">';
    $content .= '<span>' . $item['time'] . '</span>';
    $content .= '<span>浏览 ' . $item['view_count'] . '</span>';
    $content .= '<a href="' . $item['link'] . '" title="评论">评论' . $item['comment_count'] . '</a>';
    $content .= '</p>';
    $content .= '</div>';
    $content .= '</div>';

    return $content;
}

/**
 * 获取置顶的文章
 */
function zhuige_theme_xzdp_get_sticky_posts()
{
    $sticks = get_option('sticky_posts');
    if (empty($sticks)) {
        return '';
    }

    $query = new WP_Query();
    $args = [
        'post_type' => 'post',
        'orderby' => 'date',
        'post__in' => $sticks,
        'ignore_sticky_posts' => 1
    ];

    $result = $query->query($args);
    $content = '';
    foreach ($result as $post) {
        $content .= zhuige_theme_xzdp_post_string($post, true);
    }

    return $content;
}

/**
 * 获取文章列表
 */
function zhuige_theme_xzdp_get_posts($offset, $params)
{
    $query = new WP_Query();
    $posts_per_page = 10;
    $args = [
        'post_type' => 'post',
        'offset' => $offset,
        'posts_per_page' => $posts_per_page,
        'orderby' => 'date',
        'ignore_sticky_posts' => 1
    ];

    if (isset($params['cat']) && $params['cat']) {
        $args['cat'] = $params['cat'];
    } else if (isset($params['ss']) && $params['ss']) {
        $args['s'] = $params['ss'];
    } else if (isset($params['tag']) && $params['tag']) {
        $args['tag_id'] = $params['tag'];
    } else {
        $args['post__not_in'] = get_option('sticky_posts');
    }

    $result = $query->query($args);
    $content = '';
    foreach ($result as $post) {
        $content .= zhuige_theme_xzdp_post_string($post);
    }

    return ['content' => $content, 'more' => (count($result) >= $posts_per_page)];
}
