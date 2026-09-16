<?php
declare(strict_types=1);

function mb_tabulate($str): string {
    $lines = explode("\n", $str);

    foreach ($lines as &$line) {
        $line = "\t" . $line;
    }

    return implode("\n", $lines);
}