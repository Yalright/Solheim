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
