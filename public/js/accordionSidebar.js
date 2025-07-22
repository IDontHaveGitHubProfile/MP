document.addEventListener('DOMContentLoaded', () => {
    const accordions = document.querySelectorAll('.sidebar-accordion');

    accordions.forEach(accordion => {
        const arrowBtn = accordion.querySelector('.sidebar-accordion-arrow-btn');
        const content = accordion.querySelector('.sidebar-accordion-content');

        arrowBtn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();

            const isActive = accordion.classList.contains('active');

            if (isActive) {
                accordion.classList.remove('active');
                content.style.maxHeight = null;
            } else {
                accordion.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });

        // Закрываем другие аккордеоны при открытии нового
        accordion.querySelector('.sidebar-accordion-header').addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'IMG') return;
            
            const allAccordions = document.querySelectorAll('.sidebar-accordion');
            allAccordions.forEach(item => {
                if (item !== accordion && item.classList.contains('active')) {
                    item.classList.remove('active');
                    item.querySelector('.sidebar-accordion-content').style.maxHeight = null;
                }
            });
        });
    });

    // Обработка кликов по категориям для мобильной версии
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-id');
            const subCategories = document.querySelectorAll('.subcategories-container');
            
            subCategories.forEach(container => {
                if (container.getAttribute('data-parent') === categoryId) {
                    container.classList.toggle('visible');
                } else {
                    container.classList.remove('visible');
                }
            });
        });
    });
});