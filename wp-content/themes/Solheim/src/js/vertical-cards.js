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
