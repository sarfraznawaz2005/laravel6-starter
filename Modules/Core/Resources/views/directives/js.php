<?php
// Sets a php variable in javascript by binding to window global object.
// Usage: @js('varname', 'value')

use Illuminate\Support\Facades\Blade;

Blade::directive('js', static function ($arguments) {
    list($name, $value) = explode(',', str_replace(['(', ')', ' ', "'"], '', $arguments));

    return "<?php echo \"<script>window['{$name}'] = '{$value}';</script>\" ?>";
});
