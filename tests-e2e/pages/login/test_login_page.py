import configparser
import re
import os
import unittest

from test_configuration import get_config

from playwright.sync_api import sync_playwright, expect

class test_login_page(unittest.TestCase):
    
    def setUp(self):
        self.config = get_config()
        self.web_application_uri = self.config.get('WebApplication','Uri')
        self.playwright = sync_playwright().start()
        self.browser = self.playwright.chromium.launch(headless=False, slow_mo=2000)
        self.context = self.browser.new_context()
        self.page = self.context.new_page()
        

    def tearDown(self):
        self.context.close()
        self.browser.close()
        self.playwright.stop()


    def test_login_page_has_title(self):
        self.page.goto(f"{self.web_application_uri}/login.php")
        expect(self.page).to_have_title(re.compile("Login | Pine"))
        screenshot_path = f'play/screenshots/{self.test_login_page_has_title.__name__}'
        self.page.screenshot(path=f'{screenshot_path}.png', full_page=True)

    def test_login_page_redirect_to_dashboard_when_using_authentic_credentials(self):
        self.page.goto(f"{self.web_application_uri}/login.php")
        self.page.goto("http://localhost:9036/login.php")
        self.page.get_by_role("textbox", name="Username").click()
        self.page.get_by_role("textbox", name="Username").fill("zhixian")
        self.page.get_by_role("textbox", name="Password").click()
        self.page.get_by_role("textbox", name="Password").fill("Fake")
        self.page.get_by_role("button", name="Submit").click()
        expect(self.page).to_have_title(re.compile("Pine"))
        screenshot_path = f'play/screenshots/{self.test_login_page_redirect_to_dashboard_when_using_authentic_credentials.__name__}'
        self.page.screenshot(path=f'{screenshot_path}.png', full_page=True)

if __name__ == "__main__":
    unittest.main()
