/* Калькулятор стоимости ГенералКлининг.
   Цены — по .agents/references/pricing.md (брекеты по площади, 1 и 2 клинера). */
(function () {
  // Брекеты по площади с шагом 10 м²: ≤20,≤30,…,≤120 (>120 — по запросу).
  var P = {
    reg:    { name: 'Поддерживающая',
      one: [4500, 4750, 5000, 5250, 5500, 5750, 6000, 6250, 6500, 6750, 7000],
      two: [7200, 7500, 8000, 8500, 8800, 9250, 9600, 10000, 10400, 10750, 11200] },
    gen:    { name: 'Генеральная',
      one: [10000, 10500, 11000, 11750, 12500, 13750, 15000, 16250, 17500, 18750, 20000],
      two: [15000, 15750, 16500, 17500, 18750, 20500, 22500, 24500, 26250, 28000, 30000] },
    remont: { name: 'После ремонта',
      one: [14000, 14500, 15000, 15750, 16500, 17750, 19000, 20500, 22000, 23000, 24000],
      two: [19000, 19750, 20500, 21500, 22750, 24500, 26500, 28750, 31000, 32500, 34000] }
  };
  function idx(a) {
    var t = [20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120];
    for (var i = 0; i < t.length; i++) if (a <= t[i]) return i;
    return -1; // > 120
  }
  function fmt(n) { return n.toLocaleString('ru-RU'); }

  function init(calc) {
    var state = {
      service: calc.getAttribute('data-service') || 'gen',
      team: '1',
      area: parseInt(calc.getAttribute('data-area') || '40', 10)
    };
    var priceEl = calc.querySelector('[data-calc="price"]');
    var noteEl  = calc.querySelector('[data-calc="note"]');
    var areaVal = calc.querySelector('[data-calc="area-val"]');
    var range   = calc.querySelector('[data-calc="area"]');

    function seg(name, val) {
      var btns = calc.querySelectorAll('[data-calc="' + name + '"] button');
      for (var i = 0; i < btns.length; i++) {
        btns[i].classList.toggle('on', btns[i].getAttribute('data-val') === val);
      }
    }
    function render() {
      seg('service', state.service);
      seg('team', state.team);
      if (range) range.value = state.area;
      if (areaVal) areaVal.textContent = state.area;
      var svc = P[state.service];
      var i = idx(state.area);
      var teamword = state.team === '2' ? '2 клинера' : '1 клинер';
      if (i < 0) {
        priceEl.textContent = 'по запросу';
        noteEl.textContent = svc.name + ' · больше 120 м² — рассчитаем индивидуально';
        return;
      }
      var price = (state.team === '2' ? svc.two : svc.one)[i];
      priceEl.textContent = 'от ' + fmt(price) + ' ₽';
      noteEl.textContent = svc.name + ' · ' + state.area + ' м² · ' + teamword;
    }

    var sBtns = calc.querySelectorAll('[data-calc="service"] button');
    for (var a = 0; a < sBtns.length; a++) {
      sBtns[a].addEventListener('click', function () { state.service = this.getAttribute('data-val'); render(); });
    }
    var tBtns = calc.querySelectorAll('[data-calc="team"] button');
    for (var b = 0; b < tBtns.length; b++) {
      tBtns[b].addEventListener('click', function () { state.team = this.getAttribute('data-val'); render(); });
    }
    if (range) range.addEventListener('input', function () { state.area = parseInt(range.value, 10); render(); });

    render();
  }

  var calcs = document.querySelectorAll('.calc');
  for (var i = 0; i < calcs.length; i++) init(calcs[i]);
})();
