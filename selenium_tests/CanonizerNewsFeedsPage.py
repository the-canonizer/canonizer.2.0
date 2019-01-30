from CanonizerBase import Page
from Identifiers import  BrowsePageIdentifiers, TopicUpdatePageIdentifiers, AddNewsPageIdentifiers, EditNewsPageIdentifiers


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
        This Function checks, if Mandatory fields on Register Page Marked with *
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


class CanonizerEditNewsFeedsPage(Page):

    def load_edit_news_feed_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        # Click on Edit News
        self.hover(*EditNewsPageIdentifiers.EDIT_NEWS)
        self.find_element(*EditNewsPageIdentifiers.EDIT_NEWS).click()
        return CanonizerAddNewsFeedsPage(self.driver)
