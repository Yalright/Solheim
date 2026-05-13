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
