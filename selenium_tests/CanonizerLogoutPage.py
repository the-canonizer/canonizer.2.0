import time
from CanonizerBase import Page
from Identifiers import LogoutIdentifiers, HomePageIdentifiers


class CanonizerLogoutPage(Page):
    main_page = 'Canonizer Main Page'
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
        if title == self.main_page:
            self.hover(*LogoutIdentifiers.USERNAME)
            self.find_element(*LogoutIdentifiers.USERNAME).click()
            return CanonizerLogoutPage(self.driver)

    def check_home_page_loaded(self):
        if self.find_element(*HomePageIdentifiers.BODY):
            return CanonizerLogoutPage(self.driver)

    def click_log_out_page_button_before_browser_back_button(self):
        self.click_username_link_button()
        self.click_log_out_page_button()
        self.go_back()
        if self.find_element(*HomePageIdentifiers.LOGIN):
            self.hover(*HomePageIdentifiers.LOGIN)
            return CanonizerLogoutPage(self.driver)





