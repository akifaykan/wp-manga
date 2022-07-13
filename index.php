<?php get_header() ?>
<main>
    <div class="container">
        <ul class="manga_list"> 
            <?php
                $loop = new WP_Query([
                    'posts_per_page' => -1,
                    'post_type' => 'manga',
                    //'order' => 'ASC',
                ]);
        
                if( $loop->have_posts() ):
                    while( $loop->have_posts() ): $loop->the_post();
                        get_template_part('temp/content-manga');
                    endwhile;
                    wp_reset_postdata();
                endif;
            ?>
        </ul>
    </div>
</main>
<?php get_footer() ?>