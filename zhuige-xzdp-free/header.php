<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<title><?php zhuige_theme_xzdp_seo_title() ?></title>
	<?php wp_head(); ?>
	<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.14.0/css/all.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.14.0/css/v4-shims.min.css" rel="stylesheet" />
	<?php if (is_singular('zhuige_product')) { ?>
		<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/swiper.min.css" rel="stylesheet" />
	<?php  } ?>
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>?ver=1.2.2">
	<script>
		var show_login_pop;
	</script>
</head>

<?php
$my_user_id = get_current_user_id();
?>

<body>
	<?php
	$lref = isset($_GET['lref']) ? urldecode($_GET['lref']) : '';
	if ($lref) {
		echo '<input type="hidden" class="zhuige-login-lref" value="' . $lref . '" />';
	}
	?>
	<header>
		<!--主导航-->
		<nav class="container">
			<div class="logo">
				<a href="<?php echo home_url(); ?>"><?php zhuige_theme_xzdp_logo(); ?></a>
			</div>
			<div class="zhuige-nav">
				<ul class="zhuige-nav-list">
					<?php
					$site_nav = zhuige_theme_xzdp_option('site_nav');
					if (is_array($site_nav) && count($site_nav) > 0) {
						$currect_url = zhuige_theme_xzdp_url();
						foreach ($site_nav as $item) {
							if ($item['switch']) {
							$class = (zhuige_url_module($currect_url) == zhuige_url_module($item['url']) ? 'nav-activ' : '');
							$target = $item['blank'] ? '_blank' : '_self';
					?>
							<li class="<?php echo $class ?>"><a href="<?php echo $item['url'] ?>" target="<?php echo $target ?>"><?php echo $item['title'] ?></a></li>
					<?php
							}
						}
					} else {
						?>
							<li><a href="<?php echo home_url('news') ?>">资讯</a></li>
						<?php
					} 
					?>
				</ul>
			</div>
			<div class="zhuige-nav-side">
				<span>
					<a class="zhuige-nav-btn" href="https://www.zhuige.com/product.html?cat=23" target="_blank" title="发布产品">更多开源主题</a>
				</span>
			</div>
		</nav>
	</header>