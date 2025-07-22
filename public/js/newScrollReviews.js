
document.addEventListener("DOMContentLoaded", function () {
    const hashFromStorage = sessionStorage.getItem('scrollToReview');
    if (hashFromStorage) {
        const target = document.querySelector(hashFromStorage);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 400);
        }
        sessionStorage.removeItem('scrollToReview');
    }
});
