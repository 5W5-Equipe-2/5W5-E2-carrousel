<?php
/*
/*
  * Plugin name: Carrousel 5W5
  * Description: Cette extension carrousel permet d'afficher dans une boîte modale animée les images d'une galerie
  * Version: 1.2
  * Author: Noémie da Silva, Victor Desjardins, Vincent Gélinas, Vincent Hum, Dac Anne Nguyen
  * Author URI: https://github.com/5W5-Equipe-2
  */

function mon_carrousel_settings_page()
{
    add_menu_page('Carrousel Settings', 'Carrousel Settings', 'manage_options', 'mon-carrousel-settings');
}

class MonCarrouselPlugin
{
    ///////////////////////Création d'un shortcode pour implémenter le carrousel dans les pages ou articles///////////////////////////
    public function __construct()
    {
        add_shortcode('5w5e2carrousel', array($this, 'mon_carrousel_shortcode'));
    }

    public function mon_carrousel_shortcode($atts)
    {
        // Récupérez les attributs du shortcode, y compris l'attribut max_posts
        $atts = shortcode_atts(array(
            'categories' => '', // Attribut pour les catégories, vide par défaut
            'exclude_categories' => '', // Attribut pour les catégories en négatif, vide par défaut
            'operator' => 'ET', // Opérateur par défaut (ET)
            'exclude_operator' => 'ET', // Opérateur par défaut pour les catégories en négatif (ET)
            'max_posts' => -1, // Nombre maximum d'articles à afficher (par défaut, -1 signifie tous les articles)
        ), $atts);

        // Récupérez les catégories et les catégories en négatif
        $categories = $atts['categories'];
        $exclude_categories = $atts['exclude_categories'];
        // Récupérez le thème depuis les options
        $carrousel_theme = get_option('mon_carrousel_theme', 'light');


        // Récupérez les opérateurs
        $operator = $atts['operator'];
        $exclude_operator = $atts['exclude_operator'];

        // Excluez les catégories si la notation est utilisée
        $categories = $this->parseCategoriesWithOperator($categories, $operator);
        $exclude_categories = $this->parseCategoriesWithOperator($exclude_categories, $exclude_operator);

        ob_start();
?>
        <!-------------------------------------------------------------------------------- 
        Début de la création du carrousel
        ----------------------------------------------------------------------------------->
        <div class="mon-carrousel <?php echo esc_attr($carrousel_theme); ?>">
            <div class="carrousel-content">
                <?php
                // Utilisez l'attribut max_posts dans la requête WP_Query pour limiter le nombre d'articles
                $args = array(
                    'posts_per_page' => $atts['max_posts'], // Nombre d'articles à afficher
                    'category_name' => $categories,
                    'category__not_in' => array_map('get_cat_ID', explode(',', $exclude_categories)),
                );
                $query = new WP_Query($args);

                while ($query->have_posts()) : $query->the_post();
                    $titre = get_the_title();
                ?>
                    <!-------------------------------------------------------------------------------- 
                    La structure si c'est une page Autre que front page
                    ----------------------------------------------------------------------------------->
                    <?php if (!is_front_page()) { ?>
                        <div class="carrousel-item categorie__article">
                            <!--- Bloque de Guauche avec image et titre --->
                            <div class="image_titre_date">
                                <!--- Contenant de l'image --->
                                <div class="contenant__image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a class="thumbnail_carrousel" title="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('thumbnail', 'full'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <!--  Afficher le titre l'article (clicable) -->
                                <div class="contenant__titre">
                                    <h3><?= $titre ?></h3>
                                </div>
                            </div>
                            <!--- Contenu --->
                            <div class="content">
                                <div class="contenant_contenu">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-------------------------------------------------------------------------------- 
                    Si c'est la page d'accueil seulement
                    ----------------------------------------------------------------------------------->
                    <?php if (is_front_page()) { ?>
                        <div class="carrousel-item categorie__article">
                            <div class="image_titre_date">
                                <div class="contenant__image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a class="thumbnail_carrousel" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('thumbnail', 'full'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="contenant__titre">
                                    <h3><a href="<?php the_permalink(); ?>"> <?= $titre ?></a></h3>
                                    <div class="conteneur_date">
                                        <?php //icone blanche
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="25.446" height="23.588" viewBox="0 0 25.446 23.588"><g id="Groupe_213" data-name="Groupe 213" transform="translate(0.022)"><rect id="Rectangle_203" data-name="Rectangle 203" width="25.322" height="20.447" rx="2.65" transform="translate(0.04 2.621)" fill="#fff"/><path id="Tracé_181" data-name="Tracé 181" d="M20.26,6.42a1,1,0,0,0,.234.028h.243a.941.941,0,0,0,.234-.028Z" transform="translate(-1.327 -0.661)" fill="#fff"/><path id="Tracé_181_-_Contour" data-name="Tracé 181 - Contour" d="M17.986,6.14h5.258l-2.206.552a1.227,1.227,0,0,1-.3.036h-.243a1.28,1.28,0,0,1-.3-.036Z" transform="translate(-1.327 -0.661)" fill="#1d1d1b"/><path id="Tracé_182" data-name="Tracé 182" d="M23.094,3.18h-2.57V5.671a.5.5,0,0,1-.5.5h-.355a1.01,1.01,0,0,1-.234.028h-.243a.953.953,0,0,1-.234-.028h-.346a.5.5,0,0,1-.5-.5V3.18H13.927V5.7a.5.5,0,0,1-.5.5H12.012a.5.5,0,0,1-.5-.5V3.18H7.2V5.7a.5.5,0,0,1-.5.5H5.284a.5.5,0,0,1-.5-.5V3.18H2.332A1.96,1.96,0,0,0,.36,5.127v16.7a1.96,1.96,0,0,0,1.972,1.947H23.084a1.96,1.96,0,0,0,1.972-1.947V5.127A1.96,1.96,0,0,0,23.084,3.18Zm.635,17.953a1.71,1.71,0,0,1-1.719,1.7H3.425a1.7,1.7,0,0,1-1.719-1.7V9.942H23.738V21.133Z" transform="translate(-0.007 -0.559)" fill="#706f6f"/><path id="Tracé_182_-_Contour" data-name="Tracé 182 - Contour" d="M2.332,2.805H5.155V5.7a.125.125,0,0,0,.13.123H6.7a.125.125,0,0,0,.13-.123V2.805h5.058V5.7a.125.125,0,0,0,.13.123h1.411a.125.125,0,0,0,.13-.123V2.805h4.927V5.671a.125.125,0,0,0,.13.123H19l.044.011a.576.576,0,0,0,.144.017h.243a.636.636,0,0,0,.144-.017l.044-.011h.4a.125.125,0,0,0,.13-.123V2.805h2.945a2.337,2.337,0,0,1,2.337,2.322v16.7a2.337,2.337,0,0,1-2.347,2.322H2.332A2.337,2.337,0,0,1-.015,21.825V5.127A2.337,2.337,0,0,1,2.332,2.805Zm2.073.75H2.332a1.586,1.586,0,0,0-1.6,1.572v16.7a1.586,1.586,0,0,0,1.6,1.572H23.084a1.586,1.586,0,0,0,1.6-1.572V5.127a1.586,1.586,0,0,0-1.6-1.572H20.9V5.671a.877.877,0,0,1-.88.873h-.314a1.407,1.407,0,0,1-.275.028h-.243a1.341,1.341,0,0,1-.275-.028h-.3a.877.877,0,0,1-.88-.873V3.555H14.3V5.7a.877.877,0,0,1-.88.873H12.012a.877.877,0,0,1-.88-.873V3.555H7.575V5.7a.877.877,0,0,1-.88.873H5.284A.877.877,0,0,1,4.4,5.7ZM1.331,9.567H24.113V21.508H24.07a2.092,2.092,0,0,1-2.06,1.7H3.425a2.086,2.086,0,0,1-2.094-2.072Zm22.033.75H2.081V21.133a1.335,1.335,0,0,0,1.344,1.322H22.01a1.335,1.335,0,0,0,1.344-1.322v-.375h.009Z" transform="translate(-0.007 -0.559)" fill="#1d1d1b"/><path id="Tracé_183" data-name="Tracé 183" d="M13.383.5h.234A1.089,1.089,0,0,1,14.71,1.593V4.966A1.1,1.1,0,0,1,13.617,6.06h-.234A1.089,1.089,0,0,1,12.29,4.966V1.593A1.1,1.1,0,0,1,13.383.5Z" transform="translate(-0.804)" fill="#fff"/><path id="Tracé_183_-_Contour" data-name="Tracé 183 - Contour" d="M13.383,0h.234A1.6,1.6,0,0,1,15.21,1.593V4.966A1.6,1.6,0,0,1,13.617,6.56h-.234A1.6,1.6,0,0,1,11.79,4.966V1.593A1.6,1.6,0,0,1,13.383,0Zm.234,5.56a.594.594,0,0,0,.593-.593V1.593A.587.587,0,0,0,13.617,1h-.234a.594.594,0,0,0-.593.593V4.966a.587.587,0,0,0,.593.593Z" transform="translate(-0.804)" fill="#1d1d1b"/><path id="Tracé_184" data-name="Tracé 184" d="M6.183.5h.234A1.089,1.089,0,0,1,7.51,1.593V4.966A1.1,1.1,0,0,1,6.417,6.06H6.183A1.089,1.089,0,0,1,5.09,4.966V1.593A1.1,1.1,0,0,1,6.183.5Z" transform="translate(-0.331)" fill="#fff"/><path id="Tracé_184_-_Contour" data-name="Tracé 184 - Contour" d="M6.183,0h.234A1.6,1.6,0,0,1,8.01,1.593V4.966A1.6,1.6,0,0,1,6.417,6.56H6.183A1.6,1.6,0,0,1,4.59,4.966V1.593A1.6,1.6,0,0,1,6.183,0Zm.234,5.56a.594.594,0,0,0,.593-.593V1.593A.587.587,0,0,0,6.417,1H6.183a.594.594,0,0,0-.593.593V4.966a.587.587,0,0,0,.593.593Z" transform="translate(-0.331)" fill="#1d1d1b"/><path id="Tracé_185" data-name="Tracé 185" d="M21.769,1.593V4.966a1.093,1.093,0,0,1-.86,1.065,1,1,0,0,1-.234.028h-.243a.941.941,0,0,1-.234-.028,1.093,1.093,0,0,1-.86-1.065V1.593A1.089,1.089,0,0,1,20.433.5h.243A1.089,1.089,0,0,1,21.769,1.593Z" transform="translate(-1.266)" fill="#fff"/><path id="Tracé_185_-_Contour" data-name="Tracé 185 - Contour" d="M20.433,0h.243a1.6,1.6,0,0,1,1.593,1.593V4.966a1.594,1.594,0,0,1-1.248,1.553,1.511,1.511,0,0,1-.346.041h-.243a1.445,1.445,0,0,1-.346-.041A1.594,1.594,0,0,1,18.84,4.966V1.593A1.6,1.6,0,0,1,20.433,0Zm.243,5.56a.5.5,0,0,0,.112-.013l.018,0a.59.59,0,0,0,.463-.576V1.593A.587.587,0,0,0,20.676,1h-.243a.587.587,0,0,0-.593.593V4.966a.59.59,0,0,0,.463.576l.018,0a.445.445,0,0,0,.112.013Z" transform="translate(-1.266)" fill="#1d1d1b"/><rect id="Rectangle_204" data-name="Rectangle 204" width="2.373" height="2.607" rx="0.78" transform="translate(18.989 11.394)" fill="#1d1d1b"/><path id="Rectangle_204_-_Contour" data-name="Rectangle 204 - Contour" d="M.78-.25h.813A1.031,1.031,0,0,1,2.623.78V1.827a1.031,1.031,0,0,1-1.03,1.03H.78A1.031,1.031,0,0,1-.25,1.827V.78A1.031,1.031,0,0,1,.78-.25Zm.813,2.607a.531.531,0,0,0,.53-.53V.78a.531.531,0,0,0-.53-.53H.78A.531.531,0,0,0,.25.78V1.827a.531.531,0,0,0,.53.53Z" transform="translate(18.989 11.394)" fill="#1d1d1b"/><rect id="Rectangle_205" data-name="Rectangle 205" width="2.373" height="2.607" rx="0.78" transform="translate(13.878 11.394)" fill="#1d1d1b"/><path id="Rectangle_205_-_Contour" data-name="Rectangle 205 - Contour" d="M.78-.25h.813A1.031,1.031,0,0,1,2.623.78V1.827a1.031,1.031,0,0,1-1.03,1.03H.78A1.031,1.031,0,0,1-.25,1.827V.78A1.031,1.031,0,0,1,.78-.25Zm.813,2.607a.531.531,0,0,0,.53-.53V.78a.531.531,0,0,0-.53-.53H.78A.531.531,0,0,0,.25.78V1.827a.531.531,0,0,0,.53.53Z" transform="translate(13.878 11.394)" fill="#1d1d1b"/><rect id="Rectangle_206" data-name="Rectangle 206" width="2.373" height="2.607" rx="0.78" transform="translate(8.776 11.394)" fill="#1d1d1b"/><path id="Rectangle_206_-_Contour" data-name="Rectangle 206 - Contour" d="M.78-.25h.813A1.031,1.031,0,0,1,2.623.78V1.827a1.031,1.031,0,0,1-1.03,1.03H.78A1.031,1.031,0,0,1-.25,1.827V.78A1.031,1.031,0,0,1,.78-.25Zm.813,2.607a.531.531,0,0,0,.53-.53V.78a.531.531,0,0,0-.53-.53H.78A.531.531,0,0,0,.25.78V1.827a.531.531,0,0,0,.53.53Z" transform="translate(8.776 11.394)" fill="#1d1d1b"/><path id="Rectangle_207" data-name="Rectangle 207" d="M.78-.25h.813A1.031,1.031,0,0,1,2.623.78V1.827a1.031,1.031,0,0,1-1.03,1.03H.78A1.031,1.031,0,0,1-.25,1.827V.78A1.031,1.031,0,0,1,.78-.25Zm.813,2.607a.531.531,0,0,0,.53-.53V.78a.531.531,0,0,0-.53-.53H.78A.531.531,0,0,0,.25.78V1.827a.531.531,0,0,0,.53.53Z" transform="translate(8.776 16.373)" fill="#1d1d1b"/><rect id="Rectangle_208" data-name="Rectangle 208" width="2.373" height="2.607" rx="0.78" transform="translate(3.6 11.394)" fill="#1d1d1b"/><path id="Rectangle_208_-_Contour" data-name="Rectangle 208 - Contour" d="M.78-.25h.813A1.031,1.031,0,0,1,2.623.78V1.827a1.031,1.031,0,0,1-1.03,1.03H.78A1.031,1.031,0,0,1-.25,1.827V.78A1.031,1.031,0,0,1,.78-.25Zm.813,2.607a.531.531,0,0,0,.53-.53V.78a.531.531,0,0,0-.53-.53H.78A.531.531,0,0,0,.25.78V1.827a.531.531,0,0,0,.53.53Z" transform="translate(3.6 11.394)" fill="#1d1d1b"/><rect id="Rectangle_209" data-name="Rectangle 209" width="2.373" height="2.607" rx="0.78" transform="translate(3.6 16.373)" fill="#1d1d1b"/><path id="Rectangle_209_-_Contour" data-name="Rectangle 209 - Contour" d="M.78-.25h.813A1.031,1.031,0,0,1,2.623.78V1.827a1.031,1.031,0,0,1-1.03,1.03H.78A1.031,1.031,0,0,1-.25,1.827V.78A1.031,1.031,0,0,1,.78-.25Zm.813,2.607a.531.531,0,0,0,.53-.53V.78a.531.531,0,0,0-.53-.53H.78A.531.531,0,0,0,.25.78V1.827a.531.531,0,0,0,.53.53Z" transform="translate(3.6 16.373)" fill="#1d1d1b"/></g></svg>';
                                        ?>
                                        <!-- Afficher les informations des champs AFC -->
                                        <p> <?php the_field('date_et_heure'); ?></p>
                                    </div>
                                </div>
                                <!--  Afficher le titre l'article (clicable) -->

                            </div>

                            <div class="content">
                                <div class="contenant_contenu">
                                    <!--  Un div qui inclus les informations de l'évènement -->
                                    <?php
                                    $lien = get_permalink();
                                    $lire = "<span><a href='" . $lien . "'>...</a></span>" ?>
                                    <!-- Afficher un extrait de l'article -->
                                    <p> <?= wp_trim_words(get_the_excerpt(), 18, $lire) ?> </p>

                                    <h5>Information (et inscription)</h5>
                                    <!-- Afficher les informations des champs AFC -->
                                    <p><?php the_field('qui'); ?></p>
                                    <p> <?php the_field('quoi'); ?></p>
                                    <p> <?php the_field('lieu'); ?></p>

                                    <!-- Afficher l'icône pour la date de publication-->
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21.289" height="21.289" viewBox="0 0 21.289 21.289">
                                            <g transform="translate(9.644 3.37)" fill="#fffcf9">
                                                <path d="M 1 8.774174690246582 C 0.7243000268936157 8.774174690246582 0.5 8.58243465423584 0.5 8.346755027770996 L 0.5 0.9274149537086487 C 0.5 0.6917449831962585 0.7243000268936157 0.5000049471855164 1 0.5000049471855164 C 1.275699973106384 0.5000049471855164 1.5 0.6917449831962585 1.5 0.9274149537086487 L 1.5 2.12646484375 L 1.5 8.346755027770996 C 1.5 8.58243465423584 1.275699973106384 8.774174690246582 1 8.774174690246582 Z" stroke="none" />
                                                <path d="M 1 0.999995231628418 L 1 8.274165153503418 L 1 0.999995231628418 M 1 -4.76837158203125e-06 C 1.552279949188232 -4.76837158203125e-06 2 0.4152145385742188 2 0.9274148941040039 L 2 8.346755027770996 C 2 8.858955383300781 1.552279949188232 9.274165153503418 1 9.274165153503418 C 0.4477200508117676 9.274165153503418 0 8.858955383300781 0 8.346755027770996 L 0 0.9274148941040039 C 0 0.4152145385742188 0.4477200508117676 -4.76837158203125e-06 1 -4.76837158203125e-06 Z" stroke="none" fill="#fff" />
                                            </g>
                                            <g transform="translate(9.644 12.059) rotate(-45)" fill="#fffcf9" stroke="#fff" stroke-width="1">
                                                <rect width="2" height="7.256" rx="1" stroke="none" />
                                                <rect x="0.5" y="0.5" width="1" height="6.256" rx="0.5" fill="none" />
                                            </g>
                                            <g fill="none">
                                                <path d="M10.645,0A10.645,10.645,0,1,1,0,10.645,10.645,10.645,0,0,1,10.645,0Z" stroke="none" />
                                                <path d="M 10.64455032348633 2 C 5.877930641174316 2 2 5.877930641174316 2 10.64455032348633 C 2 15.41117095947266 5.877930641174316 19.28910064697266 10.64455032348633 19.28910064697266 C 15.41117095947266 19.28910064697266 19.28910064697266 15.41117095947266 19.28910064697266 10.64455032348633 C 19.28910064697266 5.877930641174316 15.41117095947266 2 10.64455032348633 2 M 10.64455032348633 0 C 16.52337074279785 0 21.28910064697266 4.765729904174805 21.28910064697266 10.64455032348633 C 21.28910064697266 16.52337074279785 16.52337074279785 21.28910064697266 10.64455032348633 21.28910064697266 C 4.765729904174805 21.28910064697266 0 16.52337074279785 0 10.64455032348633 C 0 4.765729904174805 4.765729904174805 0 10.64455032348633 0 Z" stroke="none" fill="#fff" />
                                            </g>
                                        </svg>
                                        <!-- Afficher date et heure de la publication'-->
                                        <p><?php the_time('j F Y \à G:i'); ?></p>
                                    </div>

                                    <!-- Afficher l'icône pour la date de mise à jour -->
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21.256" height="20.07" viewBox="0 0 21.256 20.07">
                                            <path id="TracÃ__169" data-name="TracÃ©_169" d="M8.975,1.959h0a4.761,4.761,0,0,0-.661.165c-.145.052-.289.093-.444.145a9.238,9.238,0,0,0,5.673,17.577v.2A10.02,10.02,0,0,1,2.827,4.025l.248-.279c.041-.041.072-.083.114-.124s.083-.093.124-.134c.062-.072.134-.134.2-.2s.134-.124.207-.2a1.632,1.632,0,0,1,.165-.145,2.069,2.069,0,0,0,.165-.145c.052-.052.134-.114.2-.165l.145-.114.176-.134.176-.124A10.049,10.049,0,0,1,9.213.532C9.326.522,9.43.5,9.543.491a1.674,1.674,0,0,0,.227-.01C9.843.47,9.926.47,10,.46a1.822,1.822,0,0,1,.238,0H11a1.752,1.752,0,0,1,.238.01l.238.021a1.272,1.272,0,0,1,.207.021h.062A10.092,10.092,0,0,1,19.825,6.64l1.343-.372a.43.43,0,0,1,.527.3.473.473,0,0,1-.01.269L20.569,9.8,20.5,10l-.372.992-.062.165a.426.426,0,0,1-.444.279.372.372,0,0,1-.227-.093l-.475-.372-3.131-2.48a.44.44,0,0,1-.072-.61.413.413,0,0,1,.227-.145l1.767-.486c-1.695-2.986-3.792-5.229-7.43-5.342H9.492l-.537.041h0Z" transform="translate(-0.454 -0.456)" fill="#fff" />
                                        </svg>
                                        <!-- Afficher la date et heure de la mise-à-jour'-->
                                        <p><?php the_modified_time('j F Y \à G:i'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <?php
            //Boutons de navigation du carrousel//
            ?>
            <div class="carrousel-navigation">
                <button class="prev-button"><?php echo '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><g><path d="M12,2A10,10,0,1,0,22,12,10.011,10.011,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.009,8.009,0,0,1,12,20Z"/><polygon points="12.707 8.707 11.293 7.293 6.586 12 11.293 16.707 12.707 15.293 10.414 13 16 13 16 11 10.414 11 12.707 8.707"/></g></svg>' ?></button>
                <span class="article-counter">1/10</span>
                <button class="next-button"><?php echo '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><g><path d="M12,2A10,10,0,1,0,22,12,10.011,10.011,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.009,8.009,0,0,1,12,20Z"/><polygon points="11.293 8.707 13.586 11 8 11 8 13 13.586 13 11.293 15.293 12.707 16.707 17.414 12 12.707 7.293 11.293 8.707"/></g></svg>' ?></button>
            </div>
        </div>

        <?php
        // Enqueue JavaScript et CSS
        wp_enqueue_script('carrousel-js', plugins_url('js/carrousel.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_style('carrousel-css', plugins_url('style.css', __FILE__));
        return ob_get_clean();
    }

    private function parseCategoriesWithOperator($categories, $operator)
    {
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
