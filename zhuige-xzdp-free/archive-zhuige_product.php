<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();

$my_user_id = get_current_user_id();

global $wp_query;
$query_obj = $wp_query->get_queried_object();
?>

<div style="width:100%;padding-top:60px;"></div>

<!-- 主内容区 -->
<?php require_once("main.php") ?>

<?php get_footer(); ?>