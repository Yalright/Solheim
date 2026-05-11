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
