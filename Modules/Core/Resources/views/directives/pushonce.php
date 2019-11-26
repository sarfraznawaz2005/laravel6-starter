<?php
//////////////////////////////////////////////////////////
// Allows to push same view only file
// Usage:
// @pushonce('stack_name')
//  some stuff
// @endpushonce
//////////////////////////////////////////////////////////

use Illuminate\Support\Facades\Blade;

Blade::directive('pushonce', static function ($expression) {
    $var = '$__env->{"__pushonce_" . md5(__FILE__ . ":" . __LINE__)}';

    return "<?php if(!isset({$var})): {$var} = true; \$__env->startPush({$expression}); ?>";
});

Blade::directive('endpushonce', static function ($expression) {
    return '<?php $__env->stopPush(); endif; ?>';
});
