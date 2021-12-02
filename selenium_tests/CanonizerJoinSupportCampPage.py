from CanonizerBase import Page
from Identifiers import JoinSupportCampPageIdentifiers, BrowsePageIdentifiers, TopicUpdatePageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class CanonizerJoinSupportCampPage(Page):

    def load_topic_page(self):
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()
        return CanonizerJoinSupportCampPage(self.driver)

    def load_join_support_camp_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        time.sleep(3)
        self.hover(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP)
        self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
        return CanonizerJoinSupportCampPage(self.driver)

    def join_support_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*JoinSupportCampPageIdentifiers.ADDNEWNICKNAME)
        except NoSuchElementException:
            return False
        return True

    def nick_name_page_should_open_join_support_camp_add_new_nick_name(self):
        try:
            elem = self.find_element(*JoinSupportCampPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False
        return True

