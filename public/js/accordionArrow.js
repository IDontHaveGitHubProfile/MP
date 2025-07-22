document.addEventListener('DOMContentLoaded', () => {
    const subcategoryAccordions = document.querySelectorAll('.subcategory-accordion');

    subcategoryAccordions.forEach(accordion => {
        const content = accordion.querySelector('.accordion-content');

        // Установка активного состояния (изначально открыты)
        accordion.classList.add('active');
        content.style.maxHeight = content.scrollHeight + 'px';
    });

    subcategoryAccordions.forEach(accordion => {
        const header = accordion.querySelector('.accordion-header');
        const content = accordion.querySelector('.accordion-content');

        header.addEventListener('click', (e) => {
            e.preventDefault();

            const isOpen = accordion.classList.contains('active');

            if (isOpen) {
                accordion.classList.remove('active');
                content.style.maxHeight = null;
            } else {
                accordion.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });

        header.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                header.click();
            }
        });
    });

    // Универсальные аккордеоны (если используются вне подкатегорий)
    const mainAccordions = document.querySelectorAll('.accordion:not(.subcategory-accordion)');
    mainAccordions.forEach((accordion, index) => {
        const header = accordion.querySelector('.accordion-header');
        const content = accordion.querySelector('.accordion-content');

        header.addEventListener('click', () => {
            const isOpen = accordion.classList.contains('active');

            if (isOpen) {
                accordion.classList.remove('active');
                content.style.maxHeight = null;
            } else {
                accordion.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });

        header.addEventListener('keydown', (e) => {
            if (e.key === 'Tab' && !accordion.classList.contains('active')) {
                e.preventDefault();
                const nextAccordion = mainAccordions[index + 1] || mainAccordions[0];
                nextAccordion.querySelector('.accordion-header').focus();
            }
        });
    });
});
