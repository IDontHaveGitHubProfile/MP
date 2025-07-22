document.addEventListener('DOMContentLoaded', function () {
  const randomNum = Math.floor(Math.random() * 5) + 1;
  const desktopIcon = document.getElementById('randomContactIcon');
  const mobileIcon = document.getElementById('mobileRandomContactIcon');

  if (desktopIcon) {
    desktopIcon.src = `../public/assets/contact-${randomNum}.svg`;
  }

  if (mobileIcon) {
    mobileIcon.src = `../public/assets/contact-${randomNum}.svg`;
  }
});
