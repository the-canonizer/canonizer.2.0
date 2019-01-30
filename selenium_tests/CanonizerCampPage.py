from CanonizerBase import Page
from Identifiers import CreateNewCampPageIdentifiers, BrowsePageIdentifiers, TopicUpdatePageIdentifiers, CampEditPageIdentifiers
from selenium.webdriver.support.ui import Select


class CanonizerCampPage(Page):

    def load_create_camp_page(self):
        """
            Go To The topic
        """
        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_IDENTIFIER).click()

        # Click on Create New Camp
        self.hover(*CreateNewCampPageIdentifiers.CREATE_CAMP)
        self.find_element(*CreateNewCampPageIdentifiers.CREATE_CAMP).click()
        return CanonizerCampPage(self.driver)

    def create_new_camp_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """

        return \
            self.find_element(*CreateNewCampPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CreateNewCampPageIdentifiers.CAMP_NAME_ASTRK)

    def enter_nick_name(self, nickname):
        self.find_element(*CreateNewCampPageIdentifiers.NICK_NAME).send_keys(nickname)

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

    def create_camp(self, nickname, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.enter_nick_name(nickname)
        self.enter_camp_name(camp_name)
        self.enter_keywords(keywords)
        self.enter_note(note)
        self.enter_camp_about_url(camp_about_url)
        self.enter_camp_about_nick_name(camp_about_nick_name)
        self.click_create_camp_button()

    def create_camp_with_blank_nick_name(self, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp('', camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CreateNewCampPageIdentifiers.ERROR_NICK_NAME).text

    def create_camp_with_blank_camp_name(self, nick_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp(nick_name, '', keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CreateNewCampPageIdentifiers.ERROR_CAMP_NAME).text

    def create_camp_with_valid_data(self, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.create_camp(nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self

    def create_new_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        return self.find_element(*CreateNewCampPageIdentifiers.ADDNEWNICKNAME).text


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

        # Click on Manage/Edit This camp
        self.hover(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP)
        self.find_element(*CampEditPageIdentifiers.MANAGE_EDIT_CAMP).click()
        return CanonizerEditCampPage(self.driver)

    def load_camp_update_page(self):
        """

        :return:
        """
        self.load_topic_agreement_page()
        # Click on SUBMIT_CAMP_UPDATE_BASED_ON_THIS
        self.hover(*CampEditPageIdentifiers.SUBMIT_CAMP_UPDATE_BASED_ON_THIS)
        self.find_element(*CampEditPageIdentifiers.SUBMIT_CAMP_UPDATE_BASED_ON_THIS).click()
        return CanonizerEditCampPage(self.driver)

    def load_view_this_version_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        # Click on View This Version
        self.hover(*CampEditPageIdentifiers.VIEW_THIS_VERSION)
        self.find_element(*CampEditPageIdentifiers.VIEW_THIS_VERSION).click()

        return CanonizerEditCampPage(self.driver)

    def load_camp_object_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        # Click on View This Version
        self.hover(*CampEditPageIdentifiers.OBJECT)
        self.find_element(*CampEditPageIdentifiers.OBJECT).click()

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

    def submit_update(self, nickname, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.enter_nick_name(nickname)
        self.enter_camp_name(camp_name)
        self.enter_keywords(keywords)
        self.enter_note(note)
        self.enter_camp_about_url(camp_about_url)
        self.enter_camp_about_nick_name(camp_about_nick_name)
        self.click_submit_update_button()

    def submit_camp_update_with_blank_nick_name(self, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.submit_update('', camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CampEditPageIdentifiers.ERROR_NICK_NAME).text

    def submit_camp_update_with_blank_camp_name(self, nick_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.submit_update(nick_name, '', keywords, note, camp_about_url, camp_about_nick_name)
        return self.find_element(*CampEditPageIdentifiers.ERROR_CAMP_NAME).text

    def submit_camp_update_with_valid_data(self, nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name):
        self.submit_update(nick_name, camp_name, keywords, note, camp_about_url, camp_about_nick_name)
        return self

    def submit_camp_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        return self.find_element(*CampEditPageIdentifiers.ADDNEWNICKNAME).text

