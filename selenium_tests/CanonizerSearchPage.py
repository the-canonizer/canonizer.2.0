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
        title = self.find_element(*CanonizerSearchPageIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.hover(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON)
            self.find_element(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON).click()
            return CanonizerSearchPage(self.driver)

    def enter_search_keyword(self, search_keyword):
        self.find_element(*CanonizerSearchPageIdentifiers.SEARCH_KEYWORD).send_keys(search_keyword)

    def check_web(self, web):
        self.find_element(*CanonizerSearchPageIdentifiers.WEB).send_keys(web)

    def check_canonizer_com(self, canonizer_com):
        self.find_element(*CanonizerSearchPageIdentifiers.CANONIZER_COM).send_keys(canonizer_com)

    def click_google_search_button(self):
        """
        """
        self.find_element(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON).click()

    def google_search(self, search_keyword, web):
        """
        """
        self.enter_search_keyword(search_keyword)
        self.check_web(web)
        self.click_google_search_button()

    def click_search_button_web(self):
        """

        :param web:
        :return:
        """
        title = self.find_element(*CanonizerSearchPageIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.hover(*CanonizerSearchPageIdentifiers.WEB_LABEL)
            self.find_element(*CanonizerSearchPageIdentifiers.WEB_LABEL).click()
            self.hover(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON)
            self.find_element(*CanonizerSearchPageIdentifiers.SEARCH_BUTTON).click()
            return CanonizerSearchPage(self.driver)

    def click_search_button_keyword_web(self, search_keyword):
        """

        :param web:
        :return:
        """
        title = self.find_element(*CanonizerSearchPageIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.enter_search_keyword(search_keyword)
            self.click_search_button_web()
            return CanonizerSearchPage(self.driver)

    def click_search_button_keyword_canonizer_com(self, search_keyword):
        """

        :param web:
        :return:
        """
        title = self.find_element(*CanonizerSearchPageIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.enter_search_keyword(search_keyword)
            self.click_search_button()
            return CanonizerSearchPage(self.driver)


