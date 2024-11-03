<div class="test" style="border: 2px green solid;" data-container="<?= $container_path ?>">
    <h2 class="test__test">
        Container - test
    </h2>
    <div class="flex" style="display: flex; flex-direction:column;">
        <?php array_walk($blocks, function ($block, $index) use ($template) {
            $template->render($block, $index);
        }); ?>
    </div>
</div>