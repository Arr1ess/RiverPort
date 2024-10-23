<?php
if (!empty($seoData)) {
    ?>
    <title><?= htmlspecialchars($seoData['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($seoData['description']) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($seoData['keywords']) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($seoData['canonical']) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seoData['og_title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seoData['og_description']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seoData['og_image']) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($seoData['og_url']) ?>">
    <meta property="og:type" content="<?= htmlspecialchars($seoData['og_type']) ?>">
    <?php
} else {
    ?>
    <title>Default Title</title>
    <meta name="description" content="Default Description">
    <meta name="keywords" content="Default Keywords">
    <?php
}