from CanonizerBase import Page
from Identifiers import LogoutIdentifiers , HomePageIdentifiers
from selenium.webdriver.support.select import Select


class CanonizerLogoutPage(Page):

    def click_log_out_page_button(self):
        """
        This function is to click on the login button

        -> Hover to the login button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*LogoutIdentifiers.LOGOUT)
        self.find_element(*LogoutIdentifiers.LOGOUT).click()
        return CanonizerLogoutPage(self.driver)

    def click_username_link_button(self):
        """
        This function is to click on the login button

        -> Hover to the login button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*LogoutIdentifiers.USERNAME)
        self.find_element(*LogoutIdentifiers.USERNAME).click()
        return CanonizerLogoutPage(self.driver)

    def check_home_page_loaded(self):
        """ To check if the Canonizer Home page loads properly"""

        return True if self.find_element(*HomePageIdentifiers.BODY) else False



