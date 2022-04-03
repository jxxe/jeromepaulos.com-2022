<?php

require_once '../private/functions.php';
require_once '../private/Parsedown.php';

header_remove('X-Powered-By');

$start_time = microtime(true);

$config = json_decode(@file_get_contents('../private/config.json'), true);

$name = $config['name'] ?? '$PHOTOGRAPHER_NAME';
$contact = $config['contact'] ?? '';
$other_links = $config['links'] ?? [];

$albums = filter_dir('pages', do_not_match: '*.md'); // JPEG files
$md_pages = array_map(fn($file) => substr($file, 0, -3), filter_dir('pages', match: '*.md')); // Markdown files

$path = strtok($_SERVER["REQUEST_URI"], '?');
$current_page = '';

if($path === '/') {
    $current_page = $albums[0];
} else {
    foreach(array_merge($albums, $md_pages) as $page) {
        if('/' . slugify($page) === $path) {
            $current_page = $page;
            break;
        }
    }
}

$title = $path === '/' ? "$name — Portfolio" : "$current_page — $name";

$images = [];
$is_md_page = false;
$md_page_content = '';

if(empty($current_page)) {
    $is_md_page = true;
    $md_page_content = '<p>Page not found</p>';
    $title = "404 — $name";
} else {
    if(is_dir("pages/$current_page")) {
        foreach(scandir("pages/$current_page") as $file) {
            if(str_starts_with($file, '.')) continue;

            $exif = exif_read_data("pages/$current_page/$file");
            if(!str_starts_with($exif['MimeType'], 'image/')) continue;

            $images[] = [
                'caption' => $exif['ImageDescription'] ?? '',
                'src' => "/pages/$current_page/$file",
                'width' => $exif['COMPUTED']['Width'],
                'height' => $exif['COMPUTED']['Height']
            ];
        }
    } else {
        $is_md_page = true;
        $md_page_content = (new Parsedown())->parse(file_get_contents("pages/$current_page.md"));
    }
}

require_once '../private/template.php';

$final_time = round((microtime(true) - $start_time) * 1000, 2);
echo "<script>console.log('Page generated in {$final_time}ms')</script>";