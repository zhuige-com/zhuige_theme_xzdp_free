<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<!-- 浮动链接 -->
<div class="zhuige-float-block">
	<div class="zhuige-float-gotop mt-20">
		<i class="fa fa-chevron-up"></i>
	</div>
</div>

<!--页脚-->
<footer>
	<!--页脚版权-->
	<div class="container zhuige-footer-mini">
		<?php
		$footer_copyright = zhuige_theme_xzdp_option('footer_copyright', '');
		echo '<div>' . $footer_copyright . '<p>主题设计：<a href="https://www.xzdp.com" title="追格小站点评（xzdp.com）主题搭建">小站点评（xzdp.com）</a></p>' . '</div>';
		?>
		<?php  ?>
		<p class="zhuige-foot-menu">
			<?php
			$footer_nav = zhuige_theme_xzdp_option('footer_nav');
			if (empty($footer_nav)) {
				$footer_nav = [
					['title' => '追格小程序', 'url' => 'https://www.zhuige.com'],
				];
			}
			$end_item = end($footer_nav);
			foreach ($footer_nav as $nav) :
				echo '<a href="' . $nav['url'] . '" title="' . $nav['title'] . '">' . $nav['title'] . '</a>';
				if ($end_item !== $nav) :
					echo '/';
				endif;
			endforeach;
			?>
		</p>
	</div>
</footer>

<!-- h5 底部自定义菜单 -->
<?php if (wp_is_mobile()) { ?>
	<div class="zhuige-custom-btn">
		<ul class="d-flex justify-content-center align-items-center">
			<?php
			$h5_tabbar = zhuige_theme_xzdp_option('h5_tabbar');
			if (is_array($h5_tabbar)) {
				$currect_url = zhuige_theme_xzdp_url();
				foreach ($h5_tabbar as $tab) {
					if ($tab['switch']) {
						$class = (zhuige_url_module($currect_url) == zhuige_url_module($tab['url']) ? 'active' : '');
						$target = $item['blank'] ? '_blank' : '_self';
			?>
						<li class="<?php echo $class ?>">
							<p>
								<a href="<?php echo $tab['url'] ?>" target="<?php echo $target ?>" title="<?php echo $tab['title'] ?>">
									<i class="<?php echo $tab['icon'] ?>" style="font-size: <?php echo  $tab['title'] ? 1.4 : 2 ?>em"></i>
								</a>
							</p>
							<?php if ($tab['title']) { ?>
								<p><a href="<?php echo $tab['url'] ?>" target="<?php echo $target ?>" title="<?php echo $tab['title'] ?>"><?php echo $tab['title'] ?></a></p>
							<?php } ?>
						</li>
				<?php
					}
				}
			} else {
				?>
				<li>
					<p><a href="/" title=""><i class="fa fa-camera"></i></a></p>
					<p><a href="/" title="">首页</a></p>
				</li>
				<li>
					<p><a href="/news" title=""><i class="fa fa-calculator"></i></a></p>
					<p><a href="/news" title="">资讯</a></p>
				</li>
			<?php
			}
			?>
		</ul>
	</div>
<?php } ?>

<?php wp_footer(); ?>

<div style="display: none;">
	<script>
		<?php echo zhuige_theme_xzdp_option('footer_statistics'); ?>
	</script>
</div>

</body>

</html>