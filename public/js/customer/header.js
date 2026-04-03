// Badge counter helper (used across pages)
function updateBadge(id, count) {
    var badge = document.getElementById(id);
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = '';
    } else {
        badge.style.display = 'none';
    }
}

// User dropdown
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
