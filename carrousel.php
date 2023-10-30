<?php
/*
/*
  * Plugin name: Carrousel 5W5
  * Description: Cette extension carrousel permet d'afficher dans une boîte modale animée les images d'une galerie
  * Version: 1.2
  * Author: Noémie da Silva, Victor Desjardins, Vincent Gélinas, Vincent Hum, Dac Anne Nguyen
  * Author URI: https://github.com/5W5-Equipe-2
  */

  class MonCarrouselPlugin {
    public function __construct() {
        add_shortcode('5w5e2carrousel', array($this, 'mon_carrousel_shortcode'));
    }

    public function mon_carrousel_shortcode($atts) {
        // Récupérez les attributs du shortcode
        $atts = shortcode_atts(array(
            'categories' => '', // Attribut pour les catégories, vide par défaut
            'exclude_categories' => '', // Attribut pour les catégories en négatif, vide par défaut
            'operator' => 'ET', // Opérateur par défaut (ET)
            'exclude_operator' => 'ET', // Opérateur par défaut pour les catégories en négatif (ET)
        ), $atts);

        // Récupérez les catégories et les catégories en négatif
        $categories = $atts['categories'];
        $exclude_categories = $atts['exclude_categories'];

        // Récupérez les opérateurs
        $operator = $atts['operator'];
        $exclude_operator = $atts['exclude_operator'];

        // Excluez les catégories si la notation est utilisée
        $categories = $this->parseCategoriesWithOperator($categories, $operator);
        $exclude_categories = $this->parseCategoriesWithOperator($exclude_categories, $exclude_operator);

        ob_start();
?>
        <div class="mon-carrousel">
            <div class="carousel-content">
                <?php
                // Utilisez les catégories dans la requête WP_Query
                $args = array(
                    'posts_per_page' => 5, // Nombre d'articles à afficher
                    'category_name' => $categories,
                    'category__not_in' => array_map('get_cat_ID', explode(',', $exclude_categories)),
                );
                $query = new WP_Query($args);

                while ($query->have_posts()) : $query->the_post();
                ?>
                    <div class="carousel-item">
                        <div class="image_titre_carrousel">
                            <div class="thimbnail_carrousel"><?php get_the_post_thumbnail(); ?></div>
                            <h2><?php the_title(); ?></h2>
                        </div>
                        <div class="content"><?php the_content(); ?></div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <div class="carousel-navigation">
                <button class="prev-button"><</button>
                <span class="article-counter">1/10</span>
                <button class="next-button">></button>
            </div>
        </div>

<?php
        // Enqueue JavaScript et CSS
        wp_enqueue_script('carrousel-js', plugins_url('js/carrousel.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_style('carrousel-css', plugins_url('style.css', __FILE__));
        return ob_get_clean();
    }

    private function parseCategoriesWithOperator($categories, $operator) {
        // Divisez les catégories par des virgules
        $categories_array = explode(',', $categories);

        // Si l'opérateur est "ET", utilisez "+" pour inclure toutes les catégories
        if ($operator === 'ET') {
            $categories = implode('+', $categories_array);
        } else {
            // Si l'opérateur est "OU", utilisez "," pour inclure au moins une catégorie
            $categories = implode(',', $categories_array);
        }

        return $categories;
    }
}

$mon_carrousel_plugin = new MonCarrouselPlugin();
