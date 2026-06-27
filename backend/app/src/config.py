# Configuration settings (Database URL, Key)
import os
from dotenv import load_dotenv

load_dotenv()  # Load environment variables from .env file

class Config:
    # Load configuration from environment variables
    SQLALCHEMY_DATABASE_URI = os.getenv('DATABASE_URL', 'sqlite:///project.db')
    SECRET_KEY = os.getenv('SECRET_KEY', 'your_secret_key_here')
    SQLALCHEMY_TRACK_MODIFICATIONS = False  # Disable track modifications to save resources