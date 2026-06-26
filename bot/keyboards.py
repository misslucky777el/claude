"""Клавиатуры бота КлинГрупп."""
from aiogram.types import (
    InlineKeyboardMarkup,
    InlineKeyboardButton,
    ReplyKeyboardMarkup,
    KeyboardButton,
)


def main_menu() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="💰 Рассчитать стоимость", callback_data="menu:calc")],
        [InlineKeyboardButton(text="📋 Цены и услуги", callback_data="menu:prices"),
         InlineKeyboardButton(text="⭐ Отзывы", callback_data="menu:reviews")],
        [InlineKeyboardButton(text="🏢 Для бизнеса", callback_data="menu:b2b"),
         InlineKeyboardButton(text="📞 Контакты", callback_data="menu:contacts")],
    ])


def service_menu() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="🧹 Регулярная — от 4 500 ₽", callback_data="svc:reg")],
        [InlineKeyboardButton(text="✨ Генеральная — от 10 000 ₽", callback_data="svc:gen")],
        [InlineKeyboardButton(text="🛠 После ремонта — от 14 000 ₽", callback_data="svc:remont")],
        [InlineKeyboardButton(text="🏢 Для бизнеса (объекты)", callback_data="menu:b2b")],
        [InlineKeyboardButton(text="⬅️ Назад", callback_data="menu:home")],
    ])


def area_skip() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="Не знаю площадь", callback_data="area:skip")],
    ])


def phone_request() -> ReplyKeyboardMarkup:
    return ReplyKeyboardMarkup(
        keyboard=[[KeyboardButton(text="📱 Отправить мой номер", request_contact=True)]],
        resize_keyboard=True,
        one_time_keyboard=True,
    )


def confirm() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="✅ Отправить заявку", callback_data="lead:send")],
        [InlineKeyboardButton(text="✏️ Исправить", callback_data="lead:edit")],
    ])


def calc_or_home() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="💰 Рассчитать", callback_data="menu:calc")],
        [InlineKeyboardButton(text="🏠 В начало", callback_data="menu:home")],
    ])


def home() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="🏠 В начало", callback_data="menu:home")],
    ])


def b2b_start() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="📝 Оставить заявку", callback_data="b2b:start")],
        [InlineKeyboardButton(text="🏠 В начало", callback_data="menu:home")],
    ])
