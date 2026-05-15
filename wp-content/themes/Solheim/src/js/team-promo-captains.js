(function () {
  function getMainContentEl() {
    return (
      document.querySelector('main#content') ||
      document.querySelector('main.site-main') ||
      document.getElementById('content')
    );
  }

  function isTeamPromoBlock(el) {
    return (
      el &&
      el.nodeType === 1 &&
      el.classList.contains('guten-block') &&
      el.classList.contains('team-promo')
    );
  }

  function smoothStep(t) {
    return t * t * (3 - 2 * t);
  }

  /**
   * Two consecutive .team-promo blocks: wrap in a 200vh runway, sticky viewport,
   * stacked full-viewport panels, scroll-driven crossfade (first out / second in).
   */
  function wrapConsecutiveTeamPromoPairs() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    var main = getMainContentEl();
    if (!main) {
      return;
    }

    var i = 0;
    while (i < main.children.length - 1) {
      var a = main.children[i];
      var b = main.children[i + 1];
      if (isTeamPromoBlock(a) && isTeamPromoBlock(b)) {
        var wrapper = document.createElement('div');
        wrapper.className = 'team-promo-pair';
        wrapper.setAttribute('data-team-promo-pair', '');

        var viewport = document.createElement('div');
        viewport.className = 'team-promo-pair__viewport';

        main.insertBefore(wrapper, a);
        wrapper.appendChild(viewport);
        viewport.appendChild(a);
        viewport.appendChild(b);

        initTeamPromoPairCrossfade(wrapper);
        i += 1;
        continue;
      }
      i += 1;
    }
  }

  function initTeamPromoPairCrossfade(wrapper) {
    var viewport = wrapper.querySelector('.team-promo-pair__viewport');
    if (!viewport) {
      return;
    }

    var sections = viewport.querySelectorAll(':scope > .team-promo');
    if (sections.length !== 2) {
      return;
    }

    var first = sections[0];
    var second = sections[1];

    first.classList.add('team-promo--pair-panel');
    second.classList.add('team-promo--pair-panel');

    var ticking = false;

    function update() {
      var rect = wrapper.getBoundingClientRect();
      var vh = window.innerHeight || document.documentElement.clientHeight || 0;
      var runway = rect.height - vh;
      var t = 0;
      if (runway > 0) {
        t = -rect.top / runway;
        if (t < 0) {
          t = 0;
        }
        if (t > 1) {
          t = 1;
        }
      }
      var s = smoothStep(t);
      first.style.opacity = String(1 - s);
      second.style.opacity = String(s);
      first.style.pointerEvents = s < 0.5 ? 'auto' : 'none';
      second.style.pointerEvents = s >= 0.5 ? 'auto' : 'none';
      first.style.zIndex = s < 0.5 ? '2' : '1';
      second.style.zIndex = s >= 0.5 ? '2' : '1';
    }

    function onScrollOrResize() {
      if (ticking) {
        return;
      }
      ticking = true;
      window.requestAnimationFrame(function () {
        ticking = false;
        update();
      });
    }

    window.addEventListener('scroll', onScrollOrResize, { passive: true });
    window.addEventListener('resize', onScrollOrResize);
    onScrollOrResize();
  }

  function setPrimaryBg(el, url) {
    if (!el) {
      return;
    }
    el.style.backgroundImage = url ? 'url(' + JSON.stringify(String(url)) + ')' : '';
  }

  function fillThumb(btn, slide) {
    if (!btn || !slide) {
      return;
    }
    var img = btn.querySelector('img');
    if (!img) {
      return;
    }
    if (slide.secondary) {
      img.src = slide.secondary;
    }
    img.alt = slide.alt ? String(slide.alt) : '';
  }

  function initCaptains(root) {
    var raw = root.getAttribute('data-team-promo-slides');
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

    var primary = root.querySelector('[data-team-promo-primary]');
    var nameEl = root.querySelector('[data-team-promo-name]');
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
      setPrimaryBg(primary, slides[idx.active].primary || '');
      if (nameEl) {
        nameEl.textContent = slides[idx.active].name ? String(slides[idx.active].name) : '';
      }

      prevBtn.setAttribute('aria-selected', 'false');
      activeBtn.setAttribute('aria-selected', 'true');
      nextBtn.setAttribute('aria-selected', 'false');
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

    prevBtn.addEventListener('click', function () {
      goPrev();
    });
    nextBtn.addEventListener('click', function () {
      goNext();
    });

    render();

    var tStartX = 0;
    var tStartY = 0;
    primary.addEventListener(
      'touchstart',
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
      'touchend',
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
    primary.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'touch') {
        return;
      }
      pDown = true;
      pStartX = e.clientX;
      primary.setPointerCapture(e.pointerId);
    });
    primary.addEventListener('pointerup', function (e) {
      if (!pDown || e.pointerType === 'touch') {
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
    primary.addEventListener('pointercancel', function () {
      pDown = false;
    });

    primary.setAttribute('tabindex', '0');
    primary.setAttribute('role', 'group');
    primary.setAttribute(
      'aria-label',
      'Captain photos, swipe or use arrow keys'
    );

    primary.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        goNext();
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        goPrev();
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    wrapConsecutiveTeamPromoPairs();
    document.querySelectorAll('[data-team-promo-captains]').forEach(initCaptains);
  });
})();
