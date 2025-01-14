document.addEventListener('DOMContentLoaded', () => {
    const seeAllBtn = document.getElementById('see-all-btn');
    const carouselOverlay = document.getElementById('carousel-overlay');
    const closeCarouselBtn = document.getElementById('close-carousel');
    const carousel = document.getElementById('carousel');

    if (carousel) {
        const slides = Array.from(carousel.getElementsByClassName('carousel-slide'));
        let currentIndex = 0;

        // Afficher le carrousel
        if (seeAllBtn) {
            seeAllBtn.addEventListener('click', () => {
                carouselOverlay.classList.add('visible');
                showSlide(currentIndex);
            });
        }

        // Fermer le carrousel
        if (closeCarouselBtn) {
            closeCarouselBtn.addEventListener('click', () => {
                carouselOverlay.classList.remove('visible');
            });
        }

        // Afficher une diapositive spécifique
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
        }

        // Changer de diapositive
        function changeSlide(direction) {
            currentIndex = (currentIndex + direction + slides.length) % slides.length;
            showSlide(currentIndex);
        }

        // Contrôles du carrousel
        const prevBtn = document.querySelector('.carousel-control.prev');
        const nextBtn = document.querySelector('.carousel-control.next');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => changeSlide(-1));
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => changeSlide(1));
        }
    } else {
        console.log('Le carrousel n\'est pas présent sur cette page.');
    }
});
