(function () {
  function initCarousel(root) {
    var track   = root.querySelector('[data-track]');
    var slides  = Array.prototype.slice.call(track.children);
    var prevBtn = root.querySelector('[data-prev]');
    var nextBtn = root.querySelector('[data-next]');
    var capSel  = root.getAttribute('data-caption');
    var caption = capSel ? document.querySelector(capSel) : null;
    var index   = 0;

    function setWidths() { slides.forEach(function(el){ el.style.width = '100%'; }); }
    function update() {
      track.style.transform = 'translateX(' + (-index * 100) + '%)';
      if (caption) {
        var img = slides[index].querySelector('img');
        caption.textContent = img ? (img.getAttribute('data-caption') || '') : '';
      }
    }
    function prev(){ index = (index - 1 + slides.length) % slides.length; update(); }
    function next(){ index = (index + 1) % slides.length; update(); }

    prevBtn && prevBtn.addEventListener('click', prev);
    nextBtn && nextBtn.addEventListener('click', next);
    window.addEventListener('resize', setWidths);

    setWidths(); update();
  }

  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('[data-carousel]').forEach(initCarousel);
  });
})();
