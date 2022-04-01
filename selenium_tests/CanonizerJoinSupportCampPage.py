from CanonizerBase import Page
from Identifiers import JoinSupportCampPageIdentifiers, BrowsePageIdentifiers, TopicUpdatePageIdentifiers, \
    CreateNewTopicPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class CanonizerJoinSupportCampPage(Page):
    supported_camps = "Supported Camps"

    def load_topic_page(self):
        # Browse to Browse Page
        self.hover(*JoinSupportCampPageIdentifiers.BROWSE)
        self.find_element(*JoinSupportCampPageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*JoinSupportCampPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*JoinSupportCampPageIdentifiers.TOPIC_IDENTIFIER).click()
        return CanonizerJoinSupportCampPage(self.driver)

    def load_join_support_camp_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        time.sleep(3)
        self.hover(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP)
        self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
        title = self.find_element(*JoinSupportCampPageIdentifiers.TITLE).text
        if title == self.supported_camps:
            return CanonizerJoinSupportCampPage(self.driver)

    def load_direct_join_and_support_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        time.sleep(3)
        try:
            self.hover(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP)
            self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
            page_title = self.find_element(*JoinSupportCampPageIdentifiers.PAGE_TITLE).text

            if page_title == self.supported_camps:
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False

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
            if count_after_support > count_before_support and support == 'Directly Join and Support':
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False

    def join_support_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*JoinSupportCampPageIdentifiers.ADDNEWNICKNAME)
        except NoSuchElementException:
            return False

    def nick_name_page_should_open_join_support_camp_add_new_nick_name(self):
        try:
            elem = self.find_element(*JoinSupportCampPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerJoinSupportCampPage(self.driver)
        except NoSuchElementException:
            return False

    def verifying_one_person_one_vote_01(self):
        self.load_topic_page()
        parent_support_count = self.find_element(*JoinSupportCampPageIdentifiers.BADGE).text
        if parent_support_count == '1':
            self.find_element(*JoinSupportCampPageIdentifiers.CHILD_CAMP).click()
            before_support_child_count = self.find_element(*JoinSupportCampPageIdentifiers.SUPPORT).text
            if before_support_child_count == '0':
                self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
                self.find_element(*JoinSupportCampPageIdentifiers.CONFIRM_SUBMIT).click()
                self.find_element(*JoinSupportCampPageIdentifiers.PARENT_CAMP).click()
                try:
                    self.find_element(*JoinSupportCampPageIdentifiers.CHILD_SUPPORT)
                except NoSuchElementException:
                    return CanonizerJoinSupportCampPage(self.driver)

    def verifying_one_person_one_vote_02(self):
        self.load_topic_page()
        parent_support_count = self.find_element(*JoinSupportCampPageIdentifiers.BADGE).text
        if parent_support_count == '1':
            self.find_element(*JoinSupportCampPageIdentifiers.CHILD_CAMP).click()
            before_support_child_count = self.find_element(*JoinSupportCampPageIdentifiers.SUPPORT).text
            if before_support_child_count == '0':
                self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
                self.find_element(*JoinSupportCampPageIdentifiers.CONFIRM_SUBMIT).click()
                child_support =self.find_element(*JoinSupportCampPageIdentifiers.SUPPORT).text
                if child_support == '1':
                    self.find_element(*JoinSupportCampPageIdentifiers.CHILD_CAMP_2).click()
                    self.find_element(*JoinSupportCampPageIdentifiers.JOINSUPPORTCAMP).click()
                    self.find_element(*JoinSupportCampPageIdentifiers.SUBMIT).click()
                    parent_count = self.find_element(*JoinSupportCampPageIdentifiers.PARENT_BADGE).text
                    child_01_badge = self.find_element(*JoinSupportCampPageIdentifiers.CHILD_01_BADGE).text
                    child_02_badge = self.find_element(*JoinSupportCampPageIdentifiers.CHILD_02_BADGE).text
                    if parent_count == '0.75' and child_01_badge == '0.5' and child_02_badge == '0.25':
                        return CanonizerJoinSupportCampPage(self.driver)




