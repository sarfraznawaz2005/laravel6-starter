<?php

// Task
Breadcrumbs::register('tasks.index', static function ($breadcrumbs) {
    $breadcrumbs->push('Task List', route('task.index'));
});

// Edit Task
Breadcrumbs::register('tasks.edit', static function ($breadcrumbs) {
    $breadcrumbs->parent('tasks.index');
    $breadcrumbs->push('Edit Task');
});
