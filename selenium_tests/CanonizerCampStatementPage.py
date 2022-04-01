from selenium.webdriver.common.keys import Keys

from CanonizerBase import Page
from Identifiers import CampStatementEditPageIdentifiers, BrowsePageIdentifiers, TopicUpdatePageIdentifiers, \
    AddCampStatementPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class CanonizerCampStatementPage(Page):
    camp_agreement = "Camp: Agreement"

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

    def load_edit_camp_statement_history_page(self):
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        time.sleep(3)
        try:
            self.hover(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT)
            self.find_element(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT).click()

        except NoSuchElementException:
            return False

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
            page_title = self.find_element(*CampStatementEditPageIdentifiers.PAGE_TITLE).text
            if page_title == self.camp_agreement:
                return CanonizerCampStatementPage(self.driver)
        except NoSuchElementException:
            return False

    def verify_history_on_edit_camp_statement_page(self):
        self.load_edit_camp_statement_history_page()
        try:
            r_element = self.find_element(*CampStatementEditPageIdentifiers.OBJECT_COLOR)
            r_bg = r_element.value_of_css_property("background-color")
            g_element = self.find_element(*CampStatementEditPageIdentifiers.LIVE_COLOR)
            g_bg = g_element.value_of_css_property("background-color")
            y_element = self.find_element(*CampStatementEditPageIdentifiers.IN_REVIEW)
            y_bg = y_element.value_of_css_property("background-color")
            b_element = self.find_element(*CampStatementEditPageIdentifiers.OLD)
            b_bg = b_element.value_of_css_property("background-color")
            if r_bg == 'rgba(255, 0, 0, 0.5)' and g_bg == 'rgba(0, 128, 0, 0.5)' and y_bg == 'rgba(255, 255, 0, 1)' and b_bg == 'rgba(21, 20, 237, 1)':
                return CanonizerCampStatementPage(self.driver)
        except NoSuchElementException:
            return False
        except NoSuchElementException:
            return False

    def verify_editable_fields_on_edit_camp_statement_page(self):
        self.load_edit_camp_statement_page()
        nickname_element = self.find_element(*CampStatementEditPageIdentifiers.NICK_NAME)
        statement_element = self.find_element(*CampStatementEditPageIdentifiers.STATEMENT)
        edit_summary_element = self.find_element(*CampStatementEditPageIdentifiers.NOTE)
        if nickname_element.is_enabled() and statement_element.is_enabled() and edit_summary_element.is_enabled():
            return CanonizerCampStatementPage(self.driver)

    def load_edit_camp_statement_view_this_version(self):
        self.load_edit_camp_statement_history_page()
        try:
            self.hover(*CampStatementEditPageIdentifiers.VIEW_THIS_VERSION)
            self.find_element(*CampStatementEditPageIdentifiers.VIEW_THIS_VERSION).click()
            page_title = self.find_element(*CampStatementEditPageIdentifiers.PAGE_TITLE_2).text
            if page_title == 'Agreement':
                return CanonizerCampStatementPage(self.driver)
        except NoSuchElementException:
            return False

    def camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        :return: the element value
        """

        return \
            self.find_element(*CampStatementEditPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CampStatementEditPageIdentifiers.STATEMENT_ASTRK)

    def edit_camp_statement_without_mandatory_fields(self, summary):
        self.load_edit_camp_statement_page()
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).clear()
        self.find_element(*CampStatementEditPageIdentifiers.NOTE).send_keys(summary)
        self.find_element(*CampStatementEditPageIdentifiers.SUBMIT_UPDATE).click()
        error = self.find_element(*CampStatementEditPageIdentifiers.ERROR_STATEMENT).text
        if error == 'The statement field is required.':
            return CanonizerCampStatementPage(self.driver)

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

    def submit_statement_update_with_blank_statement(self, nick_name, note):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).clear()
        self.submit_update(nick_name, '', note)
        error = self.find_element(*CampStatementEditPageIdentifiers.ERROR_STATEMENT).text
        if error == 'The statement field is required.':
            return CanonizerCampStatementPage(self.driver)

    def submit_statement_update_with_trailing_spaces(self, statement):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).clear()
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).send_keys(statement)
        self.click_submit_update_button()
        success_message = self.find_element(*CampStatementEditPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Success! Statement submitted successfully.':
            return CanonizerCampStatementPage(self.driver)

    def edit_camp_statement_with_enter_key(self, statement):
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).clear()
        self.find_element(*CampStatementEditPageIdentifiers.STATEMENT).send_keys(statement)
        self.find_element(*CampStatementEditPageIdentifiers.SUBMIT_UPDATE).send_keys(Keys.ENTER)
        success_message = self.find_element(*CampStatementEditPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Success! Statement submitted successfully.':
            return CanonizerCampStatementPage(self.driver)

    def statement_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*CampStatementEditPageIdentifiers.ADDNEWNICKNAME).text
        except NoSuchElementException:
            return False

    def nick_name_page_should_open_update_camp_statement_add_new_nick_name(self):
        try:
            elem = self.find_element(*CampStatementEditPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerCampStatementPage(self.driver)
        except NoSuchElementException:
            return False


class AddCampStatementPage(Page):

    def load_add_camp_statement_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*BrowsePageIdentifiers.IDENTIFIERS)
        self.find_element(*BrowsePageIdentifiers.IDENTIFIERS).click()
        time.sleep(3)

        # Click Add Camp Statement This camp
        try:
            self.hover(*AddCampStatementPageIdentifiers.ADD_CAMP_STATEMENT)
            self.find_element(*AddCampStatementPageIdentifiers.ADD_CAMP_STATEMENT).click()
            page_title = self.find_element(*AddCampStatementPageIdentifiers.PAGE_TITLE).text
            if page_title == 'Add Camp Statement':
                return AddCampStatementPage(self.driver)
        except NoSuchElementException:
            return False

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
                return self.find_element(*AddCampStatementPageIdentifiers.ERROR_NICK_NAME).text
        except NoSuchElementException:
            return False

    def submit_statement_with_blank_statement(self, nick_name, note):
        self.submit_statement(nick_name, '', note)
        error = self.find_element(*AddCampStatementPageIdentifiers.ERROR_STATEMENT).text
        if error == "The statement field is required..":
            return AddCampStatementPage(self.driver)

    def submit_statement_with_valid_data(self, nick_name, statement, note):
        self.submit_statement(nick_name, statement, note)
        return self

    def add_camp_statement_page_valid_data(self, statement, note):
        self.enter_camp_statement(statement)
        self.enter_note(note)
        self.click_submit_statement_button()
        success_message = self.find_element(*AddCampStatementPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == "Success! Statement submitted successfully.":
            return AddCampStatementPage(self.driver)

    def add_camp_statement_page_invalid_data(self, statement):
        self.enter_camp_statement(statement)
        self.click_submit_statement_button()
        error = self.find_element(*AddCampStatementPageIdentifiers.ERROR_MESSAGE).text
        if error == "The statement field is required..":
            return AddCampStatementPage(self.driver)

    def add_camp_statement_page_valid_data_with_enter_key(self, statement, note):
        self.enter_camp_statement(statement)
        self.enter_note(note)
        self.find_element(*AddCampStatementPageIdentifiers.SUBMIT_STATEMENT).send_keys(Keys.ENTER)
        success_message = self.find_element(*AddCampStatementPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Success! Statement submitted successfully.':
            return AddCampStatementPage(self.driver)

    def add_camp_statement_page_without_mandatory_fields(self, statement):
        self.enter_camp_statement(statement)
        self.click_submit_statement_button()
        error = self.find_element(*AddCampStatementPageIdentifiers.ERROR_MESSAGE).text
        if error == 'The statement field is required.':
            return AddCampStatementPage(self.driver)

    def add_camp_statement_page_mandatory_fields_only(self, statement):
        self.enter_camp_statement(statement)
        self.click_submit_statement_button()
        success_message = self.find_element(*AddCampStatementPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == "Success! Statement submitted successfully.":
            return AddCampStatementPage(self.driver)

    def add_camp_statement_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*AddCampStatementPageIdentifiers.ADDNEWNICKNAME).text
        except NoSuchElementException:
            return False

    def nick_name_page_should_open_add_camp_statement_add_new_nick_name(self):
        try:
            elem = self.find_element(*AddCampStatementPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return AddCampStatementPage(self.driver)
        except NoSuchElementException:
            return False
