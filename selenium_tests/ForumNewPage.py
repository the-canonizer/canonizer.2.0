from CanonizerBase import Page
from Identifiers import CampForumIdentifiers, BrowsePageIdentifiers, AddCampStatementPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class AddForumsPage(Page):

    def load_camp_forum_page(self):
        """
        Go To Camp Forum
        :return:
        """

        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER).click()
        time.sleep(6)

        try:
            # print("Here")
            self.hover(*CampForumIdentifiers.CAMP_FORUM)
            self.find_element(*CampForumIdentifiers.CAMP_FORUM).click()
            time.sleep(6)
            return AddForumsPage(self.driver)
        except NoSuchElementException:
            return False


    def load_create_thread_page(self):
        self.hover(*CampForumIdentifiers.CREATE_THREAD)
        self.find_element(*CampForumIdentifiers.CREATE_THREAD).click()
        time.sleep(6)

    def enter_title_of_thread(self, title):
        self.find_element(*CampForumIdentifiers.TITLE_THREAD).send_keys(title)

    def enter_nickname(self, nickname):
        self.find_element(*CampForumIdentifiers.NICK_NAME).send_keys(nickname)

    def click_submit_button(self):
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).click()

    def submit_thread(self, title, nickname):
        self.enter_nickname(nickname)
        self.enter_title_of_thread(title)
        self.click_submit_button()




