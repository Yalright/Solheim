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

