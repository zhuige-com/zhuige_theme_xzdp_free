<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<!-- 主内容区 -->
<?php
if (have_posts()) {
	the_post();
	global $post;
?>
	<div class="main-body mb-20 pt-20">
		<div class="container nav-fix">
			<div class="row d-flex flex-wrap justify-content-center">

				<!-- 中间列表区 -->
				<article class="main-cont md-9">

					<?php
					$about_nav = zhuige_theme_xzdp_option('about_nav');
					if (is_array($about_nav) && in_array($post->ID, $about_nav)) {
						$sticky_top = 'top: ' . (zhuige_theme_is_show_admin_bar() ? 94 : 62) . 'px;';
					?>
						<div class="main-cont-block zhuige-page-tab-box p-20 mb-20" style="<?php echo $sticky_top ?>">
							<!-- 顶部tab -->
							<div class="zhuige-user-tab">
								<div class="d-flex align-items-center justify-content-center">
									<?php
									foreach ($about_nav as $page_id) {
										$page = get_page($page_id);
										if ($page) {
											$class = ($page_id == $post->ID ? 'active' : '');
									?>
											<a href="<?php echo get_page_link($page_id) ?>" class="<?php echo $class ?>" title="<?php echo $page->post_title ?>"><?php echo $page->post_title ?></a>
									<?php
										}
									}
									?>
								</div>
							</div>
						</div>
					<?php
					} ?>

					<!-- 基本信息 -->
					<div class="main-cont-block p-20 mb-20">
						<!-- 单页基础结构 -->
						<div class="zhuige-easy-page">
							<?php the_content(); ?>
						</div>
					</div>
				</article>

			</div>
		</div>
	</div>
<?php } else {
	echo '这里什么也没有';
} ?>
<?php get_footer(); ?>