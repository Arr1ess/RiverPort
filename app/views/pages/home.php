<?php

use plugins\rBlock\models\pazl;
use plugins\rBlock\models\block;

$this->addScript("home.js"); ?>
<?php renderSinglePageAplictaion($this); ?>

<?php

class myBlock extends block
{
    public function view(int $block_id)
    {
        $name = $this->getPazlName();
        $block = <<<HTML
        <div data-block=$block_id>
            it's block number $block_id
            <button data-delete_block=$block_id data-pazl_name=$name>Удалить залупу</button>
        </div>
        HTML;
        echo $block;
    }
}


class myPazl extends pazl {}

$pazl = new myPazl("myBlock", $this);




$pazl->addBlock(new myBlock());
$pazl->addBlock(new myBlock());
$pazl->addBlock(new myBlock());
$pazl->addBlock(new myBlock());
$pazl->addBlock(new myBlock());
$pazl->addBlock(new myBlock());

?>
<main>
    <h1>hi, it's home page</h1>
    <a href="/" target="_blank">Главная</a>




    <?php
    $pazl->view();
    ?>
</main>