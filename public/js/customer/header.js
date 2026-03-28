document.addEventListener('click', function (e) {
    document.querySelectorAll('.user-dropdown.open').forEach(function (d) {
        if (!d.parentElement.contains(e.target)) {
            d.classList.remove('open');
            d.style.display = 'none';
        }
    });
});

document.querySelectorAll('.user-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var d = this.querySelector('.user-dropdown');
        if (d) {
            d.style.display = d.style.display === 'none' ? 'block' : 'none';
        }
    });
});
