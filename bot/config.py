"""Конфигурация бота КлинГрупп из переменных окружения."""
import os
from dotenv import load_dotenv

load_dotenv()

BOT_TOKEN = os.getenv("BOT_TOKEN", "")
MANAGER_CHAT_ID = int(os.getenv("MANAGER_CHAT_ID", "0") or "0")
PROMO_CODE = os.getenv("PROMO_CODE", "ПЕРВЫЙ15")
PHONE = os.getenv("PHONE", "8 (495) 000-00-00")
CHANNEL = os.getenv("CHANNEL", "@KlinGroup")
WORK_HOURS = os.getenv("WORK_HOURS", "8:00–22:00")
REMINDER_DELAY = int(os.getenv("REMINDER_DELAY", "7200") or "7200")

if not BOT_TOKEN:
    raise RuntimeError("BOT_TOKEN не задан. Скопируйте .env.example в .env и заполните.")
