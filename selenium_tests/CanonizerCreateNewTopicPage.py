from CanonizerBase import Page
from Identifiers import CreateNewTopicPageIdentifiers
from selenium.webdriver.support.select import Select


class CanonizerCreateNewTopicPage(Page):

    def click_create_new_topic_page_button(self):
        """
        This function is to click on the login button

        -> Hover to the login button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*CreateNewTopicPageIdentifiers.CREATE_NEW_TOPIC)
        self.find_element(*CreateNewTopicPageIdentifiers.CREATE_NEW_TOPIC).click()
        return CanonizerCreateNewTopicPage(self.driver)

    def enter_nick_name(self, nickname):
        self.find_element(*CreateNewTopicPageIdentifiers.NICK_NAME).send_keys(nickname)

    def enter_topic_name(self, topic_name):
        self.find_element(*CreateNewTopicPageIdentifiers.TOPIC_NAME).send_keys(topic_name)

    def enter_namespace(self, namespace):
        self.find_element(*CreateNewTopicPageIdentifiers.NAMESPACE).send_keys(namespace)

    def enter_other_namespace_name(self, other_namespace_name):
        self.find_element(*CreateNewTopicPageIdentifiers.OTHER_NAMESPACE_NAME).send_keys(other_namespace_name)

    def enter_note(self, note):
        self.find_element(*CreateNewTopicPageIdentifiers.NOTE).send_keys(note)

    def click_create_topic_button(self):
        """
        This function clicks the Create Account Button
        :return:
        """
        self.find_element(*CreateNewTopicPageIdentifiers.CREATETOPIC).click()

    def create_topic(self, nickname, topic_name, namespace, note):
        self.enter_nick_name(nickname)
        self.enter_topic_name(topic_name)
        self.enter_namespace(namespace)
        #self.enter_other_namespace_name(other_namespace_name)
        self.enter_note(note)
        self.click_create_topic_button()

    def create_topic_with_blank_nick_name(self, topic_name, namespace, note):
        self.create_topic('', topic_name, namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_NICK_NAME).text

    def create_topic_with_blank_topic_name(self, nickname, namespace, note):
        self.create_topic(nickname, '', namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_TOPIC_NAME).text

    def create_topic_with_blank_other_namespace_name(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, '', note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_NOTE).text

    def create_new_topic_page_should_have_add_new_nick_name_link_for_new_users(self):
        return self.find_element(*CreateNewTopicPageIdentifiers.ADDNEWNICKNAME).text

    def create_new_topic_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """
        return \
            self.find_element(*CreateNewTopicPageIdentifiers.NICK_NAME_ASTRK) and \
            self.find_element(*CreateNewTopicPageIdentifiers.TOPIC_NAME_ASTRK) and \
            self.find_element(*CreateNewTopicPageIdentifiers.NAMESPACE_ASTRK)

    def create_topic_with_duplicate_topic_name(self, nick_name, topic_name, namespace, note):
        self.create_topic(nick_name, topic_name, namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_DUPLICATE_TOPIC_NAME).text

    def create_topic_with_invalid_topic_name_length(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_INVALID_TOPIC_NAME_LENGTH).text

    def create_topic_with_valid_data(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        return self
