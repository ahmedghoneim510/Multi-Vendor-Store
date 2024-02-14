<?php
    return [
        [
            'icon'=>'nav-icon fas fa-tachometer-alt',
            'route'=>'dashboard.dashboard',
            'title'=>'Dashboard',
            'active'=>'dashboard.dashboard'
        ],
        [
            'icon'=>'far fa-circle nav-icon',
            'route'=>'dashboard.categories.index',
            'title'=>'Categories',
            'badge'=>'New',
            'active'=>'dashboard.categories.*',
            'ability'=>'categories.view'
        ],
        [
            'icon'=>'nav-icon fas fa-tachometer-alt',
            'route'=>'dashboard.products.index',
            'title'=>'Products',
            'active'=>'dashboard.products.*',
            'ability'=>'products.view'

        ],
        [
            'icon'=>'nav-icon fas fa-tachometer-alt',
            'route'=>'dashboard.orders-admins.index',
            'title'=>'Orders',
            'active'=>'dashboard.orders.*',
            'ability'=>'orders.view'
        ],
        [
            'icon'=>'nav-icon fas fa-users',
            'route'=>'dashboard.roles.index',
            'title'=>'Roles',
            'active'=>'dashboard.roles.*',
            'ability'=>'roles.view'
        ],
        [
            'icon'=>'nav-icon fas fa-users',
            'route'=>'dashboard.users.index',
            'title'=>'Users',
            'active'=>'dashboard.users.*',
            'ability'=>'users.view'
        ],
        [
            'icon'=>'nav-icon fas fa-tachometer-alt',
            'route'=>'dashboard.admins.index',
            'title'=>'Admins',
            'active'=>'dashboard.admins.*',
            'ability'=>'admins.view'
        ],
    ];
