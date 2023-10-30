<?php
/*
/*
  * Plugin name: Carrousel 5W5
  * Description: Cette extension carrousel permet d'afficher dans une boîte modale animée les images d'une galerie
  * Version: 1.0
  * Author: Noémie da Silva, Victor Desjardins, Vincent Gélinas, Vincent Hum, Dac Anne Nguyen
  * Author URI: https://github.com/5W5-Equipe-2
  */

  function mon_carrousel_shortcode($atts)
  {
      // Récupérez les attributs du shortcode
      $atts = shortcode_atts(array(
          'categories' => '', // Attribut pour les catégories, vide par défaut
      ), $atts);
  
      // Récupérez les catégories du shortcode
      $categories = $atts['categories'];
  
      // Excluez les catégories si la notation est utilisée
      $exclude_categories = array();
      if (strpos($categories, '-') !== false) {
          $categories_array = explode(',', $categories);
          foreach ($categories_array as $cat) {
              if (substr($cat, 0, 1) === '-') {
                  $exclude_categories[] = ltrim($cat, '-');
              }
          }
          $categories = implode(',', array_diff($categories_array, $exclude_categories));
      }
  
      ob_start();
  ?>
      <div class="mon-carrousel">
          <div class="carousel-content">
              <?php
              // Utilisez les catégories dans la requête WP_Query
              $args = array(
                  'posts_per_page' => 5, // Nombre d'articles à afficher
                  'category_name' => $categories,
                  'category__not_in' => array_map('get_cat_ID', $exclude_categories),
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
      // Enqueue JavaScript et CSS
      wp_enqueue_script('carrousel-js', plugins_url('js/carrousel.js', __FILE__), array('jquery'), '1.0', true);
      wp_enqueue_style('carrousel-css', plugins_url('sass/style.scss', __FILE__));
      return ob_get_clean();
  }
  add_shortcode('5w5e2carrousel', 'mon_carrousel_shortcode');