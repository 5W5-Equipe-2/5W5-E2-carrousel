<?php
/*
  * Plugin name: Carrousel 5W5
  * Description: Cette extension carrousel permet d'afficher dans une boîte modale animée les images d'une galerie
  * Version: 1.0
  * Author: Noémie da Silva, Victor Desjardins, Vincent Gélinas, Vincent Hum, Dac Anne Nguyen
  * Author URI: https://github.com/5W5-Equipe-2
  */


function mon_enqueue_css_js()
{
  $version_css = filemtime(plugin_dir_path(__FILE__) . "style.css");
  $version_js = filemtime(plugin_dir_path(__FILE__) . "js/carrousel.js");

  wp_enqueue_style(
    '5W5_plugin_carrousel_css',
    plugin_dir_url(__FILE__) . "style.css",
    array(),
    $version_css
  );

  wp_enqueue_script(
    '5W5_plugin_carrousel_js',
    plugin_dir_url(__FILE__) . "js/carrousel.js",
    array(),
    $version_js,
    true
  ); //permet d'ajouter le JS à la fin de la page

}

add_action('wp_enqueue_scripts', 'mon_enqueue_css_js');

function creation_carrousel()
{

  return "<div class='carrousel'>
            <button class='carrousel__x'>X</button>
            <div class='fleche carrousel__fleche_gauche'><</div>
            <div class='fleche carrousel__fleche_droite'>></div>
            <figure class='carrousel__figure'>
            
            </figure>
            <form class='carrousel__form'></form>
            </div> <!-- fin du carrousel -->
    ";
}
add_shortcode('5W5_carrousel', 'creation_carrousel');
