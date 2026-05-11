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
