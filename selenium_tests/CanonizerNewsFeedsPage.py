import time

from CanonizerBase import Page
from Identifiers import BrowsePageIdentifiers, TopicUpdatePageIdentifiers, AddNewsPageIdentifiers, \
    EditNewsPageIdentifiers, DeleteNewsPageIdentifiers, AddCampStatementPageIdentifiers, DeleteNewsPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.common.keys import Keys


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
        title = self.find_element(*AddNewsPageIdentifiers.TITLE).text
        if title == 'Add News':
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

    def create_new_with_blank_fields(self, link, display_text, available_for_child_camps):
        self.create_news(link, display_text, available_for_child_camps)
        error_text = self.find_element(*AddNewsPageIdentifiers.ERROR_DISPLAY_TEXT).text
        error_link = self.find_element(*AddNewsPageIdentifiers.ERROR_LINK).text
        return [error_link, error_text]

    def click_add_news_cancel_button(self):
        self.load_add_news_feed_page()
        # Click On Cancel Button
        self.hover(*AddNewsPageIdentifiers.CANCEL)
        self.find_element(*AddNewsPageIdentifiers.CANCEL).click()
        heading = self.find_element(*AddNewsPageIdentifiers.HEADING).text
        if heading == 'Camp: Agreement':
            return CanonizerAddNewsFeedsPage(self.driver)

    def create_news_with_invalid_link_format(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.ERROR_INVALID_LINK).text

    def create_news_with_valid_data(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.SUCCESS_MESSAGE).text

    def create_news_with_enter_key(self, display_text, link, available_for_child_camps):
        self.enter_display_text(display_text)
        self.enter_link(link)
        self.check_available_for_child_camps(available_for_child_camps)
        self.find_element(*AddNewsPageIdentifiers.CREATENEWS).send_keys(Keys.ENTER)
        return self.find_element(*AddNewsPageIdentifiers.SUCCESS_MESSAGE).text

    def create_news_with_duplicate_data(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.SUCCESS_MESSAGE).text

    def create_news_with_invalid_data(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        error1 = self.find_element(*AddNewsPageIdentifiers.ERROR_DISPLAY_TEXT).text
        error2 = self.find_element(*AddNewsPageIdentifiers.ERROR_LINK).text
        return [error1, error2]

    def create_news_with_invalid_data_with_enter_key(self, display_text, link, available_for_child_camps):
        self.enter_display_text(display_text)
        self.enter_link(link)
        self.check_available_for_child_camps(available_for_child_camps)
        self.find_element(*AddNewsPageIdentifiers.CREATENEWS).send_keys(Keys.ENTER)
        return self.find_element(*AddNewsPageIdentifiers.ERROR_DISPLAY_TEXT).text

    def create_news_with_trailing_spaces(self, display_text, link, available_for_child_camps):
        self.create_news(display_text, link, available_for_child_camps)
        return self.find_element(*AddNewsPageIdentifiers.SUCCESS_MESSAGE).text

    def create_news_feed_with_check_box_input(self, display_text, link, available_for_child_camps):
        # self.enter_display_text(display_text)
        # self.enter_link(link)
        # self.find_element(*AddNewsPageIdentifiers.AVAILABLE_FOR_CHILD_CAMPS).click()
        # self.click_create_news_button()
        # message = self.find_element(*AddNewsPageIdentifiers.SUCCESS_MESSAGE).text
        text_contents = [el.text for el in self.find_element(*AddCampStatementPageIdentifiers.CAMP_LIST)]
        # Print text
        for text in text_contents:
            print(text)

        return


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
            heading = self.find_element(*EditNewsPageIdentifiers.HEADING).text
            if heading == 'Edit News':
                return CanonizerEditNewsFeedsPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def click_edit_news_cancel_button(self):
        # self.load_edit_news_feed_page()
        # Click On Cancel Button
        self.hover(*EditNewsPageIdentifiers.CANCEL)
        self.find_element(*EditNewsPageIdentifiers.CANCEL).click()
        heading = self.find_element(*EditNewsPageIdentifiers.AGREEMENT_HEADING).text
        print("Heading", heading)
        if heading == 'Camp: Agreement':
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
        success = self.find_element(*EditNewsPageIdentifiers.UPDATE_SUCCESS).text
        if success == 'Success! News updated successfully':
            return self

    def update_news_with_trailing_spaces(self, display_text, link, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).clear()
        self.find_element(*EditNewsPageIdentifiers.LINK).clear()
        self.update_news(display_text, link, available_for_child_camps)
        success = self.find_element(*EditNewsPageIdentifiers.UPDATE_SUCCESS).text
        if success == 'Success! News updated successfully':
            return self

    def update_news_with_duplicate_data(self, display_text, link, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).clear()
        self.find_element(*EditNewsPageIdentifiers.LINK).clear()
        self.update_news(display_text, link, available_for_child_camps)
        success = self.find_element(*EditNewsPageIdentifiers.UPDATE_SUCCESS).text
        if success == 'Success! News updated successfully':
            return self

    def update_news_with_invalid_data(self, display_text, link, available_for_child_camps):
        self.find_element(*EditNewsPageIdentifiers.DISPLAY_TEXT).clear()
        self.find_element(*EditNewsPageIdentifiers.LINK).clear()
        self.update_news(display_text, link, available_for_child_camps)
        error1 = self.find_element(*EditNewsPageIdentifiers.ERROR_DISPLAY_TEXT).text
        error2 = self.find_element(*EditNewsPageIdentifiers.ERROR_LINK).text
        return [error1, error2]

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
        return CanonizerDeleteNewsFeedsPage(self.driver)

    def delete_news_button_visibility(self):

        self.find_element(*DeleteNewsPageIdentifiers.DELETE_NEWS).click()
        time.sleep(4)
        if self.find_element(*DeleteNewsPageIdentifiers.DELETE_NEWS_ICON):
            return CanonizerDeleteNewsFeedsPage(self.driver)
        else:
            return False

    def delete_news(self):
        self.find_element(*DeleteNewsPageIdentifiers.DELETE_NEWS).click()
        self.find_element(*DeleteNewsPageIdentifiers.DELETE_NEWS_ICON).click()
        self.driver.switch_to.alert.accept()
        return self.find_element(*DeleteNewsPageIdentifiers.SUCCESS_MESSAGE).text

    def delete_child_news(self):
        self.hover(*DeleteNewsPageIdentifiers.CHILD_NEWS)
        self.find_element(*DeleteNewsPageIdentifiers.CHILD_NEWS).click()
        self.find_element(*DeleteNewsPageIdentifiers.DELETE_NEWS).click()
        self.find_element(*DeleteNewsPageIdentifiers.DELETE_CHILD_NEWS_ICON).click()
        self.driver.switch_to.alert.accept()
        return self.find_element(*DeleteNewsPageIdentifiers.SUCCESS_MESSAGE).text
