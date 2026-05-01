window.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('.site-header__menu-toggle');
    var offcanvas = document.getElementById('site-header-offcanvas');

    if (!toggle || !offcanvas) {
        return;
    }

    toggle.addEventListener('click', function () {
        var isOpen = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        offcanvas.hidden = isOpen;
        document.body.classList.toggle('site-header-overlay-active', !isOpen);
    });
});
