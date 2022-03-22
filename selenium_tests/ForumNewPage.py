from CanonizerBase import Page
from Identifiers import CampForumIdentifiers,BrowsePageIdentifiers,AddCampStatementPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class AddForumsPage(Page):

    def load_camp_forum_page(self):
        """
            Go To The Camp Forum
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER).click()
        time.sleep(3)

        # Click Add Camp Forum This camp
        self.hover(*CampForumIdentifiers.CAMP_FORUM)
        self.find_element(*CampForumIdentifiers.CAMP_FORUM).click()
        time.sleep(3)
        return AddForumsPage(self.driver)

    def load_create_thread_page(self):
        self.hover(*CampForumIdentifiers.CREATE_THREAD)
        self.find_element(*CampForumIdentifiers.CREATE_THREAD).click()
        return AddForumsPage(self.driver)

    def enter_title_of_thread(self, title):
        self.find_element(*CampForumIdentifiers.TITLE_THREAD).send_keys(title)

    def enter_nickname(self, nickname):
        self.find_element(*CampForumIdentifiers.NICK_NAME).send_keys(nickname)

    def click_submit_button(self):
        self.hover(*CampForumIdentifiers.SUBMIT_THREAD)
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).click()
        time.sleep(3)

    def submit_thread(self, title, nickname):
        self.enter_title_of_thread(title)
        self.enter_nickname(nickname)
        self.click_submit_button()

    def submit_thread_with_blank_title(self, nickname):
        self.submit_thread('', nickname)
        return self.find_element(*CampForumIdentifiers.ERROR_TITLE).text

    def submit_thread_with_valid_data(self, title, nickname):
        self.submit_thread(title, nickname)
        return self




