from selenium.webdriver.common.keys import Keys

from CanonizerBase import Page
from Identifiers import CreateNewCampPageIdentifiers, BrowsePageIdentifiers, TopicUpdatePageIdentifiers, \
    CampEditPageIdentifiers, HomePageIdentifiers, BreadCrumbsLinksIdentifiers, CampStatementEditPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time


class CanonizerCampPage(Page):
    new_camp = 'Create New Camp'
    agreement = "Agreement"
    login = 'Log in'

    def load_topic_page(self):
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()
        return CanonizerCampPage(self.driver)

    def load_create_camp_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        self.hover(*CreateNewCampPageIdentifiers.CREATE_CAMP)
        self.find_element(*CreateNewCampPageIdentifiers.CREATE_CAMP).click()
        title = self.find_element(*CreateNewCampPageIdentifiers.TITLE).text
        if title == self.new_camp:
            return CanonizerCampPage(self.driver)

    def load_create_camp_page_without_login(self):
        self.load_topic_page()
        # Click on Create New Camp
        self.hover(*CreateNewCampPageIdentifiers.CREATE_CAMP)
        self.find_element(*CreateNewCampPageIdentifiers.CREATE_CAMP).click()
        title = self.find_element(*CreateNewCampPageIdentifiers.HEADING).text
        if title == self.login:
            return CanonizerCampPage(self.driver)

    def load_create_new_camp_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        self.hover(*HomePageIdentifiers.CREATE_NEW_CAMP)
        self.find_element(*HomePageIdentifiers.CREATE_NEW_CAMP).click()
        heading = self.find_element(*HomePageIdentifiers.CAMP_HEADING).text
        if heading == self.new_camp:
            return CanonizerCampPage(self.driver)

    def create_new_camp_page_mandatory_fields_are_marked_with_asterisk(self):
        return \
            self.find_element(*CreateNewCampPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CreateNewCampPageIdentifiers.CAMP_NAME_ASTRK)

    def enter_nick_name(self, nickname):
        self.find_element(*CreateNewCampPageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_parent_camp_name(self, parent_camp_name):
        self.find_element(*CreateNewCampPageIdentifiers.PARENT_CAMP).send_keys(parent_camp_name)

    def enter_camp_name(self, camp_name):
        self.find_element(*CreateNewCampPageIdentifiers.CAMP_NAME).send_keys(camp_name)

    def enter_keywords(self, keywords):
        self.find_element(*CreateNewCampPageIdentifiers.KEYWORDS).send_keys(keywords)

    def enter_note(self, note):
        self.find_element(*CreateNewCampPageIdentifiers.ADDITIONAL_NOTE).send_keys(note)

    def enter_camp_about_url(self, camp_about_url):
        self.find_element(*CreateNewCampPageIdentifiers.CAMP_ABOUT_URL).send_keys(camp_about_url)

    def enter_camp_about_nick_name(self, camp_about_nick_name):
        self.find_element(*CreateNewCampPageIdentifiers.CAMP_ABOUT_NICK_NAME).send_keys(camp_about_nick_name)

    def click_create_camp_button(self):
        """
        This function clicks the Create Account Button
        :return:
        """
        self.find_element(*CreateNewCampPageIdentifiers.CREATE_CAMP_BUTTON).click()

    def create_camp(self, *args):
        args = list(args[0])
        self.enter_parent_camp_name(args[0])
        self.enter_nick_name(args[1])
        self.enter_camp_name(args[2])
        self.enter_keywords(args[3])
        self.enter_note(args[4])
        self.enter_camp_about_url(args[5])
        self.enter_camp_about_nick_name(args[6])
        self.click_create_camp_button()

    def create_camp_with_blank_nick_name(self, CREATE_CAMP_LIST_1):
        self.create_camp(CREATE_CAMP_LIST_1)
        error = self.find_element(*CreateNewCampPageIdentifiers.ERROR_NICK_NAME)
        if error == 'The nick name field is required.':
            CanonizerCampPage(self.driver)

    def create_camp_with_blank_camp_name(self, CREATE_CAMP_LIST_2):
        self.create_camp(CREATE_CAMP_LIST_2)
        error = self.find_element(*CreateNewCampPageIdentifiers.ERROR_CAMP_NAME).text
        if error == "Camp name is required.":
            CanonizerCampPage(self.driver)

    def create_camp_with_valid_data(self, nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url,
                                    camp_about_nick_name):
        self.create_camp(nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self

    def create_new_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*CreateNewCampPageIdentifiers.ADDNEWNICKNAME)
        except NoSuchElementException:
            return False

    def nick_name_page_should_open_create_camp_add_new_nick_name(self):
        try:
            elem = self.find_element(*CreateNewCampPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerCampPage(self.driver)
        except NoSuchElementException:
            return False

    def create_camp_with_duplicate_camp_name(self, CREATE_CAMP_LIST_3):
        self.create_camp(CREATE_CAMP_LIST_3)
        error = self.find_element(*CreateNewCampPageIdentifiers.ERROR_DUPLICATE_CAMP_NAME).text
        if error == 'The camp name has already been taken':
            return CanonizerCampPage(self.driver)

    def create_camp_with_invalid_camp_name(self, CREATE_CAMP_LIST_4):
        self.create_camp(CREATE_CAMP_LIST_4)
        error = self.find_element(*CreateNewCampPageIdentifiers.ERROR_INVALID_CAMP_NAME).text
        if error == 'Camp name can only contain space and alphanumeric characters.':
            return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.AGREEMENT_CAMP)
        self.find_element(*BreadCrumbsLinksIdentifiers.AGREEMENT_CAMP).click()
        return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_child_camp_link(self):
        self.load_topic_page()

        self.hover(*BreadCrumbsLinksIdentifiers.CHILD_CAMP_TYPES_OF_TESTING)
        self.find_element(*BreadCrumbsLinksIdentifiers.CHILD_CAMP_TYPES_OF_TESTING).click()

        self.hover(*BreadCrumbsLinksIdentifiers.UP)
        self.find_element(*BreadCrumbsLinksIdentifiers.UP).click()

        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.CHILD_CAMP_TYPES_OF_TESTING_CAMP)
        self.find_element(*BreadCrumbsLinksIdentifiers.CHILD_CAMP_TYPES_OF_TESTING_CAMP).click()
        return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_forum_agreement_camp_link(self):
        self.load_topic_page()
        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.CAMP_FORUM)
        self.find_element(*BreadCrumbsLinksIdentifiers.CAMP_FORUM).click()
        self.hover(*BreadCrumbsLinksIdentifiers.CAMP_FORUM_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.CAMP_FORUM_AGREEMENT).click()
        title = self.find_element(*BreadCrumbsLinksIdentifiers.TITLE).text
        if self.agreement in title:
            return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_camp_statement_history_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT)
        self.find_element(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT).click()
        self.hover(*BreadCrumbsLinksIdentifiers.CAMP_STATEMENT_HISTORY_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.CAMP_STATEMENT_HISTORY_AGREEMENT).click()
        title = self.find_element(*BreadCrumbsLinksIdentifiers.TITLE).text
        if self.agreement in title:
            return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_camp_supported_camps_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.JOIN_SUPPORT_CAMP)
        self.find_element(*BreadCrumbsLinksIdentifiers.JOIN_SUPPORT_CAMP).click()
        self.hover(*BreadCrumbsLinksIdentifiers.SUPPORTED_CAMPS_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.SUPPORTED_CAMPS_AGREEMENT).click()
        title = self.find_element(*BreadCrumbsLinksIdentifiers.TITLE).text
        if self.agreement in title:
            return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_camp_history_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        self.hover(*BreadCrumbsLinksIdentifiers.CAMP_HISTORY_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.CAMP_HISTORY_AGREEMENT).click()

        return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_create_new_camp_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*HomePageIdentifiers.CREATE_NEW_CAMP)
        self.find_element(*HomePageIdentifiers.CREATE_NEW_CAMP).click()
        self.hover(*BreadCrumbsLinksIdentifiers.CREATE_NEW_CAMP_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.CREATE_NEW_CAMP_AGREEMENT).click()
        title = self.find_element(*BreadCrumbsLinksIdentifiers.TITLE).text
        if self.agreement in title:
            return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_topic_history_topic_name_link(self):
        self.load_topic_page()

        url = self.driver.current_url
        self.driver.get(url)
        self.driver.execute_script("window.scrollTo(0,document.body.scrollHeight)")

        # Click on agreement camp on bread crumbs
        self.hover(*TopicUpdatePageIdentifiers.MANAGE_EDIT_TOPIC)
        self.find_element(*TopicUpdatePageIdentifiers.MANAGE_EDIT_TOPIC).click()
        self.hover(*BreadCrumbsLinksIdentifiers.TOPIC_HISTORY_TOPIC_NAME)
        self.find_element(*BreadCrumbsLinksIdentifiers.TOPIC_HISTORY_TOPIC_NAME).click()
        title = self.find_element(*BreadCrumbsLinksIdentifiers.TITLE).text
        if self.agreement in title:
            return CanonizerCampPage(self.driver)

    def load_create_camp_page_from_bread_crumb_link(self):
        self.load_topic_page()
        time.sleep(3)
        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.AGREEMENT_CAMP)
        self.find_element(*BreadCrumbsLinksIdentifiers.AGREEMENT_CAMP).click()
        time.sleep(3)
        # Click on Create New Camp
        self.hover(*CreateNewCampPageIdentifiers.CREATE_CAMP)
        self.find_element(*CreateNewCampPageIdentifiers.CREATE_CAMP).click()
        title = self.find_element(*CreateNewCampPageIdentifiers.TITLE_CREATE_NEW_CAMP).text
        if title == self.new_camp:
            return CanonizerCampPage(self.driver)


class CanonizerEditCampPage(Page):
    success_message = "Success! Camp change submitted successfully."

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

        # Browse to Camp Name
        self.hover(*CampEditPageIdentifiers.CAMP_IDENTIFIER)
        self.find_element(*CampEditPageIdentifiers.CAMP_IDENTIFIER).click()

        return CanonizerEditCampPage(self.driver)

    def load_camp_manage_edit_page(self):
        self.load_topic_agreement_page()
        # Click on Manage/Edit This camp
        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        page_title = self.find_element(*CampEditPageIdentifiers.PAGE_TITLE).text
        if page_title == 'Camp History':
            return CanonizerEditCampPage(self.driver)

    def verify_agreement_page(self):
        self.load_camp_manage_edit_page()
        self.find_element(*CampEditPageIdentifiers.VIEW_THIS_VERSION).click()
        try:
            page_title = self.find_element(*CampEditPageIdentifiers.PAGE_HEADING).text
            if 'Agreement' in page_title:
                return CanonizerEditCampPage(self.driver)
        except NoSuchElementException:
            return False

    def load_camp_update_page(self):
        self.load_topic_agreement_page()

        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        # Click on SUBMIT_CAMP_UPDATE_BASED_ON_THIS
        self.hover(*CampEditPageIdentifiers.SUBMIT_CAMP_UPDATE_BASED_ON_THIS)
        self.find_element(*CampEditPageIdentifiers.SUBMIT_CAMP_UPDATE_BASED_ON_THIS).click()
        try:
            page_title = self.find_element(*CampEditPageIdentifiers.PAGE_TITLE).text
            if 'Camp Update' in page_title:
                return CanonizerEditCampPage(self.driver)
        except NoSuchElementException:
            return False

    def load_view_this_version_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        # Click on Manage/Edit This camp
        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        # Click on View This Version
        self.hover(*CampEditPageIdentifiers.VIEW_THIS_VERSION)
        self.find_element(*CampEditPageIdentifiers.VIEW_THIS_VERSION).click()

        return CanonizerEditCampPage(self.driver)

    def load_camp_object_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        # Click on Manage/Edit This camp

        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        # Click on View This Version
        self.hover(*CampEditPageIdentifiers.OBJECT)
        self.find_element(*CampEditPageIdentifiers.OBJECT).click()

        return CanonizerEditCampPage(self.driver)

    def load_camp_user_supports_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        # Click on user nick name
        try:
            self.hover(*CampEditPageIdentifiers.USER_NICK_NAME)
            self.find_element(*CampEditPageIdentifiers.USER_NICK_NAME).click()
            time.sleep(6)
            return CanonizerEditCampPage(self.driver)
        except NoSuchElementException:
            return False

    def load_camp_agreement_from_user_supports_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        # Click on user nick name
        self.hover(*CampEditPageIdentifiers.USER_NICK_NAME)
        self.find_element(*CampEditPageIdentifiers.USER_NICK_NAME).click()
        time.sleep(3)
        self.hover(*CampEditPageIdentifiers.USER_SUPPORTS_CAMP_NAME)
        self.find_element(*CampEditPageIdentifiers.USER_SUPPORTS_CAMP_NAME).click()
        return CanonizerEditCampPage(self.driver)

    def camp_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """

        return \
            self.find_element(*CampEditPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CampEditPageIdentifiers.CAMP_NAME_ASTRK)

    def enter_parent_camp_name(self, parent_camp_name):
        self.find_element(*CreateNewCampPageIdentifiers.PARENT_CAMP).send_keys(parent_camp_name)

    def enter_nick_name(self, nickname):
        self.find_element(*CampEditPageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_camp_name(self, camp_name):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).send_keys(camp_name)

    def enter_keywords(self, keywords):
        self.find_element(*CampEditPageIdentifiers.KEYWORDS).send_keys(keywords)

    def enter_note(self, note):
        self.find_element(*CampEditPageIdentifiers.ADDITIONAL_NOTE).send_keys(note)

    def enter_camp_about_url(self, camp_about_url):
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).send_keys(camp_about_url)

    def enter_camp_about_nick_name(self, camp_about_nick_name):
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_NICK_NAME).send_keys(camp_about_nick_name)

    def click_submit_update_button(self):
        """
        This function clicks the Submit Update Button
        :return:
        """
        self.find_element(*CampEditPageIdentifiers.SUBMIT_UPDATE).click()

    def submit_update(self, *args):
        args = list(args[0])
        self.enter_parent_camp_name(args[0])
        self.enter_nick_name(args[1])
        self.enter_camp_name(args[2])
        self.enter_keywords(args[3])
        self.enter_note(args[4])
        self.enter_camp_about_url(args[5])
        self.enter_camp_about_nick_name(args[6])
        self.click_submit_update_button()

    def submit_camp_update_with_blank_nick_name(self, CAMP_LIST_1):
        self.submit_update(CAMP_LIST_1)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_NICK_NAME)
        if error == 'The nick name field is required.':
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_blank_camp_name(self, CAMP_LIST_10):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.submit_update(CAMP_LIST_10)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_NAME).text
        if error == 'Camp name is required.':
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_valid_data(self, CAMP_LIST_2):
        self.submit_update(CAMP_LIST_2)
        success_message = self.find_element(*CampEditPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == self.success_message:
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_invalid_data(self, CAMP_LIST_3):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.submit_update(CAMP_LIST_3)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_ABOUT_URL).text
        if error == "The camp about url format is invalid. (Example: https://www.example.com?post=1234)":
            return CanonizerEditCampPage(self.driver)

    def update_with_enter_key(self, *args):
        args = list(args[0])
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.find_element(*CampEditPageIdentifiers.KEYWORDS).clear()
        self.find_element(*CampEditPageIdentifiers.ADDITIONAL_NOTE).clear()
        self.enter_parent_camp_name(args[0])
        self.enter_nick_name(args[1])
        self.enter_camp_name(args[2])
        self.enter_keywords(args[3])
        self.enter_note(args[4])
        self.enter_camp_about_url(args[5])
        self.enter_camp_about_nick_name(args[6])
        self.find_element(*CampEditPageIdentifiers.SUBMIT_UPDATE).send_keys(Keys.ENTER)

    # .send_keys(Keys.ENTER)
    def submit_camp_update_with_invalid_data_with_enter_key(self, CAMP_LIST_3):
        self.update_with_enter_key(CAMP_LIST_3)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_ABOUT_URL).text
        if error == "The camp about url format is invalid. (Example: https://www.example.com?post=1234)":
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_valid_data_with_enter_key(self, CAMP_LIST_2):
        self.update_with_enter_key(CAMP_LIST_2)
        success_message = self.find_element(*CampEditPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == self.success_message:
            return CanonizerEditCampPage(self.driver)

    def test_submit_camp_update_with_mandatory_fields_only(self, CAMP_LIST_4):
        self.find_element(*CampEditPageIdentifiers.KEYWORDS).clear()
        self.find_element(*CampEditPageIdentifiers.ADDITIONAL_NOTE).clear()
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.submit_update(CAMP_LIST_4)
        success_message = self.find_element(*CampEditPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == self.success_message:
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_tailing_spaces(self, CAMP_LIST_5):
        self.find_element(*CampEditPageIdentifiers.KEYWORDS).clear()
        self.find_element(*CampEditPageIdentifiers.ADDITIONAL_NOTE).clear()
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.submit_update(CAMP_LIST_5)
        success_message = self.find_element(*CampEditPageIdentifiers.SUCCESS_MESSAGE).text
        if success_message == self.success_message:
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_invalid_url(self, CAMP_LIST_6):
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.submit_update(CAMP_LIST_6)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_ABOUT_URL).text
        if error == "The camp about url format is invalid. (Example: https://www.example.com?post=1234)":
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        return self.find_element(*CampEditPageIdentifiers.ADDNEWNICKNAME).text

    def submit_camp_update_with_duplicate_camp_name(self, CAMP_LIST_7):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.submit_update(CAMP_LIST_7)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_DUPLICATE_CAMP_NAME).text
        if error == 'The camp name has already been taken':
            return CanonizerEditCampPage(self.driver)

    def submit_camp_update_with_invalid_camp_name(self, CAMP_LIST_8):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.submit_update(CAMP_LIST_8)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_NAME).text
        if error == 'Camp name can only contain space and alphanumeric characters.':
            return CanonizerEditCampPage(self.driver)

    def nick_name_page_should_open_update_camp_add_new_nick_name(self):
        try:
            elem = self.find_element(*CampEditPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerEditCampPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def submit_camp_update_with_invalid_length_camp_about_url(self, CAMP_LIST_9):
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.submit_update(CAMP_LIST_9)
        error = self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_ABOUT_URL).text
        if error == "Camp's about url can not be more than 1024 characters.":
            return CanonizerEditCampPage(self.driver)
