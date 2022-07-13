<?php

get_header();

$cilt = get_field('cilt');
$bolum = get_field('bolum');

?>
<main class="main" data-episode="<?=$bolum?>" data-volume="<?=$cilt?>" data-url="<?=K_URI?>">
    <div class="numbers"></div>
    <div class="single__container imgs"></div>
</main>
<?php

get_footer();