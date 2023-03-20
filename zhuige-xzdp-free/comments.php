<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
    die('Please do not load this page directly. Thanks!');
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}

?>

<div id="product-comment" class="main-cont-block p-20 mb-20">

    <?php

    $closeTimer = (time() - strtotime(get_the_time('Y-m-d G:i:s'))) / 86400;
    $close_comments_for_old_posts = get_option('close_comments_for_old_posts');
    if (comments_open() && (!$close_comments_for_old_posts || ($close_comments_for_old_posts && $closeTimer < get_option('close_comments_days_old')))) {
        if (get_option('comment_registration') && !is_user_logged_in()) {
            printf('<h1><text>您必须 <a href="%s">登录</a> 才能发表评论！</text></h1>', wp_login_url(get_permalink()));
        } else {
    ?>
            <h1>
                <text>发表评论</text>
                <span class="zhuige-comment-reply-container" style="display:none;">
                    回复：<text class="zhuige-comment-reply-nickname"></text>
                    <a href="javascript:void(0)" class="zhuige-btn-comment-reply-cancel">取消</a>
                </span>
            </h1>

            <!-- 评论框 -->
            <div class="zhuige-comment-rate pb-10">
                <div class="zhuige-coment-block">

                    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                        <div class="comment-form-box">
                            <p class="comment-form-comment">
                                <label for="comment">评论</label>
                                <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" placeholder="请回复有价值的信息，无意义的评论讲很快被删除，账号将被禁止发言。" required="required"></textarea>
                            </p>

                            <?php if (!is_user_logged_in()) { ?>
                                <ul>
                                    <li class="comment-form-author"><label for="author">*姓名：</label> <input id="author" name="author" type="text" value="" size="30" maxlength="245"></li>
                                    <li class="comment-form-email"><label for="email">*Email：</label> <input id="email" name="email" type="text" value="" size="30" maxlength="100" aria-describedby="email-notes"></li>
                                    <li class="comment-form-url"><label for="url">站点：</label> <input id="url" name="url" type="text" value="" size="30" maxlength="200"></li>
                                </ul>
                            <?php } ?>
                            <div class="form-submit mt-10">
                                <input name="submit" type="submit" id="submit" value="发表评论">
                            </div>
                            <?php comment_id_fields(); ?>
                            <?php do_action('comment_form', $post->ID); ?>
                        </div>
                    </form>


                </div>
            </div>

    <?php
        }
    } else {
        echo '<h1><text>评论已关闭</text></h1>';
    }
    ?>

    <!-- 评论列表 -->
	
		<div class="zhuige-comment-list">
			<?php wp_list_comments([
				'type' => 'comment',
				'reverse_top_level' => true,
				'callback' => 'zhuige_theme_xzdp_comment_list'
			]) ?>
		</div>

		<!-- 无评论提示 -->
		<?php if (get_comment_count($post->ID)['all'] == 0) { ?>
			<div class="zhuige-none-tips">
				<img src="<?php echo get_stylesheet_directory_uri() . '/images/not_found.png' ?>" alt="picture loss" />
				<p>暂无评论，你要说点什么吗？</p>
			</div>
		<?php } ?>


	</div>
</div>