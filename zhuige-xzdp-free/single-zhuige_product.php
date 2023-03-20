<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<?php
if (have_posts()) :
	the_post();

	global $post;
	zhuige_theme_xzdp_inc_post_view($post->ID);
	$view_count = zhuige_theme_xzdp_get_post_view($post->ID);

	$options = get_post_meta($post->ID, 'zhuige_product_options', true);

	global $wpdb;

	$my_user_id = get_current_user_id();
	$table_post_like = $wpdb->prefix . 'zhuige_theme_xzdp_post_like';
	$users = $wpdb->get_results($wpdb->prepare("SELECT `user_id` FROM `$table_post_like` WHERE `post_id`=%d", $post->ID));
	$like_count = count($users);


	$table_commentmeta = $wpdb->prefix . 'commentmeta';
	$table_comments = $wpdb->prefix . 'comments';

endif;
?>
<!-- 主内容区 -->
<div class="main-body mb-20 pt-20">
	<div class="container nav-fix">
		<div class="row d-flex flex-wrap justify-content-center">

			<!-- 中间列表区 -->
			<article class="main-cont md-9">

				<!-- 产品基本信息 -->
				<input type="hidden" class="zhuige-product-id" value="<?php echo $post->ID ?>">
				<div id="product-info" class="zhuige-prd-base p-20 info-fix">
					<div class="d-flex align-items-center justify-content-between">
						<div class="zhuige-base-list">
							<div class="zhuige-list-img d-flex">
								<?php if ($options['logo'] && $options['logo']['url']) { ?>
									<a href="javascript:void(0)" class="zhuige-img-logo">
										<img alt="" src="<?php echo $options['logo']['url'] ?>">
									</a>
								<?php } ?>
								<?php if ($options['qrcode'] && $options['qrcode']['url']) { ?>
									<a href="javascript:void(0)" class="zhuige-img-qrcode">
										<img alt="" src="<?php echo $options['qrcode']['url'] ?>">
									</a>
								<?php } ?>
							</div>
							<div class="zhuige-list-text">
								<h6>
									<text><?php the_title() ?></text>
								</h6>
								<p class="pt-10">
									<cite><?php echo zhuige_theme_xzdp_excerpt($post, zhuige_theme_xzdp_option('home_excerpt_length', 120)) ?></cite>
								</p>
							</div>
						</div>
						<a href="/link-go?web=<?php echo $post->ID ?>" title="<?php echo $link_go_title ?>" target="_blank" class="zhuige-prd-link d-flex mr-10 align-items-center justify-content-center">
							<!-- 默认：一键直达，点击就弹窗提示是否要打开网址 这个一键直达，在后台可以自定义文字 -->
							<?php $link_go_title = zhuige_theme_xzdp_option('product_link_go_title', '一键直达') ?>
							<text><?php echo $link_go_title ?></text>
						</a>
					</div>
				</div>
				<!-- 产品内容展示 -->
				<div class="main-cont-block p-20 mb-20">
					<div class="zhuige-prd-info">

						<div class="swiper-container">
							<div class="swiper-wrapper">
								<?php
								if (is_array($options['screens'])) {
									foreach ($options['screens'] as $screen) {
										echo '<img class="swiper-slide" alt="" src="' . $screen['image']['url'] . '" style="width:auto;height:360px;" />';
									}
								}
								?>
							</div>
							<div class="swiper-button-next"></div>
							<div class="swiper-button-prev"></div>
						</div>

					</div>

					<!-- 文字内容 -->
					<div class="zhuige-prd-text pt-20 pb-20">
						<?php the_content() ?>
					</div>

					<!-- 标签/时间 -->
					<div class="zhuige-prd-tags pt-20 d-flex align-items-center justify-content-between border-fix">
						<p class="d-flex">
							<?php
							$terms = get_the_terms($post, 'zhuige_product_tag');
							if (is_array($terms)) {
								foreach ($terms as $term) {
							?>
									<a href="<?php echo get_term_link($term->term_id) ?>" title="<?php echo $term->name ?>"><?php echo $term->name ?></a>
							<?php
								}
							}
							?>
						</p>
						<p>
							<text>发布时间 <?php echo get_the_time('Y-m-d', $post) ?></text>
							<text>/</text>
							<text>浏览 <?php echo $view_count ?></text>
						</p>
					</div>

				</div>

				<!-- 声明内容 -->
				<div class="main-cont-block p-20 mb-20">
					<?php
					$product_copyright = zhuige_theme_xzdp_option('product_copyright');
					if ($product_copyright) {
					?>
						<div class="zhuige-state-text">
							<p><?php echo $product_copyright ?></p>
						</div>
					<?php
					}
					?>
				</div>

				<!-- 猜你喜欢 -->
				<div id="product-rec" class="main-cont-block p-20 mb-20">
					<h3>
						<text>猜你喜欢</text>
					</h3>
					<div class="zhuige-recom">
						<div class="d-flex flex-wrap">

							<?php
							$args = array(
								// 'post_status' => 'publish',
								'post_type' => ['zhuige_product'],
								'post__not_in' => [$post->ID],
								'ignore_sticky_posts' => 1,
								'orderby' => 'comment_date',
								'posts_per_page' => 6
							);
							$posttags = get_the_tags();
							if ($posttags) {
								$tags = '';
								foreach ($posttags as $tag) {
									$tags .= $tag->term_id . ',';
								}
								$args['tag__in'] = explode(',', $tags);
							}
							$query = new WP_Query();
							$result = $query->query($args);
							foreach ($result as $item) {
								$product = zhuige_theme_xzdp_format_product($item);
							?>
								<div class="zhuige-base-list">
									<a class="d-flex" href="<?php echo $product['link'] ?>" title="<?php echo $product['title'] ?>" target="_blank">
										<div class="zhuige-list-img">
											<img alt="" src="<?php echo $product['logo'] ?>">
										</div>
										<div class="zhuige-list-text">
											<h6>
												<text><?php echo $product['title'] ?></text>
											</h6>
											<p>
												<cite><?php echo $product['excerpt'] ?></cite>
											</p>
										</div>
									</a>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>

				<!-- 评论 -->
				<?php comments_template(); ?>

			</article>

		</div>
	</div>
</div>

<script type="text/javascript">
	var swiper = new Swiper('.swiper-container', {
		slidesPerView: 'auto',
		spaceBetween: 10,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	});

	layer.photos({
		photos: '.zhuige-img-logo',
		anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
	});

	layer.photos({
		photos: '.zhuige-img-qrcode',
		anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
	});

	layer.photos({
		photos: '.swiper-container',
		anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
	});
</script>

<?php get_footer(); ?>