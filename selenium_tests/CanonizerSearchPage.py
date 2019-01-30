from CanonizerHomePage import Page
from Identifiers import CanonizerSearchPageIdentifiers


class CanonizerSearchPage(Page):
    """
    Class Name: CanonizerSearchPage
    Description: Test the functionality of Canonizer Search Page
    """

    def click_search_button(self):
        """
        This function is to click on the Google Search button
        -> Hover to the Google Search button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON)
        self.find_element(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON).click()
        return CanonizerSearchPage(self.driver)

    def enter_search_keyword(self, search_keyword):
        self.find_element(*CanonizerSearchPageIdentifiers.SEARCH_KEYWORD).send_keys(search_keyword)


    def click_google_search_button(self):
        """
        This function verify if the login page loads properly
        :return:
            Once the page is loaded, return result to the main program.
        """
        self.find_element(*LoginPageIdentifiers.SUBMIT).click()

    def google_search(self, search_keyword, option):
        """
        This function is to click the login button and return result to the main program.
        Args:
            :param user: Email ID of the User
            :param password: Password of the User
        :return:
            After Entering the Username and Password, function clicks on the login button and returns the control.
        """
        self.enter_search_keyword(search_keyword)
        self.enter_password(password)
        self.click_login_button()
