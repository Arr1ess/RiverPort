<?php

use app\models\View;
use plugins\rBlock\controllers\Container;
use plugins\rBlock\models\Template;
use plugins\rBlock\rBlock;

$this->addScript("home.js"); ?>
<?php renderSinglePageAplictaion($this); ?>

<main>
    <h1>hi, it's start page</h1>
    <a href="/home">Home</a>


    <?php

    // const PRODUCT = SERVER_NAME . "/plugins/rBlock\public\uploads\containers/product.php";
    // Container::create(PRODUCT, (new Template(SERVER_NAME . "/app/views/article.php", ['text' => ""])), SERVER_NAME . "/plugins/rBlock/views/templates/test.php");
    // plugins\rBlock\public\uploads\containers

    // Container::addBlock(PRODUCT, ['text' => "Hello world"]);

    // Container::renderContainer(PRODUCT);

    // include_once __DIR__ . "/../../database/Column.php";
    include_once __DIR__ . "/../../database/Schema.php";

    ?>
</main>