<?php

if (! function_exists('ob_capture')) {
    function ob_capture(callable $callable): string {
        ob_start();
        $callable();
        $output = ob_get_contents();
        ob_clean();

        return $output;
    }
}