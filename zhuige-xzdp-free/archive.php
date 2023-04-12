<?php
/*
Template Name: 追格-资讯
*/

if (!defined('ABSPATH')) {
	exit;
}

get_header();

global $wp_query;
$query_obj = $wp_query->get_queried_object();

$cur_cat_id = '';
if (is_category()) {
	$cur_cat_id = $query_obj->term_id;
}

$cur_tag_id = '';
if (is_tag()) {
	$cur_tag_id = $query_obj->term_id;
}

// $cur_cat_id = isset($wp_query->query_vars['cat_id']) ? $wp_query->query_vars['cat_id'] : '';
// $cur_tag_id = isset($wp_query->query_vars['tag_id']) ? $wp_query->query_vars['tag_id'] : '';
$cur_search = isset($wp_query->query_vars['search']) ? urldecode($wp_query->query_vars['search']) : '';

?>

<!-- 主内容区 -->
<div class="main-body mb-20 pt-20">
	<div class="container nav-fix">
		<div class="row d-flex justify-content-center">

			<!-- 中间列表区 -->
			<article class="main-cont md-9">

				<?php
				if (empty($cur_tag_id) && empty($cur_search)) {
				?>
					<!-- 顶部轮播 -->
					<?php
					$news_slide = zhuige_theme_xzdp_option('news_slide');
					if (is_array($news_slide)) {
					?>
						<div class="zhuige-img-news mb-20">
							<div class="lb-box" id="lb-1">
								<!-- 轮播内容 -->
								<div class="lb-content">
									<?php
									foreach ($news_slide as $slide) {
									?>
										<div class="lb-item active">
											<a href="<?php echo $slide['url'] ?>" target="_blank" title="<?php echo $slide['title'] ?>">
												<img src="<?php echo $slide['image']['url'] ?>" alt="">
												<div>
													<h2><?php echo $slide['title'] ?></h2>
												</div>
											</a>
										</div>
									<?php
									}
									?>
								</div>
								<!-- 轮播标志 -->
								<ol class="lb-sign">
									<?php for ($i = 1; $i <= count($news_slide); $i++) :
										if ($i == 1) echo '<li class="active">' . $i . '</li>';
										else echo '<li>' . $i . '</li>';
									endfor; ?>
								</ol>
								<!-- 轮播控件 -->
								<div class="lb-ctrl left"><i class="fa fa-chevron-left fa-lg"></i></div>
								<div class="lb-ctrl right"><i class="fa fa-chevron-right fa-lg"></i></div>
							</div>
						</div>
				<?php
					}
				}
				?>

				<!-- 顶部tab -->
				<?php $sticky_top = 'top: ' . (zhuige_theme_is_show_admin_bar() ? 94 : 62) . 'px;'; ?>
				<div class="zhuige-news-tab d-flex align-items-center justify-content-between p-20" style="<?php echo $sticky_top ?>">

					<div class="zhuige-base-tab d-flex">
						<?php
						if (empty($cur_tag_id) && empty($cur_search)) {
						?>
							<a href="/news" class="<?php echo ($cur_cat_id == '' ? 'active' : '') ?>" title="近期发布">近期发布</a>
							<?php
							$cat_ids = zhuige_theme_xzdp_option('news_nav_cat');
							if (is_array($cat_ids)) {
								foreach ($cat_ids as $cat_id) {
									$category = get_category($cat_id);
									if ($category) {
							?>
										<a href="<?php echo get_category_link($cat_id) ?>" title="<?php echo $category->name ?>" class="<?php echo ($cur_cat_id == $cat_id ? 'active' : '') ?>"><?php echo $category->name ?></a>
						<?php
									}
								}
							}
						} else if ($cur_tag_id) {
							$tag = get_tag($cur_tag_id);
							echo '标签：' . $tag->name;
						} else if ($cur_search) {
							echo '搜索：' . $cur_search;
						}
						?>

					</div>

					<div class="zhuige-mini-search d-flex align-items-center">
						<input type="search" class="input-keyword-news" placeholder="关键词..." required autocomplete="off">
						<a href="javascript:void(0)" class="zhuige-btn-search-news" title="搜索">
							<i class="fa fa-search"></i>
						</a>
					</div>
				</div>

				<!-- 资讯大列表 -->
				<div class="zhuige-news-list pt-20 mb-20">
					<input type="hidden" class="zhuige-theme-xzdp-cat" value="<?php echo $cur_cat_id ?>" />
					<input type="hidden" class="zhuige-theme-xzdp-tag" value="<?php echo $cur_tag_id ?>" />
					<input type="hidden" class="zhuige-theme-xzdp-ss" value="<?php echo $cur_search ?>" />
					<?php
					if ($cur_cat_id == '' && $cur_tag_id == '' && $cur_search == '') {
						echo zhuige_theme_xzdp_get_sticky_posts();
					}

					$result = zhuige_theme_xzdp_get_posts(0, ['cat' => $cur_cat_id, 'tag' => $cur_tag_id, 'ss' => $cur_search]);
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
					<div class="zhuige-more-btn d-flex justify-content-center p-30">
						<a href="javascript:void(0)" title="查看更多">查看更多</a>
					</div>
				<?php } ?>

			</article>

		</div>
	</div>
</div>

<!--轮播选项-->
<script>
	window.onload = function() {
		const options = {
			id: 'lb-1', // 轮播盒ID
			speed: 600, // 轮播速度(ms)
			delay: 3000, // 轮播延迟(ms)
			direction: 'left', // 图片滑动方向
			moniterKeyEvent: true, // 是否监听键盘事件
			moniterTouchEvent: true // 是否监听屏幕滑动事件
		}
		const lb = new Lb(options);
		lb.start();
	}
</script>

<script>
	jQuery(document).ready(function($) {
		$('.zhuige-btn-search-news').click(function() {
			let keyword = $('.input-keyword-news').val()
			if (keyword.length == 0) {
				layer.msg('请输入关键字');
				return;
			}
			window.location.href = '/news/search/' + keyword;
		});

		$('.input-keyword-news').keydown(function() {
			if (event.keyCode == 13) {
				let keyword = $('.input-keyword-news').val()
				if (keyword.length == 0) {
					layer.msg('请输入关键字');
					return;
				}
				window.location.href = '/news/search/' + keyword;
			};
		});

		$('.zhuige-more-btn').click(() => {
			var loading = layer.load();
			$.post("/wp-admin/admin-ajax.php", {
				action: "zhuige_theme_xzdp_event",
				zgaction: 'get_posts',
				offset: $('.zhuige-post-for-ajax-count').length,
				cat: $('.zhuige-theme-xzdp-cat').val(),
				tag: $('.zhuige-theme-xzdp-tag').val(),
				ss: $('.zhuige-theme-xzdp-ss').val(),
			}, (res) => {
				layer.close(loading);

				if (res.success) {
					$('.zhuige-news-list').append(res.data.content);

					if (!res.data.more) {
						$('.zhuige-more-btn').hide();
					}
				}
			});
		})
	});
</script>

<?php get_footer(); ?>