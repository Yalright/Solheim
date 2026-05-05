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
