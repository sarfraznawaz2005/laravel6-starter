<?php

// Home
Breadcrumbs::register('home', static function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});
