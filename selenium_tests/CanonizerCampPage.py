from CanonizerBase import Page
from Identifiers import CreateNewCampPageIdentifiers, BrowsePageIdentifiers, TopicUpdatePageIdentifiers, CampEditPageIdentifiers, HomePageIdentifiers, BreadCrumbsLinksIdentifiers,CampStatementEditPageIdentifiers
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException
import time


class CanonizerCampPage(Page):

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
        time.sleep(3)
        self.hover(*CreateNewCampPageIdentifiers.CREATE_CAMP)
        self.find_element(*CreateNewCampPageIdentifiers.CREATE_CAMP).click()
        return CanonizerCampPage(self.driver)

    def load_create_new_camp_page(self):
        self.load_topic_page()
        # Click on Create New Camp
        self.hover(*HomePageIdentifiers.CREATE_NEW_CAMP)
        self.find_element(*HomePageIdentifiers.CREATE_NEW_CAMP).click()
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

    def create_camp(self, nickname, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.enter_nick_name(nickname)
        self.enter_parent_camp_name(parent_camp_name)
        self.enter_camp_name(camp_name)
        self.enter_keywords(keywords)
        self.enter_note(note)
        self.enter_camp_about_url(camp_about_url)
        self.enter_camp_about_nick_name(camp_about_nick_name)
        self.click_create_camp_button()

    def create_camp_with_blank_nick_name(self, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp('', parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        try:
            return self.find_element(*CreateNewCampPageIdentifiers.ERROR_NICK_NAME)
        except NoSuchElementException:
            return False
        return True

    def create_camp_with_blank_camp_name(self, nick_name, parent_camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp(nick_name, parent_camp_name, '', keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CreateNewCampPageIdentifiers.ERROR_CAMP_NAME).text

    def create_camp_with_valid_data(self, nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp(nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self

    def create_new_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*CreateNewCampPageIdentifiers.ADDNEWNICKNAME)
        except NoSuchElementException:
            return False
        return True

    def nick_name_page_should_open_create_camp_add_new_nick_name(self):
        try:
            elem = self.find_element(*CreateNewCampPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerCampPage(self.driver)
        except NoSuchElementException:
            return False
        return True

    def create_camp_with_duplicate_camp_name(self, nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp(nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CreateNewCampPageIdentifiers.ERROR_DUPLICATE_CAMP_NAME).text

    def create_camp_with_invalid_camp_name(self, nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp(nick_name, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CreateNewCampPageIdentifiers.ERROR_INVALID_CAMP_NAME).text

    def load_agreement_page_from_bread_crumb_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.AGREEMENT_CAMP)
        self.find_element(*BreadCrumbsLinksIdentifiers.AGREEMENT_CAMP).click()
        return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_child_camp_link(self):
        self.load_topic_page()
        time.sleep(3)

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

        return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_camp_statement_history_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT)
        self.find_element(*CampStatementEditPageIdentifiers.EDIT_CAMP_STATEMENT).click()
        self.hover(*BreadCrumbsLinksIdentifiers.CAMP_STATEMENT_HISTORY_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.CAMP_STATEMENT_HISTORY_AGREEMENT).click()

        return CanonizerCampPage(self.driver)

    def load_agreement_page_from_bread_crumb_camp_supported_camps_agreement_camp_link(self):
        self.load_topic_page()

        # Click on agreement camp on bread crumbs
        self.hover(*BreadCrumbsLinksIdentifiers.JOIN_SUPPORT_CAMP)
        self.find_element(*BreadCrumbsLinksIdentifiers.JOIN_SUPPORT_CAMP).click()
        self.hover(*BreadCrumbsLinksIdentifiers.SUPPORTED_CAMPS_AGREEMENT)
        self.find_element(*BreadCrumbsLinksIdentifiers.SUPPORTED_CAMPS_AGREEMENT).click()

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
        return CanonizerCampPage(self.driver)


class CanonizerEditCampPage(Page):

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

    def load_camp_update_page(self):
        self.load_topic_agreement_page()
        # Click on Manage/Edit This camp
        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        # Click on SUBMIT_CAMP_UPDATE_BASED_ON_THIS
        self.hover(*CampEditPageIdentifiers.SUBMIT_CAMP_UPDATE_BASED_ON_THIS)
        self.find_element(*CampEditPageIdentifiers.SUBMIT_CAMP_UPDATE_BASED_ON_THIS).click()
        return CanonizerEditCampPage(self.driver)

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
            return CanonizerEditCampPage(self.driver)
        except NoSuchElementException:
            return False
        return CanonizerEditCampPage(self.driver)

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

    def submit_update(self, parentcampname, nickname, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.enter_parent_camp_name(parentcampname)
        self.enter_nick_name(nickname)
        self.enter_camp_name(camp_name)
        self.enter_keywords(keywords)
        self.enter_note(note)
        self.enter_camp_about_url(camp_about_url)
        self.enter_camp_about_nick_name(camp_about_nick_name)
        self.click_submit_update_button()

    def submit_camp_update_with_blank_nick_name(self, parent_camp_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.submit_update(parent_camp_name, '', camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        try:
            return self.find_element(*CampEditPageIdentifiers.ERROR_NICK_NAME)
        except NoSuchElementException:
            return False

        return True

    def submit_camp_update_with_blank_camp_name(self, parent_camp_name, nick_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.submit_update(parent_camp_name, nick_name, '', keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_NAME).text

    def submit_camp_update_with_valid_data(self, parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.submit_update(parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self

    def submit_camp_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        return self.find_element(*CampEditPageIdentifiers.ADDNEWNICKNAME).text

    def submit_camp_update_with_duplicate_camp_name(self, parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.submit_update(parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CampEditPageIdentifiers.ERROR_DUPLICATE_CAMP_NAME).text

    def submit_camp_update_with_invalid_camp_name(self, parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.find_element(*CampEditPageIdentifiers.CAMP_NAME).clear()
        self.submit_update(parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_NAME).text

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

    def submit_camp_update_with_invalid_length_camp_about_url(self, parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.find_element(*CampEditPageIdentifiers.CAMP_ABOUT_URL).clear()
        self.submit_update(parent_camp_name, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_ABOUT_URL).text
        time.sleep(2)

