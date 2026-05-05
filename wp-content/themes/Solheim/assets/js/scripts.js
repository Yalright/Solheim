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
      arrows: false,
      pagination: false,
      drag: true,
      breakpoints: {
        1200: {
          perPage: 2,
        },
        767: {
          perPage: 1,
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
