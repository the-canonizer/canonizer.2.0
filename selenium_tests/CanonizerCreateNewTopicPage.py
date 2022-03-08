from CanonizerBase import Page
from Identifiers import CreateNewTopicPageIdentifiers, BrowsePageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time
from selenium.webdriver.support.select import Select
from selenium.webdriver.common.keys import Keys


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
        page_title = self.find_element(*CreateNewTopicPageIdentifiers.PAGE_TITLE).text
        if page_title == 'Create New Topic':
            return CanonizerCreateNewTopicPage(self.driver)

    def create_topic_without_user_login(self):
        self.hover(*CreateNewTopicPageIdentifiers.CREATE_NEW_TOPIC)
        self.find_element(*CreateNewTopicPageIdentifiers.CREATE_NEW_TOPIC).click()
        title = self.find_element(*CreateNewTopicPageIdentifiers.LOGIN_TITLE).text
        if title == 'Log in':
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
        self.enter_note(note)
        self.click_create_topic_button()

    def create_topic_with_blank_nick_name(self, topic_name, namespace, note):
        self.create_topic('', topic_name, namespace, note)
        # return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_NICK_NAME).text
        try:
            return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_NICK_NAME)
        except NoSuchElementException:
            return False

        return True

    def create_topic_with_blank_topic_name(self, nickname, namespace, note):
        self.create_topic(nickname, '', namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_TOPIC_NAME).text

    def create_topic_with_blank_spaces_topic_name(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, "        ", namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_TOPIC_NAME).text

    def create_new_topic_page_should_have_add_new_nick_name_link_for_new_users(self):
        try:
            return self.find_element(*CreateNewTopicPageIdentifiers.ADDNEWNICKNAME)
        except NoSuchElementException:
            return False

        return True

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

    def create_topic_with_valid_data(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text

    def verify_single_support_on_new_topic_creation(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)

        success_message = self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE_TOPIC_CREATION).text
        if success_message == 'Success! Topic created successfully.':
            self.hover(*CreateNewTopicPageIdentifiers.CREATED_TOPIC_NAME)
            self.find_element(*CreateNewTopicPageIdentifiers.CREATED_TOPIC_NAME).click()
            support_badge = self.find_element(*CreateNewTopicPageIdentifiers.SUPPORT_BADGE).text
            manage_support = self.find_element(*CreateNewTopicPageIdentifiers.JOIN_CAMP_SUPPORT).text
            if support_badge == '1' and manage_support == 'Manage Support':
                print("Yes")
                return CanonizerCreateNewTopicPage(self.driver)
            else:
                return False
        else:
            return False



    # def verify_directly_join_and_support(self, nickname, topic_name, namespace, note):
    #     self.create_topic(nickname, topic_name, namespace, note)
    #
    #     success_message = self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text
    #     if success_message == 'Success! Topic created successfully.':
    #         self.hover(*CreateNewTopicPageIdentifiers.CREATED_TOPIC_NAME)
    #         self.find_element(*CreateNewTopicPageIdentifiers.CREATED_TOPIC_NAME).click()
    #         support_badge = self.find_element(*CreateNewTopicPageIdentifiers.SUPPORT_BADGE).text
    #         manage_support = self.find_element(*CreateNewTopicPageIdentifiers.JOIN_CAMP_SUPPORT).text
    #         if support_badge == '1' and manage_support == 'Manage Support':
    #             self.find_element(*CreateNewTopicPageIdentifiers.DIRECT_JOIN_AND_SUPPORT).click()
    #             page_title = self.find_element(*CreateNewTopicPageIdentifiers.PAGE_TITLE).text
    #             if page_title == 'Create New Camp':
    #                 print("Create New Camp")
    #                 self.find_element(*CreateNewTopicPageIdentifiers.CAMP_NAME).send_keys(
    #                     "Supporting Camp with New Camp")
    #                 self.find_element(*CreateNewTopicPageIdentifiers.CREATE_CAMP_BUTTON).click()
    #                 camp_success_message = self.find_element(*CreateNewTopicPageIdentifiers.CAMP_SUCCESS_MESSAGE).text
    #                 if camp_success_message == 'Success! Camp created successfully.':
    #                     print(camp_success_message, "Success Message")
    #                     self.find_element(*CreateNewTopicPageIdentifiers.NEW_SUPPORT_CAMP).click()
    #                     directly_join_and_support = self.find_element(
    #                             *CreateNewTopicPageIdentifiers.JOIN_CAMP_SUPPORT).text
    #                     print("Directly join and Support", directly_join_and_support)
    #                     if directly_join_and_support == 'Directly Join and Support':
    #                         print("Here")
    #                         return CanonizerCreateNewTopicPage(self.driver)
    #
    #     return False

    def create_topic_with_invalid_topic_name(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        error = self.find_element(*CreateNewTopicPageIdentifiers.INVALID_TOPIC_NAME).text
        if error == 'Topic name can only contain space and alphanumeric characters.':
            return CanonizerCreateNewTopicPage(self.driver)

    def create_topic_without_entering_mandatory_fields(self, nickname, topic_name, namespace, note):
        self.create_topic('', '', '', '')
        return self.find_element(*CreateNewTopicPageIdentifiers.ERROR_TOPIC_NAME).text

    def create_topic_with_entering_data_only_in_mandatory_fields(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, '')
        return self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text

    def validation_of_nick_name_dropdown(self):
        self.find_element(*CreateNewTopicPageIdentifiers.NICK_NAME).click()
        nick_names = Select(self.find_element(*CreateNewTopicPageIdentifiers.NICK_NAME))
        nick_name_values = nick_names.options
        nick_name_list = []
        for ele in nick_name_values:
            nick_name_list.append(ele.text)
        if nick_name_list:
            return CanonizerCreateNewTopicPage(self.driver)
        else:
            return False

    def create_topic_name_with_enter_key_verifying_history_page(self, nickname, topic_name, namespace, note):
        self.enter_nick_name(nickname)
        self.enter_topic_name(topic_name)
        self.enter_namespace(namespace)
        self.enter_note(note)
        self.find_element(*CreateNewTopicPageIdentifiers.CREATETOPIC).send_keys(Keys.ENTER)
        if self.find_element(
                *CreateNewTopicPageIdentifiers.TOPIC_NAME_HISTORY_PAGE).text == 'Topic Name : Testing Topic 1':
            return self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text
        else:
            return False

    def create_topic_name_with_trailing_space(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        return self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text

    def verifying_nick_name_from_dropdown_while_creating_topic(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        if self.find_element(
                *CreateNewTopicPageIdentifiers.NICK_NAME_HISTORY_PAGE).text == 'Submitter Nick Name : poo_':
            return self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text
        else:
            return False

    def verifying_topic_name_from_namespaces_in_browse(self, nickname, topic_name, namespace, note):
        self.create_topic(nickname, topic_name, namespace, note)
        result = self.find_element(*CreateNewTopicPageIdentifiers.SUCCESS_MESSAGE).text
        self.find_element(*CreateNewTopicPageIdentifiers.BROWSE).click()

        html_list = self.find_element(*CreateNewTopicPageIdentifiers.NAMESPACE_LIST)
        items = html_list.find_elements_by_tag_name("li")
        for item in items:
            text = item.text
            print(text)

    def nick_name_page_should_open_create_topic_add_new_nick_name(self):
        try:
            elem = self.find_element(*CreateNewTopicPageIdentifiers.ADDNEWNICKNAME)
            if elem.is_displayed():
                elem.click()
                time.sleep(2)
                return CanonizerCreateNewTopicPage(self.driver)
        except NoSuchElementException:
            return False
        return True
