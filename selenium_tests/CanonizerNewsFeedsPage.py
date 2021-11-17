from CanonizerBase import Page
from Identifiers import  BrowsePageIdentifiers, TopicUpdatePageIdentifiers, AddNewsPageIdentifiers, EditNewsPageIdentifiers, DeleteNewsPageIdentifiers
from selenium.common.exceptions import NoSuchElementException


class CanonizerAddNewsFeedsPage(Page):

    def load_add_news_feed_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        # Click on Add News
        self.hover(*AddNewsPageIdentifiers.ADD_NEWS)
        self.find_element(*AddNewsPageIdentifiers.ADD_NEWS).click()
        return CanonizerAddNewsFeedsPage(self.driver)

    def add_news_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Add News Page Marked with *
        :return: the element value
        """
        return \
            self.find_element(*AddNewsPageIdentifiers.DISPLAY_TEXT_ASTRK) and \
            self.find_element(*AddNewsPageIdentifiers.LINK_ASTRK)

    def enter_display_text(self, display_text):
        self.find_element(*AddNewsPageIdentifiers.DISPLAY_TEXT).send_keys(display_text)

    def enter_link(self, link):
        self.find_element(*AddNewsPageIdentifiers.LINK).send_keys(link)

    def check_available_for_child_camps(self, available_for_child_camps):
        self.find_element(*AddNewsPageIdentifiers.LINK).send_keys(available_for_child_camps)

    def click_create_news_button(self):
        """
        This function clicks the Create News Button
        :return:
        """
        self.find_element(*AddNewsPageIdentifiers.CREATENEWS).click()

    def create_news(self, display_text, link, available_for_child_camps):
        self.enter_display_text(display_text)
        self.enter_link(link)
        self.check_available_for_child_camps(available_for_child_camps)
        self.click_create_news_button()

    def create_news_with_blank_display_text(self, link, available_for_child_camps):
        self.create_news('', link, available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.ERROR_DISPLAY_TEXT).text

    def create_news_with_blank_link(self, display_text, available_for_child_camps):
        self.create_news(display_text, '', available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.ERROR_LINK).text

    def click_add_news_cancel_button(self):
        self.load_add_news_feed_page()
        # Click On Cancel Button
        self.hover(*AddNewsPageIdentifiers.CANCEL)
        self.find_element(*AddNewsPageIdentifiers.CANCEL).click()
        return CanonizerAddNewsFeedsPage(self.driver)

    def create_news_with_invalid_link_format(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.ERROR_INVALID_LINK).text

    def create_news_with_valid_data(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        return self


class CanonizerEditNewsFeedsPage(Page):

    def load_edit_news_feed_page(self):
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        # Click on Edit News
        try:
            self.hover(*EditNewsPageIdentifiers.EDIT_NEWS)
            self.find_element(*EditNewsPageIdentifiers.EDIT_NEWS).click()
            return CanonizerEditNewsFeedsPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def click_edit_news_cancel_button(self):
        #self.load_edit_news_feed_page()
        # Click On Cancel Button
        self.hover(*EditNewsPageIdentifiers.CANCEL)
        self.find_element(*EditNewsPageIdentifiers.CANCEL).click()
        return CanonizerEditNewsFeedsPage(self.driver)

    def update_display_text(self, display_text):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).send_keys(display_text)

    def update_link(self, link):
        self.find_element(*EditNewsPageIdentifiers.LINK).send_keys(link)

    def update_available_for_child_camps(self, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.LINK).send_keys(available_for_child_camps)

    def click_submit_button(self):
        """
        This function clicks the Submit Button
        :return:
        """
        self.find_element(*EditNewsPageIdentifiers.SUBMIT).click()

    def update_news(self, display_text, link, available_for_child_camps):
        self.update_display_text(display_text)
        self.update_link(link)
        self.update_available_for_child_camps(available_for_child_camps)
        self.click_submit_button()

    def update_news_with_blank_display_text(self, link, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).clear()
        self.update_news('', link, available_for_child_camps)
        return self.find_element(*EditNewsPageIdentifiers.ERROR_DISPLAY_TEXT).text

    def update_news_with_blank_link(self, display_text, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.LINK).clear()
        self.update_news(display_text, '', available_for_child_camps)
        return self.find_element(*EditNewsPageIdentifiers.ERROR_LINK).text

    def update_news_with_invalid_link_format(self, display_text, link, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).clear()
        self.find_element(*EditNewsPageIdentifiers.LINK).clear()
        self.update_news(display_text, link, available_for_child_camps)
        return self.find_element(*EditNewsPageIdentifiers.ERROR_INVALID_LINK).text

    def update_news_with_valid_data(self, display_text, link, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).clear()
        self.find_element(*EditNewsPageIdentifiers.LINK).clear()
        self.update_news(display_text, link, available_for_child_camps)
        return self

    def edit_news_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Add News Page Marked with *
        :return: the element value
        """

        return \
            self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT_ASTRK) and \
            self.find_element(*EditNewsPageIdentifiers.LINK_ASTRK)


class CanonizerDeleteNewsFeedsPage(Page):

    def click_delete_news_feed(self):
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        # Click on Delete News
        try:
            self.hover(*DeleteNewsPageIdentifiers.DELETE_NEWS)
            self.find_element(*DeleteNewsPageIdentifiers.DELETE_NEWS).click()
            return CanonizerDeleteNewsFeedsPage(self.driver)
        except NoSuchElementException:
            return False
        return True