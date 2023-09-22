(function () {
  console.log("la galerie");

  //Variables pour les classes css du carrousel et la balise html img
  let carrousel = document.querySelector(".carrousel"),
    carrouselX = document.querySelector(".carrousel__x"),
    carrouselFigure = document.querySelector(".carrousel__figure"),
    carrouselForm = document.querySelector(".carrousel__form"),
    visuelFlecheGauche = document.querySelector(".carrousel__fleche_gauche"),
    visuelFlecheDroite = document.querySelector(".carrousel__fleche_droite"),
    galerie = document.querySelector(".galerie"),
    galerieImg = galerie.querySelectorAll("img");

  // Variables pour la position des boutons radios et pour faire avancer les images du carrousel
  let position = 0;
  let index = 0;
  let ancienIndex = null;

  /******************** Pour fermer le carrousel ********************************/
  carrouselX.addEventListener("mousedown", function () {
    carrousel.classList.remove("carrousel-activer");
    document.removeEventListener("keydown", surveillerTouche);
  });

  /***************  Ajouter chaque image d'une galerie au carrousel *************/
  /* -- boucle qui permet construire le carrousel */
  for (const elt of galerieImg) {
    elt.dataset.index = position;
    /* en cliquant sur une image de la galerie */
    elt.addEventListener("mousedown", function (e) {
      /*
      si le carrousel n'est pas déjà ouvert, il faut lui ajouter
      la classe "carrousel-activer"
      */
      if (!elt.classList.contains("carrousel-activer")) {
        carrousel.classList.add("carrousel-activer");
      }

      /* Écouteur sur les touches du clavier */
      document.addEventListener("keydown", surveillerTouche);

      /* Initialiser l'index des images */
      index = e.target.dataset.index;
      carrouselForm.children[index].checked = true;

      /* Appel de la fonction pour afficher une image */
      afficherImageCarrousel();

      /* Écouteur sur les flèches de naviagtion */
      visuelFlecheGauche.addEventListener("mousedown", reculerImage);
      visuelFlecheDroite.addEventListener("mousedown", avancerImage);
    });
    /* Appel des fonctions pour ajouter des images et des boutons radio */
    ajouterUneImageDansCaroussel(elt);
    ajouterUnBoutonRadio();
  }

  /**
   * Création dynamique d'une image pour le carousel
   * @param {*} elt une image de la galerie
   */

  function ajouterUneImageDansCaroussel(elt) {
    let elImg = `<img src=${elt.src} class="carrousel__img" alt="">`;
    carrouselFigure.insertAdjacentHTML("beforeend", elImg);
  }

  /**
   * Fonction pour afficher la nouvelle image du carrousel
   */
  function afficherImageCarrousel() {
    if (ancienIndex != null) {
      carrouselFigure.children[ancienIndex].style.opacity = "0";
      carrouselFigure.children[ancienIndex].classList.remove(
        "carrousel__img-activer"
      );

      carrouselForm.children[ancienIndex].checked = false;
    }

    carrouselFigure.children[index].style.opacity = "1";
    carrouselFigure.children[index].classList.add("carrousel__img-activer");

    carrouselForm.children[index].checked = true;
    ancienIndex = index;

    /** Afficher les images sans les distortionner */
    let imgLargeur = carrouselFigure.children[index].naturalWidth,
      imgHauteur = carrouselFigure.children[index].naturalHeight,
      ratio = `${(imgHauteur / imgLargeur) * 100}%`;

    document.documentElement.style.setProperty("--ratio", ratio);
  }

  /**
   * Fonction pour ajouter des boutons radio
   * et avancer les images
   */

  function ajouterUnBoutonRadio() {
    let rad = document.createElement("input");
    rad.setAttribute("type", "radio");
    rad.setAttribute("name", "carrousel__rad");
    rad.classList.add("carrousel__rad");
    rad.dataset.index = position;
    rad.addEventListener("mousedown", function () {
      index = this.dataset.index;
      afficherImageCarrousel();
    });

    position = position + 1;
    carrouselForm.append(rad);
  }

  /**
   * Fonctions pour faire avancer ou reculer les images avec les touches du clavier
   */

  function surveillerTouche(event) {
    //flèche gauche ou A
    if (event.keyCode == 37 || event.keyCode == 65) {
      reculerImage();
      //flèche droite ou D
    } else if (event.keyCode == 39 || event.keyCode == 68) {
      avancerImage();
    }
  }

  function reculerImage() {
    index--;
    if (index < 0) {
      index = position - 1;
    }
    afficherImageCarrousel();
    carrouselForm.children[index].checked = true;
  }

  function avancerImage() {
    index++;
    if (index > position - 1) {
      index = 0;
    }
    afficherImageCarrousel();
    carrouselForm.children[index].checked = true;
  }
})();
