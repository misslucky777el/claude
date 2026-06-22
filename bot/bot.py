"""Telegram-бот приёма заявок КлинГрупп (aiogram 3).

Запуск:
    pip install -r requirements.txt
    cp .env.example .env  # заполнить BOT_TOKEN и MANAGER_CHAT_ID
    python bot.py
"""
import asyncio
import logging
from html import escape

from aiogram import Bot, Dispatcher, F
from aiogram.client.default import DefaultBotProperties
from aiogram.enums import ParseMode
from aiogram.filters import Command, CommandObject, CommandStart
from aiogram.fsm.context import FSMContext
from aiogram.fsm.state import State, StatesGroup
from aiogram.types import CallbackQuery, Message, ReplyKeyboardRemove

import keyboards as kb
import texts as t
from config import BOT_TOKEN, MANAGER_CHAT_ID, REMINDER_DELAY

logging.basicConfig(level=logging.INFO)

dp = Dispatcher()
bot = Bot(token=BOT_TOKEN, default=DefaultBotProperties(parse_mode=ParseMode.HTML))

# Задачи-напоминания о брошенной заявке: user_id -> Task
reminders: dict[int, asyncio.Task] = {}


class Lead(StatesGroup):
    area = State()
    name = State()
    phone = State()
    confirm = State()


class LeadB2B(StatesGroup):
    name = State()
    phone = State()
    obj = State()


# ---------- напоминания ----------
def cancel_reminder(user_id: int) -> None:
    task = reminders.pop(user_id, None)
    if task:
        task.cancel()


async def _remind_later(user_id: int, state: FSMContext) -> None:
    try:
        await asyncio.sleep(REMINDER_DELAY)
        if await state.get_state() in (Lead.phone.state, LeadB2B.phone.state):
            await bot.send_message(user_id, t.REMINDER, reply_markup=kb.calc_or_home())
    except asyncio.CancelledError:
        pass
    finally:
        reminders.pop(user_id, None)


def schedule_reminder(user_id: int, state: FSMContext) -> None:
    cancel_reminder(user_id)
    reminders[user_id] = asyncio.create_task(_remind_later(user_id, state))


# ---------- /start, меню ----------
@dp.message(CommandStart())
async def cmd_start(message: Message, command: CommandObject, state: FSMContext) -> None:
    source = (command.args or "direct").strip()
    cancel_reminder(message.from_user.id)
    await state.clear()
    await state.update_data(source=source)
    await message.answer(t.WELCOME, reply_markup=kb.main_menu())


@dp.message(Command("help"))
async def cmd_help(message: Message) -> None:
    await message.answer(t.HELP, reply_markup=kb.calc_or_home())


@dp.callback_query(F.data == "menu:home")
async def go_home(cb: CallbackQuery, state: FSMContext) -> None:
    cancel_reminder(cb.from_user.id)
    data = await state.get_data()
    await state.clear()
    await state.update_data(source=data.get("source", "direct"))
    await cb.message.answer(t.WELCOME, reply_markup=kb.main_menu())
    await cb.answer()


@dp.callback_query(F.data == "menu:prices")
async def show_prices(cb: CallbackQuery) -> None:
    await cb.message.answer(t.PRICES, reply_markup=kb.calc_or_home())
    await cb.answer()


@dp.callback_query(F.data == "menu:reviews")
async def show_reviews(cb: CallbackQuery) -> None:
    await cb.message.answer(t.REVIEWS, reply_markup=kb.calc_or_home())
    await cb.answer()


@dp.callback_query(F.data == "menu:contacts")
async def show_contacts(cb: CallbackQuery) -> None:
    await cb.message.answer(t.CONTACTS, reply_markup=kb.calc_or_home())
    await cb.answer()


# ---------- B2C: расчёт ----------
@dp.callback_query(F.data == "menu:calc")
async def start_calc(cb: CallbackQuery, state: FSMContext) -> None:
    await cb.message.answer(t.CHOOSE_SERVICE, reply_markup=kb.service_menu())
    await cb.answer()


@dp.callback_query(F.data.startswith("svc:"))
async def chose_service(cb: CallbackQuery, state: FSMContext) -> None:
    code = cb.data.split(":", 1)[1]
    name, _price = t.SERVICES[code]
    await state.update_data(service=name)
    await state.set_state(Lead.area)
    await cb.message.answer(t.ASK_AREA, reply_markup=kb.area_skip())
    await cb.answer()


@dp.callback_query(F.data == "area:skip", Lead.area)
async def area_skip(cb: CallbackQuery, state: FSMContext) -> None:
    await state.update_data(area=t.AREA_SKIPPED)
    await state.set_state(Lead.name)
    await cb.message.answer(t.ASK_NAME)
    await cb.answer()


@dp.message(Lead.area)
async def got_area(message: Message, state: FSMContext) -> None:
    area = message.text.strip()
    await state.update_data(area=f"{area} м²" if area.isdigit() else area)
    await state.set_state(Lead.name)
    await message.answer(t.ASK_NAME)


@dp.message(Lead.name)
async def got_name(message: Message, state: FSMContext) -> None:
    await state.update_data(name=message.text.strip())
    await state.set_state(Lead.phone)
    await message.answer(t.ASK_PHONE, reply_markup=kb.phone_request())
    schedule_reminder(message.from_user.id, state)


@dp.message(Lead.phone)
async def got_phone(message: Message, state: FSMContext) -> None:
    phone = message.contact.phone_number if message.contact else message.text.strip()
    cancel_reminder(message.from_user.id)
    await state.update_data(phone=phone)
    data = await state.get_data()
    await state.set_state(Lead.confirm)
    await message.answer(
        t.confirm_text(data["service"], data["area"], data["name"], phone),
        reply_markup=kb.confirm(),
    )
    # убираем reply-клавиатуру запроса контакта
    await message.answer("⌛", reply_markup=ReplyKeyboardRemove())


@dp.callback_query(F.data == "lead:edit", Lead.confirm)
async def lead_edit(cb: CallbackQuery, state: FSMContext) -> None:
    await cb.message.answer(t.CHOOSE_SERVICE, reply_markup=kb.service_menu())
    await cb.answer()


@dp.callback_query(F.data == "lead:send", Lead.confirm)
async def lead_send(cb: CallbackQuery, state: FSMContext) -> None:
    data = await state.get_data()
    await notify_manager(
        kind="B2C",
        user=cb.from_user,
        source=data.get("source", "direct"),
        rows=[
            ("Услуга", data.get("service", "—")),
            ("Площадь", data.get("area", "—")),
            ("Имя", data.get("name", "—")),
            ("Телефон", data.get("phone", "—")),
        ],
    )
    await state.clear()
    await cb.message.answer(t.SUCCESS, reply_markup=kb.calc_or_home())
    await cb.answer("Заявка отправлена ✅")


# ---------- B2B ----------
@dp.callback_query(F.data == "menu:b2b")
async def show_b2b(cb: CallbackQuery) -> None:
    await cb.message.answer(t.B2B, reply_markup=kb.b2b_start())
    await cb.answer()


@dp.callback_query(F.data == "b2b:start")
async def b2b_begin(cb: CallbackQuery, state: FSMContext) -> None:
    await state.set_state(LeadB2B.name)
    await cb.message.answer(t.B2B_ASK_NAME)
    await cb.answer()


@dp.message(LeadB2B.name)
async def b2b_name(message: Message, state: FSMContext) -> None:
    await state.update_data(name=message.text.strip())
    await state.set_state(LeadB2B.phone)
    await message.answer(t.B2B_ASK_PHONE, reply_markup=kb.phone_request())
    schedule_reminder(message.from_user.id, state)


@dp.message(LeadB2B.phone)
async def b2b_phone(message: Message, state: FSMContext) -> None:
    phone = message.contact.phone_number if message.contact else message.text.strip()
    cancel_reminder(message.from_user.id)
    await state.update_data(phone=phone)
    await state.set_state(LeadB2B.obj)
    await message.answer(t.B2B_ASK_OBJECT, reply_markup=ReplyKeyboardRemove())


@dp.message(LeadB2B.obj)
async def b2b_object(message: Message, state: FSMContext) -> None:
    await state.update_data(obj=message.text.strip())
    data = await state.get_data()
    await notify_manager(
        kind="B2B",
        user=message.from_user,
        source=data.get("source", "direct"),
        rows=[
            ("Имя/компания", data.get("name", "—")),
            ("Телефон", data.get("phone", "—")),
            ("Объект", data.get("obj", "—")),
        ],
    )
    await state.clear()
    await message.answer(t.B2B_SUCCESS, reply_markup=kb.home())


# ---------- отправка заявки менеджеру ----------
async def notify_manager(kind: str, user, source: str, rows: list[tuple[str, str]]) -> None:
    uname = f"@{user.username}" if user.username else f"id{user.id}"
    lines = [f"<b>🧾 Новая заявка ({kind})</b>"]
    lines += [f"• {label}: {escape(str(value))}" for label, value in rows]
    lines.append(f"• Источник: {escape(t.source_label(source))}")
    lines.append(f"• Контакт в TG: {escape(uname)}")
    text = "\n".join(lines)
    if MANAGER_CHAT_ID:
        try:
            await bot.send_message(MANAGER_CHAT_ID, text)
        except Exception as e:  # noqa: BLE001
            logging.error("Не удалось отправить заявку менеджеру: %s", e)
    else:
        logging.warning("MANAGER_CHAT_ID не задан. Заявка:\n%s", text)


# ---------- фолбэк ----------
@dp.message()
async def fallback(message: Message) -> None:
    await message.answer(t.UNKNOWN, reply_markup=kb.main_menu())


async def main() -> None:
    await bot.delete_webhook(drop_pending_updates=True)
    await dp.start_polling(bot)


if __name__ == "__main__":
    asyncio.run(main())
