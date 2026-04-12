// باز و بسته کردن منوی آبشاری
document.addEventListener('click', function(e) {
  var dd = document.querySelector('.subcat-dropdown');
  if (!dd) return;
  var field = dd.querySelector('.dropdown-field');
  if (field.contains(e.target)) {
    dd.classList.toggle('open');
  } else if (!dd.contains(e.target)) {
    dd.classList.remove('open');
  }
});

// بروزرسانی aria-expanded
document.addEventListener('click', function(e) {
  var dd = document.querySelector('.subcat-dropdown');
  if (!dd) return;
  var field = dd.querySelector('.dropdown-field');
  field.setAttribute('aria-expanded', dd.classList.contains('open'));
});
