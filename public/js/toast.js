function showToast(message, success) {
    const t = document.getElementById('toast');
    t.textContent = message;
    t.style.background = success ? '#2f241f' : '#c0392b';
    t.style.display = 'block';
    t.style.opacity = '1';
    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => t.style.display = 'none', 300);
    }, 2500);
}
