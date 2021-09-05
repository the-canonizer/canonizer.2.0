from CanonizerBase import Page
from Identifiers import *
import requests


class CanonizerBrokenURL(Page):

    def go_back(self):
        """
        This function took the control to the Previous URL
        :return:
        """

        self.driver.back()

    def click_search_button(self):
        self.find_element(*HomePageIdentifiers.GOOGLESEARCHSUBMIT).click()

    def search_topic_on_google_search(self):

        self.find_element(*HomePageIdentifiers.GOOGLESEARCH).send_keys("topic.asp")

        self.click_search_button()

        self.find_element(*HomePageIdentifiers.BROKENURL_1).click()
        if requests.get(self.get_url()).status_code != 200:
            return False
        self.go_back()

        self.find_element(*HomePageIdentifiers.BROKENURL_2).click()
        if requests.get(self.get_url()).status_code != 200:
            return False
        self.go_back()

        self.find_element(*HomePageIdentifiers.BROKENURL_3).click()
        if requests.get(self.get_url()).status_code != 200:
            return False
        self.go_back()

        self.find_element(*HomePageIdentifiers.BROKENURL_4).click()
        if requests.get(self.get_url()).status_code != 200:
            return False
        self.go_back()

        return True