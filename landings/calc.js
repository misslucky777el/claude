/* Калькулятор стоимости ГенералКлининг.
   Цены — по .agents/references/pricing.md (брекеты по площади, 1 и 2 клинера). */
(function () {
  // Брекеты по площади с шагом 10 м²: ≤20,≤30,…,≤120 (>120 — по запросу).
  var P = {
    reg:    { name: 'Поддерживающая',
      one: [4500, 4750, 5000, 5250, 5500, 5750, 6000, 6250, 6500, 6750, 7000],
      two: [7200, 7600, 8000, 8400, 8800, 9200, 9600, 10000, 10400, 10800, 11200] },
    gen:    { name: 'Генеральная',
      one: [10000, 11000, 12000, 13000, 14000, 15000, 16000, 17000, 18000, 19000, 20000],
      two: [15000, 16500, 18000, 19500, 21000, 22500, 24000, 25500, 27000, 28500, 30000] },
    remont: { name: 'После ремонта',
      one: [14000, 15000, 16000, 17000, 18000, 19000, 20000, 21000, 22000, 23000, 24000],
      two: [19000, 20500, 22000, 23500, 25000, 26500, 28000, 29500, 31000, 32500, 34000] }
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
