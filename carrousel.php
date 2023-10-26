<?php
/*
Plugin Name: Mon Carrousel
Description: Un carrousel interactif pour afficher des articles de différentes catégories.
Version: 1.0
*/

function mon_carrousel_shortcode()
{
    ob_start();
?>
    <div class="mon-carrousel">
        <div class="carousel-content">
            <?php
            // Récupérez les articles de différentes catégories
            $args = array(
                'posts_per_page' => 5, // Nombre d'articles à afficher
                'category_name' => '1j1', // Remplacez par la catégorie souhaitée
            );
            $query = new WP_Query($args);

            while ($query->have_posts()) : $query->the_post();
            ?>
                <div class="carousel-item">
                    <h2><?php the_title(); ?></h2>
                    <div class="content"><?php the_content(); ?></div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <div class="carousel-navigation">
            <button class="prev-button">Précédent</button>
            <span class="article-counter">1/10</span>
            <button class="next-button">Suivant</button>
        </div>

    </div>



<?php
    wp_enqueue_script('carrousel-js', plugins_url('js/carrousel.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('carrousel-css', plugins_url('sass/style.scss', __FILE__));
    return ob_get_clean();
}
add_shortcode('5w5e2carrousel', 'mon_carrousel_shortcode');
