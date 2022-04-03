<?php

function slugify(string $text): string {
    $divider = '-';
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, $divider);
    $text = preg_replace('~-+~', $divider, $text);
    $text = strtolower($text);
    return $text;
}

/**
 * Same as {@link scandir()}, but only returns files that match a pattern
 * @param string $directory
 * @param string|null $match
 * @param string|null $do_not_match
 * @return array
 */
function filter_dir(string $directory, string $match = null, string $do_not_match = null): array {
    $pattern = '/^' . str_replace('*', '.+?', $match ?? $do_not_match) . '$/';

    $files = scandir($directory);
    $files = array_filter($files, function($file) use ($match, $do_not_match, $pattern) {
        if(str_starts_with($file, '.')) return false;
        if($match) return preg_match($pattern, $file);
        return !preg_match($pattern, $file);
    });

    return array_values($files);
}