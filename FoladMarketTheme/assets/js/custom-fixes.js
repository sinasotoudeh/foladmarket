(function () {
    'use strict';
    document.addEventListener("DOMContentLoaded", function () {
        var tables = document.querySelectorAll('table');
        tables.forEach(function (table) {
            // اگر جدول قبلاً رپر داشت یا کلاس خاصی داشت که نباید رپ شود، نادیده بگیر
            if (table.parentElement.classList.contains('table-wrapper') || table.classList.contains('no-scroll')) {
                return;
            }

            var wrapper = document.createElement('div');
            wrapper.className = 'table-wrapper';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        });
    });
})();
