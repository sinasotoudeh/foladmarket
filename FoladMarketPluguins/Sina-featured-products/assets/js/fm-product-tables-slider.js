(function () {
    function initInstance(container) {
        var rotation = parseInt(container.getAttribute('data-rotation'), 10) || 3000;
        var slides = Array.prototype.slice.call(container.querySelectorAll('.fm-slide'));
        var dots = Array.prototype.slice.call(container.querySelectorAll('.fm-dot'));
        var prevBtn = container.querySelector('.fm-nav-prev');
        var nextBtn = container.querySelector('.fm-nav-next');
        if (!slides.length) return;
        var current = 0;
        var interval = null;

        function show(i) {
            if (i < 0) i = slides.length - 1;
            if (i >= slides.length) i = 0;
            current = i;
            slides.forEach(function (s, idx) { s.classList.toggle('fm-active', idx === i); });
            dots.forEach(function (d, idx) { d.classList.toggle('fm-active', idx === i); });
        }

        function start() {
            stop();
            interval = setInterval(function () { show(current + 1); }, rotation);
        }

        function stop() {
            if (interval) { clearInterval(interval); interval = null; }
        }

        if (prevBtn) prevBtn.addEventListener('click', function () { stop(); show(current - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function () { stop(); show(current + 1); });
        dots.forEach(function (dot, idx) {
            dot.addEventListener('click', function () { stop(); show(idx); });
        });

        container.addEventListener('mouseenter', stop);
        container.addEventListener('mouseleave', start);

        show(0);
        start();
    }

    function initAll() {
        document.querySelectorAll('.fm-featured-products-slider').forEach(function (c) { initInstance(c); });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll);
    else initAll();
})();
