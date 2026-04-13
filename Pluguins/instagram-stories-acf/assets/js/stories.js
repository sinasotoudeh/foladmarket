function isaOpenCurrentStory(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const active = container.querySelector('.story-slide.active img');
    if (!active) return;

    window.open(active.src, '_blank');
}
(function ($) {
    'use strict';

    class InstagramStories {
        constructor(container) {
            this.container = $(container);
            this.slides = this.container.find('.story-slide');
            this.progressBars = this.container.find('.progress-fill');
            this.prevBtn = this.container.find('.story-prev');
            this.nextBtn = this.container.find('.story-next');
            this.currentCounter = this.container.find('.current-story');

            this.currentIndex = 0;
            this.progressInterval = null;
            this.isPaused = false;

            // تنظیمات از data attributes
            this.duration = parseInt(this.container.data('duration')) || 15000;
            this.autoplay = this.container.data('autoplay') !== 'false';
            this.loop = this.container.data('loop') !== 'false';

            this.updateInterval = 100;

            this.init();
        }

        init() {
            this.bindEvents();
            this.showSlide(0);

            if (this.autoplay) {
                this.startProgress();
            }
        }

        bindEvents() {
            // دکمه‌های ناوبری
            this.nextBtn.on('click', () => this.nextSlide());
            this.prevBtn.on('click', () => this.prevSlide());

            // دکمه توقف/شروع

            // کلیک روی راست و چپ تصویر
            this.container.find('.stories-content').on('click', (e) => {
                const clickX = e.pageX - $(e.currentTarget).offset().left;
                const width = $(e.currentTarget).width();

                if (clickX < width / 2) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            });

            // پشتیبانی از لمس
            this.setupTouchEvents();

            // توقف با hover
            this.container.on('mouseenter', () => {
                if (!this.isPaused) {
                    this.pauseProgress();
                }
            });

            this.container.on('mouseleave', () => {
                if (!this.isPaused) {
                    this.startProgress();
                }
            });

            // کلیدهای صفحه‌کلید
            $(document).on('keydown', (e) => {
                if (this.container.is(':visible')) {
                    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                        this.nextSlide();
                    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                        this.prevSlide();
                    }
                }
            });
        }

        setupTouchEvents() {
            let touchStartX = 0;
            let touchEndX = 0;
            let touchStartY = 0;
            let touchEndY = 0;

            this.container[0].addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                touchStartY = e.changedTouches[0].screenY;
            }, { passive: true });

            this.container[0].addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                touchEndY = e.changedTouches[0].screenY;
                this.handleSwipe(touchStartX, touchEndX, touchStartY, touchEndY);
            }, { passive: true });
        }

        handleSwipe(startX, endX, startY, endY) {
            const diffX = Math.abs(endX - startX);
            const diffY = Math.abs(endY - startY);

            // فقط اگر حرکت افقی بیشتر از عمودی بود
            if (diffX > diffY && diffX > 50) {
                if (endX < startX) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
        }

        showSlide(index) {
            this.slides.removeClass('active');
            this.slides.eq(index).addClass('active');

            this.progressBars.each((i, bar) => {
                const $bar = $(bar);
                if (i < index) {
                    $bar.css('width', '100%').addClass('completed');
                } else if (i === index) {
                    $bar.css('width', '0%').removeClass('completed');
                } else {
                    $bar.css('width', '0%').removeClass('completed');
                }
            });

            this.updateCounter();
        }

        startProgress() {
            this.clearProgress();

            let progress = 0;
            const increment = (this.updateInterval / this.duration) * 100;
            const currentBar = this.progressBars.eq(this.currentIndex);

            this.progressInterval = setInterval(() => {
                progress += increment;
                currentBar.css('width', progress + '%');

                if (progress >= 100) {
                    currentBar.addClass('completed');
                    this.nextSlide();
                }
            }, this.updateInterval);
        }

        pauseProgress() {
            clearInterval(this.progressInterval);
        }

        clearProgress() {
            clearInterval(this.progressInterval);
        }

        nextSlide() {
            if (this.currentIndex < this.slides.length - 1) {
                this.currentIndex++;
                this.showSlide(this.currentIndex);
                this.startProgress();
            } else if (this.loop) {
                this.currentIndex = 0;
                this.showSlide(this.currentIndex);
                this.startProgress();
            } else {
                this.clearProgress();
            }
        }

        prevSlide() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.showSlide(this.currentIndex);
                this.startProgress();
            }
        }

        updateCounter() {
            this.currentCounter.text(this.currentIndex + 1);
        }
    }

    // راه‌اندازی برای همه کانتینرها
    $(document).ready(function () {
        $('.instagram-stories-container').each(function () {
            new InstagramStories(this);
        });
    });

})(jQuery);
