/**
 * Homepage JavaScript - Performance Optimized
 * @version 3.0
 * @description Fully optimized for Core Web Vitals & Performance
 * 
 * Optimizations Applied:
 * - Event Delegation instead of multiple listeners
 * - Removed Forced Reflows
 * - Replaced img tag replacement with simple src update
 * - Removed console.log statements
 * - Added IntersectionObserver for auto-pause
 * - Debounced resize handlers
 * - Passive event listeners
 * - RAF batching for DOM updates
 */

(function() {
    'use strict';

    /* ========================================
     * UTILITIES
     * ======================================== */
    
    /**
     * Debounce function - prevents excessive function calls
     */
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    /**
     * Check if device is mobile
     */
    function isMobile() {
        return window.innerWidth <= 768;
    }

    /* ========================================
     * SMOOTH SCROLL FOR ANCHOR LINKS
     * Using Event Delegation
     * ======================================== */
    
    document.addEventListener('click', function(e) {
        const anchor = e.target.closest('a[href^="#"]');
        if (!anchor) return;
        
        const targetId = anchor.getAttribute('href');
        if (targetId === '#') return;
        
        const target = document.querySelector(targetId);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });

    /* ========================================
     * USP SECTION - Interactive Functionality
     * ======================================== */
    
    (function initUSP() {
        const uspSection = document.querySelector('.foladmarket-usp');
        if (!uspSection) return;

        const featureButtons = uspSection.querySelectorAll('.usp-feature-item');
        const contentItems = uspSection.querySelectorAll('.usp-content-item');
        const contentDisplay = uspSection.querySelector('.usp-content-display');

        if (featureButtons.length === 0) return;

        let activeIndex = 0;
        let isAnimating = false;
        let autoRotateInterval = null;
        let isInViewport = false;
        const autoRotateDelay = 5000;

        // Initialize first item
        setActiveFeature(0, false);

        /**
         * Set Active Feature - Optimized without Forced Reflow
         */
        function setActiveFeature(index, shouldScroll = false) {
            if (index < 0 || index >= featureButtons.length || isAnimating) return;

            isAnimating = true;

            // Batch all DOM writes in single RAF
            requestAnimationFrame(() => {
                // Update buttons
                featureButtons.forEach((btn, i) => {
                    const isActive = i === index;
                    btn.classList.toggle('active', isActive);
                    btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                // Update content
                contentItems.forEach((content, i) => {
                    content.classList.toggle('active', i === index);
                });

                activeIndex = index;
            });

            // Reset animation lock
            setTimeout(() => {
                isAnimating = false;
            }, 400);

            // Smooth scroll on mobile (only when user clicks)
            if (shouldScroll && isMobile() && contentDisplay) {
                contentDisplay.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }

        /**
         * Auto-rotate functions
         */
        function startAutoRotate() {
            if (autoRotateInterval || !isInViewport) return;
            
            autoRotateInterval = setInterval(() => {
                if (!isAnimating && isInViewport) {
                    const nextIndex = (activeIndex + 1) % featureButtons.length;
                    setActiveFeature(nextIndex, false);
                }
            }, autoRotateDelay);
        }

        function stopAutoRotate() {
            if (autoRotateInterval) {
                clearInterval(autoRotateInterval);
                autoRotateInterval = null;
            }
        }

        /**
         * Event Delegation for clicks
         */
        uspSection.addEventListener('click', function(e) {
            const button = e.target.closest('.usp-feature-item');
            if (!button || isAnimating) return;

            const index = Array.from(featureButtons).indexOf(button);
            if (index !== -1 && index !== activeIndex) {
                stopAutoRotate();
                setActiveFeature(index, true);
                // Restart auto-rotate after 10 seconds of inactivity
                setTimeout(startAutoRotate, 10000);
            }
        });

        /**
         * Keyboard Navigation - Event Delegation
         */
        uspSection.addEventListener('keydown', function(e) {
            const button = e.target.closest('.usp-feature-item');
            if (!button) return;

            const index = Array.from(featureButtons).indexOf(button);

            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (!isAnimating && index !== activeIndex) {
                    setActiveFeature(index, true);
                }
            }

            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const nextIndex = e.key === 'ArrowDown'
                    ? (index + 1) % featureButtons.length
                    : (index - 1 + featureButtons.length) % featureButtons.length;
                featureButtons[nextIndex].focus();
            }
        });

        /**
         * Pause on hover/touch
         */
        uspSection.addEventListener('mouseenter', stopAutoRotate);
        uspSection.addEventListener('mouseleave', () => {
            if (isInViewport) startAutoRotate();
        });
        uspSection.addEventListener('touchstart', stopAutoRotate, { passive: true });

        /**
         * IntersectionObserver - Pause when out of viewport
         */
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    isInViewport = entry.isIntersecting;
                    
                    if (entry.isIntersecting) {
                        uspSection.classList.add('in-view');
                        startAutoRotate();
                    } else {
                        stopAutoRotate();
                    }
                });
            }, { threshold: 0.2 });

            observer.observe(uspSection);
        } else {
            // Fallback for old browsers
            isInViewport = true;
            startAutoRotate();
        }

        /**
         * Visibility Change - Pause when tab is hidden
         */
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRotate();
            } else if (isInViewport) {
                startAutoRotate();
            }
        });

    })();

    /* ========================================
     * FEATURED ARTICLES CAROUSEL
     * Optimized - No img tag replacement
     * ======================================== */
    
    (function initFeaturedArticles() {
        const featuredEl = document.getElementById('fma-featured');
        const items = document.querySelectorAll('.fma-item');

        if (!featuredEl || items.length === 0) return;

        // Cache DOM elements
        const elements = {
            link: document.getElementById('fma-main-link'),
            img: document.getElementById('fma-main-img'),
            cat: document.getElementById('fma-main-cat'),
            time: document.getElementById('fma-main-time'),
            title: document.getElementById('fma-main-title'),
            excerpt: document.getElementById('fma-main-excerpt')
        };

        let currentIndex = 0;
        let autoRotateTimer = null;
        let manualClickCooldown = null;
        let isTransitioning = false;
        let isInViewport = false;

        // Set transition style once
        featuredEl.style.transition = 'opacity 0.3s ease';

        /**
         * Switch Article - Optimized without img replacement
         */
        function switchArticle(index) {
            if (isTransitioning || index === currentIndex || index < 0 || index >= items.length) return;

            isTransitioning = true;
            const targetItem = items[index];

            // Read data attributes once
            const newData = {
                title: targetItem.dataset.title || '',
                excerpt: targetItem.dataset.excerpt || '',
                image: targetItem.dataset.image || '',
                link: targetItem.dataset.link || '#',
                date: targetItem.dataset.date || '',
                cat: targetItem.dataset.cat || ''
            };

            // Fade out
            featuredEl.style.opacity = '0';

            // Use transitionend instead of setTimeout for better accuracy
            const handleTransitionEnd = function() {
                featuredEl.removeEventListener('transitionend', handleTransitionEnd);

                // Batch all DOM updates in RAF
                requestAnimationFrame(() => {
                    // Update content
                    if (elements.link) elements.link.href = newData.link;
                    if (elements.cat) elements.cat.textContent = newData.cat;
                    if (elements.time) elements.time.textContent = newData.date;
                    if (elements.title) elements.title.textContent = newData.title;
                    if (elements.excerpt) elements.excerpt.textContent = newData.excerpt;

                    // ✅ Simply update src - no tag replacement needed!
                    if (elements.img && newData.image) {
                        elements.img.src = newData.image;
                    }

                    // Update active class
                    items.forEach(item => item.classList.remove('fma-active'));
                    targetItem.classList.add('fma-active');

                    // Fade in
                    requestAnimationFrame(() => {
                        featuredEl.style.opacity = '1';
                        currentIndex = index;
                        isTransitioning = false;
                    });
                });
            };

            featuredEl.addEventListener('transitionend', handleTransitionEnd, { once: true });

            // Fallback timeout in case transitionend doesn't fire
            setTimeout(() => {
                if (isTransitioning) {
                    handleTransitionEnd();
                }
            }, 350);
        }

        /**
         * Auto-rotate functions
         */
        function startAutoRotate() {
            stopAutoRotate();
            if (!isInViewport) return;
            
            autoRotateTimer = setInterval(() => {
                if (!isTransitioning && isInViewport) {
                    const nextIndex = (currentIndex + 1) % items.length;
                    switchArticle(nextIndex);
                }
            }, 5000);
        }

        function stopAutoRotate() {
            if (autoRotateTimer) {
                clearInterval(autoRotateTimer);
                autoRotateTimer = null;
            }
        }

        /**
         * Event Delegation for item clicks
         */
        featuredEl.addEventListener('click', function(e) {
            const item = e.target.closest('.fma-item');
            if (!item) return;

            const index = Array.from(items).indexOf(item);
            if (index !== -1) {
                switchArticle(index);
                stopAutoRotate();
                
                if (manualClickCooldown) clearTimeout(manualClickCooldown);
                manualClickCooldown = setTimeout(startAutoRotate, 10000);
            }
        });

        /**
         * Hover events
         */
        featuredEl.addEventListener('mouseenter', stopAutoRotate);
        featuredEl.addEventListener('mouseleave', () => {
            if (isInViewport) startAutoRotate();
        });

        /**
         * IntersectionObserver - Auto-pause when out of viewport
         */
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    isInViewport = entry.isIntersecting;
                    
                    if (entry.isIntersecting) {
                        startAutoRotate();
                    } else {
                        stopAutoRotate();
                    }
                });
            }, { threshold: 0.1 });

            observer.observe(featuredEl);
        } else {
            isInViewport = true;
            startAutoRotate();
        }

        /**
         * Visibility Change
         */
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRotate();
            } else if (isInViewport) {
                startAutoRotate();
            }
        });

    })();

    /* ========================================
     * PRICING CAROUSEL - Premium Version
     * ======================================== */
    
    (function initPricingCarousel() {
        const container = document.querySelector('.pricing-cards-stack');
        if (!container) return;

        const cards = Array.from(document.querySelectorAll('.pricing-card-premium'));
        const dots = Array.from(document.querySelectorAll('.progress-dot'));
        const totalCards = cards.length;

        if (totalCards === 0) return;

        let currentIndex = 0;
        let isAnimating = false;
        let autoPlayInterval = null;
        let isInViewport = false;
        const autoPlayDelay = 5000;

        // Cache navigation buttons
        const prevBtn = document.querySelector('.pricing-nav-prev');
        const nextBtn = document.querySelector('.pricing-nav-next');

        /**
         * Update card states - Optimized
         */
        function updateCardStates() {
            isAnimating = true;

            requestAnimationFrame(() => {
                cards.forEach((card, index) => {
                    // Clear all classes at once
                    card.className = card.className.replace(/\b(active|next-1|next-2|prev-1|prev-2)\b/g, '').trim();

                    if (index === currentIndex) {
                        card.classList.add('active');
                        card.setAttribute('aria-hidden', 'false');
                    } else {
                        card.setAttribute('aria-hidden', 'true');

                        const distanceNext = (index - currentIndex + totalCards) % totalCards;
                        const distancePrev = (currentIndex - index + totalCards) % totalCards;

                        if (distanceNext === 1) {
                            card.classList.add('next-1');
                        } else if (distanceNext === 2) {
                            card.classList.add('next-2');
                        } else if (distancePrev === 1) {
                            card.classList.add('prev-1');
                        } else if (distancePrev === 2) {
                            card.classList.add('prev-2');
                        }
                    }
                });

                // Update dots
                dots.forEach((dot, index) => {
                    const isActive = index === currentIndex;
                    dot.classList.toggle('active', isActive);
                    dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
            });

            setTimeout(() => {
                isAnimating = false;
            }, 700);
        }

        /**
         * Navigate
         */
        function navigate(direction) {
            if (isAnimating) return;

            stopAutoPlay();

            if (direction === 'next') {
                currentIndex = (currentIndex + 1) % totalCards;
            } else {
                currentIndex = (currentIndex - 1 + totalCards) % totalCards;
            }

            updateCardStates();
            startAutoPlay();
        }

        /**
         * Go to specific slide
         */
        function goToSlide(index) {
            if (isAnimating || index === currentIndex) return;

            stopAutoPlay();
            currentIndex = index;
            updateCardStates();
            startAutoPlay();
        }

        /**
         * Auto-play functions
         */
        function startAutoPlay() {
            stopAutoPlay();
            if (totalCards <= 1 || !isInViewport) return;

            autoPlayInterval = setInterval(() => {
                if (!isAnimating && isInViewport) {
                    currentIndex = (currentIndex + 1) % totalCards;
                    updateCardStates();
                }
            }, autoPlayDelay);
        }

        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
        }

        /**
         * Touch/Swipe support
         */
        let touchStartX = 0;

        container.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        container.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > 50) {
                navigate(diff > 0 ? 'next' : 'prev');
            }
        }, { passive: true });

        /**
         * Event listeners
         */
        if (prevBtn) {
            prevBtn.addEventListener('click', () => navigate('prev'));
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => navigate('next'));
        }

        // Dots click - Event Delegation
        const dotsContainer = document.querySelector('.pricing-progress-dots');
        if (dotsContainer) {
            dotsContainer.addEventListener('click', function(e) {
                const dot = e.target.closest('.progress-dot');
                if (!dot) return;

                const index = dots.indexOf(dot);
                if (index !== -1) {
                    goToSlide(index);
                }
            });
        }

        // Keyboard navigation (only when focused on carousel)
        container.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                navigate('prev');
            } else if (e.key === 'ArrowRight') {
                navigate('next');
            }
        });

        // Pause on hover
        container.addEventListener('mouseenter', stopAutoPlay);
        container.addEventListener('mouseleave', () => {
            if (isInViewport) startAutoPlay();
        });

        /**
         * IntersectionObserver
         */
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    isInViewport = entry.isIntersecting;
                    
                    if (entry.isIntersecting) {
                        startAutoPlay();
                    } else {
                        stopAutoPlay();
                    }
                });
            }, { threshold: 0.1 });

            observer.observe(container);
        } else {
            isInViewport = true;
        }

        // Initialize
        updateCardStates();
        if ('IntersectionObserver' in window === false) {
            startAutoPlay();
        }

    })();

    /* ========================================
     * FAQ ACCORDION
     * Optimized with Event Delegation
     * ======================================== */
    
    (function initFAQ() {
        const faqContainer = document.querySelector('.faq-section, .faq-container, [class*="faq"]');
        if (!faqContainer) return;

        const faqItems = faqContainer.querySelectorAll('.faq-item');
        if (faqItems.length === 0) return;

        // Cache current open item
        let currentOpenItem = null;

        /**
         * Close FAQ Item
         */
        function closeItem(item) {
            if (!item) return;

            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');

            if (question) question.setAttribute('aria-expanded', 'false');
            if (answer) answer.style.maxHeight = null;
            if (icon) icon.textContent = '+';
            item.classList.remove('active');
        }

        /**
         * Open FAQ Item
         */
        function openItem(item) {
            if (!item) return;

            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');

            if (question) question.setAttribute('aria-expanded', 'true');
            if (answer) answer.style.maxHeight = answer.scrollHeight + 'px';
            if (icon) icon.textContent = '−';
            item.classList.add('active');
        }

        /**
         * Event Delegation - Single listener for all FAQ items
         */
        faqContainer.addEventListener('click', function(e) {
            const question = e.target.closest('.faq-question');
            if (!question) return;

            const item = question.closest('.faq-item');
            if (!item) return;

            const isCurrentlyOpen = question.getAttribute('aria-expanded') === 'true';

            // Close currently open item (if different)
            if (currentOpenItem && currentOpenItem !== item) {
                closeItem(currentOpenItem);
            }

            // Toggle current item
            if (isCurrentlyOpen) {
                closeItem(item);
                currentOpenItem = null;
            } else {
                openItem(item);
                currentOpenItem = item;
            }
        });

        /**
         * Keyboard accessibility
         */
        faqContainer.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;

            const question = e.target.closest('.faq-question');
            if (!question) return;

            e.preventDefault();
            question.click();
        });

    })();

})();
