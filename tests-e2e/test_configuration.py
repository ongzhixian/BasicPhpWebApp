import configparser
import os

CONFIG_FILE_PATH = 'unittest.ini'

def get_config():
    config = configparser.ConfigParser()
    if os.path.exists(CONFIG_FILE_PATH):
        config.read(CONFIG_FILE_PATH)
        return config
    else:
        raise FileNotFoundError(f"Configuration file '{CONFIG_FILE_PATH}' not found.") 