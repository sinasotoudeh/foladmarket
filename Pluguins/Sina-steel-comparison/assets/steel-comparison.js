jQuery(function ($) {
    const steels = Array.isArray(window.steelData) ? window.steelData : [];
    const header = $("#steel-header");
    const body = $("#steel-body");

    const properties = [
        { key: "گرید فولاد", label: "گرید فولاد" },
        { key: "C", label: "کربن (C)" },
        { key: "Si", label: "سیلیکون (Si)" },
        { key: "Mn", label: "منگنز (Mn)" },
        { key: "P", label: "فسفر (P)" },
        { key: "S", label: "گوگرد (S)" },
        { key: "Cr", label: "کروم (Cr)" },
        { key: "W", label: "تنگستن (W)" },
        { key: "Mo", label: "مولیبدنوم (Mo)" },
        { key: "V", label: "وانادیوم (V)" },
        { key: "Ni", label: "نیکل (Ni)" },
        { key: "Co", label: "کبالت (Co)" },
        { key: "مقاومت در برابر سایش", label: "مقاومت در برابر سایش" },
        { key: "مقاومت در برابر چسبندگی", label: "مقاومت در برابر چسبندگی" },
        { key: "چقرمگی", label: "چقرمگی" },
        { key: "پایداری ابعادی", label: "پایداری ابعادی" },
        { key: "مقاومت سایشی در دمای بالا", label: "مقاومت سایشی در دمای بالا" },
        { key: "چقرمگی در دمای بالا", label: "چقرمگی در دمای بالا" },
        { key: "سختی نهایی", label: "سختی نهایی" }
    ];

    // ---- Build fixed first column (labels) ----
    body.empty();
    properties.forEach(prop => {
        body.append(`<tr data-prop="${prop.key}"><td><strong>${prop.label}</strong></td></tr>`);
    });

    // ---- Popup + grade buttons ----
    const popup = $("#steel-popup");
    const steelButtonsContainer = $("#steel-buttons");

    steelButtonsContainer.empty();
    steels.forEach(steel => {
        const grade = steel["گرید فولاد"];
        steelButtonsContainer.append(
            `<button type="button" class="sc-steel-btn" data-grade="${grade}">${grade}</button>`
        );
    });

    // Open/close popup
    $("#open-popup").on("click", () => popup.show());
    $("#close-popup").on("click", () => popup.hide());

    // Toggle selection (max 10)
    steelButtonsContainer.on("click", ".sc-steel-btn", function () {
        const wasSelected = $(this).hasClass("selected");
        if (wasSelected) {
            $(this).removeClass("selected");
            return;
        }
        // If selecting new one, enforce limit
        const selectedCount = steelButtonsContainer.find(".sc-steel-btn.selected").length;
        if (selectedCount >= 10) {
            alert("حداکثر ۱۰ فولاد می‌توانید انتخاب کنید.");
            return;
        }
        $(this).addClass("selected");
    });

    // Compare button -> build table from currently selected buttons
    $("#compare-btn").on("click", function () {
        const selectedGrades = steelButtonsContainer
            .find(".sc-steel-btn.selected")
            .map((i, el) => $(el).data("grade"))
            .get();

        updateTable(selectedGrades);
        popup.hide();
    });

    // ---- Helpers ----
    function formatValue(value) {
        if (value === null || value === undefined || value === "") return "-";

        // Match range values like "1.2 - 3.4"
        if (typeof value === "string" && /^\s*\d*\.?\d+\s*-\s*\d*\.?\d+\s*$/.test(value)) {
            const [a, b] = value.split("-").map(s => s.trim());
            return `بین <strong>${a}</strong> تا <strong>${b}</strong> درصد`;
        }

        // Match single numbers (including decimals)
        if (!isNaN(value)) {
            return `<strong>${value}</strong>`;
        }

        return value;
    }


    function updateTable(selectedGrades) {
        // Clear previous steel columns (keep first column)
        header.find("th:gt(0)").remove();
        body.find("tr").each(function () {
            $(this).find("td:gt(0)").remove();
        });

        if (!selectedGrades || !selectedGrades.length) return;

        // For each selected grade, add a column
        selectedGrades.forEach(grade => {
            const steel = steels.find(s => String(s["گرید فولاد"]) === String(grade));
            if (!steel) return;

            // Column header
            header.append(`<th>${steel["گرید فولاد"]}</th>`);

            // Cells for each property row
            properties.forEach(prop => {
                const rawValue = steel[prop.key] ?? "-";   // ✅ use prop.key
                const value = formatValue(rawValue);
                body.find(`tr[data-prop="${prop.key}"]`).append(`<td>${value}</td>`); // ✅ use prop.key
            });
        });
    }

});
