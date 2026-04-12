// grand-calculator.js (UPDATED: desktop single-stage UI, material-first flow + optimized price fetch + alloy naming)
document.addEventListener('DOMContentLoaded', function () {
  /**********************
   * Config & state
   **********************/
  const productPriceCache = {};

  const densities = {
    iron: 7860,
    coldworksteel: 7810,
    hotworksteel: 7750,
    hss: 8150,
    stainlesssteel: 7930,
    phosphorBronze: 8858,
    aluminum: 2705,
    copper: 8944,
    brass: 8587,
    ptfe: 2200,
    ptfec: 2150,
    pa: 1200,
    pe: 1000,
    abs: 1100,
  };

  const shapeDetailedImages = {
    strip: "https://foladmarket.com/wp-content/uploads/2024/06/strip_detailed.png",
    sheet: "https://foladmarket.com/wp-content/uploads/2024/06/sheet_detailed.png",
    hexagonalBar: "https://foladmarket.com/wp-content/uploads/2024/06/hexagonalBar_detailed.png",
    lProfiles: "https://foladmarket.com/wp-content/uploads/2024/06/lProfiles_detailed.jpg",
    tProfiles: "https://foladmarket.com/wp-content/uploads/2024/06/tProfiles_detailed.png",
    uProfiles: "https://foladmarket.com/wp-content/uploads/2024/06/uProfiles_detailed.png",
    rectangularTube: "https://foladmarket.com/wp-content/uploads/2024/06/rectangularTube_detailed.png",
    squareTube: "https://foladmarket.com/wp-content/uploads/2024/06/squaretube_detailed.png",
    roundTube: "https://foladmarket.com/wp-content/uploads/2024/06/roundtube_detailed.png",
    rectangularBar: "https://foladmarket.com/wp-content/uploads/2024/06/rectangularbar_detailed.png",
    squareBar: "https://foladmarket.com/wp-content/uploads/2024/06/squarebar_detailed.png",
    roundBar: "https://foladmarket.com/wp-content/uploads/2024/06/roundbar_detailed.png"
  };

  const materialPersian = {
    iron: 'آهن',
    coldworksteel: 'فولاد سردکار',
    hotworksteel: 'فولاد گرمکار',
    hss: 'فولاد تندبر',
    stainlesssteel: 'استنلس استیل',
    phosphorBronze: 'فسفربرنز',
    aluminum: 'آلومینیوم',
    copper: 'مس',
    brass: 'برنج',
    ptfe: 'تفلون PTFE',
    ptfec: 'تفلون کربن',
    pa: 'پلی آمید',
    pe: 'پلی اتیلن',
    abs: 'پلیمر ABS',
  };

  const materialShapesMap = {
    iron: ['rectangularBar', 'roundBar', 'roundTube', 'rectangularTube', 'lProfiles', 'uProfiles', 'tProfiles', 'hexagonalBar'],
    coldworksteel: ['rectangularBar', 'roundBar'],
    hotworksteel: ['rectangularBar', 'roundBar'],
    hss: ['rectangularBar', 'roundBar'],
    stainlesssteel: ['rectangularBar', 'roundBar', 'roundTube', 'rectangularTube', 'lProfiles', 'uProfiles', 'hexagonalBar'],
    aluminum: ['rectangularBar', 'roundBar', 'roundTube', 'rectangularTube', 'lProfiles', 'uProfiles', 'hexagonalBar'],
    copper: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    brass: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    phosphorBronze: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    ptfe: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    ptfec: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    pa: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    pe: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar'],
    abs: ['roundBar', 'roundTube', 'rectangularBar', 'hexagonalBar']
  };

  const productMap = {
    'iron|hexagonalBar': [],
    'iron|lProfiles': [],
    'iron|tProfiles': [],
    'iron|uProfiles': [],
    'iron|rectangularTube': [],
    'iron|roundTube': [],
    'iron|rectangularBar': [],
    'iron|roundBar': [],

    'coldworksteel|rectangularBar': [156, 170, 172, 26, 168, 162, 166, 154, 164, 158, 160],
    'coldworksteel|roundBar': [156, 170, 172, 26, 168, 162, 166, 154, 164, 158, 160],

    'hotworksteel|rectangularBar': [35],
    'hotworksteel|roundBar': [35],

    'hss|rectangularBar': [33, 34, 177, 179],
    'hss|roundBar': [33, 34, 177, 179],

    'stainlesssteel|roundTube': [], 'stainlesssteel|rectangularBar': [], 'stainlesssteel|roundBar': [],
    'phosphorBronze|hexagonalBar': [], 'phosphorBronze|roundTube': [], 'phosphorBronze|rectangularBar': [], 'phosphorBronze|roundBar': [],
    'aluminum|hexagonalBar': [], 'aluminum|roundTube': [], 'aluminum|rectangularBar': [], 'aluminum|roundBar': [],
    'copper|hexagonalBar': [], 'copper|roundTube': [], 'copper|rectangularBar': [], 'copper|roundBar': [],
    'brass|hexagonalBar': [], 'brass|roundTube': [], 'brass|rectangularBar': [], 'brass|roundBar': [],
    'ptfe|hexagonalBar': [], 'ptfe|roundTube': [], 'ptfe|rectangularBar': [], 'ptfe|roundBar': [],
    'ptfec|hexagonalBar': [], 'ptfec|roundTube': [], 'ptfec|rectangularBar': [], 'ptfec|roundBar': [],
    'pa|hexagonalBar': [], 'pa|roundTube': [], 'pa|rectangularBar': [], 'pa|roundBar': [],
    'pe|hexagonalBar': [], 'pe|roundTube': [], 'pe|rectangularBar': [], 'pe|roundBar': [],
    'abs|hexagonalBar': [], 'abs|roundTube': [], 'abs|rectangularBar': [], 'abs|roundBar': [],
  };

  /**********************
   * قیمت از ووکامرس
   **********************/
  async function fetchWCProductPrice(productId) {
    // استفاده از کش درون حافظه
    if (productPriceCache[productId]) {
      return { id: productId, price: productPriceCache[productId].price, name: productPriceCache[productId].name };
    }

    try {
      const res = await fetch(`/wp-admin/admin-ajax.php?action=get_wc_product_price&product_id=${productId}`, { cache: "no-store" });
      if (!res.ok) return { id: productId, price: 0, name: '' };
      const data = await res.json();
      if (!data || !data.success || !data.data) return { id: productId, price: 0, name: '' };

      // فرض: price برگشتی به ریال است؛ تبدیل به تومان:
      const rawPrice = parseFloat(data.data.price || 0);
      const priceInToman = rawPrice > 0 ? rawPrice / 10 : 0;

      const name = data.data.name || '';
      // ذخیره در کش
      productPriceCache[productId] = { price: priceInToman, name };

      return { id: productId, price: priceInToman, name };
    } catch (err) {
      console.error("fetchWCProductPrice error", err);
      return { id: productId, price: 0, name: '' };
    }
  }


  async function updateWCPriceFromSelection() {
    const key = `${selectedMaterial}|${selectedShape}`;
    if (!selectedMaterial || !selectedShape) return;
    const ids = productMap[key] || [];

    const wcPriceNotice = document.getElementById('wcPriceNotice');
    const wcStandardSelect = document.getElementById('wcStandardSelect');
    const wcSinglePrice = document.getElementById('wcSinglePrice');
    const wcTotalPrice = document.getElementById('wcTotalPrice');
    const wcStandardLabel = document.getElementById('wcStandardLabel');


    if (!wcPriceNotice || !wcStandardSelect) return;

    // پاکسازی UI
    wcStandardSelect.innerHTML = '';
    wcStandardSelect.onchange = null;
    if (wcSinglePrice) wcSinglePrice.textContent = '—';
    if (wcTotalPrice) wcTotalPrice.textContent = '—';

    if (!ids.length) {
      // هیچ محصولی وجود ندارد -> پیام تماس
      wcPriceNotice.style.display = 'block';
      wcStandardSelect.style.display = 'none';
      if (wcStandardLabel) wcStandardLabel.style.display = 'none';
      if (wcSinglePrice) wcSinglePrice.textContent = '—';
      if (wcTotalPrice) wcTotalPrice.textContent = '—';
      return;
    }

    wcPriceNotice.style.display = 'none';

    // واکشی همزمان؛ هر id => استفاده از کش یا fetch
    const fetchPromises = ids.map(id => fetchWCProductPrice(id).then(res => ({ id, ...res })));
    const results = await Promise.all(fetchPromises);

    // فقط نتایج معتبر (price > 0)
    const valid = results.filter(r => r && r.price > 0);

    if (!valid.length) {
      // هیچ کدام معتبر نبود -> پیام تماس
      wcPriceNotice.style.display = 'block';
      wcStandardSelect.style.display = 'none';
      if (wcStandardLabel) wcStandardLabel.style.display = 'none';
      if (wcSinglePrice) wcSinglePrice.textContent = '—';
      if (wcTotalPrice) wcTotalPrice.textContent = '—';
      return;
    }

    // build select always (حتی اگر فقط یک گزینه باشد)
    valid.forEach(({ price, name, id }) => {
      const opt = document.createElement('option');
      // مقدار گزینه: قیمت (تومان بر کیلو)
      opt.value = price;
      opt.dataset.productId = id;

      // استخراج آلیاژ از نام در صورت وجود (مثال: "1.2080")
      let alloy = '';
      if (name) {
        const m = name.match(/(\d+\.\d{3,4})/);
        alloy = m ? m[1] : '';
      }

      const label = name ? name : (alloy ? `آلیاژ ${alloy}` : `استاندارد ${id}`);
      opt.textContent = `${label} — ${Math.floor(price).toLocaleString('fa-IR')} تومان/کیلو`;

      wcStandardSelect.appendChild(opt);
    });

    // نمایش select (حتی اگر یک گزینه)
    wcStandardSelect.style.display = '';
    if (wcStandardLabel) wcStandardLabel.style.display = 'block';
    wcStandardSelect.onchange = () => {
      const selectedPrice = parseFloat(wcStandardSelect.value) || 0;
      calculateAndDisplayWCPrices(selectedPrice);
    };

    // پیش‌انتخاب گزینه اول و تریگر محاسبه
    wcStandardSelect.selectedIndex = 0;
    wcStandardSelect.dispatchEvent(new Event('change'));
  }



  function calculateAndDisplayWCPrices(pricePerKg) {
    const singleKg = parseFloat(singleWeightEl.dataset.kg) || 0;
    const quantityEl = document.getElementById('quantity');
    const q = quantityEl ? parseInt(quantityEl.value, 10) || 1 : 1;
    const totalKg = singleKg * q;

    const singlePrice = Math.floor(singleKg * pricePerKg);
    const totalPrice = Math.floor(totalKg * pricePerKg);

    const wcSinglePriceEl = document.getElementById('wcSinglePrice');
    const wcTotalPriceEl = document.getElementById('wcTotalPrice');
    if (wcSinglePriceEl) wcSinglePriceEl.textContent = `قیمت یک عدد: ${singlePrice.toLocaleString('fa-IR')} تومان`;
    if (wcTotalPriceEl) {
      if (q > 1) wcTotalPriceEl.textContent = `قیمت ${q} عدد: ${totalPrice.toLocaleString('fa-IR')} تومان`;
      else wcTotalPriceEl.textContent = '—';
    }
  }


  /**********************
   * سایر بخش‌ها
   **********************/
  /* مابقی کد بدون هیچ تغییری نسبت به نسخه کاربر */


  // helper: are we in desktop layout?
  function isDesktop() {
    return window.matchMedia('(min-width: 768px)').matches;
  }

  // wizard state
  let currentStage = 1;
  let selectedShape = null;    // final shape key used by updateShapeInputs (eg. 'rectangularBar','roundBar','roundTube','hexagonalBar','lProfiles'...)
  let chosenStage1Shape = null; // temporary id from stage1 (eg. 'profile' or 'rectangularBar' etc.)
  let selectedMaterial = null;  // eg. 'steel'
  let lastDimensionsSaved = {}; // to persist dimensions between stage switches

  /**********************
   * DOM refs
   **********************/
  const stage1 = document.getElementById('stage-1');
  const stage2 = document.getElementById('stage-2');
  const stage3 = document.getElementById('stage-3');

  const shapeGrid = document.getElementById('shapeGrid');
  // const profileSubtypes = document.getElementById('profileSubtypes');
  const materialSelect = document.getElementById('materialSelect');
  const stage1NextBtn = document.getElementById('stage1Next');
  const stage1Applied = document.getElementById('stage1Applied');

  const shapeInputsContainer = document.getElementById('shapeInputsContainer');
  const selectedShapePreview = document.getElementById('selectedShapePreview');
  const stage2Back = document.getElementById('stage2Back');
  const stage2Calculate = document.getElementById('stage2Calculate');

  const singleWeightEl = document.getElementById('singleWeight');
  const totalWeightEl = document.getElementById('totalWeight');
  const singlePriceEl = document.getElementById('singlePrice');
  const totalPriceEl = document.getElementById('totalPrice');
  const finalPricePerKg = document.getElementById('finalPricePerKg');
  const stage3Back = document.getElementById('stage3Back');
  const stage3Restart = document.getElementById('stage3Restart');

  const stage23Wrapper = document.getElementById('stage2and3Wrapper');

  /**********************
   * helper: disable / enable UI blocks (visual + a11y)
   **********************/
  function setDisabled(el, flag) {
    if (!el) return;
    if (flag) {
      el.classList.add('disabled-stage');
      el.setAttribute('aria-disabled', 'true');
      // make interactive children non-focusable; save prev tabindex
      const interactive = el.querySelectorAll('a, button, input, select, textarea, [tabindex]');
      interactive.forEach(n => {
        if (n.getAttribute && n.getAttribute('tabindex') !== null) n.dataset._prevTabindex = n.getAttribute('tabindex');
        else n.dataset._prevTabindex = '';
        try { n.setAttribute('tabindex', '-1'); } catch (e) { }
      });
    } else {
      el.classList.remove('disabled-stage');
      el.setAttribute('aria-disabled', 'false');
      const interactive = el.querySelectorAll('a, button, input, select, textarea, [tabindex]');
      interactive.forEach(n => {
        if (n.dataset && n.dataset._prevTabindex !== undefined) {
          if (n.dataset._prevTabindex === '') n.removeAttribute('tabindex');
          else n.setAttribute('tabindex', n.dataset._prevTabindex);
          delete n.dataset._prevTabindex;
        } else {
          // if no saved value, remove forced tabindex
          if (n.getAttribute('tabindex') === '-1') n.removeAttribute('tabindex');
        }
      });
    }
  }

  // persist preferences to localStorage
  function persistPrefLocal(shapeKey, materialKey) {
    try {
      const payload = { shape: shapeKey, material: materialKey, ts: Date.now() };
      localStorage.setItem('grandCalc_pref', JSON.stringify(payload));
    } catch (e) {
      // ignore quota/errors
      console.warn('Could not persist grandCalc_pref', e);
    }
  }

  function setStage2And3Disabled(flag) {
    // only enforce this visual disabled on desktop (mobile uses separate flow)
    if (!stage23Wrapper) return;
    if (!isDesktop()) {
      // on mobile don't enforce overlay
      setDisabled(stage23Wrapper, false);
      return;
    }
    setDisabled(stage23Wrapper, flag);
  }
  function updateShapeGridForMaterial(materialKey) {
    if (!shapeGrid) return;
    const available = materialShapesMap[materialKey] || [];
    document.querySelectorAll('#shapeGrid .shape-image').forEach(tile => {
      const key = tile.dataset.shape;
      if (available.includes(key)) {
        tile.classList.remove('inactive-shape');
        tile.setAttribute('aria-disabled', 'false');
      } else {
        tile.classList.add('inactive-shape');
        tile.setAttribute('aria-disabled', 'true');
      }
    });
  }

  /**********************
   * UX: tooltip/inline helpers (existing logic reused)
   **********************/
  // اگر کاربر روی خودِ تول‌تیپ کلیک کند یا درون کانتینر کلیک کند و هدف
  // یک تول‌تیپ باشد، آن را پاک کن — فِیلبک برای اطمینان
  if (shapeInputsContainer) {
    shapeInputsContainer.addEventListener('click', function (e) {
      const tt = e.target.closest('.input-tooltip');
      if (tt) {
        tt.remove();
        return;
      }
      const targetInput = e.target.closest('input, select');
      if (targetInput) {
        removeTooltip(targetInput);
        removeTooltip(document.getElementById('innerDiameter'));
        removeTooltip(document.getElementById('outerDiameter'));
        removeError(targetInput);
      }
    });

    shapeInputsContainer.addEventListener('focusin', function (e) {
      const tt = e.target.closest('.input-tooltip');
      if (tt) tt.remove();
      const targetInput = e.target.closest('input, select');
      if (targetInput) {
        removeTooltip(targetInput);
        removeTooltip(document.getElementById('innerDiameter'));
        removeTooltip(document.getElementById('outerDiameter'));
        removeError(targetInput);
      }
    });

    shapeInputsContainer.addEventListener('mousedown', function (e) {
      const targetInput = e.target.closest('input, select');
      if (targetInput) {
        removeTooltip(targetInput);
        removeTooltip(document.getElementById('innerDiameter'));
        removeTooltip(document.getElementById('outerDiameter'));
      }
    }, { passive: true });
  }

  /**********************
   * Stage management (show/hide)
   **********************/
  function showStage(n) {
    currentStage = n;
    const s1 = document.getElementById('stage-1');
    const s2 = document.getElementById('stage-2');
    const s3 = document.getElementById('stage-3');

    if (isDesktop()) {
      // Desktop: show all three stages simultaneously
      if (s1) s1.style.display = '';
      if (s2) s2.style.display = '';
      if (s3) s3.style.display = '';
    } else {
      // Mobile: keep existing wizard behavior
      if (n === 1) {
        if (s1) s1.style.display = '';
        if (s2) s2.style.display = 'none';
        if (s3) s3.style.display = 'none';
      } else if (n === 2) {
        if (s1) s1.style.display = 'none';
        if (s2) s2.style.display = '';
        if (s3) s3.style.display = 'none';
        if (s2 && selectedShape) {
          updateShapeInputs(selectedShape);
          const imgUrl = shapeDetailedImages[selectedShape] || '';
          selectedShapePreview.innerHTML = imgUrl ? `<img src="${imgUrl}" style="max-width:100%;">` : '';
          restoreSavedDimensions();
          if (typeof updateStage2Info === 'function') updateStage2Info();
          if (isDesktop()) calculateWeight();
        }
      } else if (n === 3) {
        if (s1) s1.style.display = 'none';
        if (s2) s2.style.display = 'none';
        if (s3) s3.style.display = '';
      }
    }

    // After layout adjustments, ensure stage2/3 disabled state on desktop is correct
    if (isDesktop()) {
      if (selectedMaterial && selectedShape) {
        setStage2And3Disabled(false);
        // prepare inputs if not done
        if (selectedShape) {
          updateShapeInputs(selectedShape);
          const imgUrl = shapeDetailedImages[selectedShape] || '';
          selectedShapePreview.innerHTML = imgUrl ? `<img src="${imgUrl}" style="max-width:100%;">` : '';
          restoreSavedDimensions();
          if (typeof updateStage2Info === 'function') updateStage2Info();
          calculateWeight();
        }
      } else {
        setStage2And3Disabled(true);
      }
    }
  }

  /**********************
   * Stage1: shape selection (but material-first enforced)
   **********************/
  if (shapeGrid) {
    shapeGrid.addEventListener('click', function (e) {
      if (shapeGrid.classList.contains('disabled-stage')) {
        displayError('ابتدا لطفاً جنس (متریال) را انتخاب کنید.', 'materialSelect');
        return;
      }
      const tile = e.target.closest('.shape-image');
      if (!tile) return;
      handleStage1ShapeClick(tile.dataset.shape);
    });
    shapeGrid.addEventListener('keydown', function (e) {
      if (shapeGrid.classList.contains('disabled-stage')) {
        return;
      }
      if (e.key === 'Enter' || e.key === ' ') {
        const tile = e.target.closest('.shape-image');
        if (!tile) return;
        e.preventDefault();
        handleStage1ShapeClick(tile.dataset.shape);
      }
    });
  }

  // if (profileSubtypes) {
  //   profileSubtypes.addEventListener('click', function (e) {
  //     if (profileSubtypes.classList.contains('disabled-stage')) {
  //       displayError('ابتدا لطفاً جنس (متریال) را انتخاب کنید.', 'materialSelect');
  //       return;
  //     }
  //     const tile = e.target.closest('.shape-image');
  //     if (!tile) return;
  //     handleStage1ShapeClick(tile.dataset.shape);
  //   });
  //   profileSubtypes.addEventListener('keydown', function (e) {
  //     if (profileSubtypes.classList.contains('disabled-stage')) {
  //       return;
  //     }
  //     if (e.key === 'Enter' || e.key === ' ') {
  //       const tile = e.target.closest('.shape-image');
  //       if (!tile) return;
  //       e.preventDefault();
  //       handleStage1ShapeClick(tile.dataset.shape);
  //     }
  //   });
  // }

  function clearShapeSelectionUI() {
    document.querySelectorAll('#shapeGrid .shape-image').forEach(el => el.classList.remove('selected'));
    //   document.querySelectorAll('#profileSubtypes .shape-image').forEach(el => el.classList.remove('selected'));
    //   if (profileSubtypes) profileSubtypes.style.display = 'none';
  }

  function handleStage1ShapeClick(shapeId) {
    // if material not chosen, block selection
    if (!selectedMaterial) {
      if (materialSelect) {
        materialSelect.focus();
      }
      displayError('لطفاً ابتدا جنس (متریال) انتخاب شود.', 'materialSelect');
      return;
    }
    // prevent selecting inactive shape (visually disabled)
    const tileCheck = document.querySelector(`#shapeGrid .shape-image[data-shape="${shapeId}"]`);
    if (tileCheck && tileCheck.classList.contains('inactive-shape')) {
      displayError('این شکل برای جنس انتخابی در دسترس نیست.', 'shapeGrid');
      return;
    }

    clearShapeSelectionUI();

    // if (shapeId === 'profile') {
    //   const mainTile = document.querySelector(`#shapeGrid .shape-image[data-shape="profile"]`);
    //   if (mainTile) mainTile.classList.add('selected');
    //   if (profileSubtypes) profileSubtypes.style.display = '';
    //   chosenStage1Shape = 'profile';
    //   selectedShape = null;
    //   if (stage1Applied) stage1Applied.style.display = 'none';
    //   if (stage1NextBtn) stage1NextBtn.disabled = true;
    //   return;
    // }

    // const clickedFromProfile = profileSubtypes && profileSubtypes.contains(document.querySelector(`.shape-image[data-shape="${shapeId}"]`));
    // if (clickedFromProfile) {
    //   const subtypeTile = document.querySelector(`#profileSubtypes .shape-image[data-shape="${shapeId}"]`);
    //   if (subtypeTile) subtypeTile.classList.add('selected');
    //   const mainTile = document.querySelector(`#shapeGrid .shape-image[data-shape="profile"]`);
    //   if (mainTile) mainTile.classList.add('selected');
    //   selectedShape = shapeId;
    // } else {
    const tile = document.querySelector(`#shapeGrid .shape-image[data-shape="${shapeId}"]`);
    if (tile) tile.classList.add('selected');
    selectedShape = shapeId;
    // }

    if (stage1Applied) stage1Applied.style.display = 'block';

    if (selectedMaterial && densities[selectedMaterial]) {
      stage1Applied.innerText = `شکل: ${getShapeLabel(selectedShape)} — متریال: ${materialPersian[selectedMaterial]} — چگالی: ${densities[selectedMaterial]} کیلوگرم بر متر مکعب`;
    } else {
      stage1Applied.innerText = `شکل: ${getShapeLabel(selectedShape)} — لطفاً جنس را انتخاب کنید.`;
    }

    // mobile: enable Next button only when both selected
    if (!isDesktop() && stage1NextBtn) stage1NextBtn.disabled = !(selectedShape && selectedMaterial);

    // Desktop: if both material and shape chosen, enable stage2/3 and prepare inputs automatically
    // ... کد اصلی بدون تغییر تا قبل از پایان تابع ...
    if (isDesktop() && selectedMaterial && selectedShape) {
      setStage2And3Disabled(false);
      updateShapeInputs(selectedShape);
      const imgUrl = shapeDetailedImages[selectedShape] || '';
      selectedShapePreview.innerHTML = imgUrl ? `<img src="${imgUrl}" style="max-width:100%;">` : '';
      restoreSavedDimensions();
      if (typeof updateStage2Info === 'function') updateStage2Info();
      calculateWeight();
      updateWCPriceFromSelection(); // ← اضافه شد
      if (selectedMaterial && selectedShape) {
        persistPrefLocal(selectedShape, selectedMaterial);
      }
    }
  }

  function getShapeLabel(shapeKey) {
    const map = {
      rectangularBar: 'ورق/تسمه',
      roundBar: 'میلگرد',
      roundTube: 'لوله',
      hexagonalBar: 'شش‌پر',
      lProfiles: 'نبشی',
      uProfiles: 'ناودانی',
      tProfiles: 'سه‌پر',
      rectangularTube: 'قوطی',
      profile: 'پروفیل'
    };
    return map[shapeKey] || shapeKey;
  }

  if (materialSelect) {
    materialSelect.disabled = false;
    materialSelect.addEventListener('change', function () {
      const key = this.value;
      selectedMaterial = key || null;

      if (selectedMaterial && densities[selectedMaterial]) {
        if (stage1Applied) {
          stage1Applied.style.display = 'block';
          stage1Applied.innerText = `شکل: ${getShapeLabel(selectedShape) || '—'} — متریال: ${materialPersian[selectedMaterial]} — چگالی: ${densities[selectedMaterial]} کیلوگرم بر متر مکعب`;
        }
      } else {
        if (stage1Applied) stage1Applied.innerText = `شکل: ${getShapeLabel(selectedShape) || '—'} — لطفاً جنس را انتخاب کنید.`;
      }

      if (selectedMaterial) {
        setDisabled(shapeGrid, false);
      } else {
        setDisabled(shapeGrid, true);
        selectedShape = null;
        clearShapeSelectionUI();
      }

      updateShapeGridForMaterial(selectedMaterial);

      if (isDesktop() && selectedMaterial && selectedShape) {
        setStage2And3Disabled(false);
        persistPrefLocal(selectedShape, selectedMaterial);
        updateShapeInputs(selectedShape);
        const imgUrl = shapeDetailedImages[selectedShape] || '';
        selectedShapePreview.innerHTML = imgUrl ? `<img src="${imgUrl}" alt="${getShapeLabel(selectedShape)}" style="max-width:100%;">` : '';
        restoreSavedDimensions();
        if (typeof updateStage2Info === 'function') updateStage2Info();
        calculateWeight();
        updateWCPriceFromSelection();
      } else {
        if (isDesktop()) setStage2And3Disabled(true);
      }

      if (!isDesktop() && stage1NextBtn) stage1NextBtn.disabled = !(selectedShape && selectedMaterial);

      if (currentStage === 2 && typeof updateStage2Info === 'function') updateStage2Info();
    });
  }

  if (stage1NextBtn) {
    // keep Next behavior only for mobile
    stage1NextBtn.addEventListener('click', function () {
      if (isDesktop()) {
        // hidden on desktop; ignore
        return;
      }
      if (!selectedShape) {
        alert('لطفاً ابتدا یک شکل انتخاب کنید.');
        return;
      }
      if (!selectedMaterial) {
        alert('لطفاً ابتدا جنس را انتخاب کنید.');
        return;
      }
      persistPrefLocal(selectedShape, selectedMaterial);


      persistPrefLocal(selectedShape, selectedMaterial);

      window.selectedMetal = selectedMaterial;
      persistPrefLocal(selectedShape, selectedMaterial);

      showStage(2);
    });
  }

  /**********************
   * Update stage2 info box
   **********************/
  function updateStage2Info() {
    const box = document.getElementById('stage2InfoBox');
    if (!box) return;
    const content = box.querySelector('.info-content');
    if (!content) return;

    const shapeLabel = selectedShape ? getShapeLabel(selectedShape) : '—';
    const materialKey = selectedMaterial || null;
    const materialLabel = materialKey ? (materialPersian[materialKey] || materialKey) : '—';
    const densityVal = (materialKey && densities[materialKey]) ? `${densities[materialKey]} کیلوگرم بر متر مکعب` : '—';

    content.innerHTML = `
    <div class="info-row"><strong>شکل:</strong> ${shapeLabel}</div>
    <div class="info-row"><strong>متریال:</strong> ${materialLabel}</div>
    <div class="info-row"><strong>چگالی:</strong> ${densityVal}</div>
  `;

    if (typeof bindStage2BackButtons === 'function') bindStage2BackButtons();
  }

  // bind both stage2 back buttons (the bottom one and the one inside info-box)
  function bindStage2BackButtons() {
    const backButtons = document.querySelectorAll('.stage2-back-btn');
    backButtons.forEach(btn => {
      if (!btn.dataset.bound) {
        btn.addEventListener('click', function (e) {
          try { saveCurrentDimensions(); } catch (err) { /* ignore */ }
          showStage(1);
        });
        btn.dataset.bound = '1';
      }
    });
  }

  /**********************
   * STAGE2: populate inputs & convert units
   **********************/
  function updateShapeInputs(shapeKey) {
    const container = document.getElementById("shapeInputsContainer");
    if (!container) return;

    // save existing values
    const oldInputs = container.querySelectorAll('input');
    const savedValues = {};
    oldInputs.forEach(inp => { savedValues[inp.id] = inp.value; });

    container.innerHTML = '';

    const shapeDimensions = {
      strip: ['length', 'width', 'thickness'],
      sheet: ['length', 'width', 'thickness'],
      rectangularBar: ['width', 'thickness', 'length'],
      hexagonalBar: ['acrossFlats', 'length'],
      lProfiles: ['height', 'width', 'thickness', 'length'],
      tProfiles: ['height', 'width', 'thickness', 'length'],
      uProfiles: ['height', 'width', 'thickness', 'length'],
      rectangularTube: ['height', 'width', 'thickness', 'length'],
      squareTube: ['width', 'thickness', 'length'],
      roundTube: ['outerDiameter', 'innerDiameter', 'length'],
      squareBar: ['width', 'length'],
      roundBar: ['diameter', 'length']
    };

    const dims = shapeDimensions[shapeKey] || [];

    dims.forEach(dimension => {
      const label = document.createElement('label');
      label.htmlFor = dimension;
      label.textContent = getDimensionLabel(dimension);
      container.appendChild(label);

      const wrapper = document.createElement('div');
      wrapper.style.display = 'flex';
      wrapper.style.gap = '6px';
      wrapper.style.alignItems = 'center';
      wrapper.style.position = 'relative';

      const input = document.createElement('input');
      input.type = 'text';
      input.id = dimension;
      input.placeholder = `مقدار ${getDimensionLabel(dimension)}`;
      input.min = 0;
      input.step = 'any';
      input.setAttribute('inputmode', 'decimal');
      input.setAttribute('aria-label', getDimensionLabel(dimension));
      if (savedValues[dimension]) input.value = savedValues[dimension];

      // validate + trigger live calculate on desktop
      input.addEventListener('input', function (e) {
        validateAndCalculate(e);
        if (isDesktop()) calculateWeight();
      });
      // نمایش جداکننده سه‌رقمی هنگام تایپ
      input.addEventListener('blur', function () {
        const raw = input.value.replace(/,/g, '');
        if (raw && !isNaN(raw)) {
          const formatted = Number(raw).toLocaleString();
          input.value = formatted;
        }
      });

      input.addEventListener('focus', function () {
        // وقتی فوکوس گرفت، جداکننده‌ها حذف شوند تا راحت قابل ویرایش باشد
        input.value = input.value.replace(/,/g, '');
      });

      input.style.flex = '1';
      wrapper.appendChild(input);

      const unitSelect = document.createElement('select');
      unitSelect.className = 'unit-select';
      unitSelect.dataset.for = dimension;
      unitSelect.style.width = '85px';
      unitSelect.innerHTML = `
        <option value="mm" selected>میلی‌متر</option>
        <option value="cm">سانتی‌متر</option>
        <option value="m">متر</option>
        <option value="in">اینچ</option>
        <option value="ft">فوت</option>
      `;
      unitSelect.addEventListener('change', function (e) {
        validateAndCalculate(e);
        if (isDesktop()) calculateWeight();
      });
      wrapper.appendChild(unitSelect);

      container.appendChild(wrapper);
    });

    // quantity
    const qLabel = document.createElement('label');
    qLabel.htmlFor = 'quantity';
    qLabel.textContent = 'تعداد:';
    container.appendChild(qLabel);

    const qInput = document.createElement('input');
    qInput.type = 'text';
    qInput.id = 'quantity';
    qInput.min = 1;
    qInput.step = 1;
    qInput.value = savedValues['quantity'] || 1;
    qInput.addEventListener('input', function (e) {
      validateAndCalculate(e);
      if (isDesktop()) calculateWeight();
    });
    qInput.addEventListener('blur', function () {
      const raw = qInput.value.replace(/,/g, '');
      if (raw && !isNaN(raw)) qInput.value = Number(raw).toLocaleString();
    });
    qInput.addEventListener('focus', function () {
      qInput.value = qInput.value.replace(/,/g, '');
    });

    container.appendChild(qInput);
  }

  function getDimensionLabel(dimension) {
    const labels = {
      length: 'طول (l)',
      width: 'عرض (d)',
      thickness: 'ضخامت (b)',
      side: 'طول ضلع (b)',
      height: 'ارتفاع (h)',
      outerDiameter: 'قطر خارجی (d)',
      innerDiameter: 'قطر داخلی (b)',
      diameter: 'قطر (d)',
      acrossFlats: 'آچارخور (d)',
    };
    return labels[dimension] || dimension;
  }

  function getValueInMM(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return 0;
    const select = document.querySelector(`.unit-select[data-for="${inputId}"]`);
    const unit = select ? select.value : 'mm';
    const value = parseFloat(input.value.replace(/,/g, '')) || 0;
    const factors = { mm: 1, cm: 10, m: 1000, in: 25.4, ft: 304.8 };
    return value * (factors[unit] || 1);
  }

  function validateAndCalculate(e) {
    if (!e || !e.target) return;
    removeTooltip(e.target);
    const val = parseFloat(e.target.value);
    if (isNaN(val) || val <= 0) {
      showTooltip(e.target, "فقط اعداد مثبت (برای اعشار از نقطه (.) استفاده کنید.)");
    } else {
      removeError(e.target);
    }
  }

  function removeTooltip(input) {
    if (!input || !input.parentNode) return;
    const t = input.parentNode.querySelector('.input-tooltip');
    if (t) t.remove();
  }
  function showTooltip(input, message) {
    if (!input || !input.parentNode) return;
    removeTooltip(input);
    const tooltip = document.createElement('div');
    tooltip.className = 'input-tooltip';
    tooltip.textContent = message;
    tooltip.style.position = 'absolute';
    tooltip.style.backgroundColor = '#f8d7da';
    tooltip.style.color = '#721c24';
    tooltip.style.padding = '5px 8px';
    tooltip.style.borderRadius = '4px';
    tooltip.style.fontSize = '12px';
    input.parentNode.appendChild(tooltip);
  }

  function restoreSavedDimensions() {
    for (const [key, value] of Object.entries(lastDimensionsSaved)) {
      const el = document.getElementById(key);
      if (el) el.value = value;
    }
  }

  function saveCurrentDimensions() {
    const inputs = document.querySelectorAll('#shapeInputsContainer input');
    inputs.forEach(inp => lastDimensionsSaved[inp.id] = inp.value);
  }

  if (stage2Calculate) {
    stage2Calculate.addEventListener('click', function () {
      if (!isDesktop()) {
        const ok = calculateWeight();
        if (ok) showStage(3);
      }
    });
  }

  /**********************
   * STAGE3: results and price
   **********************/
  if (finalPricePerKg) {
    finalPricePerKg.addEventListener('input', function () {
      calculateWeight();

      const price = parseFloat(this.value) || 0;
      const singleKg = parseFloat(singleWeightEl.dataset.kg) || 0;
      const quantityEl = document.getElementById('quantity');
      const q = quantityEl ? parseInt(quantityEl.value, 10) || 1 : 1;
      const totalKg = parseFloat(totalWeightEl.dataset.kg) || 0;

      if (price > 0 && singleKg > 0) {
        singlePriceEl.textContent = `قیمت یک عدد: ${Math.floor(singleKg * price).toLocaleString('fa-IR')} تومان`;
        if (q > 1 && totalKg > 0) {
          totalPriceEl.style.display = '';
          totalPriceEl.textContent = `قیمت ${q} عدد: ${Math.floor(totalKg * price).toLocaleString('fa-IR')} تومان`;
        } else {
          totalPriceEl.style.display = 'none';
        }
      } else {
        singlePriceEl.textContent = "قیمت یک عدد: لطفا هر کیلو از فلز را وارد کنید";
        totalPriceEl.style.display = 'none';
      }
    });
  }

  if (stage3Back) {
    stage3Back.addEventListener('click', function () {
      showStage(2);
    });
  }

  if (stage3Restart) {
    stage3Restart.addEventListener('click', function () {
      selectedShape = null;
      chosenStage1Shape = null;
      selectedMaterial = null;
      lastDimensionsSaved = {};
      clearShapeSelectionUI();
      if (materialSelect) {
        materialSelect.value = '';
        materialSelect.disabled = false;
      }
      if (stage1Applied) stage1Applied.style.display = 'none';
      if (stage1NextBtn) stage1NextBtn.disabled = true;
      shapeInputsContainer.innerHTML = '';
      selectedShapePreview.innerHTML = '';
      singleWeightEl.textContent = 'وزن یک عدد: ';
      totalWeightEl.textContent = 'وزن کل: ';
      singlePriceEl.textContent = 'قیمت یک عدد: ';
      totalPriceEl.textContent = 'قیمت کل: ';
      if (finalPricePerKg) finalPricePerKg.value = '';
      // Reset visual disabled state for all shapes
      document.querySelectorAll('#shapeGrid .shape-image').forEach(tile => {
        tile.classList.remove('inactive-shape');
        tile.setAttribute('aria-disabled', 'false');
      });
      showStage(1);
    });
  }

  /**********************
   * Calculation core
   **********************/
  function getDimensionValues_forCalc() {
    const inputs = document.querySelectorAll('#shapeInputsContainer input');
    const values = {};
    for (const input of inputs) {
      if (input.id === 'quantity') continue;
      const val = parseFloat(input.value);
      if (isNaN(val) || val <= 0) {
        const label = getDimensionLabel(input.id);
        displayError(`مقدار ${label} را به عدد وارد کنید.`, input.id);        return null;
      }
      values[input.id] = getValueInMM(input.id);
    }
    return values;
  }

  function calculateVolume_local(shape, values) {
    switch (shape) {
      case 'strip':
      case 'sheet':
      case 'rectangularBar':
        return (values.length * values.width * values.thickness) / 1e9;
      case 'hexagonalBar': {
        const D = values.acrossFlats;
        return ((6 * Math.pow(values.acrossFlats / 2, 2)) / Math.sqrt(3) * values.length) / 1e9;
      }
      case 'lProfiles':
      case 'tProfiles':
      case 'uProfiles':
        return ((values.height + values.width - values.thickness) * values.thickness * values.length) / 1e9;
      case 'squareBar':
        return (Math.pow(values.width, 2) * values.length) / 1e9;
      case 'roundBar':
        return (Math.PI * Math.pow(values.diameter / 2, 2) * values.length) / 1e9;
      case 'rectangularTube': {
        const innerH = values.height - 2 * values.thickness;
        const innerW = values.width - 2 * values.thickness;
        if (innerH <= 0 || innerW <= 0) return null;
        return ((values.height * values.width - innerH * innerW) * values.length) / 1e9;
      }
      case 'squareTube': {
        const inner = values.width - 2 * values.thickness;
        if (inner <= 0) return null;
        return ((Math.pow(values.width, 2) - Math.pow(inner, 2)) * values.length) / 1e9;
      }
      case 'roundTube': {
        const od = values.outerDiameter;
        const id = values.innerDiameter;

        if (typeof od === 'undefined' || typeof id === 'undefined' || isNaN(od) || isNaN(id)) {
          displayError('لطفاً قطر خارجی و قطر داخلی را وارد کنید.', 'shapeInputsContainer');
          return null;
        }

        const outerR = od / 2;
        const innerR = id / 2;

        if (innerR >= outerR) {
          const innerEl = document.getElementById('innerDiameter');
          const outerEl = document.getElementById('outerDiameter');
          if (innerEl) showTooltip(innerEl, 'قطر داخلی باید کوچکتر از قطر خارجی باشد.');
          if (outerEl) showTooltip(outerEl, 'لطفاً قطر خارجی را بزرگتر وارد کنید.');
          displayError('قطر داخلی باید کمتر از قطر خارجی باشد.', 'shapeInputsContainer');
          if (innerEl) innerEl.focus();
          return null;
        }

        return ((Math.PI * (outerR * outerR - innerR * innerR)) * values.length) / 1e9;
      }

      default:
        return null;
    }
  }
  async function calculateWeight() {
    document.querySelectorAll('.error-message').forEach(e => e.remove());

    if (!selectedShape) {
      displayError('لطفاً ابتدا شکل و متریال را در مرحلهٔ ۱ انتخاب کنید.', 'shapeGrid');
      return false;
    }

    const dims = getDimensionValues_forCalc();
    if (!dims) return false;

    const volume = calculateVolume_local(selectedShape, dims);
    if (volume === null) {
      displayError('مقادیر هندسی نامعتبر است (مثلاً ضخامت بیش از حد).', 'shapeInputsContainer');
      return false;
    }

    const quantityEl = document.getElementById('quantity');
    const quantity = quantityEl ? parseInt(quantityEl.value, 10) || 1 : 1;

    const density = (selectedMaterial && densities[selectedMaterial]) ? densities[selectedMaterial] : null;
    if (!density) {
      displayError('چگالی نامشخص است. لطفاً در مرحلهٔ ۱ متریال را انتخاب کنید.', 'materialSelect');
      return false;
    }

    // وزن خام (قبل از افت)
    let singleWeight = volume * density;

    // ✅ کم کردن درصد دلخواه (مثلاً 2%)
    const reductionPercent = 2; // ← اینجا درصد را تنظیم کن (مثلاً 2 یعنی 2%)
    singleWeight = singleWeight * (1 - reductionPercent / 100);

    // ✅ وزن کل بر اساس مقدار واقعی (بدون گرد کردن)
    const totalWeight = singleWeight * quantity;

    // ✅ نمایش
    let singleFormatted;
    if (singleWeight < 1) {
      // اگر کمتر از 1 کیلو بود → با دو رقم اعشار
      singleFormatted = singleWeight.toLocaleString('fa-IR', { maximumFractionDigits: 2 });
    } else if (singleWeight < 100) {
      // اگر بین 1 تا 99 بود → فقط یک رقم اعشار
      singleFormatted = singleWeight.toLocaleString('fa-IR', { maximumFractionDigits: 1 });
    } else {
      // اگر 100 یا بیشتر بود → فقط عدد صحیح با جداکننده
      singleFormatted = Math.floor(singleWeight).toLocaleString('fa-IR');
    }

    // وزن کل همیشه بدون اعشار ولی با جداکننده
    let totalFormatted;
    if (totalWeight < 1) {
      // اگر کمتر از 1 کیلو بود → با دو رقم اعشار
      totalFormatted = totalWeight.toLocaleString('fa-IR', { maximumFractionDigits: 2 });
    } else if (totalWeight < 100) {
      // اگر بین 1 تا 99 بود → فقط یک رقم اعشار
      totalFormatted = totalWeight.toLocaleString('fa-IR', { maximumFractionDigits: 1 });
    } else {
      // اگر 100 یا بیشتر بود → فقط عدد صحیح با جداکننده
      totalFormatted = Math.floor(totalWeight).toLocaleString('fa-IR');
    }
    // ✅ نمایش در صفحه
    singleWeightEl.textContent = `وزن یک عدد: ${singleFormatted} کیلوگرم`;
    singleWeightEl.dataset.kg = singleWeight; // ← مقدار واقعی (برای قیمت)

    // اگر تعداد بیش از 1 بود وزن کل را هم نشان بده
    if (quantity > 1) {
      totalWeightEl.style.display = '';
      totalWeightEl.textContent = `وزن ${quantity} عدد: ${totalFormatted} کیلوگرم`;
      totalWeightEl.dataset.kg = totalWeight; // ← مقدار واقعی
    } else {
      totalWeightEl.style.display = 'none';
      totalWeightEl.dataset.kg = '';
    }

    // ✅ اگر قیمت از قبل واکشی شده، بلافاصله قیمت را هم محاسبه کن
    const wcSelect = document.getElementById('wcStandardSelect');
    if (wcSelect && wcSelect.value) {
      const defaultPrice = parseFloat(wcSelect.value) || 0;
      if (defaultPrice > 0) {
        calculateAndDisplayWCPrices(defaultPrice);
      }
    }

    return true;

  }

  /**********************
   * misc helpers (errors)
   **********************/
  function displayError(message, inputId) {
    document.querySelectorAll('.error-message').forEach(e => e.remove());
    const el = document.getElementById(inputId) || document.querySelector('.stage-content');
    if (el && el.parentNode) {
      const div = document.createElement('div');
      div.className = 'error-message';
      div.textContent = message;
      div.style.color = 'red';
      div.style.marginTop = '6px';
      el.parentNode.insertBefore(div, el.nextSibling);
    } else {
      alert(message);
    }
  }

  function removeError(input) {
    if (!input) return;
    const next = input.nextElementSibling;
    if (next && next.classList.contains('error-message')) next.remove();
  }

  /**********************
   * initialization & restore prefs
   **********************/
  if (materialSelect) {
    materialSelect.value = selectedMaterial;
    materialSelect.disabled = false;
    if (stage1Applied) {
      stage1Applied.style.display = 'block';
      stage1Applied.innerText = `شکل: ${getShapeLabel(selectedShape)} — متریال: ${materialPersian[selectedMaterial]} — چگالی: ${densities[selectedMaterial]} کیلوگرم بر متر مکعب`;
    }
  }

  // const profileSubKeys = ['lProfiles', 'tProfiles', 'uProfiles', 'rectangularTube'];
  // if (selectedShape && profileSubKeys.includes(selectedShape)) {
  //   if (profileSubtypes) profileSubtypes.style.display = '';
  //   const mainTile = document.querySelector(`#shapeGrid .shape-image[data-shape="profile"]`);
  //   if (mainTile) mainTile.classList.add('selected');
  //   const subtypeTile = document.querySelector(`#profileSubtypes .shape-image[data-shape="${selectedShape}"]`);
  //   if (subtypeTile) subtypeTile.classList.add('selected');
  //   chosenStage1Shape = 'profile';
  // } else {
  const tile = document.querySelector(`#shapeGrid .shape-image[data-shape="${selectedShape}"]`);
  if (tile) tile.classList.add('selected');
  // }

  // enable shape grid because material exists
  setDisabled(shapeGrid, false);
  // if (profileSubtypes) setDisabled(profileSubtypes, false);
  // ensure shape grid visually matches restored material selection
  updateShapeGridForMaterial(selectedMaterial);
  function restorePrefLocalAndMaybeSkip() {
    try {
      const raw = localStorage.getItem('grandCalc_pref');
      if (!raw) return false;
      const obj = JSON.parse(raw);
      if (!obj || !obj.shape || !obj.material) return false;

      selectedShape = obj.shape;
      selectedMaterial = obj.material;

      // set material select UI
      if (materialSelect) {
        materialSelect.value = selectedMaterial;
        materialSelect.disabled = false;
        if (stage1Applied) {
          stage1Applied.style.display = 'block';
          stage1Applied.innerText = `شکل: ${getShapeLabel(selectedShape)} — متریال: ${materialPersian[selectedMaterial]} — چگالی: ${densities[selectedMaterial]} کیلوگرم بر متر مکعب`;
        }
      }

      // mark shape tile selected if exists
      const tile = document.querySelector(`#shapeGrid .shape-image[data-shape="${selectedShape}"]`);
      if (tile) tile.classList.add('selected');

      // enable grid and update visuals
      setDisabled(shapeGrid, false);
      updateShapeGridForMaterial(selectedMaterial);

      if (isDesktop()) {
        setStage2And3Disabled(false);
        updateShapeInputs(selectedShape);
        const imgUrl = shapeDetailedImages[selectedShape] || '';
        selectedShapePreview.innerHTML = imgUrl ? `<img src="${imgUrl}" alt="${getShapeLabel(selectedShape)}" style="max-width:100%;">` : '';
        restoreSavedDimensions();
        if (typeof updateStage2Info === 'function') updateStage2Info();
        // calculate weight (will also call updateWCPriceFromSelection)
        calculateWeight();
        // additionally ensure prices fetched even if weight wasn't ready previously
        updateWCPriceFromSelection();
        showStage(1);
        return true;
      } else {
        if (stage1NextBtn) stage1NextBtn.disabled = false;
        showStage(1);
        return true;
      }
    } catch (e) {
      console.error('restorePrefLocalAndMaybeSkip error', e);
      return false;
    }
  }



  // initial desktop behavior:
  // material should be selectable first; shape grid & profile subtypes remain disabled until material chosen
  if (materialSelect) materialSelect.disabled = false;
  if (shapeGrid) setDisabled(shapeGrid, true);
  // if (profileSubtypes) setDisabled(profileSubtypes, true);

  // initially: stage2 & stage3 disabled on desktop until both material & shape picked
  setStage2And3Disabled(true);

  if (!restorePrefLocalAndMaybeSkip()) {
    showStage(1);
  }
  if (selectedMaterial) {
    requestAnimationFrame(() => {
      updateShapeGridForMaterial(selectedMaterial);
    });
  }
  // bind back buttons
  bindStage2BackButtons();

  // global helper compatibility
  window.selectShape = function (shapeKey) {
    handleStage1ShapeClick(shapeKey);
  };

}); // end DOMContentLoaded
