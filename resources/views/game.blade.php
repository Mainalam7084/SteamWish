<?php
require app_path() . '/Includes/steam_wrapper.php';

$appid = $_GET['appid'] ?? null;
if ($appid == null) {
    die('No appid');
}

$details = getAppDetails($appid, 'es')[$appid]['data'];
$app_name = $details['name'];
$app_short_desc = $details['short_description'];
$app_header_img = $details['header_image'];
$app_price = $details['price_overview']['final_formatted'] ?? 'Free';
$app_publisher = $details['publishers'][0] ?? 'Unknown';
$app_developer = $details['developers'][0] ?? 'Unknown';
$app_detailed_desc = $details['detailed_description'] ?? '';

$discount_percent = $details['price_overview']['discount_percent'] ?? 0;
$discount_formatted = $discount_percent > 0 ? "(-$discount_percent%)" : '';

$screenshots = $details['screenshots'];
?>
<!DOCTYPE html>
<html>

<head>
    @vite(['resources/css/style.css', 'resources/css/game.css', 'resources/css/slideshow.css', 'resources/js/slideshow.js'])
    <title><?= $app_name ?></title>
</head>

<body>
    <header>
        <a href="../">Volver</a>
    </header>
    <div class="gameContainer">
        <h1><?= $app_name ?></h1>
        <img src="<?= $app_header_img ?>" alt="<?= $app_name ?>">

        <div class="info">
            <p><strong>Price:</strong> <?= $app_price ?> <?= $discount_formatted ?></p>
            <p><strong>Publisher:</strong> <?= $app_publisher ?></p>
            <p><strong>Developer:</strong> <?= $app_developer ?></p>
        </div>

        <p class="short-description"><?= $app_short_desc ?></p>
        <div class="slideshow-container">
            <?php for ($i = 0; $i < count($screenshots); $i++): ?>
            <div class="mySlides">
                <img style="width:100%" src="<?= $screenshots[$i]['path_thumbnail'] ?>">
                <div class="numbertext"><?= $i + 1 ?> / <?= count($screenshots) ?></div>
            </div>
            <?php endfor; ?>

            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>

        </div>
        <div class="detailed-description">
            <?= $app_detailed_desc ?>
        </div>
    </div>
</body>

</html>
