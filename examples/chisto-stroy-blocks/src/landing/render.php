<?php
/**
 * Server render for chisto-stroy/landing.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks markup (unused).
 * @var WP_Block $block      Block instance.
 */

$a = wp_parse_args(
	$attributes,
	array(
		'phoneDisplay' => '+7 (495) 000-00-00',
		'phoneHref'    => '+74950000000',
		'email'        => 'info@chisto-stroy.ru',
		'hours'        => 'Ежедневно 8:00–22:00',
		'heroTitle'    => 'Уберём после ремонта за 1 день. Цена — из договора, ни рубля сверху',
		'heroLead'     => 'Послестроительная и генеральная уборка квартир, домов и офисов. Считаем стоимость до начала работ и фиксируем в договоре — финальная сумма совпадёт с первой до копейки.',
		'pricePerM2'   => '90',
		'region'       => 'Москва и Московская область',
	)
);

$tel     = 'tel:' . preg_replace( '/[^0-9+]/', '', $a['phoneHref'] );
$wrapper = get_block_wrapper_attributes( array( 'class' => 'cs-landing' ) );
$year    = gmdate( 'Y' );
?>
<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>

	<!-- HEADER -->
	<header class="cs-header" id="top">
		<div class="wrap nav">
			<a class="logo" href="#top" aria-label="ЧИСТО.СТРОЙ — на главную">
				<span class="mark" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21l3-3m0 0l9-9 3 3-9 9H6v-3zm9-9l3-3 3 3-3 3"/><circle cx="18" cy="6" r="1.5" fill="currentColor"/></svg>
				</span>
				ЧИСТО<b>.</b>СТРОЙ
			</a>
			<ul class="nav-links" aria-label="Основная навигация">
				<li><a href="#services">Услуги</a></li>
				<li><a href="#process">Как работаем</a></li>
				<li><a href="#pricing">Цены</a></li>
				<li><a href="#portfolio">Портфолио</a></li>
				<li><a href="#reviews">Отзывы</a></li>
				<li><a href="#faq">Вопросы</a></li>
			</ul>
			<div class="nav-cta">
				<a class="nav-phone" href="<?php echo esc_attr( $tel ); ?>">
					<?php echo esc_html( $a['phoneDisplay'] ); ?>
					<span><?php echo esc_html( $a['hours'] ); ?></span>
				</a>
				<a class="btn btn-primary" href="#zayavka">Рассчитать цену</a>
				<button class="burger" id="csBurger" aria-label="Открыть меню" aria-expanded="false">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>
		<div class="mobile-menu" id="csMobileMenu">
			<nav aria-label="Мобильная навигация">
				<span class="sub">Меню</span>
				<a href="#services">Услуги</a>
				<a href="#process">Как работаем</a>
				<a href="#pricing">Цены</a>
				<a href="#portfolio">Портфолио</a>
				<a href="#reviews">Отзывы</a>
				<a href="#faq">Вопросы</a>
				<a class="btn btn-primary" href="#zayavka">Рассчитать цену</a>
			</nav>
		</div>
	</header>

	<!-- HERO -->
	<section class="hero">
		<div class="wrap hero-grid">
			<div class="hero-copy">
				<span class="eyebrow">Клининг в <?php echo esc_html( $a['region'] ); ?></span>
				<h1><?php echo esc_html( $a['heroTitle'] ); ?></h1>
				<p class="lead"><?php echo esc_html( $a['heroLead'] ); ?></p>
				<div class="hero-cta">
					<a class="btn btn-primary btn-lg" href="#zayavka">Рассчитать стоимость</a>
					<a class="btn btn-ghost btn-lg" href="<?php echo esc_attr( $tel ); ?>">Позвонить</a>
				</div>
				<ul class="hero-badges">
					<li><span class="tick" aria-hidden="true">✓</span> Договор и закрывающие документы</li>
					<li><span class="tick" aria-hidden="true">✓</span> Своя профхимия и техника</li>
					<li><span class="tick" aria-hidden="true">✓</span> Гарантия на результат</li>
				</ul>
			</div>
			<div class="hero-art" aria-hidden="true">
				<div class="seal-wrap">
					<div class="seal-card">
						<span class="ribbon">ПОСЛЕСТРОЙ</span>
						<div class="price">
							<small>Цена в договоре</small>
							<b>от <?php echo esc_html( $a['pricePerM2'] ); ?> <i>₽/м²</i></b>
						</div>
					</div>
					<svg class="seal" viewBox="0 0 200 200">
						<defs><path id="cs-arc" d="M100,100 m-72,0 a72,72 0 1,1 144,0 a72,72 0 1,1 -144,0"/></defs>
						<circle cx="100" cy="100" r="95" fill="#fff" stroke="currentColor" stroke-width="3"/>
						<circle cx="100" cy="100" r="86" fill="none" stroke="currentColor" stroke-width="1.4" stroke-dasharray="2 5"/>
						<g class="ring"><text font-size="13.5" letter-spacing="2.2" font-weight="700"><textPath href="#cs-arc" startOffset="0">ФИКСИРОВАННАЯ ЦЕНА · ИЗ ДОГОВОРА · НИ РУБЛЯ СВЕРХУ ·</textPath></text></g>
						<text x="100" y="96" text-anchor="middle" font-size="40" font-weight="800">0 ₽</text>
						<text x="100" y="122" text-anchor="middle" font-size="15" letter-spacing="4" font-weight="700">ДОПЛАТ</text>
					</svg>
				</div>
			</div>
		</div>
	</section>

	<!-- TRUST -->
	<div class="trust">
		<div class="wrap">
			<div class="stat"><b>1 200<i>+</i></b><span>объектов сдано</span></div>
			<div class="stat"><b>350 000<i>м²</i></b><span>убрано после ремонта</span></div>
			<div class="stat"><b>4.9<i>★</i></b><span>средняя оценка клиентов</span></div>
			<div class="stat"><b>1<i>день</i></b><span>средний срок послестроя</span></div>
		</div>
	</div>

	<!-- SERVICES -->
	<section class="pad" id="services">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Услуги</span>
				<h2>Что мы убираем</h2>
				<p>Выберите услугу — на каждой карточке цены и что входит.</p>
			</div>
			<div class="svc-grid">
				<article class="svc reveal">
					<div class="ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg></div>
					<h3>Послестроительная уборка</h3>
					<p>Убираем строительную пыль, остатки раствора, краски, клея, затирки. Готовим объект к заселению.</p>
					<ul>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Грубая и финишная уборка</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Удаление цемента, краски, скотча</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Мытьё окон, рам и подоконников</li>
					</ul>
					<a class="more" href="#zayavka">Оставить заявку <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
				</article>
				<article class="svc reveal">
					<div class="ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 3l1.5 4 4 1.5-4 1.5L9.5 14 8 10 4 8.5 8 7z"/><path d="M17 13l.9 2.4 2.4.9-2.4.9L17 20l-.9-2.8-2.4-.9 2.4-.9z"/></svg></div>
					<h3>Генеральная уборка</h3>
					<p>Глубокая уборка «до блеска»: кухня, санузлы, окна, мебель и труднодоступные места.</p>
					<ul>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Обезжиривание кухни и техники</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Дезинфекция санузлов</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Мытьё окон, зеркал, поверхностей</li>
					</ul>
					<a class="more" href="#zayavka">Оставить заявку <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
				</article>
			</div>
			<div class="chips reveal">
				<span class="chip">Мытьё окон и витражей</span>
				<span class="chip">Химчистка мебели и ковров</span>
				<span class="chip">Уборка офисов и коммерции</span>
				<span class="chip">Удаление строительной пыли</span>
				<span class="chip">Уборка после залива и пожара</span>
			</div>
		</div>
	</section>

	<!-- WHY -->
	<section class="why pad">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Почему мы</span>
				<h2>Главное — вы знаете цену заранее</h2>
				<p>Мы построили компанию на одном принципе: никаких «доплат на месте».</p>
			</div>
			<div class="why-grid">
				<div class="why-card lead-card reveal"><div class="num">01 / ГЛАВНОЕ</div><h3>Честная цена из договора</h3><p>Стоимость рассчитывается до начала работ и фиксируется в договоре. Не вырастет ни на рубль — даже если пыли больше, чем мы ждали.</p></div>
				<div class="why-card reveal"><div class="num">02</div><h3>Послестрой за 1 день</h3><p>Бригады нужного размера выходят на объект и сдают финишную уборку в день обращения или к нужной дате.</p></div>
				<div class="why-card reveal"><div class="num">03</div><h3>Своя профхимия и техника</h3><p>Профессиональные средства, парогенераторы, пылесосы и стеклоочистители. Привозим всё своё.</p></div>
				<div class="why-card reveal"><div class="num">04</div><h3>Официальный договор</h3><p>Работаем с физлицами и юрлицами: договор, чек, безналичный расчёт и закрывающие документы.</p></div>
				<div class="why-card reveal"><div class="num">05</div><h3>Гарантия на результат</h3><p>Если что-то убрано недостаточно — переделаем бесплатно. Принимаете работу, когда всё устраивает.</p></div>
				<div class="why-card reveal"><div class="num">06</div><h3>Бригадир и фотоотчёт</h3><p>На каждом объекте старший контролирует качество. По завершении присылаем фотоотчёт по зонам.</p></div>
			</div>
		</div>
	</section>

	<!-- PROCESS -->
	<section class="pad" id="process">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Как работаем</span>
				<h2>Четыре шага — от заявки до чистоты</h2>
				<p>Прозрачный процесс без сюрпризов. Оплата — по факту приёмки работ.</p>
			</div>
			<div class="steps">
				<div class="step reveal"><div class="inner"><span class="step-n">1</span><h3>Заявка и расчёт</h3><p>Бесплатно оценим стоимость по фото/видео или с выездом замерщика.</p></div></div>
				<div class="step reveal"><div class="inner"><span class="step-n">2</span><h3>Договор с ценой</h3><p>Фиксируем объём работ и финальную сумму. Дальше цена не меняется.</p></div></div>
				<div class="step reveal"><div class="inner"><span class="step-n">3</span><h3>Уборка</h3><p>Бригада со своей химией и техникой работает под контролем бригадира.</p></div></div>
				<div class="step reveal"><div class="inner"><span class="step-n">4</span><h3>Приёмка и оплата</h3><p>Принимаете результат, получаете фотоотчёт и документы. Оплата по факту.</p></div></div>
			</div>
		</div>
	</section>

	<!-- PRICING -->
	<section class="pad" id="pricing">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Цены</span>
				<h2>Прозрачные тарифы без скрытых доплат</h2>
				<p>Ниже — ориентировочные цены. Точную сумму считаем по объекту и фиксируем в договоре.</p>
			</div>
			<div class="price-grid">
				<div class="tier reveal">
					<h3>Грубая уборка</h3>
					<p class="sub">Первичная уборка после строителей: пыль и крупный мусор.</p>
					<div class="cost">от <?php echo esc_html( $a['pricePerM2'] ); ?> <small>₽/м²</small></div>
					<div class="from">Минимальный заказ — от 4 000 ₽</div>
					<ul>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Удаление строительной пыли</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Сбор и вынос мусора</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Влажная уборка полов</li>
					</ul>
					<a class="btn btn-ghost" href="#zayavka">Выбрать</a>
				</div>
				<div class="tier featured reveal">
					<span class="tag">Хит — за 1 день</span>
					<h3>Послестрой под ключ</h3>
					<p class="sub">Финишная уборка под заселение. Объект блестит «из коробки».</p>
					<div class="cost">от 140 <small>₽/м²</small></div>
					<div class="from">Минимальный заказ — от 6 000 ₽</div>
					<ul>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Всё из тарифа «Грубая»</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Удаление краски, клея, затирки</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Мытьё окон, санузлы, кухня</li>
					</ul>
					<a class="btn btn-primary" href="#zayavka">Рассчитать цену</a>
				</div>
				<div class="tier reveal">
					<h3>Генеральная уборка</h3>
					<p class="sub">Глубокая уборка жилых и офисных помещений «до блеска».</p>
					<div class="cost">от 80 <small>₽/м²</small></div>
					<div class="from">Минимальный заказ — от 4 500 ₽</div>
					<ul>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Кухня и обезжиривание техники</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Дезинфекция санузлов</li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg> Мытьё окон и зеркал</li>
					</ul>
					<a class="btn btn-ghost" href="#zayavka">Выбрать</a>
				</div>
			</div>
			<div class="price-note reveal">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg>
				Цена в калькуляторе — ориентир. Финальную сумму фиксируем в договоре, и она не меняется в процессе работ.
			</div>
		</div>
	</section>

	<!-- PORTFOLIO -->
	<section class="pad portfolio" id="portfolio">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Портфолио</span>
				<h2>Результат «до и после»</h2>
				<p>Потяните ползунок, чтобы увидеть разницу. Замените демо-плашки на ваши реальные фото.</p>
			</div>
			<div class="ba-grid">
				<article class="ba reveal">
					<div class="compare" data-compare role="slider" tabindex="0" aria-label="Сравнение до и после" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50">
						<div class="pane after"><span class="lbl">ПОСЛЕ</span></div>
						<div class="pane before"><span class="lbl">ДО</span><span class="ba-text">Строительная пыль</span></div>
						<div class="handle"><span class="grip" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7l-4 5 4 5M16 7l4 5-4 5"/></svg></span></div>
					</div>
					<div class="cap"><b>3-комнатная квартира, ЖК «Символ»</b><span>Послестрой под ключ · 86 м² · за 1 день</span></div>
				</article>
				<article class="ba reveal">
					<div class="compare" data-compare role="slider" tabindex="0" aria-label="Сравнение до и после" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50">
						<div class="pane after"><span class="lbl">ПОСЛЕ</span></div>
						<div class="pane before"><span class="lbl">ДО</span><span class="ba-text">Налёт и разводы</span></div>
						<div class="handle"><span class="grip" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7l-4 5 4 5M16 7l4 5-4 5"/></svg></span></div>
					</div>
					<div class="cap"><b>Офис, БЦ на Тульской</b><span>Генеральная уборка · 140 м²</span></div>
				</article>
			</div>
		</div>
	</section>

	<!-- REVIEWS -->
	<section class="rev pad" id="reviews">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Отзывы</span>
				<h2>Что говорят клиенты</h2>
				<p>Заменяйте на реальные отзывы с Яндекс.Карт, Авито или Профи — так доверие выше.</p>
			</div>
			<div class="rev-grid">
				<article class="review reveal"><div class="stars" aria-label="5 из 5">★★★★★</div><p>«Переехали в новостройку — после ремонта был ужас. Назвали цену по фото, в договоре она и осталась. Убрали за день, окна как новые».</p><div class="who"><span class="av">М</span><div><b>Марина К.</b><span>Квартира, ЖК «Символ»</span></div></div></article>
				<article class="review reveal"><div class="stars" aria-label="5 из 5">★★★★★</div><p>«Брали генеральную перед сдачей квартиры в аренду. Кухня и санузлы — идеально. Приехали со своей химией, докупать ничего не пришлось».</p><div class="who"><span class="av">Д</span><div><b>Дмитрий С.</b><span>Генеральная уборка</span></div></div></article>
				<article class="review reveal"><div class="stars" aria-label="5 из 5">★★★★★</div><p>«Заказывали как юрлицо, нужны были документы. Всё прислали: договор, акт, чек. По цене — ровно как договаривались, без сюрпризов».</p><div class="who"><span class="av">А</span><div><b>Анна В.</b><span>Офис, БЦ на Тульской</span></div></div></article>
			</div>
		</div>
	</section>

	<!-- FAQ -->
	<section class="pad" id="faq">
		<div class="wrap">
			<div class="head reveal">
				<span class="eyebrow">Вопросы и ответы</span>
				<h2>Коротко о главном</h2>
			</div>
			<div class="faq">
				<details class="reveal" open><summary>Правда ли, что цена не изменится?<span class="pl" aria-hidden="true"></span></summary><div class="answer">Да. Мы рассчитываем стоимость до начала работ и фиксируем её в договоре. Сумма не вырастет, даже если загрязнений оказалось больше. Доплата возможна только если вы сами добавите новые работы — и только по вашему согласию.</div></details>
				<details class="reveal"><summary>За сколько уберёте после ремонта?<span class="pl" aria-hidden="true"></span></summary><div class="answer">Стандартную квартиру убираем за 1 день. На большие объекты выводим бригаду нужного размера. Дату согласуем заранее — можем выйти в день обращения.</div></details>
				<details class="reveal"><summary>Работаете с юридическими лицами?<span class="pl" aria-hidden="true"></span></summary><div class="answer">Да. Заключаем договор, работаем по безналу, предоставляем акт, чек и закрывающие документы для бухгалтерии.</div></details>
				<details class="reveal"><summary>Своя химия и оборудование?<span class="pl" aria-hidden="true"></span></summary><div class="answer">Всё своё: профессиональные средства под каждый тип загрязнения, парогенераторы, пылесосы и стеклоочистители. Покупать ничего не нужно.</div></details>
				<details class="reveal"><summary>Выезжаете за МКАД?<span class="pl" aria-hidden="true"></span></summary><div class="answer">Да, работаем по Москве и области. Выезд за МКАД рассчитывается отдельно и тоже фиксируется в договоре заранее.</div></details>
			</div>
		</div>
	</section>

	<!-- CTA / FORM -->
	<section class="cta pad" id="zayavka">
		<div class="wrap cta-grid">
			<div class="reveal">
				<span class="eyebrow eyebrow-light">Заявка</span>
				<h2>Получите расчёт с фиксированной ценой</h2>
				<p class="lead">Оставьте контакты — посчитаем стоимость по вашему объекту и зафиксируем её в договоре. Бесплатно и без обязательств.</p>
				<ul class="cta-list">
					<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 13l4 4L19 7"/></svg> Ответим в течение 15 минут</li>
					<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 13l4 4L19 7"/></svg> Расчёт по фото или с выездом</li>
					<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 13l4 4L19 7"/></svg> Цена в договоре — без доплат</li>
				</ul>
			</div>
			<div class="form-box reveal">
				<form data-lead novalidate>
					<h3>Рассчитать стоимость</h3>
					<p class="note">Заполните 4 поля — перезвоним и назовём точную цену.</p>
					<div class="field"><label for="cs-name">Ваше имя</label><input id="cs-name" name="name" type="text" placeholder="Как к вам обращаться" required></div>
					<div class="field"><label for="cs-phone">Телефон</label><input id="cs-phone" name="phone" type="tel" placeholder="+7 (___) ___-__-__" required></div>
					<div class="field"><label for="cs-type">Тип уборки</label>
						<select id="cs-type" name="type">
							<option>Послестроительная — под ключ</option>
							<option>Послестроительная — грубая</option>
							<option>Генеральная уборка</option>
							<option>Мытьё окон</option>
							<option>Химчистка мебели</option>
							<option>Уборка офиса</option>
						</select>
					</div>
					<div class="field"><label for="cs-area">Площадь, м²</label><input id="cs-area" name="area" type="number" min="1" placeholder="Например, 65"></div>
					<button class="btn btn-primary" type="submit">Получить расчёт</button>
					<p class="privacy">Нажимая кнопку, вы соглашаетесь с политикой обработки персональных данных.</p>
				</form>
				<div class="form-ok" hidden>
					<div class="big" aria-hidden="true"><svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M5 13l4 4L19 7"/></svg></div>
					<h3>Заявка принята!</h3>
					<p>Мы перезвоним в течение 15 минут и назовём точную цену.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- FOOTER -->
	<footer class="cs-footer">
		<div class="wrap">
			<div class="foot-grid">
				<div>
					<a class="logo" href="#top"><span class="mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21l3-3m0 0l9-9 3 3-9 9H6v-3zm9-9l3-3 3 3-3 3"/><circle cx="18" cy="6" r="1.5" fill="currentColor"/></svg></span> ЧИСТО<b>.</b>СТРОЙ</a>
					<p class="foot-about">Послестроительная и генеральная уборка в <?php echo esc_html( $a['region'] ); ?>. Честная цена из договора — ни рубля сверху.</p>
				</div>
				<div>
					<h4>Услуги</h4>
					<ul class="foot-links">
						<li><a href="#services">Послестроительная уборка</a></li>
						<li><a href="#services">Генеральная уборка</a></li>
						<li><a href="#services">Мытьё окон</a></li>
						<li><a href="#services">Химчистка мебели и ковров</a></li>
						<li><a href="#services">Уборка офисов</a></li>
					</ul>
				</div>
				<div class="foot-contact">
					<h4>Контакты</h4>
					<ul>
						<li><a href="<?php echo esc_attr( $tel ); ?>"><b><?php echo esc_html( $a['phoneDisplay'] ); ?></b></a></li>
						<li><?php echo esc_html( $a['hours'] ); ?></li>
						<li><a href="mailto:<?php echo esc_attr( $a['email'] ); ?>"><?php echo esc_html( $a['email'] ); ?></a></li>
						<li><?php echo esc_html( $a['region'] ); ?></li>
					</ul>
				</div>
			</div>
			<div class="foot-bottom">
				<span>© <?php echo esc_html( $year ); ?> ЧИСТО.СТРОЙ. Все права защищены.</span>
				<span>ИП / ООО · ИНН 0000000000 · Политика конфиденциальности</span>
			</div>
		</div>
	</footer>

	<div class="callbar">
		<a class="btn btn-ghost" href="<?php echo esc_attr( $tel ); ?>">Позвонить</a>
		<a class="btn btn-primary" href="#zayavka">Рассчитать цену</a>
	</div>

</div>
