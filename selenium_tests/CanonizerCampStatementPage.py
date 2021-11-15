from CanonizerBase import Page
from Identifiers import CampStatementEditPageIdentifiers,BrowsePageIdentifiers, TopicUpdatePageIdentifiers, AddCampStatementPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class CanonizerCampStatementPage(Page):

    def load_topic_agreement_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        time.sleep(3)

    def load_edit_camp_statement_page(self):
        """
                    Go To The topic
                """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*CampStatementEditPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*CampStatementEditPageIdentifiers.TOPIC_IDENTIFIER).click()

        time.sleep(3)
        try:
            self.hover(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT)
            self.find_element(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT).click()
            self.hover(*CampStatementEditPageIdentifiers.SUBMIT_STATEMENT_UPDATE_BASED_ON_THIS)
            self.find_element(*CampStatementEditPageIdentifiers.SUBMIT_STATEMENT_UPDATE_BASED_ON_THIS).click()
            return CanonizerCampStatementPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        :return: the element value
        """

        return \
            self.find_element(*CampStatementEditPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CampStatementEditPageIdentifiers.STATEMENT_ASTRK)

    def enter_nick_name(self, nickname):
        self.find_element(*CampStatementEditPageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_camp_statement(self, statement):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).send_keys(statement)

    def enter_note(self, note):
        self.find_element(*CampStatementEditPageIdentifiers.NOTE).send_keys(note)

    def click_submit_update_button(self):
        """
        This function clicks the Submit Update Button
        :return:
        """
        self.find_element(*CampStatementEditPageIdentifiers.SUBMIT_UPDATE).click()

    def submit_update(self, nickname, statement, note):
        self.enter_nick_name(nickname)
        self.enter_camp_statement(statement)
        self.enter_note(note)
        self.click_submit_update_button()

    def submit_statement_update_with_blank_nick_name(self, statement, note):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).clear()
        self.submit_update('', statement, note)
        try:
            return self.find_element(*CampStatementEditPageIdentifiers.ERROR_NICK_NAME)
        except NoSuchElementException:
            return False
        return True

    def submit_statement_update_with_blank_statement(self, nick_name, note):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).clear()
        self.submit_update(nick_name, '', note)
        return self.find_element(*CampStatementEditPageIdentifiers.ERROR_STATEMENT).text

    def statement_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*CampStatementEditPageIdentifiers.ADDNEWNICKNAME).text
        except NoSuchElementException:
            return False
        return True

    def nick_name_page_should_open_update_camp_statement_add_new_nick_name(self):
        try:
            elem = self.find_element(*CampStatementEditPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerCampStatementPage(self.driver)
        except NoSuchElementException:
            return False
        return True


class AddCampStatementPage(Page):

    def load_add_camp_statement_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER).click()
        time.sleep(3)

        # Click Add Camp Statement This camp
        try:
            self.hover(*AddCampStatementPageIdentifiers.ADD_CAMP_STATEMENT)
            self.find_element(*AddCampStatementPageIdentifiers.ADD_CAMP_STATEMENT).click()
            time.sleep(3)
            return AddCampStatementPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def add_camp_statement_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Add Camp Statement Page Marked with *
        :return: the element value
        """

        return \
            self.find_element(*AddCampStatementPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*AddCampStatementPageIdentifiers.STATEMENT_ASTRK)

    def enter_nick_name(self, nickname):
        self.find_element(*AddCampStatementPageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_camp_statement(self, statement):
        self.find_element(*AddCampStatementPageIdentifiers.STATEMENT).send_keys(statement)

    def enter_note(self, note):
        self.find_element(*AddCampStatementPageIdentifiers.NOTE).send_keys(note)

    def click_submit_statement_button(self):
        """
        This function clicks the Submit Update Button
        :return:
        """
        self.find_element(*AddCampStatementPageIdentifiers.SUBMIT_STATEMENT).click()

    def submit_statement(self, nickname, statement, note):
        self.enter_nick_name(nickname)
        self.enter_camp_statement(statement)
        self.enter_note(note)
        self.click_submit_statement_button()

    def submit_statement_with_blank_nick_name(self, statement, note):
        try:
            elem = self.find_element(*AddCampStatementPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                self.submit_statement('', statement, note)
                time.sleep(2)
                return self.find_element(*AddCampStatementPageIdentifiers.ERROR_NICK_NAME)
        except NoSuchElementException:
            return False
        return True

    def submit_statement_with_blank_statement(self, nick_name, note):
        self.submit_statement(nick_name, '', note)
        return self.find_element(*AddCampStatementPageIdentifiers.ERROR_STATEMENT).text

    def submit_statement_with_valid_data(self, nick_name, statement, note):
        self.submit_statement(nick_name, statement, note)
        return self

    def add_camp_statement_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*AddCampStatementPageIdentifiers.ADDNEWNICKNAME).text
        except NoSuchElementException:
            return False
        return True

    def nick_name_page_should_open_add_camp_statement_add_new_nick_name(self):
        try:
            elem = self.find_element(*AddCampStatementPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return AddCampStatementPage(self.driver)
        except NoSuchElementException:
            return False
        return True




