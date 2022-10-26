<?php
return [
    'url' => [
        'prefix_admin' => 'admin123',
        'prefix_news'  => 'news69',
    ],

    'format' => [
        'long_time' => 'H:m:s d/m/Y',
        'short_time' => 'd/m/Y',
    ],

    'template' => [
        'form_input' => [
            'class'  => 'form-control col-md-6 col-xs-12'
        ],

        'form_label' => [
            'class'  => 'control-label col-md-3 col-sm-3 col-xs-12'
        ],

        'form_label_edit' => [
            'class'  => 'control-label col-md-4 col-sm-3 col-xs-12'
        ],


        'form_ckeditor' => [
            'class'  => 'form-control col-md-6 col-xs-12 ckeditor'
        ],

        'status' => [
            'all' => ['name' => 'Tất cả', 'class' => 'btn-success'],
            'active' => ['name' => 'Kích hoạt', 'class' => 'btn-success'],
            'inactive' => ['name' => 'Chưa kích hoạt', 'class' => 'btn-info'],
            'block' => ['name' => 'Bị khóa', 'class' => 'btn-danger'],
            'default' => ['name' => 'Chưa xác định', 'class' => 'btn-info']
        ],

        'is_home' => [
            'yes' => ['name' => 'Hiển thị', 'class' => 'btn-primary'],
            'no' => ['name' => 'Không hiển thị', 'class' => 'btn-warning']
        ],

        'display' => [
            'list' => ['name' => 'Danh sách'],
            'grid' => ['name' => 'Lưới']
        ],



        'type' => [
            'feature' => ['name' => 'Nổi bật'],
            'normal ' => ['name' => 'Bình thường']
        ],

        'rss_source' => [
            'vnexpress' => ['name' => 'Vnexpress'],
            'tuoitre '  => ['name' => 'TuoiTre']
        ],

        'level' => [
            'admin' => ['name' => 'Quản trị hệ thống'],
            'member' => ['name' => 'Người dùng']

        ],


        'search' => [
            'all'                  => ['name' => 'Search By All'],
            'id'                   => ['name' => 'Search By ID'],
            'name'                 => ['name' => 'Search By Name'],
            'username'             => ['name' => 'Search By Username'],
            'fullname'             => ['name' => 'Search By FullName'],
            'email'                => ['name' => 'Search By Email'],
            'description'          => ['name' => 'Search By Description'],
            'link'                 => ['name' => 'Search By Link'],
            'content'              => ['name' => 'Search By Content'],
        ],

        'button' => [
            'edit'   => ['class' => 'btn-success', 'title' => 'Edit',   'icon' => 'fa-pencil', 'route-name' => '/form'],
            'delete' => ['class' => 'btn-danger btn-delete',  'title' => 'Delete', 'icon' => 'fa-trash', 'route-name' => '/delete'],
        ]
    ],

    'config' => [
        'search' => [
            'default'   => ['all', 'id', 'fullname'],
            'slider'    => ['all', 'id', 'description', 'link', 'name'],
            'category'  => ['all', 'id', 'name'],
            'rss'       => ['all', 'id', 'link'],
            'article'   => ['all', 'name', 'content'],
            'user'      => ['all', 'username', 'email', 'fullname'],
        ],

        'button' => [
            'default'   => ['edit', 'delete'],
            'slider'    => ['edit', 'delete'],
            'category'  => ['edit', 'delete'],
            'article'  => ['edit', 'delete'],
            'user'     => ['edit', 'delete'],
            'rss'     => ['edit', 'delete'],
        ]
    ]
];
