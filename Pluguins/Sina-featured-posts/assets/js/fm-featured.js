(function(){
  function initInstance(config){
    var id = config.id;
    var container = document.getElementById(id);
    if(!container) return;
    var posts = config.posts || [];
    var rotation = parseInt(config.rotation, 10) || 3000;

    var featuredImg = container.querySelector('.fm-featured-image');
    var featuredTitle = container.querySelector('.fm-featured-title');
    var featuredExcerpt = container.querySelector('.fm-featured-excerpt');
    var featuredLink = container.querySelector('.fm-featured-link');
    var listItems = Array.prototype.slice.call(container.querySelectorAll('.fm-post-item'));
    var current = 0;
    var interval = null;

    function show(index){
      if(index < 0 || index >= posts.length) return;
      current = index;
      var p = posts[index];
      if(featuredImg) featuredImg.src = p.image;
      if(featuredTitle) featuredTitle.textContent = p.title;
      if(featuredExcerpt) featuredExcerpt.textContent = p.excerpt;
      if(featuredLink) featuredLink.href = p.link;
      listItems.forEach(function(li, i){
        li.classList.toggle('fm-active', i === index);
      });
    }

    listItems.forEach(function(li, i){
      var btn = li.querySelector('.fm-post-button');
      if(btn){
        btn.addEventListener('click', function(){
          stop();
          show(i);
        });
      }
    });

    function start(){
      stop();
      interval = setInterval(function(){
        var next = (current + 1) % posts.length;
        show(next);
      }, rotation);
    }
    function stop(){
      if(interval){ clearInterval(interval); interval = null; }
    }

    // pause on hover (UX)
    container.addEventListener('mouseenter', stop);
    container.addEventListener('mouseleave', start);

    // init
    start();
  }

  function initAll(){
    if(window.fmFeaturedInit && window.fmFeaturedInit.length){
      window.fmFeaturedInit.forEach(function(cfg){ initInstance(cfg); });
      window.fmFeaturedInit = [];
    }
  }

  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll);
  else initAll();
})();
