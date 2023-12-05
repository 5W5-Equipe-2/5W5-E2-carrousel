document.addEventListener('DOMContentLoaded', function () {
    const carrouselContent = document.querySelector('.carrousel-content');
    const carrouselItems = document.querySelectorAll('.carrousel-item');
    let currentItem = 0;

    function showItem(index) {
        // Masquez l'élément actuel avec un effet de fondu
        carrouselItems[currentItem].style.opacity = 0;
        carrouselItems[currentItem].style.zIndex = 1;

        // Affichez l'élément cible avec un effet de fondu
        carrouselItems[index].style.opacity = 1;
        carrouselItems[index].style.zIndex = 10;

        currentItem = index;
        updateCounter(); // Mettez à jour le compteur d'articles
    }

    function updateCounter() {
        const counter = document.querySelector('.article-counter');
        const totalItems = carrouselItems.length;
        const currentNumber = currentItem + 1;
        counter.textContent = `${currentNumber}/${totalItems}`;
    }

    // Fonction pour afficher l'élément précédent
    function showPreviousItem() {
        let index = (currentItem - 1 + carrouselItems.length) % carrouselItems.length;
        showItem(index);
    }

    // Fonction pour afficher l'élément suivant
    function showNextItem() {
        let index = (currentItem + 1) % carrouselItems.length;
        showItem(index);
    }

    // Ajoutez un gestionnaire de clic pour le bouton "Précédent"
    const prevButton = document.querySelector('.prev-button');
    prevButton.addEventListener('click', showPreviousItem);

    // Ajoutez un gestionnaire de clic pour le bouton "Suivant"
    const nextButton = document.querySelector('.next-button');
    nextButton.addEventListener('click', showNextItem);

    // Démarrez le carrousel en ajoutant la classe 'active' au premier élément
    carrouselItems[currentItem].style.opacity = 1;
    carrouselItems[currentItem].style.zIndex = 10;
    updateCounter(); // Mettez à jour le compteur d'articles initial
});
