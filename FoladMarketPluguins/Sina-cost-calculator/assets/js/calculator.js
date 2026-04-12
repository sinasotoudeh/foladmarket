
const PRODUCT_DENSITY = parseFloat(calcData?.density) || 7840;

// Function to lookup CostPerCm based on material, shape type, and the essential input
function lookupCostPerCm(material, shapeType, userSize) {
    // For "metal", we do not calculate a numeric value.
    if (material === 'metal') {
        return null;
    }
    let tableList = [];
    if (shapeType === 'roundbar') {
        if (material === 'steel' && calcData.costTable.steel && calcData.costTable.steel.roundbar) {
            tableList = calcData.costTable.steel.roundbar;
        } else if (material === 'iron' && calcData.costTable.iron && calcData.costTable.iron.roundbar) {
            tableList = calcData.costTable.iron.roundbar;
        }
    } else if (shapeType === 'rectangular') {
        if (material === 'steel' && calcData.costTable.steel && calcData.costTable.steel.rectangular) {
            tableList = calcData.costTable.steel.rectangular;
        } else if (material === 'iron' && calcData.costTable.iron && calcData.costTable.iron.rectangular) {
            tableList = calcData.costTable.iron.rectangular;
        }
    }
    for (let i = 0; i < tableList.length; i++) {
        let row = tableList[i];
        if (userSize >= row.min && userSize <= row.max) {
            return row.cost;
        }
    }
    return 0; // default if no matching row found
}

// Initialize shape selection (remains largely unchanged)
function selectShape(shape) {
    const roundBarFrame = document.getElementById('roundBarFrame');
    const rectangularBarFrame = document.getElementById('rectangularBarFrame');
    const dynamicInputs = document.getElementById("dynamicInputs");

    // Reset styles
    roundBarFrame.classList.remove('selected');
    rectangularBarFrame.classList.remove('selected');

    if (shape === 'roundBar') {
        roundBarFrame.classList.add('selected');
        dynamicInputs.innerHTML = `
            <div class="row two-column-row">
                <div class="input-group" title="قطر میلگرد را وارد کنید">
                    <label for="diameter">
                        <span class="icon">📏</span>
                        قطر (میلی‌متر):
                    </label>
                    <input type="number" id="diameter" placeholder="قطر میلگرد" oninput="calculateWeightAndPrice()">
                </div>
                <div class="input-group" title="طول میلگرد را وارد کنید">
                    <label for="length">
                        <span class="icon">📏</span>
                        طول (میلی‌متر):
                    </label>
                    <input type="number" id="length" placeholder="طول میلگرد" oninput="calculateWeightAndPrice()">
                </div>
            </div>
        `;
    } else if (shape === 'rectangularBar') {
        rectangularBarFrame.classList.add('selected');
        dynamicInputs.innerHTML = `
            <div class="row three-column-row">
                <div class="input-group" title="عرض تسمه را وارد کنید">
                    <label for="width">
                        <span class="icon">📏</span>
                        عرض (میلی‌متر):
                    </label>
                    <input type="number" id="width" placeholder="عرض" oninput="calculateWeightAndPrice()">
                </div>
                <div class="input-group" title="ضخامت تسمه را وارد کنید">
                    <label for="height">
                        <span class="icon">📏</span>
                        ضخامت (میلی‌متر):
                    </label>
                    <input type="number" id="height" placeholder="ضخامت" oninput="calculateWeightAndPrice()">
                </div>
                <div class="input-group" title="طول تسمه را وارد کنید">
                    <label for="length">
                        <span class="icon">📏</span>
                        طول (میلی‌متر):
                    </label>
                    <input type="number" id="length" placeholder="طول" oninput="calculateWeightAndPrice()">
                </div>
            </div>
        `;
    }
    calculateWeightAndPrice();
}

// Function to calculate weight, price, and cut price.
function calculateWeightAndPrice() {
    const shape = document.querySelector('.shape-frame.selected')?.id;
    const quantity = parseInt(document.getElementById("quantity")?.value, 10);
    const pricePerKg = parseFloat(document.getElementById("pricePerKg")?.value);
    
    // Retrieve length only for weight/price calculation.
    const length = parseFloat(document.getElementById("length")?.value);
    let weightPriceValid = true;
    if (!shape || isNaN(quantity) || quantity <= 0) {
        weightPriceValid = false;
    }
    if (isNaN(length) || length <= 0) {
        weightPriceValid = false;
    }
    
    // Calculate Weight and Price if inputs are valid.
    if (weightPriceValid) {
        let volume = 0;
        if (shape === 'roundBarFrame') {
            const diameter = parseFloat(document.getElementById("diameter")?.value);
            if (!isNaN(diameter) && diameter > 0) {
                const radius = diameter / 2 / 1000; // Convert diameter from mm to m
                volume = Math.PI * Math.pow(radius, 2) * (length / 1000);
            }
        } else if (shape === 'rectangularBarFrame') {
            const width = parseFloat(document.getElementById("width")?.value);
            const height = parseFloat(document.getElementById("height")?.value);
            if (!isNaN(width) && width > 0 && !isNaN(height) && height > 0) {
                volume = (width / 1000) * (height / 1000) * (length / 1000);
            }
        }
        const singleWeight = volume * PRODUCT_DENSITY;
        const totalWeight = singleWeight * quantity;
        document.getElementById("singleWeight").textContent = `هر عدد: ${singleWeight.toFixed(2)} کیلوگرم`;
        document.getElementById("totalWeight").textContent = `وزن کل: ${totalWeight.toFixed(2)} کیلوگرم`;
    
        if (!isNaN(pricePerKg) && pricePerKg > 0) {
            const singlePrice = singleWeight * pricePerKg;
            const totalPrice = totalWeight * pricePerKg;
            document.getElementById("singlePrice").textContent = `هر عدد: ${singlePrice.toLocaleString(undefined, { maximumFractionDigits: 0 })} تومان`;
            document.getElementById("totalPrice").textContent = `قیمت کل: ${totalPrice.toLocaleString(undefined, { maximumFractionDigits: 0 })} تومان`;
        } else {
            document.getElementById("singlePrice").textContent = 'هر عدد: -';
            document.getElementById("totalPrice").textContent = 'قیمت کل: -';
        }
    } else {
        document.getElementById("singleWeight").textContent = 'هر عدد: -';
        document.getElementById("totalWeight").textContent = 'وزن کل: -';
        document.getElementById("singlePrice").textContent = 'هر عدد: -';
        document.getElementById("totalPrice").textContent = 'قیمت کل: -';
    }
    
    // -------------------------------
    // Calculate Cut Price Independently (does not depend on length)
    // -------------------------------
    // اگر متریال metal باشد، از همان ابتدا پیام نمایش داده شود
    if (calcData.material === 'metal') {
        document.getElementById("singleCutPrice").textContent = 'برش این محصول از طریق هوابرش انجام می‌شود.';
        document.getElementById("totalCutPrice").style.display = 'none';
    } else {
        if (shape === 'roundBarFrame') {
            const diameter = parseFloat(document.getElementById("diameter")?.value);
            if (!isNaN(diameter) && diameter > 0) {
                const costPerCm = lookupCostPerCm(calcData.material, 'roundbar', diameter);
                const singleCutPrice = Math.pow((diameter / 20), 2) * Math.PI * costPerCm;
                const totalCutPrice = singleCutPrice * quantity;
                document.getElementById("singleCutPrice").textContent = `هر عدد: ${singleCutPrice.toLocaleString(undefined, { maximumFractionDigits: 0 })} تومان`;
                document.getElementById("totalCutPrice").textContent = `هزینه برش کل: ${totalCutPrice.toLocaleString(undefined, { maximumFractionDigits: 0 })} تومان`;
            } else {
                document.getElementById("singleCutPrice").textContent = 'هر عدد: -';
                document.getElementById("totalCutPrice").textContent = 'هزینه برش کل: -';
            }
        } else if (shape === 'rectangularBarFrame') {
            const width = parseFloat(document.getElementById("width")?.value);
            const height = parseFloat(document.getElementById("height")?.value);
            if (!isNaN(width) && width > 0 && !isNaN(height) && height > 0) {
                const costPerCm = lookupCostPerCm(calcData.material, 'rectangular', width);
                const singleCutPrice = (width * height * costPerCm) / 100;
                const totalCutPrice = singleCutPrice * quantity;
                document.getElementById("singleCutPrice").textContent = `هر عدد: ${singleCutPrice.toLocaleString(undefined, { maximumFractionDigits: 0 })} تومان`;
                document.getElementById("totalCutPrice").textContent = `هزینه برش کل: ${totalCutPrice.toLocaleString(undefined, { maximumFractionDigits: 0 })} تومان`;
            } else {
                document.getElementById("singleCutPrice").textContent = 'هر عدد: -';
                document.getElementById("totalCutPrice").textContent = 'هزینه برش کل: -';
            }
        }
    }
    
    // Add fade-in effect to the result frame
    document.querySelector('.result-frame').classList.add('fade-in-visible');
}


function displayError(message) {
    document.getElementById("singleWeight").textContent = message;
    document.getElementById("totalWeight").textContent = '';
    document.getElementById("singlePrice").textContent = 'قر عدد: -';
    document.getElementById("totalPrice").textContent = 'قیمت کل: -';
    document.getElementById("singleCutPrice").textContent = 'هر عدد: -';
    document.getElementById("totalCutPrice").textContent = 'هزینه برش کل: -';
}

// Set the default shape on page load
window.onload = () => selectShape('roundBar');



// ——— Dynamic mover for mobile ———
(function() {
  function mover() {
    const calcEl      = document.querySelector('.product-weight-calculator');
    const sidebarCol  = document.getElementById('calculator-sidebar');
    const placeholder = document.getElementById('calculator-placeholder');
    if (!calcEl || !sidebarCol || !placeholder) return;

    if (window.innerWidth < 768) {
      if (placeholder.firstElementChild !== calcEl) {
        placeholder.appendChild(calcEl);
      }
    } else {
      if (!sidebarCol.contains(calcEl)) {
        sidebarCol.appendChild(calcEl);
      }
    }
  }

  document.addEventListener('DOMContentLoaded', mover);
  window.addEventListener('resize', mover);
})();



