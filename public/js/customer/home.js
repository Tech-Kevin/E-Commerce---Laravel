// ========================
// Wishlist Toggle
// ========================
document.querySelectorAll('.wishlist-toggle-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var id   = this.dataset.id;
        var icon = this.querySelector('i');

        fetch('/customer/wishlist/toggle/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            showToast(data.message, data.status);
            icon.className  = data.in_wishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
            icon.style.color = data.in_wishlist ? '#e05a2b' : '';
            updateBadge('wishlist-count', data.wishlist_count);
        })
        .catch(function () { showToast('Something went wrong.', false); });
    });
});

// ========================
// Scroll Reveal Animation
// ========================
(function () {
    var reveals = document.querySelectorAll('.reveal');

    function checkReveal() {
        var windowHeight = window.innerHeight;
        reveals.forEach(function (el) {
            var top = el.getBoundingClientRect().top;
            if (top < windowHeight - 80) {
                el.classList.add('visible');
            }
        });
    }

    window.addEventListener('scroll', checkReveal);
    checkReveal(); // trigger on load
})();

// ========================
// Animated Counters
// ========================
(function () {
    var counters = document.querySelectorAll('.stat-number');
    var animated = false;

    function animateCounters() {
        if (animated) return;
        var statsEl = document.querySelector('.hero-stats');
        if (!statsEl) return;

        var top = statsEl.getBoundingClientRect().top;
        if (top < window.innerHeight - 40) {
            animated = true;
            counters.forEach(function (counter) {
                var target = parseInt(counter.getAttribute('data-target'), 10) || 0;
                var duration = 1500;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    // ease out quad
                    var eased = 1 - (1 - progress) * (1 - progress);
                    counter.textContent = Math.floor(eased * target);
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        counter.textContent = target;
                    }
                }

                requestAnimationFrame(step);
            });
        }
    }

    window.addEventListener('scroll', animateCounters);
    animateCounters(); // trigger on load
})();

// ========================
// Horizontal Scroll Buttons
// ========================
document.querySelectorAll('.scroll-row').forEach(function (row) {
    var track = row.querySelector('.scroll-track');
    var leftBtn = row.querySelector('.scroll-left');
    var rightBtn = row.querySelector('.scroll-right');

    if (leftBtn && track) {
        leftBtn.addEventListener('click', function () {
            track.scrollBy({ left: -300, behavior: 'smooth' });
        });
    }

    if (rightBtn && track) {
        rightBtn.addEventListener('click', function () {
            track.scrollBy({ left: 300, behavior: 'smooth' });
        });
    }
});

// ========================
// Smooth scroll for anchor links
// ========================
document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
        var targetId = this.getAttribute('href');
        if (targetId === '#') return;
        var target = document.querySelector(targetId);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
