<?php

if (!defined('ABSPATH')) {
	exit;
}

add_action('init', 'zhuige_post_type_product');

function zhuige_post_type_product()
{
	/**
	 * 产品
	 */
	$product_labels = array(
		'name' => '产品',
		'singular_name' => '产品',
		'add_new' => '新建产品',
		'add_new_item' => '新建产品',
		'edit_item' => '编辑产品',
		'new_item' => '新的产品',
		'view_item' => '查看产品',
		'search_items' => '产品搜索',
		'not_found' => '没发现产品',
		'not_found_in_trash' => '回收站里无产品',
		'parent_item_colon' => '',
		'menu_name' => '追格产品'
	);

	$product_args = array(
		'labels' => $product_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		// 'rewrite' => array('slug'  => 'product'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'author', 'excerpt', 'comments')
	);
	register_post_type('zhuige_product', $product_args);


	/**
	 * 产品分类
	 */
	$product_cat_labels = array(
		'name'              => '分类', 'taxonomy 名称',
		'singular_name'     => '分类', 'taxonomy 单数名称',
		'search_items'      => '搜索分类',
		'all_items'         => '所有分类',
		'parent_item'       => '该分类的上级分类',
		'parent_item_colon' => '该分类的上级分类：',
		'edit_item'         => '编辑分类',
		'update_item'       => '更新分类',
		'add_new_item'      => '添加新的分类',
		'new_item_name'     => '分类',
		'menu_name'         => '分类',
	);
	$product_cat_args = array(
		'hierarchical' => true,
		'labels' => $product_cat_labels,
		// 'show_ui'           => true,
		// 'show_admin_column' => true,
		// 'query_var'         => true,
		// 'rewrite'           => array( 'slug' => 'product-cat' ),
	);
	register_taxonomy('zhuige_product_cat', 'zhuige_product', $product_cat_args);

	/**
	 * 产品标签
	 */
	$product_tag_labels = array(
		'name'              => '标签', 'taxonomy 名称',
		'singular_name'     => '标签', 'taxonomy 单数名称',
		'search_items'      => '搜索标签',
		'all_items'         => '所有标签',
		'parent_item'       => '该标签的上级标签',
		'parent_item_colon' => '该标签的上级标签：',
		'edit_item'         => '编辑标签',
		'update_item'       => '更新标签',
		'add_new_item'      => '添加新的标签',
		'new_item_name'     => '标签',
		'menu_name'         => '标签',
		'separate_items_with_commas' => '多个标签请用英文逗号（,）分开',
		'choose_from_most_used' => '从常用标签中选择',
		'not_found' => '未找到标签'
	);
	$producttag_args = array(
		'hierarchical' => false,
		'labels' => $product_tag_labels,
		// 'show_ui'           => true,
		// 'show_admin_column' => true,
		// 'query_var'         => true,
		// 'rewrite'           => array( 'slug' => 'product-tag' ),
	);
	register_taxonomy('zhuige_product_tag', 'zhuige_product', $producttag_args);
}

//文章属性
$prefix_activity_opts = 'zhuige_product_options';

CSF::createMetabox($prefix_activity_opts, array(
	'title'        => '追格产品设置',
	'post_type'    => 'zhuige_product',
	// 'show_restore' => true,
));

CSF::createSection($prefix_activity_opts, array(
	'fields' => array(

		array(
			'id'      => 'logo',
			'type'    => 'media',
			'title'   => 'LOGO',
			'library' => 'image',
		),

		array(
			'id'      => 'qrcode',
			'type'    => 'media',
			'title'   => '二维码',
			'library' => 'image',
		),

		array(
			'id'     => 'screens',
			'type'   => 'group',
			'title'  => '截图',
			'fields' => array(

				array(
					'id'      => 'image',
					'type'    => 'media',
					'title'   => '截图',
					'library' => 'image',
				),

			),
		),

		array(
			'id'    => 'web',
			'type'  => 'text',
			'title' => '网址',
			'default' => 'https://www.zhuige.com/',
		),
	)
));

//产品分类属性
$zhuige_product_cat_options = 'zhuige_product_cat_options';
CSF::createTaxonomyOptions($zhuige_product_cat_options, array(
	'taxonomy' => 'zhuige_product_cat',
));
CSF::createSection($zhuige_product_cat_options, array(
	'fields' => array(
		array(
			'id'      => 'logo',
			'type'    => 'media',
			'title'   => 'LOGO',
			'library' => 'image',
		),

		array(
			'id'          => 'keywords',
			'type'        => 'text',
			'title'       => '关键词',
			'placeholder' => '关键词',
			'after'    => '<p>请用英文逗号分割.</p>',
		),
	)
));

//产品标签属性
$zhuige_product_tag_options = 'zhuige_product_tag_options';
CSF::createTaxonomyOptions($zhuige_product_tag_options, array(
	'taxonomy' => 'zhuige_product_tag',
));
CSF::createSection($zhuige_product_tag_options, array(
	'fields' => array(
		array(
			'id'          => 'keywords',
			'type'        => 'text',
			'title'       => '关键词',
			'placeholder' => '关键词',
			'after'    => '<p>请用英文逗号分割.</p>',
		),
	)
));

//文章分类属性
$zhuige_category_options = 'zhuige_category_options';
CSF::createTaxonomyOptions($zhuige_category_options, array(
	'taxonomy' => 'category',
));
CSF::createSection($zhuige_category_options, array(
	'fields' => array(
		array(
			'id'          => 'keywords',
			'type'        => 'text',
			'title'       => '关键词',
			'placeholder' => '关键词',
			'after'    => '<p>请用英文逗号分割.</p>',
		),
	)
));

//文章标签属性
$zhuige_post_tag_options = 'zhuige_post_tag_options';
CSF::createTaxonomyOptions($zhuige_post_tag_options, array(
	'taxonomy' => 'post_tag',
));
CSF::createSection($zhuige_post_tag_options, array(
	'fields' => array(
		array(
			'id'          => 'keywords',
			'type'        => 'text',
			'title'       => '关键词',
			'placeholder' => '关键词',
			'after'    => '<p>请用英文逗号分割.</p>',
		),
	)
));
