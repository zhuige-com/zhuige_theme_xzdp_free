/**
 * 追格小站点评主题
 */

jQuery(document).ready(function ($) {
    $.post("/wp-admin/admin-ajax.php",
        {
            action: 'zhuige_home_pop_ad'
        },
        function (data, status) {
            if (status != 'success' || !data.success) {
                return;
            }

            if (data.data.pop != 1) {
                return;
            }

            $('.home-ad-pop-image').on('load', function () {
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 1,
                    area: ['auto'],
                    skin: 'layui-layer-noboxshade', //没有背景色没有边框阴影
                    shadeClose: true,
                    content: $('#home-ad-pop')
                });
            })

            $('.home-ad-pop-link').attr('href', data.data.link);
            $('.home-ad-pop-image').attr('src', data.data.image);
        });
});