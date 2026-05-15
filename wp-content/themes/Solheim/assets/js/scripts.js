/**
 * Accommodation — highlight the map pin that matches the location row
 * nearest the top of the viewport (scroll spy).
 */
window.addEventListener('DOMContentLoaded', function () {
  var sections = document.querySelectorAll('[data-accommodation-spy]');
  if (!sections.length) {
    return;
  }

  Array.prototype.forEach.call(sections, function (section) {
    var pins = section.querySelectorAll('.accommodation__hero-pin[data-accommodation-loc]');
    var lis = section.querySelectorAll('.accommodation__locations .accommodation__location[data-accommodation-loc]');
    var defaultSrc = section.getAttribute('data-accommodation-pin-default-src');
    var activeSrc = section.getAttribute('data-accommodation-pin-active-src');
    if (!pins.length || !lis.length || !defaultSrc || !activeSrc) {
      return;
    }

    var scheduled = false;

    function scrollOffset() {
      var raw = section.getAttribute('data-accommodation-scroll-offset');
      var n = parseInt(raw, 10);
      if (!isNaN(n) && n >= 0) {
        return n;
      }
      return Math.min(200, Math.max(96, Math.round(window.innerHeight * 0.18)));
    }

    function sync() {
      var offset = scrollOffset();
      var activeLoc = null;
      for (var i = 0; i < lis.length; i++) {
        var r = lis[i].getBoundingClientRect();
        if (r.top <= offset) {
          activeLoc = lis[i].getAttribute('data-accommodation-loc');
        }
      }
      if (activeLoc === null) {
        activeLoc = lis[0].getAttribute('data-accommodation-loc');
      }

      Array.prototype.forEach.call(pins, function (pin) {
        var loc = pin.getAttribute('data-accommodation-loc');
        var on = loc === activeLoc;
        pin.classList.toggle('is-active', on);
        var img = pin.querySelector('.accommodation__hero-pin-img');
        if (img) {
          img.setAttribute('src', on ? activeSrc : defaultSrc);
        }
      });
    }

    function onScrollOrResize() {
      if (scheduled) {
        return;
      }
      scheduled = true;
      window.requestAnimationFrame(function () {
        scheduled = false;
        sync();
      });
    }

    window.addEventListener('scroll', onScrollOrResize, { passive: true });
    window.addEventListener('resize', onScrollOrResize);
    onScrollOrResize();
  });
});

/**
 * Viewport entrance: subtle fade-in + slide-down on Gutenberg ACF blocks only.
 * Uses Intersection Observer (efficient vs scroll listeners; takeRecords for above-fold).
 * Exclusions must stay in sync with src/scss/global/_animations.scss
 * (header is excluded so site-header / offcanvas are never opacity-hidden).
 */
(function () {
  'use strict';

  var EXCLUDED_BLOCK =
    '.guten-block:not(.block-search-filters):not(.block-search-results):not(.results-wrapper)';
  var REVEAL_CLASS = 'fadeIn-down';

  function collectTargets() {
    return document.querySelectorAll(EXCLUDED_BLOCK);
  }

  function revealWithoutAnimation(els) {
    els.forEach(function (el) {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
  }

  function init() {
    if (document.body.classList.contains('wp-admin')) {
      return;
    }

    var els = collectTargets();
    if (!els.length) {
      return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    if (typeof window.IntersectionObserver === 'undefined') {
      revealWithoutAnimation(els);
      return;
    }

    var observer = new window.IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) {
            return;
          }
          var target = entry.target;
          observer.unobserve(target);
          target.classList.add(REVEAL_CLASS);
        });
      },
      {
        root: null,
        rootMargin: '40px 0px 0px 0px',
        threshold: 0,
      }
    );

    els.forEach(function (el) {
      observer.observe(el);
    });

    // Synchronously handle elements already in view (avoids waiting for the next frame).
    var pending = observer.takeRecords();
    if (pending.length) {
      pending.forEach(function (entry) {
        if (entry.isIntersecting) {
          observer.unobserve(entry.target);
          entry.target.classList.add(REVEAL_CLASS);
        }
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

(function () {
  function applyOrder(stack) {
    var cards = stack.querySelectorAll(".cards-overlayed__card");
    cards.forEach(function (card, index) {
      card.style.setProperty("--card-index", String(index));
      card.classList.toggle("is-active", index === 0);
    });
  }

  function initStack(stack) {
    if (!stack) {
      return;
    }
    applyOrder(stack);

    stack.addEventListener("click", function (e) {
      var clickedCard = e.target.closest(".cards-overlayed__card");
      if (!clickedCard || !stack.contains(clickedCard)) {
        return;
      }
      var cards = Array.prototype.slice.call(
        stack.querySelectorAll(".cards-overlayed__card")
      );
      if (!cards.length || cards[0] === clickedCard) {
        return;
      }
      stack.insertBefore(clickedCard, cards[0]);
      applyOrder(stack);
    });
  }

  window.addEventListener("DOMContentLoaded", function () {
    document
      .querySelectorAll(".cards-overlayed__stack")
      .forEach(function (stackEl) {
        initStack(stackEl);
      });
  });
})();

window.addEventListener('DOMContentLoaded', function () {
  if (typeof window.Splide === 'undefined') {
    return;
  }

  var sliders = document.querySelectorAll('[data-cta-ticker]');
  if (!sliders.length) {
    return;
  }

  var AutoScroll =
    window.splide &&
    window.splide.Extensions &&
    window.splide.Extensions.AutoScroll;

  var prefersReducedMotion =
    window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  sliders.forEach(function (sliderEl) {
    var useAutoScroll = AutoScroll && !prefersReducedMotion;

    var splide = new window.Splide(sliderEl, {
      type: 'loop',
      drag: prefersReducedMotion,
      arrows: false,
      pagination: false,
      autoWidth: true,
      gap: '3rem',
      focus: 'center',
      speed: 400,
      easing: 'linear',
      pauseOnHover: true,
      pauseOnFocus: true,
      autoScroll: useAutoScroll
        ? {
            speed: 0.65,
            pauseOnHover: true,
            pauseOnFocus: true,
            rewind: false,
          }
        : false,
    });

    splide.mount(useAutoScroll ? { AutoScroll: AutoScroll } : {});
  });
});

window.addEventListener('DOMContentLoaded', function () {
  var blocks = document.querySelectorAll('[data-faqs-style2]');
  if (!blocks.length) {
    return;
  }

  blocks.forEach(function (section) {
    var tabs = section.querySelectorAll('[data-faqs-s2-tab]');
    var panels = section.querySelectorAll('[data-faqs-s2-panel]');
    if (!tabs.length || !panels.length || tabs.length !== panels.length) {
      return;
    }

    function activate(index) {
      var i;
      for (i = 0; i < tabs.length; i++) {
        var on = i === index;
        tabs[i].classList.toggle('is-active', on);
        tabs[i].setAttribute('aria-selected', on ? 'true' : 'false');
        tabs[i].tabIndex = on ? 0 : -1;
        if (on) {
          panels[i].removeAttribute('hidden');
        } else {
          panels[i].setAttribute('hidden', 'hidden');
        }
      }
    }

    tabs.forEach(function (tab, index) {
      tab.addEventListener('click', function () {
        activate(index);
      });
    });

    var tablist = section.querySelector('.faqs__s2-tabs');
    if (tablist) {
      tablist.addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') {
          return;
        }
        var active = section.querySelector('.faqs__s2-tab.is-active');
        if (!active) {
          return;
        }
        var idx = parseInt(active.getAttribute('data-faqs-s2-index'), 10);
        if (isNaN(idx)) {
          return;
        }
        e.preventDefault();
        var next =
          e.key === 'ArrowRight'
            ? (idx + 1) % tabs.length
            : (idx - 1 + tabs.length) % tabs.length;
        activate(next);
        tabs[next].focus();
      });
    }
  });
});

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

window.addEventListener('DOMContentLoaded', function () {
  if (typeof window.Splide === 'undefined') {
    return;
  }

  var sliders = document.querySelectorAll('[data-hero-testimonial-slider]');
  if (!sliders.length) {
    return;
  }

  sliders.forEach(function (sliderEl) {
    var section = sliderEl.closest('.hero-testimonial');
    if (!section) {
      return;
    }

    var slides = sliderEl.querySelectorAll('.splide__slide');
    var count = slides.length;
    if (count < 1) {
      return;
    }

    var prevBtn = section.querySelector('.hero-testimonial__nav-btn--prev');
    var nextBtn = section.querySelector('.hero-testimonial__nav-btn--next');
    var multi = count > 1;

    // Fade carousel with rewind: wraps last → first and first → last (infinite).
    var splide = new window.Splide(sliderEl, {
      type: multi ? 'fade' : 'slide',
      rewind: multi,
      arrows: false,
      pagination: false,
      speed: multi ? 900 : 400,
      easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
      drag: multi,
    });

    if (prevBtn && multi) {
      prevBtn.addEventListener('click', function () {
        splide.go('<');
      });
    }
    if (nextBtn && multi) {
      nextBtn.addEventListener('click', function () {
        splide.go('>');
      });
    }

    splide.mount();
  });
});

window.addEventListener('DOMContentLoaded', function () {
  var frames = document.querySelectorAll('[data-hero-video-frame]');
  if (!frames.length) {
    return;
  }

  frames.forEach(function (frame) {
    var playBtn = frame.querySelector('[data-hero-video-play]');
    var video = frame.querySelector('.hero-video__video');
    if (!playBtn || !video) {
      return;
    }

    playBtn.addEventListener('click', function () {
      frame.classList.add('is-playing');
      video.removeAttribute('hidden');
      video.muted = false;
      var playPromise = video.play();
      if (playPromise !== undefined) {
        playPromise.catch(function () {
          video.setAttribute('controls', 'controls');
        });
      }
    });
  });
});

window.addEventListener('DOMContentLoaded', function () {
  if (typeof window.Splide === 'undefined') {
    return;
  }

  var sliders = document.querySelectorAll('[data-image-slider]');
  if (!sliders.length) {
    return;
  }

  sliders.forEach(function (sliderEl) {
    var splide = new window.Splide(sliderEl, {
      type: 'loop',
      arrows: false,
      pagination: false,
      drag: true,
      autoWidth: true,
      focus: 0,
      gap: '20px',
    });

    splide.mount();
  });
});


window.addEventListener('DOMContentLoaded', function () {
  if (typeof window.Splide === 'undefined') {
    return;
  }

  var sliders = document.querySelectorAll('[data-latest-news-slider]');
  if (!sliders.length) {
    return;
  }

  sliders.forEach(function (sliderEl) {
    var section = sliderEl.closest('.latest-news');
    if (!section) {
      return;
    }

    var prevBtn = section.querySelector('.latest-news__arrow--prev');
    var nextBtn = section.querySelector('.latest-news__arrow--next');

    var splide = new window.Splide(sliderEl, {
      type: 'loop',
      perPage: 3,
      perMove: 1,
      gap: '1rem',
      padding: { right: '14%' },
      arrows: false,
      pagination: false,
      drag: true,
      breakpoints: {
        1200: {
          perPage: 2,
          padding: { right: '12%' },
        },
        767: {
          perPage: 1,
          padding: { right: 0 },
        },
      },
    });

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        splide.go('<');
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        splide.go('>');
      });
    }

    splide.mount();
  });
});

window.addEventListener('DOMContentLoaded', function () {
  var roots = document.querySelectorAll('[data-links-cards]');
  if (!roots.length) {
    return;
  }

  roots.forEach(function (root) {
    var tabs = root.querySelectorAll('[data-links-cards-tab]');
    var panels = root.querySelectorAll('[data-links-cards-panel]');
    if (!tabs.length || !panels.length) {
      return;
    }

    function activate(index) {
      var i = parseInt(String(index), 10);
      if (isNaN(i)) {
        return;
      }

      tabs.forEach(function (tab, j) {
        var on = j === i;
        tab.classList.toggle('is-active', on);
        tab.setAttribute('aria-selected', on ? 'true' : 'false');
        tab.setAttribute('tabindex', on ? '0' : '-1');
      });

      panels.forEach(function (panel, j) {
        var on = j === i;
        panel.classList.toggle('is-active', on);
        if (on) {
          panel.removeAttribute('hidden');
        } else {
          panel.setAttribute('hidden', 'hidden');
        }
      });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var idx = tab.getAttribute('data-index');
        activate(idx);
      });
    });
  });
});

window.addEventListener('DOMContentLoaded', function () {
  if (typeof window.Splide === 'undefined') {
    return;
  }

  var sliders = document.querySelectorAll('[data-logo-bar-slider]');
  if (!sliders.length) {
    return;
  }

  sliders.forEach(function (sliderEl) {
    var splide = new window.Splide(sliderEl, {
      type: 'loop',
      arrows: false,
      pagination: false,
      drag: true,
      autoplay: true,
      interval: 2200,
      pauseOnHover: false,
      pauseOnFocus: false,
      speed: 900,
      perPage: 5,
      perMove: 1,
      gap: '0.6rem',
      breakpoints: {
        1200: {
          perPage: 4,
        },
        900: {
          perPage: 3,
        },
        600: {
          perPage: 2,
        },
      },
    });

    splide.mount();
  });
});

// Accordion logic removed.
// Wait until DOM is fully loaded before running accordion logic
window.addEventListener('DOMContentLoaded', function () {
  // Select all elements matching the selector and store in a reusable variable.
  const resultsGridFilterLabels = document.querySelectorAll(
    '.results-grid-content__filter-item .search-filter-label'
  );

  resultsGridFilterLabels.forEach(label => {
    label.addEventListener('click', function () {
      // Find the closest ancestor with the class .results-grid-content__filter-item
      const filterItem = label.closest('.results-grid-content__filter-item');
      if (filterItem) {
        if (filterItem.classList.contains('active')) {
          filterItem.classList.remove('active');
        } else {
          filterItem.classList.add('active');
        }
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    var tabBlocks = document.querySelectorAll('.js-single-venue-tabs');

    tabBlocks.forEach(function (tabBlock) {
        var buttons = tabBlock.querySelectorAll('.single-venue__tab');
        var panels = tabBlock.querySelectorAll('.single-venue__tab-panel');

        function activateTab(target) {
            buttons.forEach(function (button) {
                var isActive = button.getAttribute('data-tab-target') === target;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                var isActive = panel.getAttribute('data-tab-panel') === target;
                panel.classList.toggle('is-active', isActive);
                if (isActive) {
                    panel.removeAttribute('hidden');
                } else {
                    panel.setAttribute('hidden', 'hidden');
                }
            });
        }

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var target = button.getAttribute('data-tab-target');
                if (target) {
                    activateTab(target);
                }
            });
        });

        var defaultButton = tabBlock.querySelector('.single-venue__tab.is-active') || buttons[0];
        if (defaultButton) {
            var defaultTarget = defaultButton.getAttribute('data-tab-target');
            if (defaultTarget) {
                activateTab(defaultTarget);
            }
        }
    });
});

(function () {
  function setPrimaryBg(el, url) {
    if (!el) {
      return;
    }
    el.style.backgroundImage = url ? "url(" + JSON.stringify(String(url)) + ")" : "";
  }

  function fillThumb(btn, slide) {
    if (!btn || !slide) {
      return;
    }
    var img = btn.querySelector("img");
    if (!img) {
      return;
    }
    if (slide.secondary) {
      img.src = slide.secondary;
    }
    img.alt = slide.alt ? String(slide.alt) : "";
  }

  function initCaptains(root) {
    var raw = root.getAttribute("data-team-promo-slides");
    if (!raw) {
      return;
    }

    var slides;
    try {
      slides = JSON.parse(raw);
    } catch (err) {
      return;
    }

    if (!slides || !slides.length) {
      return;
    }

    var primary = root.querySelector("[data-team-promo-primary]");
    var nameEl = root.querySelector("[data-team-promo-name]");
    var prevBtn = root.querySelector('[data-team-promo-thumb="prev"]');
    var activeBtn = root.querySelector('[data-team-promo-thumb="active"]');
    var nextBtn = root.querySelector('[data-team-promo-thumb="next"]');

    if (!primary || !prevBtn || !activeBtn || !nextBtn) {
      return;
    }

    var count = slides.length;
    var current = 0;

    function indices() {
      if (count <= 1) {
        return { prev: 0, active: 0, next: 0 };
      }
      return {
        prev: (current - 1 + count) % count,
        active: current,
        next: (current + 1) % count,
      };
    }

    function render() {
      var idx = indices();
      fillThumb(prevBtn, slides[idx.prev]);
      fillThumb(activeBtn, slides[idx.active]);
      fillThumb(nextBtn, slides[idx.next]);
      setPrimaryBg(primary, slides[idx.active].primary || "");
      if (nameEl) {
        nameEl.textContent = slides[idx.active].name ? String(slides[idx.active].name) : "";
      }

      prevBtn.setAttribute("aria-selected", "false");
      activeBtn.setAttribute("aria-selected", "true");
      nextBtn.setAttribute("aria-selected", "false");
    }

    function goTo(index) {
      if (count <= 1) {
        return;
      }
      if (index < 0) {
        index = count - 1;
      }
      if (index >= count) {
        index = 0;
      }
      current = index;
      render();
    }

    function goNext() {
      goTo(current + 1);
    }

    function goPrev() {
      goTo(current - 1);
    }

    prevBtn.addEventListener("click", function () {
      goPrev();
    });
    nextBtn.addEventListener("click", function () {
      goNext();
    });

    render();

    var tStartX = 0;
    var tStartY = 0;
    primary.addEventListener(
      "touchstart",
      function (e) {
        if (!e.touches || !e.touches[0]) {
          return;
        }
        tStartX = e.touches[0].clientX;
        tStartY = e.touches[0].clientY;
      },
      { passive: true }
    );
    primary.addEventListener(
      "touchend",
      function (e) {
        if (!e.changedTouches || !e.changedTouches[0]) {
          return;
        }
        var dx = e.changedTouches[0].clientX - tStartX;
        var dy = e.changedTouches[0].clientY - tStartY;
        if (Math.abs(dx) < 48 || Math.abs(dx) < Math.abs(dy)) {
          return;
        }
        if (dx < 0) {
          goNext();
        } else {
          goPrev();
        }
      },
      { passive: true }
    );

    var pDown = false;
    var pStartX = 0;
    primary.addEventListener("pointerdown", function (e) {
      if (e.pointerType === "touch") {
        return;
      }
      pDown = true;
      pStartX = e.clientX;
      primary.setPointerCapture(e.pointerId);
    });
    primary.addEventListener("pointerup", function (e) {
      if (!pDown || e.pointerType === "touch") {
        return;
      }
      pDown = false;
      var dx = e.clientX - pStartX;
      if (Math.abs(dx) < 48) {
        return;
      }
      if (dx < 0) {
        goNext();
      } else {
        goPrev();
      }
    });
    primary.addEventListener("pointercancel", function () {
      pDown = false;
    });

    primary.setAttribute("tabindex", "0");
    primary.setAttribute("role", "group");
    primary.setAttribute(
      "aria-label",
      "Captain photos, swipe or use arrow keys"
    );

    primary.addEventListener("keydown", function (e) {
      if (e.key === "ArrowRight") {
        e.preventDefault();
        goNext();
      } else if (e.key === "ArrowLeft") {
        e.preventDefault();
        goPrev();
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    document
      .querySelectorAll("[data-team-promo-captains]")
      .forEach(initCaptains);
  });
})();

/**
 * Vertical Cards — deck carousel (no Splide).
 */
window.addEventListener('DOMContentLoaded', function () {
  var roots = document.querySelectorAll('[data-vertical-cards-slider]');
  if (!roots.length) {
    return;
  }

  var STACK_PEEK_PX = 30;
  var PREVIEW_COUNT = 2;

  roots.forEach(function (sliderRoot) {
    var section = sliderRoot.closest('[data-vertical-cards]');
    if (!section) {
      return;
    }

    var list = sliderRoot.querySelector('.vertical-cards__list');
    var slides = list ? list.querySelectorAll('.vertical-cards__slide') : null;
    if (!list || !slides || !slides.length) {
      return;
    }

    var pageBtns = section.querySelectorAll('[data-vcards-go]');
    var btnPrev = section.querySelector('[data-vcards-prev]');
    var btnNext = section.querySelector('[data-vcards-next]');
    var track = sliderRoot.querySelector('.vertical-cards__track');

    var total = slides.length;
    var index = 0;

    function norm(i) {
      return ((i % total) + total) % total;
    }

    function goTo(i) {
      index = norm(i);
      update();
    }

    function goNext() {
      goTo(index + 1);
    }

    function goPrev() {
      goTo(index - 1);
    }

    function syncTabs() {
      Array.prototype.forEach.call(pageBtns, function (btn, j) {
        var on = j === index;
        btn.classList.toggle('is-active', on);
        btn.setAttribute('aria-selected', on ? 'true' : 'false');
      });
    }

    function syncAriaHidden() {
      Array.prototype.forEach.call(slides, function (slide, idx) {
        slide.setAttribute('aria-hidden', idx === index ? 'false' : 'true');
      });
    }

    function syncStack() {
      Array.prototype.forEach.call(slides, function (slide) {
        slide.classList.remove(
          'vertical-cards__slide--past',
          'vertical-cards__slide--future',
          'vertical-cards__slide--far-future',
          'is-active'
        );
        slide.style.setProperty('--vcards-stack', '0');
        slide.style.setProperty('--vcards-deck-y', '0');
        var cardReset = slide.querySelector('.vertical-cards__card');
        if (cardReset) {
          cardReset.style.minHeight = '';
        }
      });

      var maxCardH = 0;
      Array.prototype.forEach.call(slides, function (slide) {
        var c = slide.querySelector('.vertical-cards__card');
        if (c) {
          maxCardH = Math.max(maxCardH, c.scrollHeight);
        }
      });

      if (maxCardH > 0) {
        Array.prototype.forEach.call(slides, function (slide) {
          var c = slide.querySelector('.vertical-cards__card');
          if (c) {
            c.style.minHeight = maxCardH + 'px';
          }
        });
      }

      var i = index;
      var activeSlide = slides[i];
      if (activeSlide) {
        activeSlide.classList.add('is-active');
      }

      Array.prototype.forEach.call(slides, function (slide, idx) {
        if (idx < i) {
          slide.classList.add('vertical-cards__slide--past');
        } else if (idx > i + PREVIEW_COUNT) {
          slide.classList.add('vertical-cards__slide--far-future');
        } else if (idx > i) {
          slide.classList.add('vertical-cards__slide--future');
          var depth = idx - i;
          slide.style.setProperty('--vcards-stack', String(depth));
          slide.style.setProperty(
            '--vcards-deck-y',
            String(depth * STACK_PEEK_PX)
          );
        }
      });

      var activeCard = activeSlide
        ? activeSlide.querySelector('.vertical-cards__card')
        : null;
      var activeH = activeCard
        ? activeCard.offsetHeight
        : activeSlide
          ? activeSlide.offsetHeight
          : 0;

      var maxBottom = activeH;
      Array.prototype.forEach.call(slides, function (slide, idx) {
        if (idx <= i || idx > i + PREVIEW_COUNT) {
          return;
        }
        var cardEl = slide.querySelector('.vertical-cards__card');
        var h = cardEl ? cardEl.offsetHeight : slide.offsetHeight;
        var depth = idx - i;
        var y = depth * STACK_PEEK_PX;
        maxBottom = Math.max(maxBottom, y + h);
      });
      list.style.minHeight = Math.max(maxBottom, 0) + 'px';
    }

    function update() {
      syncTabs();
      syncAriaHidden();
      window.requestAnimationFrame(syncStack);
    }

    if (btnPrev) {
      btnPrev.addEventListener('click', goPrev);
    }
    if (btnNext) {
      btnNext.addEventListener('click', goNext);
    }

    Array.prototype.forEach.call(pageBtns, function (btn) {
      btn.addEventListener('click', function () {
        var idx = parseInt(btn.getAttribute('data-vcards-go'), 10);
        if (!isNaN(idx)) {
          goTo(idx);
        }
      });
    });

    if (track) {
      var swipeThreshold = 48;
      var dragStartY = null;
      var dragStartX = null;
      var swipePointerId = null;

      track.addEventListener('pointerdown', function (e) {
        if (e.pointerType === 'mouse' && e.button !== 0) {
          return;
        }
        if (e.target.closest('a, button, input, textarea, select, label')) {
          return;
        }
        swipePointerId = e.pointerId;
        dragStartY = e.clientY;
        dragStartX = e.clientX;
        try {
          track.setPointerCapture(e.pointerId);
        } catch (err) {}
      });

      function endSwipe(e) {
        if (swipePointerId !== null && e.pointerId !== swipePointerId) {
          return;
        }
        try {
          track.releasePointerCapture(e.pointerId);
        } catch (err) {}
        if (dragStartY === null) {
          swipePointerId = null;
          return;
        }
        var dy = e.clientY - dragStartY;
        var dx = e.clientX - dragStartX;
        dragStartY = null;
        dragStartX = null;
        swipePointerId = null;
        if (Math.abs(dy) < swipeThreshold || Math.abs(dy) <= Math.abs(dx)) {
          return;
        }
        if (dy < 0) {
          goNext();
        } else {
          goPrev();
        }
      }

      track.addEventListener('pointerup', endSwipe);
      track.addEventListener('pointercancel', function (e) {
        try {
          track.releasePointerCapture(e.pointerId);
        } catch (err) {}
        swipePointerId = null;
        dragStartY = null;
        dragStartX = null;
      });
    }

    section.querySelectorAll('.vertical-cards__card-img').forEach(function (img) {
      img.addEventListener('load', function () {
        window.requestAnimationFrame(syncStack);
      });
    });

    update();

    var resizeTimer;
    window.addEventListener('resize', function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(function () {
        window.requestAnimationFrame(syncStack);
      }, 150);
    });
  });
});
