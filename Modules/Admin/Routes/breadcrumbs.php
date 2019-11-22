<?php

// Admin Panel
Breadcrumbs::register('admin.panel', static function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('admin.panel'));
});

// User listing
Breadcrumbs::register('admin.users.index', static function ($breadcrumbs) {
    $breadcrumbs->parent('admin.panel');
    $breadcrumbs->push('Users', route('admin.users.index'));
});
