<?php
/*
Template Name: 追格-链接跳转
*/

if (!defined('ABSPATH')) {
	exit;
}

get_header();

$web = isset($_GET['web']) ? sanitize_text_field(wp_unslash($_GET['web'])) : '';
$product = get_post($web);
if (!$product) {
	wp_safe_redirect(home_url());
	exit;
}

$options = get_post_meta($product->ID, 'zhuige_product_options', true);
?>

<!-- 主内容区 -->
<div class="main-body mb-20 pt-20">
	<div class="container nav-fix">
		<div class="row d-flex flex-wrap justify-content-center">

			<!-- 中间列表区 -->
			<article class="main-cont md-9">

				<!-- 产品基本信息 -->
				<div class="zhuige-link-tips-box">
					<div class="zhuige-link-tips p-20">
						<div class="pb-20">
							<h3>
								<i class="fa fa-exclamation-circle"></i>
								<text><?php echo zhuige_theme_xzdp_option('product_link_go_tip', '即将离开本站，要继续吗？') ?></text>
							</h3>
							<p class="pt-10"><?php echo $options['web'] ?></p>
						</div>
						<div class="pt-20 d-flex align-items-center flex-d-row-reverse">
							<a href="<?php echo $options['web'] ?>" title="继续访问">继续访问</a>
							<div><span class="zhuige-count-down" data-web="<?php echo $options['web'] ?>">10</span>秒后自动跳转</div>
						</div>
					</div>
				</div>

			</article>

		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		var countDown = setInterval(() => {
			let cd = $('.zhuige-count-down');
			let cdval = parseInt(cd.text()) - 1;
			if (cdval == 0) {
				clearInterval(countDown);
				window.location.href = cd.data('web');
				return;
			}
			cd.text(cdval);
		}, 1000)
	});
</script>

<?php get_footer(); ?>