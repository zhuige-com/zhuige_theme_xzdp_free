<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();

$my_user_id = get_current_user_id();

$home_header = zhuige_theme_xzdp_option('home_header');
if (!$home_header) {
	$home_header = [
		'bg_image' => '',
		'bg_video' => '',
		'title' => '追格小站点评主题',
		'tip' => '搜索原来如此简单...',
		'hot_words' => '追格'
	];
}
$hot_words = explode(',', $home_header['hot_words']);

$query_obj = $wp_query->get_queried_object();

if (is_home()) {
?>
	<div class="zhuige-base-block relative">
		<div class="zhuige-search-box absolute">
			<div class="container">
				<!--主搜索区-->
				<div class="zhuige-main-search">
					<h1><?php echo $home_header['title'] ?></h1>
					<div class="zhuige-search-form">
						<input type="search" class="input-keyword" placeholder="<?php echo $home_header['tip'] ?>" required value="" autocomplete="off">
						<a href="javascript:void(0)" class="zhuige-btn-search" title="搜索">
							<i class="fa fa-search"></i>
						</a>
					</div>
					<div class="zhuige-search-tag">
						<?php foreach ($hot_words as $hot_word) {
							if (!empty($hot_word)) {
								echo '<a href="' . home_url('/?s=' . $hot_word) . '">' . $hot_word . '</a>';
							}
						} ?>
					</div>
				</div>
			</div>
		</div>

		<?php
		if ($home_header['bg_image'] && $home_header['bg_image']['url']) {
			$home_header_background = $home_header['bg_image']['url'];
		} else {
			$home_header_background = get_stylesheet_directory_uri() . '/images/home_header_background.jpg';
		}
		?>
		<div class="zhuige-search-bg" style="background: url(<?php echo $home_header_background ?>) no-repeat center; background-size: cover;">
			<!-- 搜索区背景可以设置为图片或视频 -->
			<?php
			if (!wp_is_mobile() && $home_header['bg_video']) {
			?>
				<!-- 背景视频自动循环播放 -->
				<video autoplay muted loop>
					<source src="<?php echo $home_header['bg_video'] ?>" type="video/mp4" />
				</video>
			<?php
			}
			?>
		</div>

	</div>
<?php
} else {
?>
	<div style="width:100%;padding-top:60px;"></div>
<?php
}
?>

<!-- 主内容区 -->
<?php require_once("main.php") ?>

<?php get_footer(); ?>