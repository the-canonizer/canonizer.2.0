import time
from CanonizerBase import Page
from Identifiers import LogoutIdentifiers, HomePageIdentifiers
from selenium.webdriver.support.select import Select
from Config import *
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException


class CanonizerLogoutPage(Page):

    def go_back(self):
        """
        This function took the control to the Previous URL
        :return:
        """

        self.driver.back()

    def open(self, url):
        url = self.base_url + url
        self.driver.get(url)
        time.sleep(6)

    def click_log_out_page_button(self):
        self.hover(*LogoutIdentifiers.LOGOUT)
        self.find_element(*LogoutIdentifiers.LOGOUT).click()
        return CanonizerLogoutPage(self.driver)

    def click_username_link_button(self):
        title = self.find_element(*LogoutIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.hover(*LogoutIdentifiers.USERNAME)
            self.find_element(*LogoutIdentifiers.USERNAME).click()
            return CanonizerLogoutPage(self.driver)

    def check_home_page_loaded(self):
        return True if self.find_element(*HomePageIdentifiers.BODY) else False

    def click_log_out_page_button_before_browser_back_button(self):
        self.click_username_link_button()
        self.click_log_out_page_button()
        self.go_back()
        if self.find_element(*HomePageIdentifiers.LOGIN):
            self.hover(*HomePageIdentifiers.LOGIN)
            return True
        else:
            return False

    def click_log_out_button_should_log_out_from_every_tab(self):
        self.click_username_link_button()
        self.click_log_out_page_button()
        base_url = DEFAULT_BASE_URL
        print("Base URL", base_url)
        self.driver.execute_script("window.open('{}');".format(base_url))
        time.sleep(6)
        # self.switch_to_window(self.driver.window_handles[0])
        # self.hover(*HomePageIdentifiers.LOGIN)
        self.find_element(*HomePageIdentifiers.LOGIN).click()



