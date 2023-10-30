document.addEventListener('DOMContentLoaded', function () {
  const carouselContent = document.querySelector('.carousel-content');
  const carouselItems = document.querySelectorAll('.carousel-item');
  let currentItem = 0;

  function showItem(index) {
      // Masquez l'élément actuel avec un effet de fondu
      carouselItems[currentItem].style.opacity = 0;

      // Affichez l'élément cible avec un effet de fondu
      carouselItems[index].style.opacity = 1;

      currentItem = index;
      updateCounter(); // Mettez à jour le compteur d'articles
  }

  function updateCounter() {
      const counter = document.querySelector('.article-counter');
      const totalItems = carouselItems.length;
      const currentNumber = currentItem + 1;
      counter.textContent = `${currentNumber}/${totalItems}`;
  }

  // Fonction pour afficher l'élément précédent
  function showPreviousItem() {
      let index = (currentItem - 1 + carouselItems.length) % carouselItems.length;
      showItem(index);
  }

  // Fonction pour afficher l'élément suivant
  function showNextItem() {
      let index = (currentItem + 1) % carouselItems.length;
      showItem(index);
  }

  // Ajoutez un gestionnaire de clic pour le bouton "Précédent"
  const prevButton = document.querySelector('.prev-button');
  prevButton.addEventListener('click', showPreviousItem);

  // Ajoutez un gestionnaire de clic pour le bouton "Suivant"
  const nextButton = document.querySelector('.next-button');
  nextButton.addEventListener('click', showNextItem);

  // Démarrez le carrousel en ajoutant la classe 'active' au premier élément
  carouselItems[currentItem].style.opacity = 1;
  updateCounter(); // Mettez à jour le compteur d'articles initial
});
