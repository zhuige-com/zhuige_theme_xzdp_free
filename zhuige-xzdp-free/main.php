<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="main-body mb-20 pt-20">
    <div class="container">
        <div class="row d-flex flex-wrap">

            <!-- 左侧索引 -->
            <aside class="main-nav md-2">
                <?php $sticky_top = 'position: sticky; top: ' . (zhuige_theme_is_show_admin_bar() ? 114 : 82) . 'px;'; ?>
                <div class="zhuige-side-nav" style="<?php echo $sticky_top ?>">
                    <ul>
                        <li class="<?php echo is_home() ? 'active' : '' ?>">
                            <a href="<?php echo home_url() ?>" title="全部产品">
                                <span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/all_icon.png' ?>" alt="图标">
                                </span>
                                <text>全部产品</text>
                            </a>
                        </li>
                        <?php
                        $cat_ids = zhuige_theme_xzdp_option('home_cat_show');
                        if (is_array($cat_ids)) {
                            foreach ($cat_ids as $cat_id) {
                                $term = get_term($cat_id);
                                $term_option = get_term_meta($cat_id, 'zhuige_product_cat_options', true);
                        ?>
                                <li class="<?php echo ($query_obj && ($cat_id == $query_obj->term_id)) ? 'active' : '' ?>">
                                    <a href="<?php echo get_term_link($term) ?>" title="<?php echo $term->name ?>">
                                        <?php if (isset($term_option['logo']) && $term_option['logo']['url']) { ?>
                                            <span>
                                                <img src="<?php echo $term_option['logo']['url'] ?>" alt="<?php echo $term->name ?>">
                                            </span>
                                        <?php } ?>
                                        <text><?php echo $term->name ?></text>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </aside>

            <!-- 中间列表区 -->
            <article class="main-cont md-7">

                <?php
                if (is_home()) {
                ?>
                    <div class="main-cont-block zhuige-prd-group pt-20 pb-20 mb-20">
                        <h1 class="pl-20 pr-20">
                            <text>热门精选</text>
                        </h1>
                        <?php
                        $query = new WP_Query();
                        $result = $query->query([
                            'post_type' => ['zhuige_product'],
                            'post__in' => zhuige_theme_xzdp_option('home_post_recommend'),
                            'orderby' => 'post__in'
                        ]);
                        foreach ($result as $post) {
                            $product = zhuige_theme_xzdp_format_product($post);
                        ?>
                            <!-- 产品/热门/近期列表 -->
                            <div class="zhugi-prd-bg pl-20 pr-20">
                                <div class="zhuige-prd-list">

                                    <!-- 列表操作 -->
                                    <div class="zhuige-prd-opt">
                                        <a href="<?php echo $product['link'] ?>" title="热度">
                                            <p><i class="fa fa-fire-alt"></i></p>
                                            <p><text class="like-count"><?php echo $product['view_count'] ?></text></p>
                                        </a>
                                    </div>

                                    <!-- 列表基础 -->
                                    <div class="zhuige-base-list">
                                        <div class="zhuige-list-img">
                                            <a href="<?php echo $product['link'] ?>" target="_blank">
                                                <img alt="" src="<?php echo $product['logo'] ?>">
                                            </a>
                                        </div>
                                        <div class="zhuige-list-text">
                                            <h6>
                                                <a href="<?php echo $product['link'] ?>" target="_blank" title="<?php echo $product['title'] ?>">
                                                    <text><?php echo $product['title'] ?></text>
                                                </a>
                                            </h6>
                                            <div>
                                                <a href="<?php echo $product['link'] ?>" target="_blank" title="<?php echo $product['title'] ?>">
                                                    <cite><?php echo $product['excerpt'] ?></cite>
                                                </a>
                                            </div>
                                            <p class="pt-10">
                                                <?php
                                                $tag_count = 0;
                                                foreach ($product['tags'] as $tag) {
                                                ?>
                                                    <a href="<?php echo $tag['link'] ?>" title="<?php echo $tag['name'] ?>"><?php echo $tag['name'] ?></a>
                                                <?php
                                                    $tag_count++;
                                                    if ($tag_count >= 4) {
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                <?php
                }
                ?>

                <div class="main-cont-block zhuige-prd-group pt-20 pb-20 mb-20 zhuige-prd-last-con">
                    <?php
                    $param_template = 'index';
                    $param_term_id = '';
                    $param_s = '';

                    if ($query_obj && is_tax('zhuige_product_cat')) {
                        $param_template = 'zhuige_product_cat';
                        $param_term_id = $query_obj->term_id;
                    } else if ($query_obj && is_tax('zhuige_product_tag')) {
                        $param_template = 'zhuige_product_tag';
                        $param_term_id = $query_obj->term_id;
                    } else if (is_search()) {
                        $param_template = 'search';
                        $param_s = sanitize_text_field($_GET['s']);
                    }
                    ?>
                    <h1 class="pl-20 pr-20">
                        <text>
                            <?php
                            if ($query_obj && is_tax('zhuige_product_cat')) {
                                echo '分类：' . $query_obj->name;
                            } else if ($query_obj && is_tax('zhuige_product_tag')) {
                                echo '标签：' . $query_obj->name;
                            } else if (is_search()) {
                                echo '搜索：' . $param_s;
                            } else {
                                echo '近期发布';
                            }
                            ?>
                        </text>
                    </h1>
                    <input type="hidden" class="zhuige-theme-xzdp-template" value="<?php echo $param_template ?>" />
                    <input type="hidden" class="zhuige-theme-xzdp-temr_id" value="<?php echo $param_term_id ?>" />
                    <input type="hidden" class="zhuige-theme-xzdp-s" value="<?php echo $param_s ?>" />
                    <?php
                    $result = zhuige_theme_xzdp_get_products(0, ['template' => $param_template, 'term_id' => $param_term_id, 's' => $param_s, 'sort' => 'last']);
                    echo $result['content'];
                    ?>

                    <!-- 数据为空 -->
                    <?php
                    if (!$result['content']) {
                    ?>
                        <div class="main-cont-block p-20 mb-20">
                            <div class="zhuige-none-tips">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/not_found.png' ?>" alt=" " />
                                <p>暂无数据，随便逛逛...</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php if ($result['more']) { ?>
                    <div class="zhuige-more-btn d-flex justify-content-center p-30 zhuige-btn-more-product">
                        <a href="javascript:void(0)">加载更多</a>
                    </div>
                <?php } ?>

            </article>

            <!-- 右侧边栏 -->
            <aside class="main-side md-3">

                <!-- 侧边栏列表 -->
                <!-- 最新资讯 -->
                <?php
                $home_right_news = zhuige_theme_xzdp_option('home_right_news');
                if (!is_array($home_right_news)) {
                    $home_right_news = [
                        'count' => 4,
                        'title' => '最新新闻'
                    ];
                }

                $query = new WP_Query();
                $result = $query->query([
                    // 'post_type' => ['post'],
                    'posts_per_page' => $home_right_news['count'],
                    'orderby' => 'date',
                ]);
                ?>
                <div class="zhuige-side-block p-10 mb-20">
                    <h3 class="pt-10 pb-10"><?php echo $home_right_news['title'] ?></h3>
                    <ul class="zhuige-side-list">
                        <?php
                        foreach ($result as $post) {
                            $item = zhuige_theme_xzdp_format_post($post);
                        ?>
                            <li class="zhuige-base-list">
                                <?php
                                if ($item['thumb']) {
                                ?>
                                    <div class="zhuige-list-img">
                                        <a href="<?php echo $item['link'] ?>" target="_blank">
                                            <img alt="" src="<?php echo $item['thumb'] ?>">
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="zhuige-list-text">
                                    <a href="<?php echo $item['link'] ?>" target="_blank">
                                        <h6 class="pb-10"><?php echo $item['title'] ?></h6>
                                        <p>
                                            <cite><?php echo get_the_time('Y-m-d', $post) ?></cite>
                                        </p>
                                    </a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- 热门标签 -->
                <?php
                $home_right_tags = zhuige_theme_xzdp_option('home_right_tags');
                if (!is_array($home_right_tags)) {
                    $home_right_tags = [
                        'count' => 6,
                        'title' => '热门标签'
                    ];
                }
                $tags_list = get_terms([
                    'taxonomy' => 'zhuige_product_tag',
                    'number' => $home_right_tags['count'],
                    'orderby' => 'count',
                    'order' => 'DESC'
                ]);
                ?>
                <div class="zhuige-side-block p-10 mb-20">
                    <h3 class="pt-10 pb-10"><?php echo $home_right_tags['title'] ?></h3>
                    <ul class="zhuige-hot-tag">
                        <?php foreach ($tags_list as $tag) { ?>
                            <li>
                                <a href="<?php echo get_tag_link($tag) ?>">
                                    <h5><?php echo $tag->name ?></h5>
                                    <p><?php echo $tag->count ?>个产品</p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- 单图广告 -->
                <?php
                $home_right_ad = zhuige_theme_xzdp_option('home_right_ad');
                if (is_array($home_right_ad)) {
                    foreach ($home_right_ad as $item) {
                ?>
                        <div class="zhuige-side-block zhuige-single-img mb-20">
                            <a href="<?php echo $item['link'] ?>" target="_blank" title="单图广告">
                                <img alt="" src="<?php echo $item['image']['url'] ?>">
                            </a>
                        </div>
                <?php
                    }
                }
                ?>

            </aside>
        </div>
    </div>
</div>