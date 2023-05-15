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

	global $wpdb;

	$my_user_id = get_current_user_id();
	$table_post_like = $wpdb->prefix . 'zhuige_theme_xzdp_post_like';
	$users = $wpdb->get_results($wpdb->prepare("SELECT `user_id` FROM `$table_post_like` WHERE `post_id`=%d", $post->ID));
	$like_count = count($users);
endif;
?>
<!-- 主内容区 -->
<div class="main-body mb-20 pt-20">
	<div class="container nav-fix">
		<div class="row d-flex flex-wrap justify-content-center info-fix">

			<!-- 左侧索引 -->
			<aside>
				<?php $sticky_top = 'position: sticky; top: ' . (zhuige_theme_is_show_admin_bar() ? 114 : 82) . 'px;'; ?>
				<div class="zhugie-news-data" style="<?php echo $sticky_top ?>">
					<div class="zhuige-news-time">
						<p>-<?php echo get_the_time('Y') ?>-</p>
						<p><?php echo get_the_time('m/d') ?></p>
						<p class="m-20 d-flex justify-content-center">
							<text>浏览 <?php echo $view_count ?></text>
						</p>
					</div>
					<div class="zhuige-news-opt">
						<div class="zhuige-btn-to-comment">
							<span><?php echo $post->comment_count; // get_comment_count($post->ID)['approved'] 
									?></span>
							<i class="fa fa-commenting"></i>
						</div>
					</div>
				</div>
			</aside>

			<!-- 中间列表区 -->
			<article class="main-cont md-9">

				<!-- 面包屑 -->
				<div class="zhuige-cooky p-20">
					<?php zhuige_theme_xzdp_breadcrumbs() ?>
				</div>

				<!-- 文章内容展示 -->
				<div class="main-cont-block p-20 mb-20">

					<!-- 标题 -->
					<h1 class="zhuige-news-title"><?php the_title() ?></h1>


					<!-- 文字内容 -->
					<div class="zhuige-prd-text pt-20 pb-20">
						<?php the_content() ?>
					</div>

					<div class="zhuige-end-line p-20">- END -</div>

					<!-- 标签 -->
					<div class="zhuige-prd-tags pb-20 d-flex align-items-center justify-content-between">
						<p class="d-flex">
							<?php the_tags('', '', '') ?>
						</p>
					</div>

				</div>

				<!-- 上下篇 -->
				<?php
				$prev_post = get_previous_post();
				$is_prev_post = is_a($prev_post, 'WP_Post');
				$next_post = get_next_post();
				$is_next_post = is_a($next_post, 'WP_Post');
				if ($is_prev_post || $is_next_post) :
				?>
					<div class="main-cont-block p-20 mb-20">
						<div class="zhuige-next d-flex align-items-center justify-content-between">
							<?php
							if ($is_prev_post) : ?>
								<div>
									<h6 class="mb-20">
										<a href="<?php echo get_permalink($prev_post->ID); ?>" title="上一篇">上一篇</a>
									</h6>
									<p>
										<a href="<?php echo get_permalink($prev_post->ID); ?>" title="标题"><?php echo apply_filters('the_title', $prev_post->post_title); ?></a>
									</p>
								</div>
							<?php endif; ?>

							<?php
							if ($is_next_post) : ?>
								<div>
									<h6 class="mb-20">
										<a href="<?php echo get_permalink($next_post->ID); ?>" title="下一篇">下一篇</a>
									</h6>
									<p>
										<a href="<?php echo get_permalink($next_post->ID); ?>" title="标题"><?php echo apply_filters('the_title', $next_post->post_title); ?></a>
									</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- 猜你喜欢 -->
				<?php
				$args = array(
					'post__not_in' => [$post->ID],
					'ignore_sticky_posts' => 1,
					'orderby' => 'comment_date',
					'posts_per_page' => 4
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
				if (count($result) > 0) {
				?>
					<div class="main-cont-block p-20 mb-20">
						<h1>
							<text>猜你喜欢</text>
						</h1>
						<div class="zhuige-news-recom-box pt-10 d-flex align-items-center">
							<?php
							foreach ($result as $item) {
								$item = zhuige_theme_xzdp_format_post($item, true);
							?>
								<div class="zhuige-news-recom">
									<a href="<?php echo $item['link'] ?>" title="">
										<h6><?php echo $item['title'] ?></h6>
										<img alt="" src="<?php echo $item['thumb'] ?>">
									</a>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php
				}
				?>

				<!-- 评论 -->
				<?php comments_template(); ?>

			</article>

		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('.zhuige-btn-to-comment').click(function() {
			$([document.documentElement, document.body]).animate({
				scrollTop: $('#product-comment').offset().top - 100
			})
		});
	});
</script>
<?php get_footer(); ?>