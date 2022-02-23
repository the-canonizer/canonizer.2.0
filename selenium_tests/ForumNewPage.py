from CanonizerBase import Page
from Identifiers import CampForumIdentifiers, BrowsePageIdentifiers, AddCampStatementPageIdentifiers
from selenium.common.exceptions import NoSuchElementException
import time
from selenium.webdriver.common.keys import Keys
from selenium.webdriver import ActionChains
from Config import *


class AddForumsPage(Page):

    def load_camp_forum_page(self):
        """
        Go To Camp Forum
        :return:
        """

        # Browse to Browse Page
        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER).click()

        try:
            # print("Here")
            self.hover(*CampForumIdentifiers.CAMP_FORUM)
            self.find_element(*CampForumIdentifiers.CAMP_FORUM).click()

            return AddForumsPage(self.driver)
        except NoSuchElementException:
            return False

    def load_camp_forum_page_with_page_crash(self):
        self.load_camp_forum_page()
        page_title = self.find_element(*CampForumIdentifiers.CAMP_FORUM_HEADING).text
        if page_title == 'Canonizer Forum Details':
            return AddForumsPage(self.driver)

    def load_create_thread_page(self):
        self.hover(*CampForumIdentifiers.CREATE_THREAD)
        self.find_element(*CampForumIdentifiers.CREATE_THREAD).click()
        return AddForumsPage(self.driver)

    def load_create_thread_page_with_page_crash(self):
        self.hover(*CampForumIdentifiers.CREATE_THREAD)
        self.find_element(*CampForumIdentifiers.CREATE_THREAD).click()
        page_title = self.find_element(*CampForumIdentifiers.CREATE_THREAD_HEADING).text
        if page_title == 'Create a new thread for Camp : Agreement':
            return AddForumsPage(self.driver)

    def load_my_thread_page(self):
        self.hover(*CampForumIdentifiers.MY_THREADS)
        self.find_element(*CampForumIdentifiers.MY_THREADS).click()
        page_title = self.find_element(*CampForumIdentifiers.MY_THREAD_HEADING).text
        if page_title == 'Canonizer Forum Details':
            return AddForumsPage(self.driver)

    def load_top_10_thread_page(self):
        self.hover(*CampForumIdentifiers.TOP_10_THREADS)
        self.find_element(*CampForumIdentifiers.TOP_10_THREADS).click()
        page_title = self.find_element(*CampForumIdentifiers.MY_THREAD_HEADING).text
        if page_title == 'Canonizer Forum Details':
            return AddForumsPage(self.driver)

    def load_my_participation(self):
        self.hover(*CampForumIdentifiers.MY_PARTICIPATION)
        self.find_element(*CampForumIdentifiers.MY_PARTICIPATION).click()
        page_title = self.find_element(*CampForumIdentifiers.MY_THREAD_HEADING).text
        if page_title == 'Canonizer Forum Details':
            return AddForumsPage(self.driver)

    def load_all_threads(self):
        self.hover(*CampForumIdentifiers.ALL_THREADS)
        self.find_element(*CampForumIdentifiers.ALL_THREADS).click()
        page_title = self.find_element(*CampForumIdentifiers.MY_THREAD_HEADING).text
        if page_title == 'Canonizer Forum Details':
            return AddForumsPage(self.driver)

    def check_no_thread_availability(self):
        self.hover(*CampForumIdentifiers.ALL_THREADS)
        self.find_element(*CampForumIdentifiers.ALL_THREADS).click()
        page_title = self.find_element(*CampForumIdentifiers.MY_THREAD_HEADING).text
        statement = self.find_element(*CampForumIdentifiers.NO_THREAD_STATEMENT).text
        if page_title == 'Canonizer Forum Details' and 'No threads available for this topic' in statement:
            return AddForumsPage(self.driver)

    def create_thread_mandatory_fields_are_marked_with_asteris(self):
        self.hover(*CampForumIdentifiers.CREATE_THREAD)
        self.find_element(*CampForumIdentifiers.CREATE_THREAD).click()
        return self.find_element(*CampForumIdentifiers.EDIT_THREAD_TITLE) and self.find_element(
            *CampForumIdentifiers.SELECTED_NICK_NAME)

    def enter_title_of_thread(self, title):
        self.find_element(*CampForumIdentifiers.TITLE_THREAD).send_keys(title)

    def enter_nickname(self, nickname):
        self.find_element(*CampForumIdentifiers.NICK_NAME).send_keys(nickname)

    def click_submit_button(self):
        self.find_element(*CampForumIdentifiers.EDIT_SUBMIT_BUTTON).click()

    def enter_post_reply(self, reply):
        self.find_element(*CampForumIdentifiers.THREAD_REPLY).send_keys(reply)

    def click_post_submit_button(self):
        self.find_element(*CampForumIdentifiers.POST_SUBMIT).click()

    def create_thread(self, title, nickname):
        print("Title: ", title)
        print("Nickname: ", nickname)
        self.enter_title_of_thread(title)
        self.enter_nickname(nickname)
        self.click_submit_button()

    def edit_thread(self, thread_title):
        self.find_element(*CampForumIdentifiers.EDIT_THREAD).click()
        self.find_element(*CampForumIdentifiers.TITLE_THREAD).clear()
        self.find_element(*CampForumIdentifiers.TITLE_THREAD).send_keys(thread_title)

    def create_thread_with_duplicate_title_name(self, nickname, thread_title):
        self.create_thread(thread_title, nickname)
        error = self.find_element(*CampForumIdentifiers.ERROR_DUPLICATE_TITLE).text
        if error == 'Thread title must be unique!':
            return AddForumsPage(self.driver)

    def create_thread_with_blank_title_name(self, nickname):
        self.create_thread('', nickname)
        error = self.find_element(*CampForumIdentifiers.ERROR_BLANK_TITLE).text
        if error == 'Title is required.':
            return AddForumsPage(self.driver)

    def create_thread_with_special_char(self, nickname, thread_title):
        self.create_thread(thread_title, nickname)
        error = self.find_element(*CampForumIdentifiers.ERROR_TITLE_WITH_SPECIAL_CHAR).text
        if error == 'Title can only contain space and alphanumeric characters.':
            return AddForumsPage(self.driver)

    def create_thread_with_correct_title_name(self, thread_title, nickname):
        self.create_thread(thread_title, nickname)
        success_message = self.find_element(*CampForumIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Thread Created Successfully!':
            return AddForumsPage(self.driver)

    def create_thread_with_invalid_data(self, thread_title, nickname):
        self.enter_title_of_thread(thread_title)
        self.enter_nickname(nickname)
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).click()
        error  =self.find_element(*CampForumIdentifiers.ERROR_TITLE_WITH_SPECIAL_CHAR).text
        if error == 'Title can only contain space and alphanumeric characters.':
            return AddForumsPage(self.driver)

    def create_thread_with_blank_mandatory_fields(self, thread_title, nickname):
        self.enter_title_of_thread(" ")
        self.enter_nickname("")
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).click()
        error = self.find_element(*CampForumIdentifiers.ERROR_TITLE_WITH_SPECIAL_CHAR).text
        if error == 'Title is required.':
            return AddForumsPage(self.driver)

    def create_thread_with_only_mandatory_fields(self, thread_title, nickname):
        self.enter_title_of_thread(thread_title)
        self.enter_nickname(nickname)
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).click()
        success_message = self.find_element(*CampForumIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Thread Created Successfully!':
            return AddForumsPage(self.driver)

    def create_thread_with_valid_data_with_enter_key(self, thread_title, nickname):
        self.enter_title_of_thread(thread_title)
        self.enter_nickname(nickname)
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).send_keys(Keys.ENTER)
        success_message = self.find_element(*CampForumIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Thread Created Successfully!':
            return AddForumsPage(self.driver)

    def create_thread_with_trailing_spaces(self, thread_title, nickname):
        self.enter_title_of_thread(thread_title)
        self.enter_nickname(nickname)
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).click()
        success_message = self.find_element(*CampForumIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Thread Created Successfully!':
            return AddForumsPage(self.driver)

    def verify_camp_link_form(self):
        self.find_element(*CampForumIdentifiers.AGREEMENT).click()
        page_title = self.find_element(*CampForumIdentifiers.NEWS_FEED).text
        print(page_title)
        if page_title == 'News Feeds':
            return AddForumsPage(self.driver)

    def create_thread_with_invalid_data_with_enter_key(self, thread_title, nickname):
        self.enter_title_of_thread(thread_title)
        self.enter_nickname(nickname)
        self.find_element(*CampForumIdentifiers.SUBMIT_THREAD).send_keys(Keys.ENTER)
        error = self.find_element(*CampForumIdentifiers.ERROR_TITLE_WITH_SPECIAL_CHAR).text
        if error == 'Title can only contain space and alphanumeric characters.':
            return AddForumsPage(self.driver)

    def update_thread_title(self, thread_title):
        self.load_my_thread_page()
        self.edit_thread(thread_title)
        self.find_element(*CampForumIdentifiers.UPDATE_THREAD_BUTTON).click()

    def update_thread(self, thread_title):
        self.update_thread_title(thread_title)
        success_message = self.find_element(*CampForumIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Thread title updated.':
            return AddForumsPage(self.driver)

    def edit_thread_title_with_duplicate_title(self, thread_title):
        self.update_thread_title(thread_title)
        error = self.find_element(*CampForumIdentifiers.ERROR_IN_EDIT_TITLE).text
        print(error)
        if error == 'Thread title must be unique':
            return AddForumsPage(self.driver)

    def edit_thread_title_with_special_char(self, thread_title):
        self.update_thread_title(thread_title)
        error = self.find_element(*CampForumIdentifiers.ERROR_IN_EDIT_TITLE).text
        if error == 'Title must only contain space and alphanumeric characters.':
            return AddForumsPage(self.driver)

    def check_all_replies_to_thread(self):
        self.hover(*CampForumIdentifiers.THREAD_TITLE)
        self.find_element(*CampForumIdentifiers.THREAD_TITLE).click()
        title = self.find_element(*CampForumIdentifiers.TITLE_REPLY_ALL).text
        if 'Thread Created' in title:
            return AddForumsPage(self.driver)

    def edit_reply_to_thread(self, reply):
        self.load_all_threads()
        self.hover(*CampForumIdentifiers.THREAD_TITLE)
        self.find_element(*CampForumIdentifiers.THREAD_TITLE).click()
        self.hover(*CampForumIdentifiers.EDIT_REPLY_BUTTON)
        self.find_element(*CampForumIdentifiers.EDIT_REPLY_BUTTON).click()
        self.find_element(*CampForumIdentifiers.EDI_REPLY_TEXT_BOX).clear()
        self.find_element(*CampForumIdentifiers.EDI_REPLY_TEXT_BOX).send_keys(reply)
        self.click_post_submit_button()
        title = self.find_element(*CampForumIdentifiers.TITLE_REPLY_ALL).text
        if 'Thread Created' in title:
            return AddForumsPage(self.driver)

    def check_page_crash(self):

        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()

        # Browse to Topic Name
        self.hover(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER)
        self.find_element(*AddCampStatementPageIdentifiers.TOPIC_IDENTIFIER).click()

        self.find_element(*CampForumIdentifiers.MANAGE_EDIT_TOPIC).click()
        self.find_element(*CampForumIdentifiers.THIS_VERSION).click()

        self.hover(*CampForumIdentifiers.CAMP_FORUM)
        self.find_element(*CampForumIdentifiers.CAMP_FORUM).click()
        heading = self.find_element(*CampForumIdentifiers.CAMP_FORUM_HEADING).text
        url = AddForumsPage(self.driver).get_url()
        return [url, heading]

    def load_thread_posts_page(self):
        self.find_element(*CampForumIdentifiers.THREAD_TITLE).click()
        page_title = self.find_element(*CampForumIdentifiers.CAMP_FORUM_HEADING).text
        print(page_title)
        if page_title == '« List of All Camp Threads':
            return AddForumsPage(self.driver)

    def thread_posts_mandatory_fields_are_marked_with_asterisk(self):
        self.find_element(*CampForumIdentifiers.THREAD_TITLE).click()
        if self.find_element(*CampForumIdentifiers.NICK_NAME_ASTRK):
            return AddForumsPage(self.driver)

    def post_reply_to_thread(self, reply):
        self.hover(*CampForumIdentifiers.THREAD_TITLE)
        self.find_element(*CampForumIdentifiers.THREAD_TITLE).click()
        before_reply = self.find_element(*CampForumIdentifiers.POST_COUNTER).text
        counter_before_reply = [int(word) for word in before_reply.split() if word.isdigit()]
        self.enter_post_reply(reply)
        self.click_post_submit_button()
        after_reply = self.find_element(*CampForumIdentifiers.POST_COUNTER).text
        counter_after_reply = [int(word) for word in after_reply.split() if word.isdigit()]
        if counter_before_reply < counter_after_reply:
            return AddForumsPage(self.driver)

    def thread_pagination(self):
        page_heading = self.find_element(*CampForumIdentifiers.PAGE_TITLE).text
        if page_heading == 'List of All Camp Threads':
            # rows = self.find_element(*CampForumIdentifiers.TABLE_ROWS)
            rows = self.driver.find_elements_by_xpath("/html/body/div[1]/div[2]/div/div[3]/table/tbody/tr")
            length_rows = len(rows)
            pre_pagination = self.find_element(*CampForumIdentifiers.PRE_PAGINATION).text
            next_pagination = self.find_element(*CampForumIdentifiers.NEXT_PAGINATION).text
            if pre_pagination == "«" and next_pagination == "»" and length_rows == 10:
                return AddForumsPage(self.driver)

    def verify_nickname_on_thread_title(self):
        self.hover(*CampForumIdentifiers.THREAD_TITLE)
        self.find_element(*CampForumIdentifiers.THREAD_TITLE).click()

        created_by = self.find_element(*CampForumIdentifiers.CREATED_BY).text
        print(created_by)
        if 'dL' == created_by:
            self.find_element(*CampForumIdentifiers.CREATED_BY).click()
            heading = self.find_element(*CampForumIdentifiers.SUPPORTED_CAMPS_LIST).text
            if heading == 'List of supported camps':
                return AddForumsPage(self.driver)


