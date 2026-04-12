/**
 * Header Scripts - Performance Optimized
 * @version 2.0
 * @description Optimized for Navigation & Megamenu with minimal reflows
 * 
 * Optimizations:
 * - Event Delegation for all click handlers
 * - Debounced resize handler
 * - Single DOMContentLoaded block
 * - Cached DOM queries
 * - Prevent duplicate submenu creation
 * - Memory leak prevention
 */

(function () {
  'use strict';

  /* ========================================
   * UTILITIES
   * ======================================== */

  /**
   * Debounce function
   */
  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  /**
   * Check if desktop
   */
  function isDesktop() {
    return window.innerWidth > 768;
  }

  /* ========================================
   * MAIN INITIALIZATION
   * ======================================== */

  document.addEventListener('DOMContentLoaded', function () {

    // Cache DOM elements
    const elements = {
      mobileToggle: document.querySelector('.mobile-menu-toggle'),
      mobileMenu: document.querySelector('.mobile-menu'),
      megamenuTrigger: document.querySelector('.nav-trigger'),
      megamenuNav: document.querySelector('.megamenu-categories'),
      body: document.body
    };

    /* ========================================
     * MOBILE MENU
     * ======================================== */

    if (elements.mobileToggle && elements.mobileMenu) {
      elements.mobileToggle.addEventListener('click', function () {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';

        this.setAttribute('aria-expanded', !isExpanded);
        elements.mobileMenu.setAttribute('aria-hidden', isExpanded);
        elements.body.style.overflow = !isExpanded ? 'hidden' : '';
      });
    }

    /* ========================================
     * MOBILE SUBMENU - Event Delegation
     * ======================================== */

    if (elements.mobileMenu) {
      elements.mobileMenu.addEventListener('click', function (e) {
        const toggle = e.target.closest('.submenu-toggle');
        if (!toggle) return;

        const parent = toggle.parentElement;
        parent.classList.toggle('active');
      });
    }

    /* ========================================
     * DESKTOP MEGAMENU
     * ======================================== */

    if (elements.megamenuTrigger) {
      // Toggle megamenu
      elements.megamenuTrigger.addEventListener('click', function () {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
      });

      // Close megamenu on outside click - Event Delegation
      document.addEventListener('click', function (e) {
        if (!e.target.closest('.has-megamenu')) {
          elements.megamenuTrigger.setAttribute('aria-expanded', 'false');
        }
      });
    }

    /* ========================================
     * DYNAMIC CATEGORY MEGAMENU
     * ======================================== */

    initDynamicMegamenu(elements.megamenuNav);

    /* ========================================
     * RESIZE HANDLER - Debounced
     * ======================================== */

    const handleResize = debounce(function () {
      if (isDesktop() && elements.mobileMenu && elements.mobileToggle) {
        elements.mobileToggle.setAttribute('aria-expanded', 'false');
        elements.mobileMenu.setAttribute('aria-hidden', 'true');
        elements.body.style.overflow = '';
      }
    }, 150);

    window.addEventListener('resize', handleResize);

  });

  /* ========================================
   * DYNAMIC MEGAMENU INITIALIZATION
   * ======================================== */

  function initDynamicMegamenu(nav) {
    if (!nav) return;

    // Read menu tree data
    const dataEl = document.getElementById('cmenu-data');
    if (!dataEl) return;

    let tree;
    try {
      tree = JSON.parse(dataEl.textContent);
    } catch (e) {
      console.error('Invalid menu data JSON');
      return;
    }

    /**
     * Create submenu DOM
     */
    function createSubmenu(children) {
      const subUl = document.createElement('ul');
      subUl.className = 'cmenu-submenu';

      // Use DocumentFragment for better performance
      const fragment = document.createDocumentFragment();

      children.forEach(item => {
        const subLi = document.createElement('li');
        subLi.className = 'cmenu-submenu-item ' +
          (item.type === 'category' ? 'cmenu-subcategory' : 'cmenu-product');

        if (item.type === 'category') {
          subLi.dataset.id = item.id;
        }
        subLi.dataset.type = item.type;

        const a = document.createElement('a');
        a.href = item.url;
        a.textContent = item.name;

        subLi.appendChild(a);
        fragment.appendChild(subLi);
      });

      subUl.appendChild(fragment);
      return subUl;
    }

    /**
     * Open submenu for item
     */
    function openSubmenu(li) {
      // Prevent duplicate creation
      if (li.dataset.opened === 'true') return;

      const itemId = li.dataset.id;
      const children = tree[itemId] || [];

      if (!children.length) return;

      // Add class first (for styling)
      li.classList.add('cmenu-has-submenu');

      // Create and append submenu
      const submenu = createSubmenu(children);
      li.appendChild(submenu);

      // Mark as opened
      li.dataset.opened = 'true';
    }

    /* ========================================
     * DESKTOP: Hover to open
     * ======================================== */

    nav.addEventListener('mouseover', function (e) {
      if (!isDesktop()) return;

      const li = e.target.closest('li[data-id]');
      if (!li) return;

      openSubmenu(li);
    });

    /* ========================================
     * MOBILE: Click to toggle
     * ======================================== */

    nav.addEventListener('click', function (e) {
      if (isDesktop()) return;

      const li = e.target.closest('li[data-id]');
      if (!li) return;

      const itemId = li.dataset.id;
      const children = tree[itemId] || [];

      // If no children, allow link click
      if (!children.length) return;

      // Prevent navigation
      e.preventDefault();

      // Create submenu if first time
      if (li.dataset.opened !== 'true') {
        openSubmenu(li);
      }

      // Toggle open class
      li.classList.toggle('open');
    });
  }

})();
