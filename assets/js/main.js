document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
const suggestionsContainer = document.getElementById('search-suggestions');
let debounceTimer;

if (searchInput) {
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length > 1) {
            debounceTimer = setTimeout(() => {
                fetch(`api/events.php?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsContainer.innerHTML = '';
                        if(data.length > 0) {
                            data.forEach(event => {
                                const item = document.createElement('a');
                                item.href = `event_detail.php?id=${event.id}`;
                                item.className = 'suggestion-item';
                                item.textContent = event.name;
                                suggestionsContainer.appendChild(item);
                            });
                            suggestionsContainer.style.display = 'block';
                        } else {
                            suggestionsContainer.style.display = 'none';
                        }
                    });
            }, 300);
        } else {
            suggestionsContainer.style.display = 'none';
        }
    });
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });
}
    // Inisialisasi Slider dengan Tombol Navigasi
    const initSwiper = (selector) => {
        if(document.querySelector(selector)) {
            new Swiper(selector, {
                slidesPerView: 1, spaceBetween: 20,
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
            });
        }
    };
    initSwiper('.event-slider');
    initSwiper('.news-slider');

    // Animasi Reveal saat Scroll
    const revealElements = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => { entry.target.classList.add('visible'); }, delay);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealElements.forEach(el => observer.observe(el));

    // Animasi Parallax di Hero Section
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        window.addEventListener('scroll', () => {
            const offset = window.pageYOffset;
            heroContent.style.transform = `translateY(${offset * 0.3}px)`;
        });
    }
});