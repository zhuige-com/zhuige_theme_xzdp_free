/**
 * 追格小站点评主题
 */

jQuery(document).ready(function ($) {
    /**
     * 评论
     */
    $('.zhuige-btn-comment-submit').click(() => {
        let post_id = $('.zhuige-comment-post_id').val();
        let content = $('.zhuige-comment-content').val();
        let parent = $('.zhuige-comment-parent').val();

        var params = {
            action: 'zhuige_theme_xzdp_event',
            zgaction: "comment",
            post_id: post_id,
            content: content,
            parent: parent
        };
        $.post("/wp-admin/admin-ajax.php", params, (res) => {
            if (!res.success) {
				if (res.data.error && res.data.error == 'login') {
					show_login_pop();
					return;
				}
                layer.msg(res.data);
                return;
            }

            window.location.reload();
        });

        return false;
    });


    /**
     * 评论 回复
     */
    $('.zhuige-comment-btn-reply').click(function () {
        $('.zhuige-comment-reply-nickname').text($(this).data('nickname'));
        $('.zhuige-comment-parent').val($(this).data('comment_id'));
        $('.zhuige-comment-reply-container').show();
    });

    /**
     * 评论 回复 取消
     */
    $('.zhuige-btn-comment-reply-cancel').click(() => {
        $('.zhuige-comment-reply-container').hide();
    })
}); 