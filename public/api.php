<?php

require_once '../private/functions.php';
header_remove('X-Powered-By');
header('Access-Control-Allow-Origin: *');

$config = json_decode(@file_get_contents('../private/config.json'), true);
$domain = $config['domain'];

$page = $_GET['page'] ?? die('Missing `page` parameter');
$width = $_GET['width'] ?? die('Missing `width` parameter');
$images = [];

foreach(scandir("pages/$page") as $image) {
    if(preg_match('/.+\.(png|jpg|jpeg)/', $image)) {
        $exif = exif_read_data("pages/$page/$image");
        if(!str_starts_with($exif['MimeType'], 'image/')) continue;

        $images[get_cdn_url("$domain/pages/$page/$image", $width)] =
            $exif['COMPUTED']['Width'] / $exif['COMPUTED']['Height'];
    }
}

header('Content-Type: application/json');
echo json_encode($images);