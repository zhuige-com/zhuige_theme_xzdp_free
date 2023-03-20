<?php

/**
 * 追格小站点评主题
 */

if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'zhuige-theme-xzdp';

//
// Create options
//
CSF::createOptions($prefix, array(
    'framework_title' => '追格小站点评主题 <small>by <a href="https://www.zhuige.com" target="_blank" title="追格">www.zhuige.com</a></small>',
    'menu_title' => '追格小站点评主题',
    'menu_slug'  => 'zhuige-theme-xzdp',
    'menu_position' => 2,
    'show_bar_menu' => false,
    'show_sub_menu' => false,
    'footer_credit' => 'Thank you for creating with <a href="https://www.zhuige.com/" target="_blank">追格</a>',
    'menu_icon' => 'dashicons-layout',
));

$content = '欢迎使用追格小站点评主题! <br/><br/> 微信客服：jianbing2011 (加开源群、问题咨询、项目定制、购买咨询) <br/><br/> <a href="https://www.zhuige.com/product" target="_blank">更多免费产品</a>';
if (stripos($_SERVER["REQUEST_URI"], 'zhuige-theme-xzdp')) {
    $res = wp_remote_get("https://www.zhuige.com/api/ad/wordpress?id=zhuige_theme_xzdp", ['timeout' => 1, 'sslverify' => false]);
    if (!is_wp_error($res) && $res['response']['code'] == 200) {
        $data = json_decode($res['body'], TRUE);
        if ($data['code'] == 1) {
            $content = $data['data'];
        }
    }
}

//
// 概要
//
CSF::createSection($prefix, array(
    'title'  => '概要',
    'icon'   => 'fas fa-rocket',
    'fields' => array(

        array(
            'type'    => 'content',
            'content' => $content,
        ),

    )
));

//
// 基础设置
//
CSF::createSection($prefix, array(
    'title' => '基础设置',
    'icon'  => 'fas fa-apple-alt',
    'fields' => array(

        array(
            'id'      => 'site_logo',
            'type'    => 'media',
            'title'   => 'LOGO设置',
            'library' => 'image',
        ),

        array(
            'id'      => 'site_favicon',
            'type'    => 'media',
            'title'   => 'favicon',
            'subtitle' => '.ico格式',
            'library' => 'image',
        ),

    )
));

//
// 菜单设置
//
CSF::createSection($prefix, array(
    'title' => '菜单设置',
    'icon'  => 'fas fa-hamburger',
    'fields' => array(

        array(
            'id'     => 'site_nav',
            'type'   => 'group',
            'title'  => '顶部菜单',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '链接',
                    'default'  => 'https://www.zhuige.com',
                    'validate' => 'csf_validate_url',
                ),
                array(
                    'id'    => 'blank',
                    'type'  => 'switcher',
                    'title' => '新页面打开',
                    'default' => ''
                ),
                array(
                    'id'    => 'switch',
                    'type'  => 'switcher',
                    'title' => '是否启用',
                    'default' => '1'
                ),
            ),
        ),

        array(
            'id'     => 'h5_tabbar',
            'type'   => 'group',
            'title'  => 'H5导航',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'      => 'icon',
                    'type'    => 'icon',
                    'title'   => '图标',
                    'default' => 'fas fa-at',
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '广告链接',
                    'default'  => 'https://www.zhuige.com',
                    'validate' => 'csf_validate_url',
                ),
                array(
                    'id'    => 'blank',
                    'type'  => 'switcher',
                    'title' => '新页面打开',
                    'default' => ''
                ),
                array(
                    'id'    => 'switch',
                    'type'  => 'switcher',
                    'title' => '是否启用',
                    'default' => '1'
                ),
            ),
        ),
    )
));

//
// 首页设置
//
CSF::createSection($prefix, array(
    'title' => '首页设置',
    'icon'  => 'fas fa-home',
    'fields' => array(

        array(
            'id'     => 'home_header',
            'type'   => 'fieldset',
            'title'  => '头部设置',
            'fields' => array(
                array(
                    'id'      => 'bg_image',
                    'type'    => 'media',
                    'title'   => '背景图片',
                    'library' => 'image',
                ),
                array(
                    'id'    => 'bg_video',
                    'type'  => 'text',
                    'title' => '背景视频地址',
                    'subtitle' => '可选，只在PC显示'
                ),
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'          => 'tip',
                    'type'        => 'text',
                    'title'       => '搜索提示',
                    'placeholder' => '搜索提示'
                ),
                array(
                    'id'          => 'hot_words',
                    'type'        => 'text',
                    'title'       => '热门搜索词',
                    'subtitle'    => '请用英文逗号分割'
                ),
            ),
        ),

        array(
            'id'          => 'home_cat_show',
            'type'        => 'select',
            'title'       => '显示分类',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'placeholder' => 'Select an option',
            'options'     => 'categories',
            'query_args'  => array(
                'taxonomy'  => 'zhuige_product_cat',
            ),
        ),

        array(
            'id'          => 'home_post_recommend',
            'type'        => 'select',
            'title'       => '精选产品',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'options'     => 'posts',
            'placeholder' => '请选择文章',
            'query_args'  => array(
                'post_type' => 'zhuige_product',
            ),
        ),

        array(
            'id'     => 'home_right_news',
            'type'   => 'fieldset',
            'title'  => '近期资讯',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题',
                    'default'     => '近期资讯'
                ),
                array(
                    'id'       => 'count',
                    'type'     => 'spinner',
                    'title'    => '显示个数',
                    'subtitle' => 'max:100 | min:0 | step:1',
                    'max'      => 100,
                    'min'      => 0,
                    'step'     => 1,
                    'default'  => 2,
                ),
            ),
        ),

        array(
            'id'     => 'home_right_tags',
            'type'   => 'fieldset',
            'title'  => '热门标签',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题',
                    'default'     => '热门标签'
                ),
                array(
                    'id'       => 'count',
                    'type'     => 'spinner',
                    'title'    => '显示个数',
                    'subtitle' => 'max:100 | min:0 | step:1',
                    'max'      => 100,
                    'min'      => 0,
                    'step'     => 1,
                    'default'  => 6,
                ),
            ),
        ),

        array(
            'id'     => 'home_right_ad',
            'type'   => 'group',
            'title'  => '右侧广告',
            'fields' => array(
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => '图片',
                    'library' => 'image',
                ),
                array(
                    'id'       => 'link',
                    'type'     => 'text',
                    'title'    => '广告链接',
                    'default'  => 'https://www.zhuige.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'       => 'home_excerpt_length',
            'type'     => 'spinner',
            'title'    => '摘要长度',
            'subtitle' => 'max:100 | min:0 | step:1',
            'max'      => 100,
            'min'      => 0,
            'step'     => 1,
            'default'  => 25,
        ),
    )
));

//
// 资讯设置
//
CSF::createSection($prefix, array(
    'title' => '资讯设置',
    'icon'  => 'fas fa-newspaper',
    'fields' => array(

        array(
            'id'     => 'news_slide',
            'type'   => 'group',
            'title'  => '幻灯片',
            'fields' => array(
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => '背景',
                    'library' => 'image',
                ),
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '广告链接',
                    'default'  => 'https://www.zhuige.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'          => 'news_nav_cat',
            'type'        => 'select',
            'title'       => '显示分类',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'placeholder' => 'Select an option',
            'options'     => 'category'
        ),

        array(
            'id'       => 'news_excerpt_length',
            'type'     => 'spinner',
            'title'    => '文章摘要长度',
            'subtitle' => 'max:100 | min:0 | step:1',
            'max'      => 100,
            'min'      => 0,
            'step'     => 1,
            'default'  => 25,
        ),
    )
));

//
// 产品详情
//
CSF::createSection($prefix, array(
    'title' => '产品详情',
    'icon'  => 'fas fa-atom',
    'fields' => array(

        array(
            'id'          => 'product_copyright',
            'type'        => 'textarea',
            'title'       => '版权信息',
            'placeholder' => '版权信息',
        ),

        array(
            'id'          => 'product_link_go_title',
            'type'        => 'text',
            'title'       => '链接跳转标题',
            'placeholder' => '',
            'default'     => '一键直达'
        ),

        array(
            'id'          => 'product_link_go_tip',
            'type'        => 'text',
            'title'       => '链接跳转提示',
            'placeholder' => '',
            'default'     => '即将离开本站，要继续吗？'
        ),
    )
));

//
// 页脚设置
//
CSF::createSection($prefix, array(
    'title' => '页脚设置',
    'icon'  => 'fas fa-chalkboard',
    'fields' => array(

        array(
            'id'    => 'footer_copyright',
            'type'  => 'wp_editor',
            'title' => '页脚版权',
        ),

        array(
            'id'     => 'footer_nav',
            'type'   => 'group',
            'title'  => '快速导航',
            'fields' => array(
                array(
                    'id'       => 'title',
                    'type'     => 'text',
                    'title'    => '标题',
                    'default'  => '',
                ),
                array(
                    'id'       => 'url',
                    'type'     => 'text',
                    'title'    => '链接',
                    'default'  => 'https://www.zhuige.com',
                    'validate' => 'csf_validate_url',
                ),
            ),
        ),

        array(
            'id'       => 'footer_statistics',
            'type'     => 'code_editor',
            'title'    => '网站统计',
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'default' => '',
        ),
    )
));

//
// SEO设置
//
CSF::createSection($prefix, array(
    'title' => 'SEO设置',
    'icon'  => 'fas fa-bolt',
    'fields' => array(

        array(
            'id'     => 'seo_home',
            'type'   => 'fieldset',
            'title'  => '首页',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),

                array(
                    'id'          => 'keywords',
                    'type'        => 'text',
                    'title'       => '关键词',
                    'placeholder' => '关键词',
                    'after'    => '<p>请用英文逗号分割.</p>',
                ),

                array(
                    'id'          => 'description',
                    'type'        => 'textarea',
                    'title'       => '描述',
                    'placeholder' => '描述',
                ),
            ),
        ),

        array(
            'id'     => 'seo_news',
            'type'   => 'fieldset',
            'title'  => '资讯首页',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),

                array(
                    'id'          => 'keywords',
                    'type'        => 'text',
                    'title'       => '关键词',
                    'placeholder' => '关键词',
                    'after'    => '<p>请用英文逗号分割.</p>',
                ),

                array(
                    'id'          => 'description',
                    'type'        => 'textarea',
                    'title'       => '描述',
                    'placeholder' => '描述',
                ),
            ),
        ),
    )
));

//
// 关于页面
//
CSF::createSection($prefix, array(
    'title' => '关于页面',
    'icon'  => 'fab fa-wordpress',
    'fields' => array(

        array(
            'id'          => 'about_nav',
            'type'        => 'select',
            'title'       => '选择页面',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'placeholder' => 'Select an option',
            'options'     => 'pages'
        ),

    )
));


//
// 备份
//
CSF::createSection($prefix, array(
    'title'       => '备份',
    'icon'        => 'fas fa-shield-alt',
    'fields'      => array(
        array(
            'type' => 'backup',
        ),
    )
));
