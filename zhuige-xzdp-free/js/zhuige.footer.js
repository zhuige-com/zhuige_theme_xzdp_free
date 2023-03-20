/**
 * 追格小站点评主题
 */

jQuery(document).ready(function ($) {

    /** 返回顶部 start */
    $(window).scroll(function (event) {
        let scrollTop = $(this).scrollTop();
        if (scrollTop == 0) {
            $(".zhuige-float-gotop").hide();
        } else {
            $(".zhuige-float-gotop").show();
        }
    });

    $(".zhuige-float-gotop").click(function (event) {
        $("html,body").animate(
            { scrollTop: "0px" },
            666
        )
    });
    /** 返回顶部 end */


    // 搜索产品 start
    {
        $('.zhuige-btn-search').click(function () {
            let keyword = $('.input-keyword').val();
            keyword = keyword.trim();
            if (keyword.length == 0) {
                layer.msg('请输入关键字');
                return;
            }
            window.location.href = '/?s=' + keyword;
        });

        $('.input-keyword').keydown(function (event) {
            if (event.keyCode == 13) {
                let keyword = $(this).val();
                keyword = keyword.trim();
                if (keyword.length == 0) {
                    layer.msg('请输入关键字');
                    return;
                }
                window.location.href = '/?s=' + keyword;
            };
        });
    }
    // 搜索产品 end

    /**
     * 加载产品
     */
    function index_load_products(sort = 'last') {
        var loading = layer.load();
        $.post("/wp-admin/admin-ajax.php", {
            action: "zhuige_theme_xzdp_event",
            zgaction: 'get_products',
            offset: $('.zhuige-prd-for-ajax-count').length,
            template: $('.zhuige-theme-xzdp-template').val(),
            temr_id: $('.zhuige-theme-xzdp-temr_id').val(),
            s: $('.zhuige-theme-xzdp-s').val(),
            sort: sort
        }, (res) => {
            layer.close(loading);

            if (res.success) {
                $('.zhuige-prd-last-con').append(res.data.content);

                if (!res.data.more) {
                    $('.zhuige-btn-more-product').hide();
                }
            }
        });
    }
    $('.zhuige-btn-more-product a').click(function () {
        index_load_products($(this).data('sort'))
    })

});