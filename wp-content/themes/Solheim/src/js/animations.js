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
