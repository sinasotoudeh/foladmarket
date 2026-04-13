<!-- grand-calculator-view.php (CORRECTED) -->
<div class="calculator-container">

  <!-- Wizard stages wrapper -->
  <div id="wizard">

    <!-- ---------- STAGE 1: انتخاب شکل و متریال ---------- -->
    <section id="stage-1" class="stage">
      <h2 class="stage-title">مرحله ۱ — انتخاب مقطع</h2>

      <div class="stage-content">
        <p style="color:#EEC92E;">ابتدا متریال (جنس فلز یا پلیمر) را انتخاب کنید، سپس مقطع را انتخاب نمایید.</p>
        
        <!-- material select -->
        <div style="margin-top:18px;">
          <label for="materialSelect" style="color:#fff">انتخاب متریال:</label>
          <select id="materialSelect" disabled>
            <option value="">ابتدا متریال (جنس فلز یا پلیمر) را انتخاب کنید</option>

            <optgroup label="آهن و فولاد">
              <option value="iron">آهن</option>
              <option value="coldworksteel">فولاد سردکار</option>
              <option value="hotworksteel">فولاد گرمکار</option>
              <option value="hss">فولاد تندبر</option>
              <option value="stainlesssteel">استنلس استیل</option>
            </optgroup>

            <optgroup label="فلزات رنگی">
              <option value="aluminum">آلومینیوم</option>
              <option value="copper">مس</option>
              <option value="brass">برنج</option>
              <option value="phosphorBronze">فسفربرنز</option>
            </optgroup>

            <optgroup label="پلیمرها">
              <option value="ptfe">تفلون PTFE</option>
              <option value="ptfec">تفلون کربن</option>
              <option value="pa">پلی آمید (نایلون)</option>
              <option value="pe">پلی‌اتیلن</option>
              <option value="abs">پلیمر ABS</option>
            </optgroup>
          </select>
        </div>

        <!-- Label برای shapes grid -->
        <div style="margin-top:18px;">
          <label style="color:#fff">انتخاب شکل مقطع:</label>
        </div>

        <!-- shapes grid -->
        <div id="shapeGrid" class="shape-images">
          <div class="shape-image" data-shape="rectangularBar" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/steel-beam-8.png" alt="ورق/تسمه">
            <div class="shape-label">ورق / تسمه</div>
          </div>
          <div class="shape-image" data-shape="roundBar" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/iron-bar-1.png" alt="میلگرد">
            <div class="shape-label">میلگرد</div>
          </div>
          <div class="shape-image" data-shape="roundTube" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/roundtube.png" alt="لوله">
            <div class="shape-label">لوله</div>
          </div>
          <div class="shape-image" data-shape="hexagonalBar" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/prism.png" alt="شش پر">
            <div class="shape-label">شش‌پر</div>
          </div>
          <div class="shape-image" data-shape="lProfiles" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/steel-beam-5.png" alt="نبشی">
            <div class="shape-label">نبشی (L)</div>
          </div>
          <div class="shape-image" data-shape="uProfiles" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/steel-beam-4.png" alt="ناودانی">
            <div class="shape-label">ناودانی (U)</div>
          </div>
          <div class="shape-image" data-shape="tProfiles" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/steel-beam-6.png" alt="سه پر">
            <div class="shape-label">سه پر (T)</div>
          </div>
          <div class="shape-image" data-shape="rectangularTube" tabindex="0" role="button" aria-pressed="false">
            <img src="https://steelcenteriran.com/wp-content/uploads/2025/10/steel-beam-7.png" alt="قوطی">
            <div class="shape-label">قوطی</div>
          </div>
        </div>

        <!-- hint / applied density display -->
        <div id="stage1Applied" style="margin-top:10px;color:#EEC92E; display:none;"></div>

        <!-- next button -->
        <div style="margin-top:16px; display:flex; gap:8px;">
          <button id="stage1Next" class="btn" disabled>وارد کردن ابعاد ◀</button>
        </div>
      </div>
    </section>

    <!-- ---------- STAGE 2 & 3 wrapper ---------- -->
    <div id="stage2and3Wrapper">

      <!-- STAGE 2 -->
      <section id="stage-2" class="stage" style="display:none;">
        <h2 class="stage-title">مرحله ۲ — وارد کردن ابعاد</h2>

        <div class="stage-content" style="display:flex; gap:12px; flex-direction:column;">
          <!-- PREVIEW WRAPPER -->
          <div id="stage2PreviewWrap" class="stage2-preview-wrap" style="display:flex; gap:12px; align-items:flex-start;">

            <!-- تصویر جزئیات شکل -->
            <div id="selectedShapePreview" style="flex:1; max-width:100%; margin-bottom:10px; display:flex; align-items:center; justify-content:center;">
              <!-- تصویر داخل این DIV قرار می‌گیرد -->
            </div>

            <!-- باکس اطلاعات -->
            <aside id="stage2InfoBox" class="stage2-info-box" aria-live="polite" style="flex:0 0 0px; min-width:200px;">
              <button id="stage2BackBox" class="stage2-back-btn btn" type="button" style="background:#EEC92E; color:#000; border: none;">
                ▶ تغییر شکل و متریال
              </button>

              <div class="info-content" style="margin-top:12px;">
                <div class="info-row"><strong>شکل:</strong> —</div>
                <div class="info-row"><strong>متریال:</strong> —</div>
                <div class="info-row"><strong>چگالی:</strong> —</div>
              </div>
            </aside>
          </div>

          <!-- inputs container -->
          <div id="shapeInputsContainer"></div>

          <div style="display:flex; gap:8px; margin-top:12px;">
            <button id="stage2Back" class="btn stage2-back-btn">▶ تغییر شکل و متریال</button>
            <button id="stage2Calculate" class="btn primary">محاسبه وزن ◀</button>
          </div>
        </div>
      </section>

      <!-- STAGE 3 -->
      <section id="stage-3" class="stage" style="display:none;">
        <h2 class="stage-title">مرحله ۳ — نتایج</h2>

        <div class="stage-content">
          <!-- Weight results box -->
          <div id="resultWeightBox" class="result-weight-box">
            <p id="singleWeight">وزن یک عدد: </p>
            <p id="totalWeight">وزن کل: </p>
          </div>

          <div id="resultPriceBox" class="result-price-box" style="margin-top:16px;">
            <p id="wcPriceNotice" style="color:#EEC92E; display:none;">
              برای اطلاع از قیمت این محصول لطفاً <a href="https://steelcenteriran.com/contact-us/">با ما تماس بگیرید.</a>
            </p>

            <p id="wcStandardLabel" style="color:#EEC92E; display:none; margin-top:8px;">
              انتخاب گرید مورد نظر برای محاسبه قیمت:
            </p>
            <select id="wcStandardSelect" style="display:none; margin-top:8px; padding:6px 8px;">
            </select>

            <p id="wcSinglePrice" style="margin-top:10px; color:#fff;">قیمت یک عدد: —</p>
            <p id="wcTotalPrice" style="margin-top:4px; color:#fff;">قیمت کل: —</p>
          </div>

          <div style="display:flex; gap:8px; margin-top:12px;">
            <button id="stage3Back" class="btn">▶ تغییر ابعاد</button>
            <button id="stage3Restart" class="btn">شروع مجدد</button>
          </div>
        </div>
      </section>

    </div> <!-- end stage2and3Wrapper -->

  </div> <!-- end wizard -->
</div> <!-- end calculator-container -->
