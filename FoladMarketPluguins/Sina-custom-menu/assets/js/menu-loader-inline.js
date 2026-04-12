document.addEventListener('DOMContentLoaded', () => {
  const tree = JSON.parse(document.getElementById('cmenu-data').textContent);
  const nav  = document.querySelector('.cmenu-nav');
  if (!nav) return;

  // --- دسکتاپ: همان منطق hover قبلی ---
  nav.addEventListener('mouseover', e => {
    if (window.innerWidth <= 768) return;
    const li = e.target.closest('li[data-id]');
    if (!li || li.dataset.opened === 'true') return;
    const children = tree[li.dataset.id] || [];
    if (!children.length) return;
    li.classList.add('cmenu-has-submenu');
    const subUl = document.createElement('ul');
    subUl.className = 'cmenu-submenu';
    children.forEach(item => {
      const subLi = document.createElement('li');
      subLi.className = 'cmenu-submenu-item ' +
        (item.type === 'category' ? 'cmenu-subcategory' : 'cmenu-product');
      subLi.dataset.id = item.type === 'category' ? item.id : '';
      subLi.dataset.type = item.type;
      const a = document.createElement('a');
      a.href = item.url;
      a.textContent = item.name;
      subLi.appendChild(a);
      subUl.appendChild(subLi);
    });
    li.appendChild(subUl);
    li.dataset.opened = 'true';
  });

  // --- موبایل: کلیک به جای hover ---
  nav.addEventListener('click', e => {
    if (window.innerWidth > 768) return;
    const li = e.target.closest('li[data-id]');
    if (!li) return;
    const children = tree[li.dataset.id] || [];
    if (!children.length) {
      // هیچ زیرمنویی ندارد، اجازه بده لینک برود
      return;
    }

    // اگر هنوز زیرمنو نساخته‌ایم، بسازیم
    if (li.dataset.opened !== 'true') {
      li.classList.add('cmenu-has-submenu');
      const subUl = document.createElement('ul');
      subUl.className = 'cmenu-submenu';
      children.forEach(item => {
        const subLi = document.createElement('li');
        subLi.className = 'cmenu-submenu-item ' +
          (item.type === 'category' ? 'cmenu-subcategory' : 'cmenu-product');
        subLi.dataset.id = item.type === 'category' ? item.id : '';
        subLi.dataset.type = item.type;
        const a = document.createElement('a');
        a.href = item.url;
        a.textContent = item.name;
        subLi.appendChild(a);
        subUl.appendChild(subLi);
      });
      li.appendChild(subUl);
      li.dataset.opened = 'true';
    }

    // جلوی رفتار پیش‌فرض لینک را بگیر و کلاس open را toggle کن
    e.preventDefault();
    li.classList.toggle('open');
  });
});
