// document.addEventListener("DOMContentLoaded", () => {
//     const sidebarAccordions = document.querySelectorAll(".accordion-sidebar.accordion");

//     sidebarAccordions.forEach((accordion) => {
//         const header = accordion.querySelector(".accordion-header");
//         const content = accordion.querySelector(".accordion-content");
//         const arrow = accordion.querySelector(".accordion-arrow");

//         header.addEventListener("click", function () {
//             const isOpen = this.classList.contains("active");

//             this.classList.toggle("active");

//             if (isOpen) {
//                 content.style.maxHeight = null;
//                 arrow.style.transform = "rotate(0deg)";
//             } else {
//                 content.style.maxHeight = content.scrollHeight + "px";
//                 arrow.style.transform = "rotate(180deg)";
//             }
//         });
//     });
// });
