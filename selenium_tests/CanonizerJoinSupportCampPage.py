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
        title = self.find_element(*JoinSupportCampPageIdentifiers.TITLE).text
        if title == 'Supported Camps':
            return CanonizerJoinSupportCampPage(self.driver)

    def load_direct_join_and_support_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        time.sleep(3)
        try:
            self.hover(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP)
            self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
            page_title = self.find_element(*JoinSupportCampPageIdentifiers.PAGE_TITLE).text

            if page_title == 'Supported Camps':
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def verify_warning_directly_supporting_child_camp(self):
        self.load_topic_page()
        self.hover(*JoinSupportCampPageIdentifiers.CHILD_CAMP)
        self.find_element(*JoinSupportCampPageIdentifiers.CHILD_CAMP).click()
        try:
            self.find_element(*JoinSupportCampPageIdentifiers.JOIN_CAMP_SUPPORT).click()
            warning = self.find_element(*JoinSupportCampPageIdentifiers.WARNING).text
            if warning:
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False
        return False

    def verify_support_to_child_camp(self):
        self.load_topic_page()
        count_before_support = self.find_element(*JoinSupportCampPageIdentifiers.COUNT_BEFORE_SUPPORT).text
        self.hover(*JoinSupportCampPageIdentifiers.CHILD_CAMP)
        self.find_element(*JoinSupportCampPageIdentifiers.CHILD_CAMP).click()
        try:
            self.find_element(*JoinSupportCampPageIdentifiers.JOIN_CAMP_SUPPORT).click()
            self.find_element(*JoinSupportCampPageIdentifiers.SUBMIT).click()
            count_after_support = self.find_element(*JoinSupportCampPageIdentifiers.COUNT_BEFORE_SUPPORT).text
            self.find_element(*JoinSupportCampPageIdentifiers.PARENT_CAMP).click()
            support = self.find_element(*JoinSupportCampPageIdentifiers.JOIN_CAMP_SUPPORT).text
            if count_after_support > count_before_support and support =='Directly Join and Support':
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False
        return False





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

