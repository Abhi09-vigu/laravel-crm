<?php

return [
    [
        'key' => 'real_estate',
        'name' => 'real_estate::app.acl.real_estate',
        'route' => 'admin.real_estate.projects.index',
        'sort' => 8,
    ], [
        'key' => 'real_estate.projects',
        'name' => 'real_estate::app.acl.projects',
        'route' => 'admin.real_estate.projects.index',
        'sort' => 1,
    ], [
        'key' => 'real_estate.projects.create',
        'name' => 'real_estate::app.acl.create',
        'route' => ['admin.real_estate.projects.create', 'admin.real_estate.projects.store'],
        'sort' => 1,
    ], [
        'key' => 'real_estate.projects.view',
        'name' => 'real_estate::app.acl.view',
        'route' => 'admin.real_estate.projects.view',
        'sort' => 2,
    ], [
        'key' => 'real_estate.projects.edit',
        'name' => 'real_estate::app.acl.edit',
        'route' => ['admin.real_estate.projects.edit', 'admin.real_estate.projects.update', 'admin.real_estate.projects.update_status'],
        'sort' => 3,
    ], [
        'key' => 'real_estate.projects.delete',
        'name' => 'real_estate::app.acl.delete',
        'route' => 'admin.real_estate.projects.delete',
        'sort' => 4,
    ],
];
