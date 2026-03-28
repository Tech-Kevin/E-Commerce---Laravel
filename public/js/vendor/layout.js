setTimeout(function () {
    const flash = document.getElementById('flash-message');
    if (flash) {
        flash.style.animation = 'fadeOut 0.5s ease forwards';
        setTimeout(function () { flash.remove(); }, 500);
    }
}, 3000);
