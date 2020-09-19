from CanonizerBase import Page
from Identifiers import TopicUpdatePageIdentifiers, BrowsePageIdentifiers, TopicObjectPageIdentifiers
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException


class CanonizerTopicUpdatePage(Page):

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

        # Browse To Manage/Edit This Topic
        self.hover(*TopicUpdatePageIdentifiers.MANAGE_EDIT_TOPIC)
        self.find_element(*TopicUpdatePageIdentifiers.MANAGE_EDIT_TOPIC).click()
        return self.driver

    def load_topic_update_page(self):
        """

        :return:
        """
        self.load_topic_agreement_page()
        # Click on SUBMIT_TOPIC_UPDATE_BASED_ON_THIS
        self.hover(*TopicUpdatePageIdentifiers.SUBMIT_TOPIC_UPDATE_BASED_ON_THIS)
        self.find_element(*TopicUpdatePageIdentifiers.SUBMIT_TOPIC_UPDATE_BASED_ON_THIS).click()
        return CanonizerTopicUpdatePage(self.driver)

    def load_view_this_version_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()
        # Click on View This Version
        self.hover(*TopicUpdatePageIdentifiers.VIEW_THIS_VERSION)
        self.find_element(*TopicUpdatePageIdentifiers.VIEW_THIS_VERSION).click()

        return CanonizerTopicUpdatePage(self.driver)

    def load_topic_object_page(self):
        """
            Go To The topic
        """
        self.load_topic_agreement_page()

        try:
            self.hover(*TopicUpdatePageIdentifiers.OBJECT)
            self.find_element(*TopicUpdatePageIdentifiers.OBJECT).click()
            return CanonizerTopicUpdatePage(self.driver)

        except NoSuchElementException:
            return False

        return True

    def topic_update_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """

        return \
                self.find_element(*TopicUpdatePageIdentifiers.NICK_NAME_ASTRK) and \
                self.find_element(*TopicUpdatePageIdentifiers.TOPIC_NAME_ASTRK) and \
                self.find_element(*TopicUpdatePageIdentifiers.NAMESPACE_ASTRK) and \
                self.find_element(*TopicUpdatePageIdentifiers.OTHER_NAMESPACE_NAME_ASTRK)

    def topic_objection_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """

        return \
            self.find_element(*TopicObjectPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*TopicObjectPageIdentifiers.TOPIC_NAME_ASTRK) and \
            self.find_element(*TopicObjectPageIdentifiers.OBJECTION_REASON_ASTRK)

    def topic_update_page_should_have_add_new_nick_name_link_for_new_users(self):
            #return self.find_element(*TopicUpdatePageIdentifiers.ADDNEWNICKNAME).text

        try:
            return self.find_element(*TopicUpdatePageIdentifiers.ADDNEWNICKNAME)
        except NoSuchElementException:
            return False

        return True

    def enter_nick_name(self, nickname):
        self.find_element(*TopicUpdatePageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_topic_name(self, topic_name):
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_NAME).send_keys(topic_name)

    def enter_namespace(self, namespace):
        self.find_element(*TopicUpdatePageIdentifiers.NAMESPACE).send_keys(namespace)

    def enter_note(self, note):
        self.find_element(*TopicUpdatePageIdentifiers.NOTE).send_keys(note)

    def click_submit_update_button(self):
        """
        This function clicks the Create Account Button
        :return:
        """
        self.find_element(*TopicUpdatePageIdentifiers.SUBMIT_UPDATE).click()

    def submit_update(self, nickname, topic_name, namespace, note):
        self.enter_nick_name(nickname)
        self.enter_topic_name(topic_name)
        self.enter_namespace(namespace)
        self.enter_note(note)
        self.click_submit_update_button()

    def submit_update_with_blank_nick_name(self, topic_name, namespace, note):
        self.submit_update('', topic_name, namespace, note)
        #return self.find_element(*TopicUpdatePageIdentifiers.ERROR_NICK_NAME).text
        try:
            return self.find_element(*TopicUpdatePageIdentifiers.ERROR_NICK_NAME)
        except NoSuchElementException:
            return False

        return True

    def submit_update_with_blank_topic_name(self, nickname, namespace, note):
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_NAME).clear()
        self.submit_update(nickname, '', namespace, note)
        return self.find_element(*TopicUpdatePageIdentifiers.ERROR_TOPIC_NAME).text

    def submit_update_with_blank_other_namespace_name(self, nickname, topic_name, namespace, note):
        self.submit_update(nickname, topic_name, namespace, '', note)
        return self.find_element(*TopicUpdatePageIdentifiers.ERROR_OTHER_NAMESPACE_NAME).text

    def submit_topic_update_with_duplicate_topic_name(self, nick_name, topic_name, namespace, note):
        self.find_element(*TopicUpdatePageIdentifiers.TOPIC_NAME).clear()
        self.submit_update(nick_name, topic_name, namespace, note)
        return self.find_element(*TopicUpdatePageIdentifiers.ERROR_DUPLICATE_TOPIC_NAME).text

