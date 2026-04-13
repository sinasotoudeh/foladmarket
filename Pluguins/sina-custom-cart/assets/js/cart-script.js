/**
 * اسکریپت مدیریت سبد خرید سینا - نسخه Session-Based (بدون کش)
 *
 * @package Sina_Custom_Cart
 * @since 1.2.0
 */

(function ($) {
    'use strict';

    /**
     * شیء اصلی سبد خرید
     */
    const SinaCart = {

        /**
         * تایمرهای debounce
         */
        debounceTimers: {},

        /**
         * ✅ پرچم جلوگیری از Reload مکرر
         */
        isReloading: false,

        /**
         * مقداردهی اولیه
         */
        init: function () {
            if (typeof sinaCartVars === 'undefined') {
                console.error('❌ Sina Cart: sinaCartVars is not defined');
                return;
            }

            // ✅ اطمینان از وجود Session Cookie
            this.ensureSessionCookie();

            // اتصال رویدادها
            this.bindEvents();

            // مقداردهی کنترل‌های تعداد
            this.initQuantityControls();

            // لاگ اولیه
            if (sinaCartVars.debug_mode) {
                console.log('✅ Sina Cart Script Loaded', {
                    ajax_url: sinaCartVars.ajax_url,
                    nonce: sinaCartVars.nonce ? '✓' : '✗',
                    session_id: this.getCookie('sina_cart_session_id') ? '✓' : '✗'
                });
            }
        },

        /**
         * ✅ اطمینان از وجود Session در Cookie
         */
        ensureSessionCookie: function () {
            const cookieName = 'sina_cart_session_id';
            const existingSession = this.getCookie(cookieName);

            // اگر Cookie وجود ندارد ولی PHP Session ID پاس داده است
            if (!existingSession && sinaCartVars.sessionId) {
                this.setCookie(cookieName, sinaCartVars.sessionId, 365);
                if (sinaCartVars.debug_mode) {
                    console.log('🍪 Session Cookie Set from JS:', sinaCartVars.sessionId.substring(0, 20) + '...');
                }
            }
        },

        /**
         * دریافت مقدار Cookie
         */
        getCookie: function (name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        },

        /**
         * ذخیره Cookie
         */
        setCookie: function (name, value, days) {
            const expires = new Date(Date.now() + days * 864e5).toUTCString();
            const secure = window.location.protocol === 'https:' ? '; Secure' : '';
            document.cookie = `${name}=${value}; expires=${expires}; path=/; SameSite=Lax${secure}`;
        },

        /**
         * اتصال رویدادها
         */
        bindEvents: function () {
            const self = this;

            $(document).on('click', '.sina-add-to-cart-btn', function (e) {
                self.addToCart.call(this, e);
            });

            $(document).on('click', '.sina-remove-cart-item', function (e) {
                self.removeItem.call(this, e);
            });

            $(document).on('click', '.qty-increase', function (e) {
                self.increaseQuantity.call(this, e);
            });

            $(document).on('click', '.qty-decrease', function (e) {
                self.decreaseQuantity.call(this, e);
            });

            $(document).on('change', '.sina-cart-quantity-input', function () {
                self.manualQuantityChange.call(this);
            });

            $(document).on('keypress', '.sina-cart-quantity-input', function (e) {
                return self.preventInvalidInput.call(this, e);
            });

            $(document).on('submit', '.quantity-controls', function (e) {
                e.preventDefault();
                return false;
            });
        },

        /**
         * مقداردهی اولیه کنترل‌های تعداد
         */
        initQuantityControls: function () {
            $('.sina-cart-quantity-input').each(function () {
                const $input = $(this);
                const min = parseInt($input.attr('min')) || 1;
                const max = parseInt($input.attr('max')) || 9999;
                const value = parseInt($input.val()) || min;

                if (value < min) {
                    $input.val(min);
                } else if (value > max) {
                    $input.val(max);
                }
            });
        },

        /**
         * ✅ افزودن محصول به سبد خرید
         */
        addToCart: function (e) {
            e.preventDefault();

            const $btn = $(this);
            const postId = $btn.data('post-id');
            const rowIndex = $btn.data('row-index');
            const quantity = parseInt($btn.data('quantity')) || 1;

            if (!postId || rowIndex === undefined || rowIndex === null) {
                SinaCart.showNotification('error', 'داده‌های محصول نامعتبر است');
                console.error('❌ Invalid data:', { postId, rowIndex, quantity });
                return;
            }

            if (quantity < 1) {
                SinaCart.showNotification('error', sinaCartVars.messages.invalid_qty);
                return;
            }

            const originalText = $btn.text();
            $btn.prop('disabled', true)
                .addClass('loading')
                .html(`<span class="loading-spinner"></span> ${sinaCartVars.messages.adding}`);

            $.ajax({
                url: sinaCartVars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sina_add_to_cart',
                    nonce: sinaCartVars.nonce,
                    post_id: postId,
                    row_index: rowIndex,
                    quantity: quantity
                },
                success: function (response) {
                    if (response.success) {
                        SinaCart.showNotification('success', response.data.message || sinaCartVars.messages.added);

                        // ✅ به‌روزرسانی UI
                        SinaCart.updateCartUI(response.data);

                        $btn.removeClass('loading')
                            .addClass('success')
                            .html(`<span class="success-icon">✓</span> ${sinaCartVars.messages.added}`);

                        setTimeout(() => {
                            $btn.text(originalText)
                                .removeClass('success')
                                .prop('disabled', false);
                        }, 2000);

                        // ✅ رفرش با پرچم
                        if ($('.sina-cart-wrapper').length > 0 && !SinaCart.isReloading) {
                            SinaCart.isReloading = true;
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    } else {
                        const errorMsg = response.data?.message || sinaCartVars.messages.error;
                        SinaCart.showNotification('error', errorMsg);
                        SinaCart.resetButton($btn, originalText);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ AJAX Error:', { xhr, status, error });

                    let errorMsg = sinaCartVars.messages.error;
                    if (xhr.status === 403) {
                        errorMsg = 'خطای امنیتی. لطفاً صفحه را رفرش کنید.';
                    } else if (xhr.status === 0) {
                        errorMsg = 'خطای ارتباط با سرور';
                    }

                    SinaCart.showNotification('error', errorMsg);
                    SinaCart.resetButton($btn, originalText);
                }
            });
        },

        /**
         * ✅ حذف محصول از سبد
         */
        removeItem: function (e) {
            e.preventDefault();

            if (!confirm(sinaCartVars.messages.confirm_remove)) {
                return;
            }

            const $btn = $(this);
            const itemId = $btn.data('item-id');
            const $row = $btn.closest('.sina-cart-item');

            if (!itemId) {
                SinaCart.showNotification('error', 'شناسه محصول نامعتبر است');
                return;
            }

            $row.addClass('removing');
            $btn.prop('disabled', true);

            $.ajax({
                url: sinaCartVars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sina_remove_cart_item',
                    nonce: sinaCartVars.nonce,
                    item_id: itemId
                },
                success: function (response) {
                    if (response.success) {
                        SinaCart.showNotification('success', response.data.message || sinaCartVars.messages.removed);

                        // ✅ به‌روزرسانی UI
                        SinaCart.updateCartUI(response.data);

                        $row.fadeOut(400, function () {
                            $(this).remove();

                            // ✅ رفرش با پرچم
                            const remainingItems = $('.sina-cart-table tbody .sina-cart-item').length;
                            if ((remainingItems === 0 || response.data.is_empty) && !SinaCart.isReloading) {
                                SinaCart.isReloading = true;
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        });
                    } else {
                        $row.removeClass('removing');
                        $btn.prop('disabled', false);
                        SinaCart.showNotification('error', response.data.message || sinaCartVars.messages.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ Remove Error:', error);
                    $row.removeClass('removing');
                    $btn.prop('disabled', false);
                    SinaCart.showNotification('error', sinaCartVars.messages.error);
                }
            });
        },

        /**
         * ✅ افزایش تعداد محصول
         */
        increaseQuantity: function (e) {
            e.preventDefault();

            const $btn = $(this);
            const $input = $btn.siblings('.sina-cart-quantity-input');
            const currentValue = parseInt($input.val()) || 1;
            const max = parseInt($input.attr('max')) || 9999;

            if (currentValue < max) {
                $input.val(currentValue + 1).trigger('change');
            } else {
                SinaCart.showNotification('warning', `حداکثر تعداد مجاز ${max} عدد است`);
            }
        },

        /**
         * ✅ کاهش تعداد محصول
         */
        decreaseQuantity: function (e) {
            e.preventDefault();

            const $btn = $(this);
            const $input = $btn.siblings('.sina-cart-quantity-input');
            const currentValue = parseInt($input.val()) || 1;
            const min = parseInt($input.attr('min')) || 1;

            if (currentValue > min) {
                $input.val(currentValue - 1).trigger('change');
            } else {
                SinaCart.showNotification('warning', `حداقل تعداد ${min} عدد است`);
            }
        },

        /**
         * ✅ تغییر دستی تعداد (با Debounce)
         */
        manualQuantityChange: function () {
            const $input = $(this);
            const itemId = $input.data('item-id');
            let quantity = parseInt($input.val()) || 1;
            const min = parseInt($input.attr('min')) || 1;
            const max = parseInt($input.attr('max')) || 9999;

            if (quantity < min) {
                quantity = min;
                $input.val(quantity);
            } else if (quantity > max) {
                quantity = max;
                $input.val(quantity);
                SinaCart.showNotification('warning', `حداکثر مقدار ${max} است`);
            }

            clearTimeout(SinaCart.debounceTimers[itemId]);

            SinaCart.debounceTimers[itemId] = setTimeout(() => {
                SinaCart.updateCartQuantity(itemId, quantity, $input);
            }, 800);
        },

        /**
         * ✅ به‌روزرسانی تعداد در سرور
         */
        updateCartQuantity: function (itemId, quantity, $input) {
            const $row = $input.closest('.sina-cart-item');

            if (!itemId) {
                SinaCart.showNotification('error', 'شناسه محصول نامعتبر است');
                return;
            }

            $row.addClass('updating');
            $input.prop('disabled', true);

            $.ajax({
                url: sinaCartVars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sina_update_cart_quantity',
                    nonce: sinaCartVars.nonce,
                    item_id: itemId,
                    quantity: quantity
                },
                success: function (response) {
                    $row.removeClass('updating');
                    $input.prop('disabled', false);

                    if (response.success) {
                        // ✅ به‌روزرسانی قیمت آیتم
                        if (response.data.item_total_formatted) {
                            $row.find('.sina-cart-item-total .total-amount').text(
                                response.data.item_total_formatted
                            );
                        }

                        // ✅ به‌روزرسانی UI
                        SinaCart.updateCartUI(response.data);

                        // ✅ رفرش با پرچم
                        if (!SinaCart.isReloading) {
                            SinaCart.isReloading = true;
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }
                    } else {
                        const oldQuantity = $input.data('old-value') || 1;
                        $input.val(oldQuantity);
                        SinaCart.showNotification('error', response.data.message || sinaCartVars.messages.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('❌ Update Error:', error);
                    $row.removeClass('updating');
                    $input.prop('disabled', false);

                    const oldQuantity = $input.data('old-value') || 1;
                    $input.val(oldQuantity);

                    SinaCart.showNotification('error', sinaCartVars.messages.error);
                }
            });

            $input.data('old-value', quantity);
        },

        /**
         * جلوگیری از ورود کاراکتر نامعتبر
         */
        preventInvalidInput: function (e) {
            const charCode = e.which ? e.which : e.keyCode;

            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                e.preventDefault();
                return false;
            }
            return true;
        },

        /**
         * ✅ به‌روزرسانی UI سبد خرید
         */
        updateCartUI: function (data) {
            // به‌روزرسانی تعداد
            if (data.cart_count !== undefined) {
                const countText = data.cart_count > 0 ? data.cart_count : '';
                $('.sina-cart-count, .cart-count-badge, .sina-cart-count-badge').text(countText);

                if (data.cart_count > 0) {
                    $('.sina-cart-count, .cart-count-badge, .sina-cart-count-badge').show();
                } else {
                    $('.sina-cart-count, .cart-count-badge, .sina-cart-count-badge').hide();
                }
            }

            // به‌روزرسانی قیمت
            if (data.cart_total !== undefined) {
                const formattedTotal = data.cart_total_formatted
                    ? data.cart_total_formatted
                    : (typeof data.cart_total === 'number'
                        ? data.cart_total.toLocaleString('fa-IR') + ' تومان'
                        : data.cart_total);

                $('.cart-total-amount strong, .sina-cart-total strong').text(formattedTotal);
                $('.sina-cart-total .total-amount, .total-amount').text(formattedTotal);
            }
        },

        /**
         * ✅ نمایش نوتیفیکیشن
         */
        showNotification: function (type, message) {
            $('.sina-notification').remove();

            const iconMap = {
                success: '✓',
                error: '✕',
                info: 'ℹ',
                warning: '⚠'
            };

            const $notification = $(`
                <div class="sina-notification sina-notification-${type}" role="alert" aria-live="polite">
                    <span class="sina-notification-icon" aria-hidden="true">${iconMap[type] || 'ℹ'}</span>
                    <span class="sina-notification-message">${message}</span>
                    <button type="button" class="sina-notification-close" aria-label="بستن">×</button>
                </div>
            `);

            $('body').append($notification);

            setTimeout(() => {
                $notification.addClass('show');
            }, 100);

            $notification.find('.sina-notification-close').on('click', function () {
                $notification.removeClass('show');
                setTimeout(() => $notification.remove(), 300);
            });

            setTimeout(() => {
                $notification.removeClass('show');
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            }, 4000);
        },

        /**
         * بازنشانی دکمه
         */
        resetButton: function ($btn, originalText) {
            $btn.text(originalText || 'استعلام قیمت')
                .removeClass('loading success')
                .prop('disabled', false);
        }
    };

    /**
     * ✅ اجرای اسکریپت
     */
    $(document).ready(function () {
        SinaCart.init();
    });

    window.SinaCart = SinaCart;
    const SinaCheckout = {

        init: function () {
            if ($('#sina-checkout-form').length === 0) return;

            this.bindEvents();

            if (sinaCartVars.debug_mode) {
                console.log("✅ SinaCheckout initialized");
            }
        },

        bindEvents: function () {
            const self = this;

            // ارسال فرم Checkout
            $(document).on("submit", "#sina-checkout-form", function (e) {
                self.submitOrder.call(this, e);
            });

            // اعتبارسنجی زنده موبایل
            $(document).on("input", "#customer_phone", function () {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(value);

                if (value.length === 11) {
                    if (/^09\d{9}$/.test(value)) {
                        $(this).removeClass("invalid").addClass("valid");
                    } else {
                        $(this).removeClass("valid").addClass("invalid");
                    }
                } else {
                    $(this).removeClass("valid invalid");
                }
            });

            // فوکوس روی اولین فیلد خالی Required
            const $firstEmpty = $('#sina-checkout-form input[required]').filter(function () {
                return !this.value;
            }).first();

            if ($firstEmpty.length) $firstEmpty.focus();
        },

        submitOrder: function (e) {
            e.preventDefault();

            const $form = $(this);
            const $submitBtn = $("#sina-checkout-submit");
            const $btnText = $submitBtn.find(".button-text");
            const $btnLoading = $submitBtn.find(".button-loading");

            // مقداردهی فیلدها
            const customerName = $("#customer_name").val().trim();
            const customerPhone = $("#customer_phone").val().trim();
            const customerEmail = $("#customer_email").val().trim();
            const customerCompany = $("#customer_company").val().trim();
            const customerAddress = $("#customer_address").val().trim();
            const customerNotes = $("#customer_notes").val().trim();

            // اعتبارسنجی Frontend
            if (!customerName) {
                SinaCart.showNotification("error", "لطفاً نام و نام خانوادگی را وارد کنید");
                $("#customer_name").focus();
                return;
            }

            if (!/^09\d{9}$/.test(customerPhone)) {
                SinaCart.showNotification("error", "شماره موبایل نامعتبر است (مثال: 09123456789)");
                $("#customer_phone").focus();
                return;
            }

            // فعال‌سازی لودینگ
            $submitBtn.prop("disabled", true);
            $btnText.hide();
            $btnLoading.show();

            $.ajax({
                url: sinaCartVars.ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    action: "sina_submit_order",
                    nonce: sinaCartVars.nonce,

                    customer_name: customerName,
                    customer_phone: customerPhone,
                    customer_email: customerEmail,
                    customer_company: customerCompany,
                    customer_address: customerAddress,
                    customer_notes: customerNotes
                },

                success: function (response) {

                    if (response.success) {

                        // نمایش نوتیفیکیشن
                        SinaCart.showNotification("success", response.data.message || "درخواست استعلام ثبت شد");

                        // غیرفعال کردن فرم
                        $form.find("input, textarea, button").prop("disabled", true);

                        // تغییر متن دکمه
                        $btnLoading.hide();
                        $btnText.text("استعلام با موفقیت ثبت شد").show();

                        return;

                    } else {
                        const msg = response.data?.message || "خطا در ثبت استعلام";
                        SinaCart.showNotification("error", msg);

                        $submitBtn.prop("disabled", false);
                        $btnText.show();
                        $btnLoading.hide();
                    }
                },

                error: function (xhr, status, error) {
                    console.error("❌ Checkout Error:", { xhr, status, error });

                    let msg = "خطا در ثبت استعلام";
                    if (xhr.status === 403) msg = "خطای امنیتی. صفحه را رفرش کنید.";
                    else if (xhr.status === 0) msg = "خطای ارتباط با سرور.";
                    else if (xhr.responseJSON?.data?.message) msg = xhr.responseJSON.data.message;

                    SinaCart.showNotification("error", msg);

                    $submitBtn.prop("disabled", false);
                    $btnText.show();
                    $btnLoading.hide();
                }
            });
        }
    };

    $(document).ready(function () {
        SinaCheckout.init();
    });

    window.SinaCheckout = SinaCheckout;
})(jQuery);
