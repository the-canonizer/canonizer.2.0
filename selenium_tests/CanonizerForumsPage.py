from CanonizerBase import Page
from Identifiers import *
from selenium.common.exceptions import NoSuchElementException, ImeNotAvailableException
from time import sleep
import re


class CanonizerForumsPage(Page):

    def enter_thread_title(self, title):
        """
            Enter Title Of The Thread
        :return:
        """
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_TITLE).send_keys(title)

    def click_submit_button_to_create_thread(self):
        """
            Click submit button to create thread
        :return:
        """
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_SUBMIT).click()

    def click_create_new_thread_link(self):
        self.hover(*CampForumIdentifiers.CAMP_FORUM_CREATE_NEW)
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_CREATE_NEW).click()

    def click_threads_page_2(self):
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_PAGTN).click()

    def click_on_thread(self):
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_PATH).click()

    def load_camp_forum_page(self):
        """
            Go To The Camp Forum
        """
        # Browse to Camp
        self.hover(*CampForumIdentifiers.CAMP_IDENTIFIER)
        self.find_element(*CampForumIdentifiers.CAMP_IDENTIFIER).click()

        # Browse To Camp Forum
        self.hover(*CampForumIdentifiers.CAMP_FORUM_IDENTIFIER)
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_IDENTIFIER).click()

    def enter_post_body(self):
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_POST_BODY).send_keys("Test Body Post")

    def verify_topic_and_camp_name(self):
        """
            Verify The Topic and Camp Name
        """

        self.load_camp_forum_page()
        self.hover(*CampForumIdentifiers.CAMP_FORUM_TOPIC_NAME)

        topic_name = self.find_element(*CampForumIdentifiers.CAMP_FORUM_TOPIC_NAME)
        camp_name  = self.find_element(*CampForumIdentifiers.CAMP_FORUM_TOPIC_NAME)

        return len(topic_name.text.split(" : ")), camp_name.text

    # Need to Check
    def forum_threads_must_be_clickable(self):
        """
            Forum Threads Must Be Clickable
        :return:
        """
        self.hover(*CampForumIdentifiers.CAMP_FORUM_THREAD_1)
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_1).click()

        return self.driver

    def forums_load_create_new_thread_page(self):
        """
            Load Create New Threads Page on Camp Forums
        :return:
        """
        self.load_camp_forum_page()

        self.hover(*CampForumIdentifiers.CAMP_FORUM_CREATE_NEW)
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_CREATE_NEW).click()

        return self.driver

    def forums_create_new_thread(self):
        """
            Create New Thread on Camp Forums
        :return:
            If Success, Threads created successfully, else failed
        """
        self.load_camp_forum_page()

        self.forums_load_create_new_thread_page()
        self.enter_thread_title("Automated Test Thread Created")
        self.click_submit_button_to_create_thread()

        if re.search('threads$', self.driver.current_url, re.M):
            return 'Threads Created SuccessFully'
        else:
            return 'Failed To Create New Thread'

    def forums_threads_have_pagination(self):
        """
            Check pagination of forums threads
        :return:
        """
        self.load_camp_forum_page()
        self.click_threads_page_2()

        if re.search('page=2$', self.driver.current_url, re.M):
            return True
        else:
            sleep(5)
            return False

    def forums_authenticated_user_can_reply_to_threads(self, test_thread_url):
        """
            Authenticate User can Reply to thread
        :return:
        """
        self.driver.get(test_thread_url)
        self.click_on_thread()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_POST_SUBMIT)
        except NoSuchElementException:
            return False

        self.enter_post_body()
        self.find_element(*CampForumIdentifiers.CAMP_FORUM_POST_SUBMIT).click()

        return True

    def forums_guest_user_cannot_reply_to_threads(self, test_thread_url):
        """
            Guest User cannot post to threads
        :param test_thread_url:
        :return:
        """
        self.driver.get(test_thread_url)
        self.click_on_thread()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_POST_SUBMIT)
        except NoSuchElementException:
            return False
        return True

    def forums_threads_must_have_title_field(self, test_thread_url):
        """
            Automated Test Case to check if the thread has title field
        :param test_thread_url:
        :return:
        """
        self.driver.get(test_thread_url)
        self.click_create_new_thread_link()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_TITLE)
        except NoSuchElementException:
            return False

        return True

    def forums_thread_title_cannot_be_left_blank(self, test_thread_url):
        """

        :param test_thread_url:
        :return:
        """
        self.driver.get(test_thread_url)
        self.click_create_new_thread_link()

        self.click_submit_button_to_create_thread()

        return self.find_element(*CampForumIdentifiers.CAMP_FORUM_ERR_MESSAGE).text

    def forums_user_can_read_all_post_assosiated_with_thread(self, test_thread_url):
        """

        :param test_thread_url: To upload the FORUM Thread URL
        :return:
        """
        self.driver.get(test_thread_url)

        try:
            self.click_on_thread()
        except ImeNotAvailableException:
            return False

        return True

    def forums_create_thread_page_has_nick_name_field(self, test_thread_url):
        """
            Function to check if Create New Thread Page has Nick Name Field
        :param test_thread_url:
        :return:
        """
        self.driver.get(test_thread_url)
        self.click_create_new_thread_link()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_NICK_NAME)
        except NoSuchElementException:
            return False

        return True

    def forums_post_to_thread_has_nick_name_field(self, test_thread_url):
        """
            Function to check if new post has Nick Name field
        :param test_thread_url:
        :return:
        """
        self.driver.get(test_thread_url)
        self.click_on_thread()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_NICK_NAME)
        except NoSuchElementException:
            return False

        return True

    def forum_thread_title_marked_with_asterisk(self, test_thread_url):
        """
            Function to check if the thread title (mandatory) is marked with asterick
        :param test_thread_url: test Thread URL from config file
        :return:
        """

        self.driver.get(test_thread_url)
        self.click_create_new_thread_link()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_TITLE_ASTRK)
        except NoSuchElementException:
            return False

        return True

    def forum_thread_nick_name_marked_with_asterisk(self, test_thread_url):
        """
            Function to check if the thread nick name field (mandatory) is marked with asterick
        :param test_thread_url: test Thread URL from config file
        :return:
        """

        self.driver.get(test_thread_url)
        self.click_create_new_thread_link()

        try:
            self.find_element(*CampForumIdentifiers.CAMP_FORUM_THREAD_NICK_NAME_ASTRK)
        except NoSuchElementException:
            return False

        return True
