<?php
// Polyfill for PHP each(), removed in PHP 8.0.
// Used by vendored libraries: jpgraph, ezpdf, barcode (simtaka/anjungan).
if (!function_exists('each')) {
    function each(&$array) {
        $key = key($array);
        if ($key === null) {
            return false;
        }
        $pair = array(0 => $key, 1 => current($array), 'key' => $key, 'value' => current($array));
        next($array);
        return $pair;
    }
}
