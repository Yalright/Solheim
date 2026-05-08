window.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('.site-header__menu-toggle');
    var offcanvas = document.getElementById('site-header-offcanvas');

    if (!toggle || !offcanvas) {
        return;
    }

    var subCol = offcanvas.querySelector('[data-header-subcol]');

    function clearSubCol() {
        if (subCol) {
            subCol.innerHTML = '';
        }
    }

    function setExpanded(isOpen) {
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        offcanvas.hidden = !isOpen;
        document.body.classList.toggle('site-header-overlay-active', isOpen);
        if (!isOpen) {
            clearSubCol();
            currentTopLi = null;
        }
    }

    toggle.addEventListener('click', function () {
        var isOpen = toggle.getAttribute('aria-expanded') === 'true';
        setExpanded(!isOpen);
    });

    // Close if clicking on the dark backdrop
    offcanvas.addEventListener('click', function (e) {
        if (e.target === offcanvas) {
            setExpanded(false);
        }
    });

    // ESC closes
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setExpanded(false);
        }
    });

    function isDesktop() {
        return window.matchMedia && window.matchMedia('(min-width: 1024px)').matches;
    }

    // Two-column submenu behavior (desktop), accordion (mobile)
    var primaryMenu = offcanvas.querySelector('.site-header__nav');
    if (!primaryMenu || !subCol) {
        return;
    }

    function showSubmenuForLi(li) {
        if (!li) {
            return;
        }
        // Find the direct child `.sub-menu` without relying on `:scope` support.
        var submenu = null;
        for (var i = 0; i < li.children.length; i++) {
            var child = li.children[i];
            if (child && child.classList && child.classList.contains('sub-menu')) {
                submenu = child;
                break;
            }
        }
        if (!submenu) {
            clearSubCol();
            return;
        }

        // Clone to avoid moving the original menu DOM
        var clone = submenu.cloneNode(true);
        clone.classList.add('site-header__subnav');
        subCol.innerHTML = '';
        subCol.appendChild(clone);
    }

    function topLevelLiFromTarget(target) {
        if (!target || !target.closest) return null;
        var link = target.closest('a');
        if (!link) return null;
        var li = target.closest('li');
        if (!li || !primaryMenu.contains(li)) {
            return null;
        }
        // Only react to top-level items in the primary column
        if (li.parentElement !== primaryMenu) {
            return null;
        }
        // Only react when hovering/focusing the top-level link itself
        if (link.parentElement !== li) {
            return null;
        }
        return li;
    }

    var currentTopLi = null;

    primaryMenu.addEventListener('mouseover', function (e) {
        // Hover intent: only on devices that actually hover
        if (window.matchMedia && !window.matchMedia('(hover: hover)').matches) {
            return;
        }
        if (!isDesktop()) {
            return;
        }
        var topLi = topLevelLiFromTarget(e.target);
        if (!topLi) return;
        if (currentTopLi === topLi) return;
        currentTopLi = topLi;

        if (topLi.classList.contains('menu-item-has-children')) {
            showSubmenuForLi(topLi);
            return;
        }

        clearSubCol();
    });

    primaryMenu.addEventListener('focusin', function (e) {
        if (!isDesktop()) {
            return;
        }
        var topLi = topLevelLiFromTarget(e.target);
        if (!topLi) return;
        if (currentTopLi === topLi) return;
        currentTopLi = topLi;

        if (topLi.classList.contains('menu-item-has-children')) {
            showSubmenuForLi(topLi);
            return;
        }

        clearSubCol();
    });

    // Tap/click on parent link:
    // - desktop: show submenu in second column
    // - mobile: toggle accordion under the parent
    primaryMenu.addEventListener('click', function (e) {
        var link = e.target && e.target.closest ? e.target.closest('a') : null;
        if (!link) return;
        // Only handle top-level items in the primary column
        var li = link.parentElement && link.parentElement.tagName === 'LI' ? link.parentElement : null;
        if (!li || li.parentElement !== primaryMenu) return;

        if (isDesktop()) {
            currentTopLi = li;
            if (li.classList.contains('menu-item-has-children')) {
                e.preventDefault();
                showSubmenuForLi(li);
            } else {
                clearSubCol();
            }
            return;
        }

        // Mobile accordion
        if (!li.classList.contains('menu-item-has-children')) {
            return;
        }

        e.preventDefault();
        var isOpen = li.classList.contains('is-open');
        // close siblings
        Array.prototype.forEach.call(primaryMenu.children, function (child) {
            if (child !== li) {
                child.classList.remove('is-open');
                var a = child.querySelector(':scope > a');
                if (a) a.setAttribute('aria-expanded', 'false');
            }
        });
        li.classList.toggle('is-open', !isOpen);
        link.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
    });

    function syncMode() {
        if (isDesktop()) {
            // clear mobile state
            Array.prototype.forEach.call(primaryMenu.children, function (child) {
                child.classList.remove('is-open');
                var a = child.querySelector(':scope > a');
                if (a) a.setAttribute('aria-expanded', 'false');
            });
        } else {
            // clear desktop subcolumn content
            clearSubCol();
            currentTopLi = null;
        }
    }

    syncMode();
    window.addEventListener('resize', syncMode, { passive: true });
});
