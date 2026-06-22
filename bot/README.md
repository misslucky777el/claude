# КлинГрупп — Telegram-бот заявок

Бот приёма заявок на уборку (aiogram 3), реализующий сценарий из
[`../.agents/references/telegram-bot-script.md`](../.agents/references/telegram-bot-script.md).

## Возможности
- Меню услуг и расчёт заявки (услуга → площадь → имя → телефон → подтверждение).
- Отдельная **B2B-ветка** (компания → телефон → объект).
- **Атрибуция источника** через deep-link: `t.me/<bot>?start=tgads_remont`, `?start=seed_<канал>`, `?start=site_<страница>` — источник попадает в заявку.
- Промокод −15% после заявки.
- Экраны: цены, отзывы, контакты.
- **Напоминание** о брошенной заявке (если не оставил телефон).
- Заявки уходят менеджеру в Telegram (`MANAGER_CHAT_ID`).

## Установка
```bash
cd bot
python -m venv .venv && source .venv/bin/activate   # опционально
pip install -r requirements.txt
cp .env.example .env
# заполните BOT_TOKEN (от @BotFather) и MANAGER_CHAT_ID
python bot.py
```

## Настройка
- **BOT_TOKEN** — у [@BotFather](https://t.me/BotFather): `/newbot`.
- **MANAGER_CHAT_ID** — ваш личный id (узнать у [@userinfobot](https://t.me/userinfobot)) или id группы менеджеров (добавьте туда бота).
- Включите режим deep-link ссылок: они работают сразу, формат `https://t.me/ИМЯ_БОТА?start=ИСТОЧНИК`.
- Остальные параметры (промокод, телефон, канал, часы, задержка напоминания) — в `.env`.

## Deep-link источники (для рекламы)
| Ссылка | Источник в заявке |
|---|---|
| `?start=tgads_general` / `tgads_remont` / `tgads_reg` / `tgads_b2b` | Telegram Ads по услуге |
| `?start=seed_<канал>` | посев в конкретном канале |
| `?start=site_<страница>` | переход с лендинга |

## Что заменить на реальное
- Токен и `MANAGER_CHAT_ID`.
- Отзывы в `texts.py` (`REVIEWS`) — на настоящие.
- Телефон/канал/часы в `.env`.
- При желании — подключить заявки не в Telegram-чат, а в CRM (доработать `notify_manager`).

## Структура
```
bot.py          — хендлеры, FSM, напоминания, отправка менеджеру
keyboards.py    — инлайн/реплай клавиатуры
texts.py        — тексты экранов и услуги/цены
config.py       — чтение .env
requirements.txt
.env.example
```
