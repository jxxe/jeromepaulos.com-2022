<?php

/**
 * @var string $title The page's <title>
 * @var string $name
 * @var string $contact
 * @var string[] $albums
 * @var array[] $other_links Each subarray contains a `name` and `href` item
 * @var string[] $md_pages
 * @var array[] $images Each subarray contains a `caption`, `src`, `width`, and `height` item
 * @var bool $is_md_page
 * @var string $md_page_content
 */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://use.typekit.net/lvr7tib.css">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body class="<?= $is_md_page ? 'text' : 'gallery' ?>-page">

<div id="sidebar">
    <div class="top">
        <div class="info">
            <h1 class="title">
                <a href="/"><?= htmlspecialchars($name) ?></a>
            </h1>
            <h2 class="contact"><?= htmlspecialchars($contact) ?></h2>
        </div>

        <div class="link-groups">
            <nav class="links">
                <?php foreach($albums as $index => $album): ?>
                    <a href="/<?= $index === 0 ? '' : slugify($album) ?>"><?= htmlspecialchars($album) ?></a>
                <?php endforeach; ?>
            </nav>

            <?php if(!empty($md_pages)): ?>
                <div class="links">
                    <?php foreach($md_pages as $page): ?>
                        <a href="/<?= slugify($page) ?>"><?= htmlspecialchars($page) ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($other_links)): ?>
                <div class="links">
                    <?php foreach($other_links as $link): ?>
                        <a href="<?= htmlspecialchars($link['href']) ?>"><?= htmlspecialchars($link['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bottom">
        <p class="caption"><?= $images[0]['caption'] ?? '' ?></p>
    </div>
</div>

<?php if($is_md_page): ?>
    <div id="main" class="text">
        <?= $md_page_content ?>
    </div>
<?php else: ?>
    <div id="main" class="gallery">
        <div class="images">
            <?php foreach($images as $index => $image): ?>
                <img
                    width="<?= htmlspecialchars($image['width']) ?>"
                    height="<?= htmlspecialchars($image['height']) ?>"
                    src="<?= htmlspecialchars($image['src']) ?>"
                    data-caption="<?= htmlspecialchars($image['caption']) ?>"
                    alt="<?= htmlspecialchars($image['caption']) ?>"
                    data-hash="<?= hash('crc32', $image['src']) ?>"
                    class="image<?= $index === 0 ? ' focused' : '' ?>"
                >
            <?php endforeach; ?>
        </div>
    </div>

    <script src="/assets/main.js"></script>

    <noscript>
        <style>
            .gallery { overflow: scroll!important; }
            .gallery img { opacity: 1!important; }
            noscript { display: none!important; }
        </style>
    </noscript>
<?php endif; ?>

</body>
</html>