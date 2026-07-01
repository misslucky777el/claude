/* Калькулятор стоимости ГенералКлининг.
   Цены — по .agents/references/pricing.md (брекеты по площади, 1 и 2 клинера). */
(function () {
  var P = {
    reg:    { name: 'Поддерживающая',
      one: [4500, 5000, 5500, 6000, 6500, 7000, 7500],
      two: [7200, 8000, 8800, 9600, 10400, 11200, 12000] },
    gen:    { name: 'Генеральная',
      one: [10000, 11000, 12500, 15000, 17500, 20000, 22500],
      two: [15000, 16500, 18750, 22500, 26250, 30000, 33750] },
    remont: { name: 'После ремонта',
      one: [14000, 15000, 16500, 19000, 22000, 24000, 26500],
      two: [19000, 20500, 22750, 26500, 31000, 34000, 37750] }
  };
  function idx(a) {
    if (a <= 20) return 0; if (a <= 40) return 1; if (a <= 60) return 2;
    if (a <= 80) return 3; if (a <= 100) return 4; if (a <= 120) return 5;
    if (a <= 140) return 6; return -1;
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
        noteEl.textContent = svc.name + ' · больше 140 м² — рассчитаем индивидуально';
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
