import unittest
from CanonizerHomePage import *
from CanonizerRegistrationPage import *
from CanonizerLoginPage import *
from CanonizerTestCases import test_cases
from Config import *
from selenium import webdriver
from CanonizerUploadFilePage import *
from CanonizerForgotPasswordPage import *
from CanonizerBrowsePage import *
from CanonizerCreateNewTopicPage import *
from CanonizerLogoutPage import *
from CanonizerAccountSettingsPage import *
from CanonizerHelpPage import *
from CanonizerSearchPage import *
from CanonizerTopicUpdatePage import *
from CanonizerCampPage import *
from CanonizerCampStatementPage import *
from CanonizerNewsFeedsPage import *
from CanonizerBrokenURL import *
from CanonizerJoinSupportCampPage import *
from datetime import datetime
from time import time
import os
import re
from ForumNewPage import *
import requests
import string
import random


class TestPages(unittest.TestCase):

    def setUp(self):
        """
            Initialize the Things
            :return:
        """
        driver_location = DEFAULT_CHROME_DRIVER_LOCATION

        options = webdriver.ChromeOptions()
        options.binary_location = DEFAULT_BINARY_LOCATION
        # options.add_argument('headless')
        options.add_argument("--start-maximized")
        # options.add_argument('window-size=1920x1080')

        self.driver = webdriver.Chrome(driver_location, options=options)
        self.driver.get(DEFAULT_BASE_URL)

    def login_to_canonizer_app(self):
        """
            This Application will allow you to login to canonizer App on need basis
        :param flag:
        :return:
        """
        result = CanonizerLoginPage(self.driver).click_login_page_button().login_with_valid_user(DEFAULT_USER,
                                                                                                 DEFAULT_PASS).get_url()
        self.assertIn("", result)

    # TC_LOAD_CANONIZER_HOME_PAGE
    def test_canonizer_home_page_load(self):
        print("\n" + str(test_cases('TC_LOAD_CANONIZER_HOME_PAGE')))
        self.assertTrue(CanonizerMainPage(self.driver).check_home_page_loaded())

    # TC_LOAD_REGITSER_PAGE
    def test_canonizer_register_button(self):
        print("\n" + str(test_cases('TC_LOAD_REGITSER_PAGE')))
        registerPage = CanonizerRegisterPage(self.driver).click_register_button()
        self.assertIn("/signup", registerPage.get_url())

    # TC_LOAD_LOGIN_PAGE
    def test_canonizer_login_button(self):
        print("\n" + str(test_cases('TC_LOAD_LOGIN_PAGE')))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        self.assertIn("/login", loginPage.get_url())

    # TC_LOGIN_WITH_VALID_USER
    def test_canonizer_login_with_valid_user(self):
        print("\n" + str(test_cases('TC_LOGIN_WITH_VALID_USER')))
        result = self.login_to_canonizer_app()
        self.assertIn("", result.get_url())

    # 05
    def test_login_with_invalid_user(self):
        print("\n" + str(test_cases(4)))
        page = CanonizerLoginPage(self.driver)
        loginPage = page.click_login_page_button()
        result = loginPage.login_with_invalid_user(DEFAULT_INVALID_USER, DEFAULT_INVALID_PASSWORD).get_url()
        self.assertIn("/login", result)

    # Register Page Test Cases Start
    # TC_REGISTER_WITH_BLANK_FIRST_NAME
    def test_registration_with_blank_first_name(self):
        print("\n" + str(test_cases('TC_REGISTER_WITH_BLANK_FIRST_NAME')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_first_name(
            REG_LIST_3).get_url()
        self.assertIn("/signup", result)

    # TC_REGISTER_WITH_BLANK_LAST_NAME
    def test_registration_with_blank_last_name(self):
        print("\n" + str(test_cases('TC_REGISTER_WITH_BLANK_LAST_NAME')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_last_name(
            REG_LIST_4).get_url()
        self.assertIn("/signup", result)

    # TC_REGISTRATION_WITH_BLANK_EMAIL
    def test_registration_with_blank_email(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_BLANK_EMAIL')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_email(
            REG_LIST_5).get_url()
        self.assertIn("/signup", result)

    # TC_REGISTRATION_WITH_BLANK_PASSWORD
    def test_registration_with_blank_password(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_BLANK_PASSWORD')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_password(
            REG_LIST_6).get_url()
        self.assertIn("/signup", result)

    # TC_REGISTRATION_WITH_INVALID_PASSWORD_LENGTH
    def test_registration_with_invalid_password_length(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_INVALID_PASSWORD_LENGTH')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_password_length(
            REG_LIST_7).get_url()
        self.assertIn("/signup", result)

    # TC_REGISTRATION_WITH_DIFFERENT_CONFIRM_PASSWORD
    def test_registration_with_different_confirmation_password(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_DIFFERENT_CONFIRM_PASSWORD')))
        result = CanonizerRegisterPage(
            self.driver).click_register_button().registration_with_different_confirmation_password(
            REG_LIST_8).get_url()
        self.assertIn("/signup", result)

    # TC_LOAD_WHAT_IS_CANONIZER_PAGE
    def test_what_is_canonizer_page_loaded_properly(self):
        print("\n" + str(test_cases('TC_LOAD_WHAT_IS_CANONIZER_PAGE')))
        result = CanonizerMainPage(
            self.driver).click_what_is_canonizer_page_link().check_what_is_canonizer_page_loaded().get_url()
        self.assertIn("/browse", result)

    # TC_JOIN_SUPPORT_WITH_LOGIN
    def test_user_must_be_signin_to_join_or_support_camp(self):
        print("\n" + str(test_cases('TC_JOIN_SUPPORT_WITH_LOGIN')))
        self.assertIn("login",
                      CanonizerJoinSupportCampPage(self.driver).user_must_be_signin_to_join_or_support_camp().get_url())

    # TC_LOAD_DIRECT_JOIN_AND_SUPPORT
    def test_load_direct_join_and_support_page(self):
        print("\n", str(test_cases('TC_LOAD_DIRECT_JOIN_AND_SUPPORT')))
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).load_direct_join_and_support_page()
        if result:
            self.assertIn("support/598-Testing-Topic-14/1-Agreement", result.get_url())

    # TC_VERIFY_SINGLE_SUPPORT_ON_NEW_TOPIC_CREATION
    def test_verify_single_support_on_new_topic_creation(self):
        print("\n" + str(test_cases('TC_VERIFY_SINGLE_SUPPORT_ON_NEW_TOPIC_CREATION')))
        self.login_to_canonizer_app()
        add_name = ''.join(random.choices(string.ascii_uppercase +
                                          string.digits, k=7))

        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .verify_single_support_on_new_topic_creation(
            DEFAULT_NICK_NAME,
            "Verify " + add_name,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        )
        self.assertIn("/topic", result.get_url())

    # TC_VERIFY_WARNING_DIRECTLY_SUPPORTING_CHILD_CAMP
    def test_verify_warning_directly_supporting_child_camp(self):
        print("\n" + str(test_cases('TC_VERIFY_WARNING_DIRECTLY_SUPPORTING_CHILD_CAMP')))
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).verify_warning_directly_supporting_child_camp().get_url()
        self.assertIn('support/598-Testing-Topic-14/2-Child-camp-1', result)

    # TC_VERIFY_SUPPORT_TO_CHILD_CAMP
    def test_verify_support_to_child_camp(self):
        print("\n" + str(test_cases('TC_VERIFY_SUPPORT_TO_CHILD_CAMP')))
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).verify_support_to_child_camp()
        self.assertIn("/topic", result.get_url())

    # TC_REQUEST_OTP_WITH_BLANK_EMAIL
    def test_request_otp_with_blank_email_or_phone_number(self):
        print("\n" + str(test_cases('TC_REQUEST_OTP_WITH_BLANK_EMAIL')))
        result = CanonizerLoginPage(
            self.driver).click_login_page_button().request_otp_with_blank_email_or_phone_number().get_url()
        self.assertIn("/login", result)

    # TC_REGISTER_PAGE_SHOULD_HAVE_LOGIN_OPTION_FOR_EXISTING_USER
    def test_register_page_should_have_login_option_for_existing_users(self):
        print("\n" + str(test_cases('TC_REGISTER_PAGE_SHOULD_HAVE_LOGIN_OPTION_FOR_EXISTING_USER')))
        self.assertIn('Log in Here', CanonizerRegisterPage(
            self.driver).click_register_button().registration_should_have_login_option_for_existing_users())

    # TC_LOGIN_PAGE_SHOULD_HAVE_REGISTER_OPTION_FOR_FEW_USERS
    def test_login_page_should_have_register_option_for_new_users(self):
        print("\n" + str(test_cases('TC_LOGIN_PAGE_SHOULD_HAVE_REGISTER_OPTION_FOR_FEW_USERS')))
        result = CanonizerLoginPage(
            self.driver).click_login_page_button().login_page_should_have_register_option_for_new_users().get_url()
        self.assertIn("/login", result)

    # TC_REGISER_PAGE_MANDATORY_FIELDS_MARKED_WITH_ASTERISK
    def test_register_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_REGISER_PAGE_MANDATORY_FIELDS_MARKED_WITH_ASTERISK')))
        self.assertTrue(CanonizerRegisterPage(
            self.driver).click_register_button().register_page_mandatory_fields_are_marked_with_asterisk())

    # ----- FORGOT PASSWORD Test Cases Start -----

    # TC_LOAD_FORGOT_PASSWORD_PAGE
    def test_click_forgot_password_page_button(self):
        print("\n" + str(test_cases('TC_LOAD_FORGOT_PASSWORD_PAGE')))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        self.assertIn("/forgotpassword",
                      CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().get_url())

    # TC_FORGOT_PASSWORD_WITH_BLANK_EMAIL
    def test_forgot_password_with_blank_email(self):
        print("\n" + str(test_cases('TC_LOAD_FORGOT_PASSWORD_PAGE')))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and put email as blank
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_blank_email().get_url()
        self.assertIn("forgotpassword", result)

    # TC_FORGOT_PASSWORD_WITH_INVALID_EMAIL
    def test_forgot_password_with_invalid_email(self):
        print("\n" + str(test_cases('TC_FORGOT_PASSWORD_WITH_INVALID_EMAIL')))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and check with invalid email
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_invalid_email(
            DEFAULT_INVALID_USER).get_url()
        self.assertIn("forgotpassword", result)

    # TC_FORGOT_PASSWORD_WITH_VALID_EMAIL
    def test_forgot_password_with_valid_email(self):
        print("\n" + str(test_cases('TC_FORGOT_PASSWORD_WITH_VALID_EMAIL')))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and check with valid email
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_valid_email(DEFAULT_USER)
        self.assertIn("resetlinksent", result.get_url())

    # TC_FORGOT_PASSWORD_PAGE_MANDATORY_FIELDS_WITH_ASTERISK
    def test_forgot_password_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_FORGOT_PASSWORD_PAGE_MANDATORY_FIELDS_WITH_ASTERISK')))
        CanonizerLoginPage(self.driver).click_login_page_button()
        self.assertTrue(CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_page_mandatory_fields_are_marked_with_asterisk())

    # ----- FORGOT PASSWORD Test Cases End -----

    # ----- Browse Page Test Cases Start -----
    # TC_LOAD_BROWSE_PAGE
    def test_click_browse_page_button(self):
        print("\n" + str(test_cases('TC_LOAD_BROWSE_PAGE')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(self.driver).click_browse_page_button().get_url())

    # TC_CLICK_ONLY_MY_TOPICS_BUTTON
    def test_click_only_my_topics_button(self):
        print("\n" + str(test_cases('TC_CLICK_ONLY_MY_TOPICS_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link and click on "Only My Topics"
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().click_only_my_topics_button().get_url())

    # TC_CHECK_NAMESPACE_DROPDOWN
    def test_check_namespace_dropdown(self):
        print("\n" + str(test_cases('TC_CHECK_NAMESPACE_DROPDOWN')))
        self.login_to_canonizer_app()
        # Click on the Browse link and click on "Only My Topics"
        result = CanonizerBrowsePage(
            self.driver).click_browse_page_button().check_namespace_dropdown().get_url()
        self.assertIn("/create/topic'", result)

    # TC_BROWSE_NAMESPACE_TOPIC_LIST
    def test_check_topic_namespace(self):
        print("\n" + str(test_cases('TC_CHECK_NAMESPACE_DROPDOWN')))
        self.login_to_canonizer_app()
        # Click on the Browse link and click on "Only My Topics"
        result = CanonizerBrowsePage(
            self.driver).click_browse_page_button().check_topic_namespace().get_url()
        self.assertIn("/browse", result)

    # ----- Browse Page Test Cases End -----

    # ----- Upload File Page Test Cases Start -----

    # TC_VERFIFY_UPLOAD_BUTTON
    def test_click_upload_file_page_button(self):
        print("\n" + str(test_cases('TC_VERFIFY_UPLOAD_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Upload File link and check upload in URL Name
        self.assertIn("upload", CanonizerUploadFilePage(self.driver).click_upload_file_page_button().get_url())

    # TC_VERIFY_LOGIN_ON_UPLOAD_FILE_PAGE
    def test_verify_login_on_upload_file_page(self):
        print("\n" + str(test_cases('TC_VERIFY_LOGIN_ON_UPLOAD_FILE_PAGE')))
        result = CanonizerUploadFilePage(self.driver).verify_login_on_upload_file_page().get_url()
        self.assertIn("/login", result)

    # TC_VERIFY_UPLOAD_FILE_BUTTON_IS_CLICKABLE
    def test_verify_upload_file_button_is_clickable(self):
        print("\n" + str(test_cases('TC_VERIFY_UPLOAD_FILE_BUTTON_IS_CLICKABLE')))
        self.login_to_canonizer_app()
        result = CanonizerUploadFilePage(self.driver).verify_upload_file_button_is_clickable().get_url()
        self.assertIn("/upload", result)

    # TC_VERIFY_CHOOSE_FILE_BUTTON_IS_CLICKABLE
    def test_verify_choose_file_button_is_clickable(self):
        print("\n" + str(test_cases('TC_VERIFY_CHOOSE_FILE_BUTTON_IS_CLICKABLE')))
        self.login_to_canonizer_app()
        result = CanonizerUploadFilePage(self.driver).verify_choose_file_button_is_clickable().get_url()
        self.assertIn("/upload", result)

    # TC_VERIFY_FILE_UPLOAD_WARNING
    def test_verify_file_upload_warning(self):
        print("\n" + str(test_cases('TC_VERIFY_FILE_UPLOAD_WARNING')))
        self.login_to_canonizer_app()
        result = CanonizerUploadFilePage(self.driver).verify_file_upload_warning().get_url()
        self.assertIn("/upload", result)

    # TC_UPLOAD_FILE_WITH_BLANK_FIEL
    def test_upload_file_with_blank_file(self):
        print("\n" + str(test_cases('TC_UPLOAD_FILE_WITH_BLANK_FIEL')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().upload_file_with_blank_file().get_url()
        self.assertIn("/upload", result)

    # ----- Upload File Page Test Cases End -----
    # ----- Create New Topic Test Cases Start -----

    # TC_CREATE_TOPIC_WITH_USER_LOGIN
    def test_click_create_new_topic_page_button(self):
        print("\n" + str(test_cases('TC_CREATE_TOPIC_WITH_USER_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check topic/create in URL Name
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button()
        self.assertIn("create/topic", result.get_url())

    # TC_CREATE_TOPIC_WITH_BLANK_NICK_NAME
    def test_create_topic_with_blank_nick_name(self):
        print("\n" + str(test_cases('TC_CREATE_TOPIC_WITH_BLANK_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if nick name is blank
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_blank_nick_name(
            DEFAULT_TOPIC_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_TOPIC_WITH_BLANK_TOPIC_NAME
    def test_create_topic_with_blank_topic_name(self):
        print("\n" + str(test_cases('TC_CREATE_TOPIC_WITH_BLANK_TOPIC_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if topic name is blank
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_blank_topic_name(
            DEFAULT_NICK_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_TOPIC_WITH_BLANK_SPACES_TOPIC_NAME
    def test_create_topic_with_blank_spaces_topic_name(self):
        print("\n" + str(test_cases('TC_CREATE_TOPIC_WITH_BLANK_SPACES_TOPIC_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if topic name is blank
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_blank_spaces_topic_name(
            "        ",
            DEFAULT_NICK_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_NEW_TOPIC_MANADATORY_FIELDS_WITH_ASTERIK
    def test_create_new_topic_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_CREATE_NEW_TOPIC_MANADATORY_FIELDS_WITH_ASTERIK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link
        self.assertTrue(CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_new_topic_page_mandatory_fields_are_marked_with_asterisk())

    # TC_CREATE_TOPIC_WITH_DUPLICATE_NAME
    def test_create_topic_with_duplicate_topic_name(self):
        print("\n" + str(test_cases('TC_CREATE_TOPIC_WITH_DUPLICATE_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check for duplicate topic name
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_duplicate_topic_name(
            DEFAULT_NICK_NAME,
            DUPLICATE_TOPIC_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_NEW_TOPIC_WITH_INVALID_DATA
    def test_create_topic_with_invalid_data(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_WITH_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_with_invalid_data(
            '',
            "Invalid Data *&&&&&&&",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_NEW_WITHOUT_MANDATORY_FIELDS_DATA
    def test_create_topic_without_entering_mandatory_fields(self):
        print("\n", str(test_cases('TC_CREATE_NEW_WITHOUT_MANDATORY_FIELDS_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_without_entering_mandatory_fields(
            "",
            "",
            "",
            "",
        ).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_NEW_TOPIC_ENTERING_DATA_ONLY_IN_MANDATORY_FIELDS
    def test_create_topic_with_entering_data_only_in_mandatory_fields(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_ENTERING_DATA_ONLY_IN_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_with_entering_data_only_in_mandatory_fields(
            DEFAULT_NICK_NAME,
            "New Topic Testing 2",
            DEFAULT_NAMESPACE,
            "",
        ).get_url()
        self.assertIn("topic", result)

    # TC_VALIDATION_OF_NICK_NAME_DROPDOWN
    def test_validation_of_nick_name_dropdown(self):
        print("\n", str(test_cases('TC_VALIDATION_OF_NICK_NAME_DROPDOWN')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .validation_of_nick_name_dropdown().get_url()
        self.assertIn("settings/nickname", result)

    # TC_CREATE_NEW_TOPIC_WITH_ENTER_KEY
    def test_create_topic_name_with_enter_key(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_17')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_name_with_enter_key(
            DEFAULT_NICK_NAME,
            "Topic Name Testing 5",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("topic", result)

    # TC_CREATE_NEW_TOPIC_WITH_ENTER_KEY_AND_VERIFY_HISTORY_PAGE
    def test_create_topic_name_with_enter_key_verifying_history_page(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_WITH_ENTER_KEY_AND_VERIFY_HISTORY_PAGE')))
        self.login_to_canonizer_app()
        add_name = ''.join(random.choices(string.ascii_uppercase +
                                          string.digits, k=7))
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_name_with_enter_key_verifying_history_page(
            DEFAULT_NICK_NAME,
            "New Topic " + add_name,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("topic", result)

    # TC_CREATE_NEW_TOPIC_WITH_TRAILING_SPACES
    def test_create_topic_name_with_trailing_space(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_WITH_TRAILING_SPACES')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_name_with_trailing_space(
            DEFAULT_NICK_NAME,
            "          Topic 3 with Trailing",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("topic", result)

    # TC_VERIFYING_NICKNAME_FROM_DROPDOWN_WHILE_CRAETING_TOPIC
    def test_verifying_nick_name_from_dropdown_while_creating_topic(self):
        print("\n", str(test_cases('TC_VERIFYING_NICKNAME_FROM_DROPDOWN_WHILE_CRAETING_TOPIC')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .verifying_nick_name_from_dropdown_while_creating_topic(
            "pooja_zibtek",
            "Verifying Nick Name 4",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_VERIFY_TOPIC_NAME_FROM_NAMESPACES_IN_BROWSE
    def test_verifying_topic_name_from_namespaces_in_browse(self):
        print("\n", str(test_cases('TC_VERIFY_TOPIC_NAME_FROM_NAMESPACES_IN_BROWSE')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .verifying_topic_name_from_namespaces_in_browse(
            "poo_",
            "Verify Topic in Namespace 1",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        print(result)
        self.assertIn("", result)

    # TC_CREATE_TOPIC_WITHOUT_USER_LOGIN
    def test_create_topic_without_user_login(self):
        print("\n" + str(test_cases('TC_CREATE_TOPIC_WITHOUT_USER_LOGIN')))
        # Click on the Create New Topic link
        self.assertIn("/login", CanonizerCreateNewTopicPage(self.driver).create_topic_without_user_login().get_url())

    # ----- Create New Topic Test Cases End ----
    # ----- Log out Test Cases Start -----
    # TC_CLICK_LOGOUT_PAGE_BUTTON
    def test_click_log_out_page_button(self):
        print("\n" + str(test_cases('TC_CLICK_LOGOUT_PAGE_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Username and click on Log Out
        result = CanonizerLogoutPage(
            self.driver).click_username_link_button().click_log_out_page_button().check_home_page_loaded().get_url()
        self.assertIn("/", result)

    # TC_CLICK_LOGOUT_PAGE_BUTTON_BEFORE_BROWSE_BACK_BUTTON
    def test_click_log_out_page_button_before_browser_back_button(self):
        print("\n", str(test_cases('TC_CLICK_LOGOUT_PAGE_BUTTON_BEFORE_BROWSE_BACK_BUTTON')))
        self.login_to_canonizer_app()
        result = CanonizerLogoutPage(self.driver).click_log_out_page_button_before_browser_back_button().get_url()
        self.assertIn("/", result)

    # ----- Log out Test Cases End -----

    # ----- Account Settings Test Cases Start -----
    # TC_LOAD_ACCOUNT_SETTING_PAGE
    def test_click_account_settings_page_button(self):
        print("\n" + str(test_cases('TC_LOAD_ACCOUNT_SETTING_PAGE')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings in URL Name
        self.assertIn("settings", CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().get_url())

    # TC_CLICK_ACCOUNT_SETTINGS_MANAGE_PROFILE_INFO_PAGE_BUTTON
    def test_click_account_settings_manage_profile_info_page_button(self):
        print("\n" + str(test_cases('TC_CLICK_ACCOUNT_SETTINGS_MANAGE_PROFILE_INFO_PAGE_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        self.assertIn("settings", result.get_url())

    # TC_CLICK_ACCOUNT_SETTING_ADD_MANAGE_NICKANAMES_PAGE_BUTTON
    def test_click_account_settings_add_manage_nick_names_page_button(self):
        print("\n" + str(test_cases('TC_CLICK_ACCOUNT_SETTING_ADD_MANAGE_NICKANAMES_PAGE_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/nickname in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertIn("settings/nickname", result.get_url())

    # TC_CLICK_ACCOUNT_SETTINGS_MY_SUPPORTS_PAGE_BUTTON
    def test_click_account_settings_my_supports_page_button(self):
        print("\n" + str(test_cases('TC_CLICK_ACCOUNT_SETTINGS_MY_SUPPORTS_PAGE_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check support in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        self.assertIn("support", result.get_url())

    # TC_CLICK_ACCOUNT_SETTINGS_SOCAIL_OATH_VERIFICATION_PAGE_BUTTON
    def test_click_account_settings_social_oauth_verification_page_button(self):
        print("\n" + str(test_cases('TC_CLICK_ACCOUNT_SETTINGS_SOCAIL_OATH_VERIFICATION_PAGE_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/Social Oauth Verification in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_social_oauth_verification_page_button()
        self.assertIn("settings/sociallinks", result.get_url())

    # ----- Account Settings  Test Cases End -----

    # ----- Change Password Test Cases Start -----
    # TC_CLICK_ACCOUNT_SETTINGS_CHANGE_PASSWORD_PAGE_BUTTON
    def test_click_account_settings_change_password_page_button(self):
        print("\n" + str(test_cases('TC_CLICK_ACCOUNT_SETTINGS_CHANGE_PASSWORD_PAGE_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/changepassword in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        self.assertIn("settings/changepassword", result.get_url())

    # TC_CHANGE_PASSWORD_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_change_password_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_CHANGE_PASSWORD_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab & Check for Mandatory fields on Change Password page
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        self.assertTrue(CanonizerAccountSettingsChangePasswordPage(
            self.driver).change_password_page_mandatory_fields_are_marked_with_asterisk())

    # TC_SAVE_WIH_BLANK_CURRENT_PASSWORD
    def test_save_with_blank_current_password(self):
        print("\n" + str(test_cases('TC_CHANGE_PASSWORD_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_current_password(
            DEFAULT_NEW_PASSWORD,
            DEFAULT_CONFIRM_PASSWORD).get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_SAVE_WITH_BLANK_NEW_PASSWORD
    def test_save_with_blank_new_password(self):
        print("\n" + str(test_cases('TC_SAVE_WITH_BLANK_NEW_PASSWORD')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_new_password(
            DEFAULT_CURRENT_PASSWORD,
            DEFAULT_CONFIRM_PASSWORD).get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_SAVE_WITH_BLANK_CONFIRM_PASSWORD
    def test_save_with_blank_confirm_password(self):
        print("\n" + str(test_cases('TC_SAVE_WITH_BLANK_CONFIRM_PASSWORD')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_confirm_password(
            DEFAULT_CURRENT_PASSWORD,
            DEFAULT_NEW_PASSWORD).get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_SAVE_WITH_INVALID_CURRENT_PASSWORD
    def test_save_with_invalid_current_password(self):
        print("\n" + str(test_cases('TC_SAVE_WITH_INVALID_CURRENT_PASSWORD')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_invalid_current_password(
            'Test@12345',
            'Test@123456',
            'Test@123456').get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_SAVE_WIH_MISMATCH_NEW_CONFIRM_PASSWORD
    def test_save_with_mismatch_new_confirm_password(self):
        print("\n" + str(test_cases('TC_SAVE_WIH_MISMATCH_NEW_CONFIRM_PASSWORD')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_mismatch_new_confirm_password(
            DEFAULT_PASS,
            'Test@12345',
            'Test@123456').get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_SAVE_WITH_SAME_NEW_AND_CURRENT_PASSWORD
    def test_save_with_same_new_and_current_password(self):
        print("\n" + str(test_cases('TC_SAVE_WITH_SAME_NEW_AND_CURRENT_PASSWORD')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_same_new_and_current_password(
            DEFAULT_PASS,
            DEFAULT_PASS,
            'Test@1234567').get_url()
        self.assertIn("/settings/changepassword", result)

    # ----- Change Password Test Cases End -----

    # ----- Help Page Test Cases Start -----
    # TC_LOAD_WHAT_IS_CANONIZER_HELP_PAGE_WITHOUT_LOGIN
    def test_check_what_is_canonizer_help_page_loaded_without_login(self):
        print("\n" + str(test_cases('TC_LOAD_WHAT_IS_CANONIZER_HELP_PAGE_WITHOUT_LOGIN')))
        self.assertIn("topic/132-Help/1-Agreement",
                      CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().get_url())

    # TC_LOAD_WHAT_IS_CANONIZER_HELP_PAGE_WITH_LOGIN
    def test_check_what_is_canonizer_help_page_loaded_with_login(self):
        print("\n" + str(test_cases('TC_LOAD_WHAT_IS_CANONIZER_HELP_PAGE_WITHOUT_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help
        self.assertIn("topic/132-Help/1-Agreement",
                      CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().get_url())

    # TC_CHECK_STEPS_TO_CREATE_A_NEW_TOPIC_PAGE_LOADED_WITH_LOGIN
    def test_check_steps_to_create_a_new_topic_page_loaded_with_login(self):
        print("\n" + str(test_cases('TC_CHECK_STEPS_TO_CREATE_A_NEW_TOPIC_PAGE_LOADED_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on the Steps_to_Create_a_New_Topic
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_steps_to_create_a_new_topic_page_loaded().get_url()
        self.assertIn("topic/132-Steps-to-Create-a-New-Topic/3", result)

    # TC_CHECK_DEALING_WITH_DISAGREEMENTS_PAGE_LOADED_WITH_LOGIN
    def test_check_dealing_with_disagreements_page_loaded_with_login(self):
        print("\n" + str(test_cases('TC_CHECK_DEALING_WITH_DISAGREEMENTS_PAGE_LOADED_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on the Dealing With Disagreements
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_dealing_with_disagreements_page_loaded_with_login().get_url()
        self.assertIn("topic/132-Dealing-with-disagreement/4", result)

    # TC_LOAD_WIKI_MARKUP_INFORMATION_PAGE_LOAD_WITH_LOGIN
    def test_check_wiki_markup_information_page_loaded_with_login(self):
        print("\n" + str(test_cases('TC_LOAD_WIKI_MARKUP_INFORMATION_PAGE_LOAD_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on Wiki Markup Information
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_wiki_markup_information_page_loaded_with_login().get_url()
        self.assertIn("topic/132-Canonizer-wiki-text-formatting/5", result)

    # TC_VERIFY_CANONIZER_FEEDBACK_CAMP_OUTLINE_LOADED_PAGE_LOADED
    def test_check_adding_the_canonizer_feedback_camp_outline_to_internet_articles_page_loaded(self):
        print("\n" + str(test_cases('TC_VERIFY_CANONIZER_FEEDBACK_CAMP_OUTLINE_LOADED_PAGE_LOADED')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Adding the Canonizer Feedback Camp Outline to Internet Articles
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_adding_the_canonizer_feedback_camp_outline_to_internet_articles_page_loaded().get_url()
        self.assertIn("topic/132-Adding-feedback-outline/6", result)

    # TC_CHECK_STEP_TO_CREATE_A_NEW_TOPIC_PAGE_LOADED_WITHOUT_LOGIN
    def test_check_steps_to_create_a_new_topic_page_loaded_without_login(self):
        print("\n" + str(test_cases('TC_CHECK_STEP_TO_CREATE_A_NEW_TOPIC_PAGE_LOADED_WITHOUT_LOGIN')))
        # Click on the Help and Click on the Steps_to_Create_a_New_Topic
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_steps_to_create_a_new_topic_page_loaded().get_url()
        self.assertIn("topic/132-Steps-to-Create-a-New-Topic/3", result)

    # TC_CHECK_DEALING_WITH_DISAGREEMENTS_PAGE_LOADED_WITHOUT_LOGIN
    def test_check_dealing_with_disagreements_page_loaded_without_login(self):
        print("\n" + str(test_cases('TC_CHECK_DEALING_WITH_DISAGREEMENTS_PAGE_LOADED_WITHOUT_LOGIN')))
        # Click  on the Help and Click on the Dealing With Disagreements
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_dealing_with_disagreements_page_loaded_with_login().get_url()
        self.assertIn("topic/132-Dealing-with-disagreement/4", result)

    # TC_VERIFY_WIKI_MARKUP_INFORMATION_PAGE_LOADED_WITH_LOGIN
    def check_wiki_markup_information_page_loaded_with_login(self):
        print("\n" + str(test_cases('TC_VERIFY_WIKI_MARKUP_INFORMATION_PAGE_LOADED_WITH_LOGIN')))
        # Click on the Help and Click on Wiki Markup Information
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_wiki_markup_information_page_loaded_with_login().get_url()
        self.assertIn("topic/132-Canonizer-wiki-text-formatting/5", result)

    # TC_VERIFY_ADDING_THE_CANONIZER_FEEDBACK_CAMP_OUTLINE_TO_INTERNET_ARTICLES_PAGE_LOADED
    def test_check_adding_the_canonizer_feedback_camp_outline_to_internet_articles_page_loaded(self):
        print("\n" + str(
            test_cases('TC_VERIFY_ADDING_THE_CANONIZER_FEEDBACK_CAMP_OUTLINE_TO_INTERNET_ARTICLES_PAGE_LOADED')))
        # Click on the Help and Click on Adding the Canonizer Feedback Camp Outline to Internet Articles
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_adding_the_canonizer_feedback_camp_outline_to_internet_articles_page_loaded().get_url()
        self.assertIn("topic/132-Adding-feedback-outline/6", result)

    # ----- Help Page Test Cases End -----
    # ----- Add & Manage Nick Names Page Test Cases Start -----
    # TC_NICK_NAMES_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_nick_names_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_NICK_NAMES_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertTrue(CanonizerAccountSettingsNickNamesPage(
            self.driver).nick_names_page_mandatory_fields_are_marked_with_asterisk())

    # TC_CREATE_WITH_BLANK_NICK_NAME
    def test_create_with_blank_nick_name(self):
        print("\n" + str(test_cases('TC_CREATE_WITH_BLANK_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertIn("Nick name is required.",
                      CanonizerAccountSettingsNickNamesPage(self.driver).create_with_blank_nick_name())

    # TC_CREATE_WITH_DUPLICATE_NICK_NAME
    def test_create_with_duplicate_nick_name(self):
        print("\n" + str(test_cases('TC_CREATE_WITH_DUPLICATE_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_duplicate_nick_name(DEF_NICK_NAME)
        self.assertIn("The nick name has already been taken.", result)

    # TC_CREATE_WITH_BLANK_SPACES_NICK_NAME
    def test_create_with_blank_spaces_nick_name(self):
        print("\n" + str(test_cases('TC_CREATE_WITH_BLANK_SPACES_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_blank_spaces_nick_name('       ')
        self.assertIn("Nick name is required.", result)

    # TC_CREATE_NICK_NAME_WITH_TRAILING_SPACES
    def test_create_nick_name_with_trailing_spaces(self):
        print("\n" + str(test_cases("TC_CREATE_NICK_NAME_WITH_TRAILING_SPACES")))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button() \
            .click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_nick_name_with_trailing_spaces('       pkk')
        self.assertIn("Nick name created successfully.", result)

    # TC_CLICK_BROWSE_PAGE_BUTTON_WITHOUT_LOGIN
    def test_click_browse_page_button_without_login(self):
        print("\n" + str(test_cases('TC_CLICK_BROWSE_PAGE_BUTTON_WITHOUT_LOGIN')))
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(self.driver).click_browse_page_button().get_url())

    # ----- Add & Manage Nick Names Page Test Cases End -----
    # ----- My Profile Page Test Cases Start -----
    # TC_MANAGE_PROFILE_INFO_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_manage_profile_info_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_MANAGE_PROFILE_INFO_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        self.assertTrue(AccountSettingsManageProfileInfoPage(
            self.driver).manage_profile_info_page_mandatory_fields_are_marked_with_asterisk())

    # TC_UPDATE_PROFILE_WITH_BLANK_FIRST_TIME
    def test_update_profile_with_blank_first_name(self):
        print("\n" + str(test_cases('TC_UPDATE_PROFILE_WITH_BLANK_FIRST_TIME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_blank_first_name(
            DEFAULT_MIDDLE_NAME,
            DEFAULT_LAST_NAME).get_url()
        self.assertIn("/settings", result)

    # TC_UPDATE_PROFILE_WITH_BLANK_LAST_NAME
    def test_update_profile_with_blank_last_name(self):
        print("\n" + str(test_cases('TC_UPDATE_PROFILE_WITH_BLANK_LAST_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_blank_last_name(
            DEFAULT_FIRST_NAME,
            DEFAULT_MIDDLE_NAME,
        ).get_url()
        self.assertIn("/settings", result)

    # ----- My Profile Page Test Cases End -----

    # ----- Browse Page Test Cases Start -----
    # TC_SELECT_BY_VALUE_GENERAL
    def test_select_by_value_general(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_GENERAL')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_general().get_url())

    # TC_SELECT_BY_VALUE_CORPORATIONS
    def test_select_by_value_corporations(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_CORPORATIONS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=2", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_corporations().get_url())

    # TC_SELECT_BY_VALUE_CRYPTO_CURRENCY
    def test_select_by_value_crypto_currency(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_CRYPTO_CURRENCY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=3", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_crypto_currency().get_url())

    # TC_SELECT_BY_VALUE_FAMILY
    def test_select_by_value_family(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_FAMILY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=4", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_family().get_url())

    # TC_SELECT_BY_VALUE_FAMILY_JESPERSON_OSCAR_F
    def test_select_by_value_family_jesperson_oscar_f(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_FAMILY_JESPERSON_OSCAR_F')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=5", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_family_jesperson_oscar_f().get_url())

    # TC_SELECT_BY_VALUE_OCCUPY_WALL_STREET
    def test_select_by_value_occupy_wall_street(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_OCCUPY_WALL_STREET')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=6", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_occupy_wall_street().get_url())

    # TC_SELECT_BY_VALUE_ORGRANIZATIONS
    def test_select_by_value_organizations(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGRANIZATIONS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=7", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER
    def test_select_by_value_organizations_canonizer(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=8", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_canonizer().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER_HELP
    def test_select_by_value_organizations_canonizer_help(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER_HELP')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=9", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_canonizer_help().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_MTA
    def test_select_by_value_organizations_mta(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_MTA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=10", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_mta().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_TV07
    def test_select_by_value_organizations_tv07(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_TV07')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=11", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_tv07().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_WTA
    def test_select_by_value_organizations_wta(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_WTA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=12", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_wta().get_url())

    # TC_SELECT_BY_VALUE_PERSONAL_ATTRIBUTES
    def test_select_by_value_personal_attributes(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_PERSONAL_ATTRIBUTES')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=13", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_personal_attributes().get_url())

    # TC_SELECT_BY_VALUE_PERSONAL_REPUTATIONS
    def test_select_by_value_personal_reputations(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_PERSONAL_REPUTATIONS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=14", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_personal_reputations().get_url())

    # TC_SELECT_BY_VALUE_PROFESSIONAL_SERVICES
    def test_select_by_value_professional_services(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_PROFESSIONAL_SERVICES')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=15", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_professional_services().get_url())

    # TC_SELECT_BY_VALUE_SANDBOX
    def test_select_by_value_sandbox(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_SANDBOX')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=16", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_sandbox().get_url())

    # TC_SELECT_BY_VALUE_TERMINOLOGY
    def test_select_by_value_terminology(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_TERMINOLOGY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=17", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_terminology().get_url())

    # TC_SELECT_BY_VALUE_WWW
    def test_select_by_value_www(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_WWW')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=18", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_www().get_url())

    # TC_SELECT_BY_VALUE_ALL
    def test_select_by_value_all(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ALL')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_all().get_url())

    # TC_SELECT_BY_VALUE_ALL_DEFAULT
    def test_select_by_value_all_default(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ALL_DEFAULT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_all_default().get_url())

    # TC_SELECT_BY_VALUE_GENERAL_ONLY_MY_TOPICS
    def test_select_by_value_general_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_GENERAL_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=1&my=1",
                      CanonizerBrowsePage(self.driver).select_by_value_general_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_COOPERATIONS_ONLY_MY_TOPICS
    def test_select_by_value_corporations_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_COOPERATIONS_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=2&my=2",
                      CanonizerBrowsePage(self.driver).select_by_value_corporations_only_my_topics().get_url())

    # TC_SELECT_VY_VALUE_CRYPTO_CURRENCY_ONLY_MY_TOPICS
    def test_select_by_value_crypto_currency_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_VY_VALUE_CRYPTO_CURRENCY_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=3&my=3",
                      CanonizerBrowsePage(self.driver).select_by_value_crypto_currency_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_FAMILY_ONLY_MY_TOPICS
    def test_select_by_value_family_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_FAMILY_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=4&my=4",
                      CanonizerBrowsePage(self.driver).select_by_value_family_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_FAMILY_JESPERSON_OSCAR_F_ONLY_MY_TOPICS
    def test_select_by_value_family_jesperson_oscar_f_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_FAMILY_JESPERSON_OSCAR_F_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=5&my=5", CanonizerBrowsePage(
            self.driver).select_by_value_family_jesperson_oscar_f_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_OCCUPY_WALL_STREET_ONLY_MY_TOPICS
    def test_select_by_value_occupy_wall_street_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_OCCUPY_WALL_STREET_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=6&my=6",
                      CanonizerBrowsePage(self.driver).select_by_value_occupy_wall_street_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZAIIONS_ONLY_MY_TOPICS
    def test_select_by_value_organizations_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZAIIONS_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=7&my=7",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_CNAONIZER_ONLY_MY_TOPICS
    def test_select_by_value_organizations_canonizer_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_CNAONIZER_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=8&my=8", CanonizerBrowsePage(
            self.driver).select_by_value_organizations_canonizer_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER_HELP_ONLY_MY_TOPICS
    def test_select_by_value_organizations_canonizer_help_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER_HELP_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=9&my=9", CanonizerBrowsePage(
            self.driver).select_by_value_organizations_canonizer_help_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_MTA_ONLY_MY_TOPICS
    def test_select_by_value_organizations_mta_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_MTA_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=10&my=10",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_mta_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_TV07_ONLY_MY_TOPICS
    def test_select_by_value_organizations_tv07_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_TV07_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=11&my=11",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_tv07_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ORGANIZATIONS_WTA_ONLY_MY_TOPICS
    def test_select_by_value_organizations_wta_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ORGANIZATIONS_WTA_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=12&my=12",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_wta_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_PERSONAL_ATTRIBUTES_ONLY_MY_TOPICS
    def test_select_by_value_personal_attributes_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_PERSONAL_ATTRIBUTES_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=13&my=13",
                      CanonizerBrowsePage(self.driver).select_by_value_personal_attributes_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_PERSONAL_REPUTATIONS_ONLY_MY_TOPICS
    def test_select_by_value_personal_reputations_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_PERSONAL_REPUTATIONS_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=14&my=14",
                      CanonizerBrowsePage(self.driver).select_by_value_personal_reputations_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_PROFESSIONAL_SERVICES_ONLY_MY_TOPICS
    def test_select_by_value_professional_services_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_PROFESSIONAL_SERVICES_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=15&my=15",
                      CanonizerBrowsePage(self.driver).select_by_value_professional_services_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_SANDBOX_ONLY_MY_TOPICS
    def test_select_by_value_sandbox_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_SANDBOX_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=16&my=16",
                      CanonizerBrowsePage(self.driver).select_by_value_sandbox_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_TERMINOLOGY_ONLY_MY_TOPICS
    def test_select_by_value_terminology_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_TERMINOLOGY_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=17&my=17",
                      CanonizerBrowsePage(self.driver).select_by_value_terminology_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_WWW_ONLY_MY_TOPICS
    def test_select_by_value_www_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_WWW_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=18&my=18",
                      CanonizerBrowsePage(self.driver).select_by_value_www_only_my_topics().get_url())

    # TC_SELECT_BY_VALUE_ALL_ONLY_MY_TOPICS
    def test_select_by_value_all_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_ALL_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=&my=",
                      CanonizerBrowsePage(self.driver).select_by_value_all_only_my_topics().get_url())

    # ----- Browse Page Test Cases End -----

    # ----- White Paper Test Cases Start -----

    # TC_LOAD_WHITE_PAPER_WITH_LOGIN
    def test_check_white_paper_should_open_with_login(self):
        print("\n" + str(test_cases('TC_LOAD_WHITE_PAPER_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the White Paper link
        CanonizerWhitePaper(self.driver).check_white_paper_should_open().open("files/2012_amplifying_final.pdf")

    # TC_LOAD_WHITE_PAPER_WITHOU_LOGIN
    def test_check_white_paper_should_open_without_login(self):
        print("\n" + str(test_cases('TC_LOAD_WHITE_PAPER_WITHOU_LOGIN')))
        # Click on the White Paper link
        CanonizerWhitePaper(self.driver).check_white_paper_should_open().open("files/2012_amplifying_final.pdf")

    # ----- White Paper Test Cases End -----

    # ----- Blog Test Cases Start -----
    # TC_LOAD_BLOG_PAGE_WITH_LOGIN
    def test_check_blog_page_should_open_with_login(self):
        print("\n" + str(test_cases('TC_LOAD_BLOG_PAGE_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Blog link
        self.assertIn("/blog/", CanonizerBlog(self.driver).check_blog_page_should_open().get_url())

    # TC_LOAD_BLOG_PAGE_WITHOUT_LOGIN
    def test_check_blog_page_should_open_without_login(self):
        print("\n" + str(test_cases('TC_LOAD_BLOG_PAGE_WITHOUT_LOGIN')))
        # Click on the Blog link
        self.assertIn("/blog/", CanonizerBlog(self.driver).check_blog_page_should_open().get_url())

    # ----- Blog Test Cases End -----

    # ----- Algorithm Information Test Cases Start -----
    # TC_VERIFY_ALGORITHM_INFORMATION_PAGE_SHOULD_OPEN
    def test_check_algorithm_information_page_should_open(self):
        print("\n" + str(test_cases('TC_VERIFY_ALGORITHM_INFORMATION_PAGE_SHOULD_OPEN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Algorithm Information link
        self.assertIn("topic/53-Canonizer-Algorithms/1", CanonizerAlgorithmInformation(
            self.driver).check_algorithm_information_page_should_open().get_url())

    # ----- Algorithm Information Test Cases End -----

    # ----- As Of Filters Test Cases Start -----
    # TC_VERIFY_INCLUDE_REVIEW_FILTER_APPLIED
    def test_check_include_review_filter_applied(self):
        print("\n" + str(test_cases('TC_VERIFY_INCLUDE_REVIEW_FILTER_APPLIED')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on include review filter
        self.assertIn(DEFAULT_BASE_URL,
                      CanonizerAsOfFilters(self.driver).check_include_review_filter_applied().get_url())

    # TC_CHECK_DEFAULT_FILTER_APPLIED
    def test_check_default_filter_applied(self):
        print("\n" + str(test_cases('TC_CHECK_DEFAULT_FILTER_APPLIED')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on default filter
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_default_filter_applied().get_url())

    # TC_VERIFY_AS_OF_DATE_FILTER_APPLIED
    def test_check_as_of_date_filter_applied(self):
        print("\n" + str(test_cases('TC_VERIFY_AS_OF_DATE_FILTER_APPLIED')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on as of (yy/mm/dd) and select date
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_as_of_date_filter_applied().get_url())

    # ----- As Of Filters Test Cases End -----

    # ----- Update Topic Test Cases Start -----
    # TC_LOAD_TOPIC_UPDATE_PAGE
    def test_load_topic_update_page(self):
        print("\n" + str(test_cases('TC_LOAD_TOPIC_UPDATE_PAGE')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Check Topic Update page load
        self.assertIn("manage/topic", CanonizerTopicUpdatePage(self.driver).load_topic_update_page().get_url())

    # TC_LOAD_VIEW_THIS_VERSION_PAGE
    def test_load_view_this_version_page(self):
        print("\n" + str(test_cases('TC_LOAD_VIEW_THIS_VERSION_PAGE')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_view_this_version_page().get_url())

    # TC_LOAD_TOPIC_OBJECT_PAGE
    def test_load_topic_object_page(self):
        print("\n" + str(test_cases('TC_LOAD_TOPIC_OBJECT_PAGE')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_object_page()
        if result == 1:
            self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_topic_object_page().get_url())

    # TC_TOPIC_UPDATE_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_topic_update_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_TOPIC_UPDATE_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        self.assertTrue(CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().topic_update_page_mandatory_fields_are_marked_with_asterisk())

    # TC_TOPIC_OBJECTION_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_topic_objection_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_TOPIC_OBJECTION_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_object_page()
        if result == 1:
            self.assertTrue(CanonizerTopicUpdatePage(
                self.driver).load_topic_object_page().topic_objection_page_mandatory_fields_are_marked_with_asterisk())

    # TC_TOPIC_UPDATE_PAGE_SHOULD_HAVE_ADD_NEW_NICK_NAME_LINK_FOR_NEW_USERS
    def test_topic_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases('TC_TOPIC_UPDATE_PAGE_SHOULD_HAVE_ADD_NEW_NICK_NAME_LINK_FOR_NEW_USERS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().topic_update_page_should_have_add_new_nick_name_link_for_new_users()
        if result == 1:
            self.assertIn("Add New Nick Name", result)

    # TC_SUBMIT_UPDATE_WITH_BLANK_NICK_NAME
    def test_submit_update_with_blank_nick_name(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WITH_BLANK_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Topic update and check if nick name is blank
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_blank_nick_name(
            "Test",
            "",
            "")
        if result == 1:
            self.assertIn("The nick name field is required.", result)

    # ----- Update Topic Test Cases End -----

    # ----- Create New Camp and Edit Camp Test Cases Start -----
    # TC_LOAD_CREATE_NEW_CAMP_PAGE
    def test_load_create_camp_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_CREATE_NEW_CAMP_PAGE')))
        self.assertIn("camp/create",
                      CanonizerCampPage(self.driver).load_create_camp_page().get_url())

    # TC_CREATE_NEW_CAMP_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_create_new_camp_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_CREATE_NEW_CAMP_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        self.assertTrue(CanonizerCampPage(
            self.driver).load_create_camp_page().create_new_camp_page_mandatory_fields_are_marked_with_asterisk())

    # TC_CREATE_CAMP_WITH_BLANK_CAMP_NAME
    def test_create_camp_with_blank_camp_name(self):
        print("\n" + str(test_cases('TC_CREATE_CAMP_WITH_BLANK_CAMP_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_blank_camp_name(
            CREATE_CAMP_LIST_1).get_url()
        self.assertIn("camp/create/", result)

    # TC_CREATE_CAMP_WITH_INVALID_DATA
    def test_create_camp_with_invalid_data(self):
        print("\n" + str(test_cases('TC_CREATE_CAMP_WITH_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_invalid_camp_name(
            CREATE_CAMP_LIST_3).get_url()
        self.assertIn("camp/create/", result)

    # TC_CREATE_CAMP_WITHOUT_ENTERING_DATA_IN_MANDATORY_FIELDS
    def test_create_camp_without_entering_data_in_mandatory_fields(self):
        print("\n" + str(test_cases('TC_CREATE_CAMP_WITHOUT_ENTERING_DATA_IN_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_blank_camp_name(
            CREATE_CAMP_LIST_1).get_url()
        self.assertIn("camp/create/", result)

    # TC_CREATE_CAMP_WITH_EXISTING_DATA
    def test_create_camp_with_existing_data(self):
        print("\n" + str(test_cases('TC_CREATE_CAMP_WITH_EXISTING_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if camp name is duplicate
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_duplicate_camp_name(
            CREATE_CAMP_LIST_2)
        self.assertIn("camp/create", result.get_url())

    # TC_LOAD_CAMP_UPDATE_PAGE
    def test_load_camp_update_page(self):
        print("\n" + str(test_cases('TC_LOAD_CAMP_UPDATE_PAGE')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        self.assertIn("/manage/camp", CanonizerEditCampPage(self.driver).load_camp_update_page().get_url())

    # TC_LOAD_CAMP_MANAGE_EDIT_PAGE
    def test_load_camp_manage_edit_page(self):
        print("\n" + str(test_cases("TC_LOAD_CAMP_MANAGE_EDIT_PAGE")))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_manage_edit_page().get_url()
        self.assertIn("camp/history/173-Software-Testing", result)

    # TC_VERIFY_AGREEMENT_PAGE
    def test_verify_agreement_page(self):
        print("\n" + str(test_cases("TC_VERIFY_AGREEMENT_PAGE")))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_agreement_page()
        if result:
            self.assertIn("topic/173-Software-Testing/2-Types-Of-Testing", result.get_url())

    # TC_VERIFY_CAMP_UPDATE_PAGE
    def test_verify_camp_update_page(self):
        print("\n" + str(test_cases("TC_VERIFY_CAMP_UPDATE_PAGE")))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page()
        if result:
            self.assertIn("manage/camp", result.get_url())

    # TC_VERIFY_CAMP_UPDATE_FIELDS
    def test_verify_camp_update_fields(self):
        print("\n" + str(test_cases("TC_VERIFY_CAMP_UPDATE_PAGE")))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_camp_update_fields()
        if result:
            self.assertIn("manage/camp", result.get_url())

    # TC_EDIT_CAMP_PAGE_MANDATORY_FIELDS_WITH_ASTERISK
    def test_camp_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_PAGE_MANDATORY_FIELDS_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp  link
        self.assertTrue(CanonizerEditCampPage(
            self.driver).load_camp_update_page().camp_edit_page_mandatory_fields_are_marked_with_asterisk())

    # TC_UPDATE_CAMP_WITH_INVALID_DATA
    def test_submit_camp_update_with_invalid_data(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_invalid_data(
            CAMP_LIST_3
        ).get_url()
        self.assertIn("manage/camp", result)

    # TC_UPDATE_CAMP_WITH_INVALID_DATA_WITH_ENTER_KEY
    def test_submit_camp_update_with_invalid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_INVALID_DATA_WITH_ENTER_KEY')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().submit_camp_update_with_invalid_data_with_enter_key(
            CAMP_LIST_3
        ).get_url()
        self.assertIn("manage/camp", result)

    # TC_UPDATE_CAMP_WITH_MANDATORY_FIELDS_ONLY
    def test_submit_camp_update_with_mandatory_fields_only(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_MANDATORY_FIELDS_ONLY')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().test_submit_camp_update_with_mandatory_fields_only(
            CAMP_LIST_4
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_UPDATE_CAMP_WITH_TRAILING_SPACES
    def test_submit_camp_update_with_tailing_spaces(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_TRAILING_SPACES')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_tailing_spaces(
            CAMP_LIST_5
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_UPDATE_CAMP_WITH_INVALID_URL
    def test_submit_camp_update_with_invalid_url(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_INVALID_URL')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_invalid_url(
            CAMP_LIST_6
        ).get_url()
        print(result)
        self.assertIn("", result)

    # ----- Create New Camp Test Cases End -----

    # ----- Create New Camp Statement and Edit Camp Statement Test Cases Start -----
    # TC_LOAD_EDIT_CAMP_STATEMENT_HISTORY_PAGE
    def test_load_edit_camp_statement_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_EDIT_CAMP_STATEMENT_HISTORY_PAGE')))
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_history_page()
        if result:
            self.assertIn("statement/history", result.get_url())

    # TC_VERIFY_HISTORY_ON_EDIT_CAMP_STATEMENT
    def test_verify_history_on_edit_camp_statement_page(self):
        print("\n", str(test_cases('TC_VERIFY_HISTORY_ON_EDIT_CAMP_STATEMENT')))
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).verify_history_on_edit_camp_statement_page().get_url()
        if result:
            self.assertIn("statement/history", result)

    # TC_LOAD_EDIT_CAMP_STATEMENT_VIEW_THIS_VERSION
    def test_load_edit_camp_statement_view_this_version(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_EDIT_CAMP_STATEMENT_VIEW_THIS_VERSION')))
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_view_this_version()
        if result:
            self.assertIn("topic/", result.get_url())

    # TC_LOAD_EDIT_CAMP_STATEMENT_PAGE
    def test_load_edit_camp_statement_submit_statement_update_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_EDIT_CAMP_STATEMENT_PAGE')))
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            self.assertIn("manage/statement", result.get_url())

    # TC_VERIFY_EDITABLE_FIELDS_ON_EDIT_CAMP_STATEMENT_PAGE
    def test_verify_editable_fields_on_edit_camp_statement_page(self):
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_VERIFY_EDITABLE_FIELDS_ON_EDIT_CAMP_STATEMENT_PAGE')))
        result = CanonizerCampStatementPage(self.driver).verify_editable_fields_on_edit_camp_statement_page()
        if result:
            self.assertIn("manage/statement", result.get_url())

    # TC_EDIT_CAMP_STATEMENT_PAGE_MANDATORY_FIELDS_WITH_ASTERISK
    def test_camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_PAGE_MANDATORY_FIELDS_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp Statement  link
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            self.assertTrue(CanonizerCampStatementPage(
                self.driver).load_edit_camp_statement_page().camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk())

    # TC_EDIT_CAMP_STATEMENT_WITHOUT_MANDATORY_FIELDS
    def test_edit_camp_statement_without_mandatory_fields(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_WITHOUT_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp Statement  link
        result = CanonizerCampStatementPage(self.driver).edit_camp_statement_without_mandatory_fields(
            "summary").get_url()
        self.assertIn("manage/statement", result)

    # TC_SUBMIT_STATEMENT_UPDATE_WITH_BLANK_NICK_NAME
    def test_submit_statement_update_with_blank_nick_name(self):
        print("\n" + str(test_cases('TC_SUBMIT_STATEMENT_UPDATE_WITH_BLANK_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(self.driver).submit_statement_update_with_blank_nick_name(
                "",
                "", )
            if result == 1:
                self.assertIn("The nick name field is required.", result)

    # ----- Create New Camp Statement and Edit Camp Statement Test Cases End -----

    # ----- Add News and Edit News Test Cases Start -----
    # TC_LOAD_ADD_NEWS_FEED_PAGE
    def test_load_add_news_feed_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_ADD_NEWS_FEED_PAGE')))
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page()
        self.assertIn("addnews/", result.get_url())

    # TC_ADD_NEWS_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_add_news_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_ADD_NEWS_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Add News link
        self.assertTrue(CanonizerAddNewsFeedsPage(
            self.driver).load_add_news_feed_page().add_news_page_mandatory_fields_are_marked_with_asterisk())

    # TC_CREATE_NEWS_WITH_BLANK_DISPLAY_TEXT
    def test_create_news_with_blank_display_text(self):
        print("\n" + str(test_cases('TC_CREATE_NEWS_WITH_BLANK_DISPLAY_TEXT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check if display text is blank
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_blank_display_text(
            "Test",
            "").get_url()
        self.assertIn("addnews/173-Software-Testing/1-Agreement", result)

    # TC_CREATE_NEWS_WITH_BLANK_LINK
    def test_create_news_with_blank_link(self):
        print("\n" + str(test_cases('TC_CREATE_NEWS_WITH_BLANK_LINK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check if link is blank
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_blank_link(
            "Test",
            "").get_url()
        self.assertIn("", result)

    # TC_NEW_FEED_WITH_BLANK_FIELDS
    def test_create_new_with_blank_fields(self):
        print("\n", str(test_cases('TC_NEW_FEED_WITH_BLANK_FIELDS')))
        self.login_to_canonizer_app()
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_new_with_blank_fields(
            "",
            "",
            ""
        ).get_url()
        self.assertIn("/addnews", result)

    # TC_CLICK_ADD_NEWS_CANCEL_BUTTON
    def test_click_add_news_cancel_button(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_CLICK_ADD_NEWS_CANCEL_BUTTON')))
        self.assertIn("topic/", CanonizerAddNewsFeedsPage(self.driver).click_add_news_cancel_button().get_url())

    # TC_LOAD_EDIT_NEWS_FEED_PAGE
    def test_load_edit_news_feed_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_EDIT_NEWS_FEED_PAGE')))
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            self.assertIn("editnews/", result.get_url())

    # TC_CLICK_EDIT_NEWS_CANCEL_BUTTON
    def test_click_edit_news_cancel_button(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_CLICK_EDIT_NEWS_CANCEL_BUTTON')))
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            self.assertIn("topic/", CanonizerEditNewsFeedsPage(self.driver).click_edit_news_cancel_button().get_url())

    # TC_UPDATE_NEWS_WITH_BLANK_DISPLAY_TEXT
    def test_update_news_with_blank_display_text(self):
        print("\n" + str(test_cases('TC_UPDATE_NEWS_WITH_BLANK_DISPLAY_TEXT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check if display text is blank
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().update_news_with_blank_display_text(
            "Test",
            "").get_url()
        self.assertIn("/editnews/173-Software-Testing/1-Agreement", result)

    # TC_UPDATE_NEWS_WITH_BLANK_LINK
    def test_update_news_with_blank_link(self):
        print("\n" + str(test_cases('TC_UPDATE_NEWS_WITH_BLANK_LINK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check if link is blank
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().update_news_with_blank_link(
            "Test",
            "").get_url()
        self.assertIn("editnews/173-Software-Testing/1-Agreement", result)

    # TC_UPDATE_NEWS_WITH_INVALID_LINK_FORMAT
    def test_update_news_with_invalid_link_format(self):
        print("\n" + str(test_cases('TC_UPDATE_NEWS_WITH_INVALID_LINK_FORMAT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check entered link is invalid
        result = CanonizerEditNewsFeedsPage(
            self.driver).load_edit_news_feed_page().update_news_with_invalid_link_format(
            "Test",
            "Test",
            "").get_url()
        self.assertIn("editnews/173-Software-Testing/1-Agreement", result)

    # TC_CREATE_NEWS_WITH_INVALID_LINK_FORMAT
    def test_create_news_with_invalid_link_format(self):
        print("\n" + str(test_cases('TC_CREATE_NEWS_WITH_INVALID_LINK_FORMAT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check entered link is invalid
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_invalid_link_format(
            "Test",
            "Test",
            "").get_url()
        self.assertIn("addnews/", result)

    # TC_CREATE_NEWS_WITH_VALID_DATA
    def test_create_news_with_valid_data(self):
        print("\n" + str(test_cases('TC_CREATE_NEWS_WITH_VALID_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check update news with valid data
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_valid_data(
            "Testing news 2",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_CREATE_NEWS_WITH_MANDATORY_FIELDS_ONLY
    def test_create_news_with_mandatory_fields_only(self):
        print("\n" + str(test_cases('TC_CREATE_NEWS_WITH_MANDATORY_FIELDS_ONLY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check update news with valid data
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_valid_data(
            "Testing news 3",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("topic/", result)

    # TC_NEWS_FEED_DUPLICATE_DATA
    def test_create_news_with_duplicate_data(self):
        print("\n" + str(test_cases('TC_NEWS_FEED_DUPLICATE_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_duplicate_data(
            "Test2",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_NEWS_FEED_INVALID_DATA
    def test_create_news_with_invalid_data(self):
        print("\n" + str(test_cases('TC_NEWS_FEED_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_invalid_data(
            "Test-2",
            "https://www.google.com/***",
            "").get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_NEWS_FEED_WITH_ENTER_KEY
    def test_create_news_with_enter_key(self):
        print("\n", str(test_cases("TC_NEWS_FEED_WITH_ENTER_KEY")))
        self.login_to_canonizer_app()
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_enter_key(
            "Testtting news 12",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_NEWS_FEED_INVALID_DATA_WITH_ENTER_KEY
    def test_create_news_with_invalid_data_with_enter_key(self):
        print("\n", str(test_cases("TC_NEWS_FEED_INVALID_DATA_WITH_ENTER_KEY")))
        self.login_to_canonizer_app()
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page() \
            .create_news_with_invalid_data_with_enter_key(
            "Test-2",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("addnews/173-Software-Testing/1-Agreement", result)

    # TC_NEWS_FEED_WITH_TRAILING_SPACES
    def test_create_news_with_trailing_spaces(self):
        print("\n", str(test_cases("TC_NEWS_FEED_WITH_TRAILING_SPACES")))
        self.login_to_canonizer_app()
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_trailing_spaces(
            "             Testing Trailing Spaces",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_UPDATE_NEWS_WITH_VALID_DATA
    def test_update_news_with_valid_data(self):
        print("\n" + str(test_cases('TC_UPDATE_NEWS_WITH_VALID_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check update news with valid data
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            result = CanonizerEditNewsFeedsPage(self.driver).update_news_with_valid_data(
                "Updpate News Testt 2",
                "https://www.google.com/",
                "")
            self.assertIn("topic/", result.get_url())

    # TC_UPDATE_NEWS_WITH_TRAILING_SPACES
    def test_update_news_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_UPDATE_NEWS_WITH_TRAILING_SPACES')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check update news with valid data
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            result = CanonizerEditNewsFeedsPage(self.driver).update_news_with_trailing_spaces(
                "      Updpate News Test 1",
                "     https://www.google.com/",
                "")
            self.assertIn("topic/", result.get_url())

    # TC_UPDATE_NEWS_WITH_DUPLICATE_DATA
    def test_update_news_with_duplicate_data(self):
        print("\n" + str(test_cases("TC_UPDATE_NEWS_WITH_DUPLICATE_DATA")))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check update news with valid data
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            result = CanonizerEditNewsFeedsPage(self.driver).update_news_with_duplicate_data(
                "Test1",
                "https://www.google.com/",
                "")
            self.assertIn("topic/", result.get_url())

    # TC_UPDATE_NEWS_FEED_INVALID_DATA
    def test_update_news_with_invalid_data(self):
        print("\n" + str(test_cases('TC_UPDATE_NEWS_FEED_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().update_news_with_invalid_data(
            "Test-2",
            "https://www.google.com/***",
            "").get_url()
        self.assertIn("editnews", result)

    # ----- Delete News Test Cases Start  -----
    #  TC_DELETE_NEWS_01
    def test_delete_news_button_visibility(self):
        print("\n", str(test_cases('TC_DELETE_NEWS_01')))
        self.login_to_canonizer_app()
        result = CanonizerDeleteNewsFeedsPage(
            self.driver).click_delete_news_feed().delete_news_button_visibility().get_url()
        self.assertIn("/topic/173-Software-Testing/1-Agreement", result)

    # TC_DELETE_NEWS_02
    def test_delete_news(self):
        print("\n", str(test_cases("TC_DELETE_NEWS_01")))
        self.login_to_canonizer_app()
        result = CanonizerDeleteNewsFeedsPage(self.driver).click_delete_news_feed().delete_news().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_DELETE_NEWS_03
    def test_delete_child_news(self):
        print("\n", str(test_cases("TC_DELETE_NEWS_03")))
        self.login_to_canonizer_app()
        result = CanonizerDeleteNewsFeedsPage(self.driver).click_delete_news_feed().delete_child_news().get_url()
        self.assertIn("topic/173-Software-Testing/", result)

    # ----- Delete News Test Cases End  -----

    # ----- Add News, Edit News and Delete News Test Cases End -----

    # ----- File Upload Test Cases Start -----
    # TC_UPLOAD_FILE_WITH_VALID_FORMAT
    def test_upload_file_with_valid_format(self):
        print("\n" + str(test_cases('TC_UPLOAD_FILE_WITH_VALID_FORMAT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with invalid file format
        result = CanonizerUploadFilePage(self.driver).click_upload_file_page_button().upload_file_with_valid_format(
            DEFAULT_ORIGINAL_FILE_NAME,
            "Testfilefour"
        )
        self.assertIn("upload", result.get_url())

    # TC_UPLOAD_FILE_WITH_SIZE_FILE_MORE_THAN_5MB
    def test_upload_file_with_size_file_more_than_5mb(self):
        print("\n" + str(test_cases('TC_UPLOAD_FILE_WITH_SIZE_FILE_MORE_THAN_5MB')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with file size more than 5MB
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().upload_file_with_size_file_more_than_5mb(
            FILE_WITH_MORE_THAN_5MB,
            'testmaxlimitfile'
        ).get_url()
        self.assertIn("/upload", result)

    # TC_UPLOAD_FILE_WITH_SAME_FILE_NAME
    def test_upload_file_with_same_file_name(self):
        print("\n" + str(test_cases('TC_UPLOAD_FILE_WITH_SAME_FILE_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with existing file name
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().upload_file_with_same_file_name(
            FILE_WITH_SAME_NAME,
            "Testfilethree"
        ).get_url()
        self.assertIn("/upload", result)

    # TC_UPLOAD_FILE_WITH_SIZE_ZERO_BYTES
    def test_upload_file_with_size_zero_bytes(self):
        print("\n" + str(test_cases('TC_UPLOAD_FILE_WITH_SAME_FILE_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with size zero bytes
        result = CanonizerUploadFilePage(self.driver).click_upload_file_page_button().upload_file_with_size_zero_bytes(
            FILE_WITH_ZERO_BYTES,
            "testzerobytefile"
        ).get_url()
        self.assertIn("upload", result)

    # TC_UPLOAD_FILE_16
    def test_verify_recent_upload_file_name_from_list_of_files(self):
        print("\n" + str(test_cases("TC_UPLOAD_FILE_16")))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with size zero bytes
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().verify_recent_upload_file_name_from_list_of_files(
            RECENT_FILE,
            "testrecentfileone"
        )
        self.assertIn("upload", result.get_url())

    # TC_UPLOAD_FILE_17
    def test_verify_uploaded_image_file_format(self):
        print("\n" + str(test_cases("TC_UPLOAD_FILE_16")))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with size zero bytes
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().verify_uploaded_image_file_format(
            OTHER_FILE_TYPE,
            "textfileone"
        )
        self.assertIn("upload", result.get_url())

    # ----- File Upload Test Cases End -----

    # ----- Search Test Cases Start -----
    # TC_CLICK_SEARCH_BUTTON
    def test_click_search_button(self):
        print("\n" + str(test_cases('TC_CLICK_SEARCH_BUTTON')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button()
        self.assertIn("", result.get_url())

    # TC_CLICK_SEARCH_BUTTON_WEB
    def test_click_search_button_web(self):
        print("\n" + str(test_cases('TC_CLICK_SEARCH_BUTTON_WEB')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button_web()
        self.assertIn("", result.get_url())

    # TC_CLICK_SEARCH_BUTTON_KEYWORD_WEB
    def test_click_search_button_keyword_web(self):
        print("\n" + str(test_cases('TC_CLICK_SEARCH_BUTTON_KEYWORD_WEB')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button_keyword_web('Testing')
        self.assertIn("", result.get_url())

    # TC_CLICK_SEARCH_BUTTON_KEYWORD_CANONIZER_COM
    def test_click_search_button_keyword_canonizer_com(self):
        print("\n" + str(test_cases('TC_CLICK_SEARCH_BUTTON_KEYWORD_CANONIZER_COM')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button_keyword_canonizer_com('Testing')
        self.assertIn("", result.get_url())

    # TC_SEARCH_BAR_PLACEHOLDER
    def test_verify_search_button_placeholder(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).verify_search_button_placeholder()
        self.assertIn("", result.get_url())

    # ----- Search Test Cases End -----

    # TC_VERIFY_PHONE_NUMBER_WITH_BLANK_PHONE_NUMBER
    def test_verify_phone_number_with_blank_phone_number(self):
        print("\n" + str(test_cases('TC_VERIFY_PHONE_NUMBER_WITH_BLANK_PHONE_NUMBER')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).verify_phone_number_with_blank_phone_number()
        self.assertIn("Phone number is required.", result)

    # TC_SELECT_BY_VALUE_CRYPTO_CURRENCY_ETHEREUM
    def test_select_by_value_crypto_currency_ethereum(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_CRYPTO_CURRENCY_ETHEREUM')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_crypto_currency_ethereum().get_url())

    # TC_SELECT_BY_VALUE_CRYPTO_CURRENCY_ETHEREUM_ONLY_MY_TOPICS
    def test_select_by_value_crypto_currency_ethereum_only_my_topics(self):
        print("\n" + str(test_cases('TC_SELECT_BY_VALUE_CRYPTO_CURRENCY_ETHEREUM_ONLY_MY_TOPICS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).select_by_value_crypto_currency_ethereum_only_my_topics().get_url())

    # TC_VERIFY_CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING_PAGE_LOADED
    def test_check_canonizer_is_the_final_word_on_everything_page_loaded(self):
        print("\n" + str(test_cases('TC_VERIFY_CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING_PAGE_LOADED')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Canonizer is the final word on everything
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_canonizer_is_the_final_word_on_everything_page_loaded().get_url()
        self.assertIn("https://vimeo.com/307590745", result)

    # TC_VERIFY_CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING_PAGE_LOADED_WITHOUT_LOGIN
    def test_check_Canonizer_is_the_final_word_on_everything_page_loaded_without_login(self):
        print("\n" + str(test_cases('TC_VERIFY_CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING_PAGE_LOADED_WITHOUT_LOGIN')))
        # Click on the Help and Click on Canonizer is the final word on everything
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Canonizer_is_the_final_word_on_everything_page_loaded().open(
            "https://vimeo.com/307590745")

    # TC_VERIFY_CONSENSUS_OUT_OF_CONTROVERSY_USER_CASE_PAGE_LOADED
    def test_check_consensus_out_of_controversy_use_case_page_loaded(self):
        print("\n" + str(test_cases('TC_VERIFY_CONSENSUS_OUT_OF_CONTROVERSY_USER_CASE_PAGE_LOADED')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Consensus out of controversy use case
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_consensus_out_of_controversy_use_case_page_loaded().get_url()
        self.assertIn("topic/132-Consensus-out-of-controversy/2", result)

    # TC_VERIFY_CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE_PAGE_LOADED_WITHOUT_LOGIN
    def test_check_consensus_out_of_controversy_use_case_page_loaded_without_login(self):
        print("\n" + str(test_cases('TC_VERIFY_CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE_PAGE_LOADED_WITHOUT_LOGIN')))
        # Click on the Help and Click on Consensus out of controversy use case
        result = CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_consensus_out_of_controversy_use_case_page_loaded().get_url()
        self.assertIn("topic/132-Consensus-out-of-controversy/2", result)

    # TC_LOAD_CREATE_NEW_CAMP_PAGE
    def test_load_create_new_camp_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_CREATE_NEW_CAMP_PAGE')))
        self.assertIn("camp/create/173-Software-Testing/1-Agreement",
                      CanonizerCampPage(self.driver).load_create_new_camp_page().get_url())

    # TC_SAVE_WITH_INVALID_NEW_PASSWORD
    def test_save_with_invalid_new_password(self):
        print("\n" + str(test_cases('TC_SAVE_WITH_INVALID_NEW_PASSWORD')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_invalid_new_password(
            'Test@12345',
            'TEST@123456',
            'TEST@123456').get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_VERIFY_FORGOT_PASSWORD_SAVE_BUTTON
    def test_verify_forgot_password_save_button(self):
        print("\n" + str(test_cases('TC_VERIFY_FORGOT_PASSWORD_SAVE_BUTTON')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(
            self.driver).verify_forgot_password_save_button().get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_VERIFY_FORGOT_PASSWORD_SAVE_BUTTON_ON_ENTER_KEY
    def test_verify_forgot_password_save_button_on_enter_key(self):
        print("\n" + str(test_cases('TC_VERIFY_FORGOT_PASSWORD_SAVE_BUTTON')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(
            self.driver).verify_forgot_password_save_button_on_enter_key().get_url()
        self.assertIn("/settings/changepassword", result)

    # TC_LOGIN_WITH_BLANK_EMAIL
    def test_login_with_blank_email(self):
        print("\n" + str(test_cases('TC_LOGIN_WITH_BLANK_EMAIL')))
        result = CanonizerLoginPage(self.driver).click_login_page_button().login_with_blank_email(
            DEFAULT_PASS).get_url()
        self.assertIn("/login", result)

    # TC_LOGIN_WITH_BLANK_PASSWORD
    def test_login_with_blank_password(self):
        print("\n" + str(test_cases('TC_LOGIN_WITH_BLANK_PASSWORD')))
        result = CanonizerLoginPage(self.driver).click_login_page_button().login_with_blank_password(
            DEFAULT_USER).get_url()
        self.assertIn("/login", result)

    # TC_LOGIN_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_login_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_LOGIN_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        self.assertTrue(CanonizerLoginPage(
            self.driver).click_login_page_button().login_page_mandatory_fields_are_marked_with_asterisk())

    # TC_LOGIN_SHOULD_HAVE_FORGOT_PASSWORD_LINK
    def test_login_should_have_forgot_password_link(self):
        print("\n" + str(test_cases('TC_LOGIN_SHOULD_HAVE_FORGOT_PASSWORD_LINK')))
        self.assertIn('login', CanonizerLoginPage(
            self.driver).click_login_page_button().login_should_have_forgot_password_link().get_url())

    # TC_REGISTRATION_WITH_DUPLICATE_EMAIL
    def test_registration_with_duplicate_email(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_DUPLICATE_EMAIL')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_duplicate_email(
            REG_LIST_2).get_url()
        self.assertIn("/signup", result)

    # TC_VERIFY_TOPIC_PAGE_FROM_MY_SUPPORTS_LOADED
    def test_check_topic_page_from_my_supports_loaded(self):
        print("\n" + str(test_cases('TC_VERIFY_TOPIC_PAGE_FROM_MY_SUPPORTS_LOADED')))
        # Click on Account Settings->My Supports->Topic name
        self.login_to_canonizer_app()
        # Click on the Account Settings->My Supports->Topic name link
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        result = AccountSettingsMySupportsPage(self.driver).check_topic_page_from_my_supports_loaded()
        self.assertIn("topic/631-Verify-Single-Support-Camp-4/1-Agreement", result.get_url())

    # TC_CHECK_CAMP_PAGE_FROM_MY_SUPPORTS_LOADED
    def test_check_camp_page_from_my_supports_loaded(self):
        print("\n" + str(test_cases('TC_CHECK_CAMP_PAGE_FROM_MY_SUPPORTS_LOADED')))
        # Click on Account Settings->My Supports->Topic name
        self.login_to_canonizer_app()
        # Click on the Account Settings->My Supports->Camp name link
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        result = AccountSettingsMySupportsPage(self.driver).check_camp_page_from_my_supports_loaded()
        self.assertIn("topic/631-Verify-Single-Support-Camp-4/1-Agreement", result.get_url())

    # TC_SUBMIT_UPDATE_WIH_BLANK_TOPIC_NAME
    def test_submit_update_with_blank_topic_name(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WIH_BLANK_TOPIC_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Topic update and check if topic name is blank
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_blank_topic_name(
            "Test",
            "",
            "")
        self.assertIn("manage/topic/", result.get_url())

    # TC_SUBMIT_TOPIC_UPDATE_WITH_DUPLICATE_TOPIC_NAME
    def test_submit_topic_update_with_duplicate_topic_name(self):
        print("\n" + str(test_cases('TC_SUBMIT_TOPIC_UPDATE_WITH_DUPLICATE_TOPIC_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Topic update and check if topic name is duplicate
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().submit_topic_update_with_duplicate_topic_name(
            "",
            DUPLICATE_TOPIC_NAME,
            "",
            "")
        self.assertIn("manage/topic/", result.get_url())

    # TC_CREATE_CAMP_WITH_DUPLICATE_CAMP_NAME
    def test_create_camp_with_duplicate_camp_name(self):
        print("\n" + str(test_cases('TC_CREATE_CAMP_WITH_DUPLICATE_CAMP_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if camp name is duplicate
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_duplicate_camp_name(
            CREATE_CAMP_LIST_2)
        self.assertIn("camp/create", result.get_url())

    # TC_UPDATE_CAMP_WITH_EXISTING_DATA
    def test_submit_camp_update_with_duplicate_camp_name(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_EXISTING_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit This Camp link and check if camp name is duplicate
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_duplicate_camp_name(
            CAMP_LIST_7).get_url()
        self.assertIn("manage/camp", result)

    # TC_UPDATE_CAMP_VALIDATION_OF_CAMP_NAME
    def test_update_camp_validation_of_camp_name(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_VALIDATION_OF_CAMP_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit This Camp link and check if camp name is duplicate
        result = CanonizerEditCampPage(self.driver).update_camp_validation_of_camp_name().get_url()
        self.assertIn("manage/camp", result)

    # TC_EDIT_NEWS_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_edit_news_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_EDIT_NEWS_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Edit News link
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            self.assertTrue(
                CanonizerEditNewsFeedsPage(self.driver).edit_news_page_mandatory_fields_are_marked_with_asterisk())

    # ----- Add Camp Statement Test Cases Start -----

    # TC_LOAD_ADD_CAMP_STATEMENT_PAGE
    def test_load_add_camp_statement_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_ADD_CAMP_STATEMENT_PAGE')))
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        self.assertIn("create/statement/", result.get_url())

    # TC_ADD_CAMP_STATEMENT_PAGE_MANDATORY_FIELDS_WITH_ASTERISK
    def test_add_camp_statement_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_PAGE_MANDATORY_FIELDS_WITH_ASTERISK')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            self.assertTrue(
                AddCampStatementPage(self.driver).add_camp_statement_page_mandatory_fields_are_marked_with_asterisk())

    # TC_ADD_CAMP_STATEMENT_WITHOUT_MANDATORY_FIELDS
    def test_add_camp_statement_page_without_mandatory_fields(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_WITHOUT_MANDATORY_FIELDS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(
            self.driver).load_add_camp_statement_page().add_camp_statement_page_without_mandatory_fields(" ").get_url()
        self.assertIn("create/statement/", result)

    # TC_ADD_CAMP_STATEMENT_WITH_MANDATORY_FIELDS
    def test_add_camp_statement_page_mandatory_fields_only(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_WITH_MANDATORY_FIELDS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(
            self.driver).load_add_camp_statement_page().add_camp_statement_page_mandatory_fields_only(
            "Test statement").get_url()
        self.assertIn("statement/history", result)

    # TC_ADD_CAMP_STATEMENT_WITH_VALID_DATA
    def test_add_camp_statement_page_valid_data(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_WITH_VALID_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page().add_camp_statement_page_valid_data(
            "Test statement", "Testing for note").get_url()
        self.assertIn("statement/history", result)

    # TC_ADD_CAMP_STATEMENT_WITH_INVALID_DATA
    def test_add_camp_statement_page_invalid_data(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_WITH_INVALID_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(
            self.driver).load_add_camp_statement_page().add_camp_statement_page_without_mandatory_fields(
            "").get_url()
        self.assertIn("create/statement", result)

    # TC_ADD_CAMP_STATEMENT_PAGE_DATA_WITH_TRAILING_SPACES
    def test_add_camp_statement_page_data_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_PAGE_DATA_WITH_TRAILING_SPACES')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page().add_camp_statement_page_valid_data(
            "             Test statement Trailing spaces",
            "        Testing for with trailing spaces").get_url()
        self.assertIn("statement/history", result)

    # TC_SUBMIT_STATEMENT_WITH_BLANK_NICK_NAME
    def test_submit_statement_with_blank_nick_name(self):
        print("\n" + str(test_cases('TC_SUBMIT_STATEMENT_WITH_BLANK_NICK_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(self.driver).submit_statement_with_blank_nick_name("", "")
            if result == 1:
                self.assertIn("The nick name field is required.", result)

    # TC_ADD_CAMP_STATEMENT_BLANK_STATEMENT
    def test_submit_statement_with_blank_statement(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_BLANK_STATEMENT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(self.driver).submit_statement_with_blank_statement(
                "Test",
                "").get_url()
            self.assertIn("create/statement", result)

    # TC_ADD_CAMP_STATEMENT_PAGE_SHOULD_HAVE_ADD_NEW_NICK_NAME_LINK_FOR_NEW_USERS
    def test_add_camp_statement_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_PAGE_SHOULD_HAVE_ADD_NEW_NICK_NAME_LINK_FOR_NEW_USERS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(
                self.driver).add_camp_statement_page_should_have_add_new_nick_name_link_for_new_users()
            if result == 1:
                self.assertIn("Add New Nick Name", result)

    # TC_REGISTRATION_WITH_BLANK_SPACES_FIRST_NAME
    def test_registration_with_blank_spaces_first_name(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_BLANK_SPACES_FIRST_NAME')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_spaces_first_name(
            REG_LIST_1).get_url()
        self.assertIn("/signup", result)

    # TC_BROKEN_URL
    def test_broken_url(self):
        self.assertTrue(
            CanonizerBrokenURL(self.driver).search_topic_on_google_search()
        )

    # 178
    def test_check_topic_create_new_camp_page_from_my_supports_loaded(self):
        print("\n" + str(test_cases(177)))
        # Click on Account Settings->My Supports->Topic name->Create New Camp
        self.login_to_canonizer_app()
        # Click on the Account Settings->My Supports->Topic name link
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        result = AccountSettingsMySupportsPage(self.driver).check_topic_create_new_camp_page_from_my_supports_loaded()
        if result:
            self.assertIn("camp/create/", result.get_url())

    # TC_EDIT_CAMP_STATEMENT_WITH_BLANK_STATEMENT
    def test_submit_statement_update_with_blank_statement(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_WITH_BLANK_STATEMENT')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(self.driver).submit_statement_update_with_blank_statement(
                "",
                "").get_url()
            self.assertIn("statement/history", result)

    # TC_EDIT_CAMP_STATEMENT_WITH_TRAILING_SPACES
    def test_submit_statement_update_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_WITH_TRAILING_SPACES')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(self.driver).submit_statement_update_with_trailing_spaces(
                "          camp statement with trailing sapces").get_url()
            print(result)
            self.assertIn("statement/history", result)

    # TC_EDIT_CAMP_STATEMENT_WITH_ONLY_MANDATORY_FIELDS
    def test_submit_statement_update_with_only_mandatory_fields(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_WITH_ONLY_MANDATORY_FIELDS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(self.driver).submit_statement_update_with_trailing_spaces(
                "Camp Statement with Only Mandatory Fields").get_url()
            self.assertIn("statement/history", result)

    # TC_EDIT_CAMP_STATEMENT_WITH_ENTER_KEY
    def test_edit_camp_statement_with_enter_key(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_WITH_ENTER_KEY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(self.driver).edit_camp_statement_with_enter_key(
                "Update Camp Statement with Enter Keys").get_url()
            self.assertIn("statement/history", result)

    # 180
    def test_check_create_new_camp_page_from_algo_info_topic_loaded(self):
        print("\n" + str(test_cases(179)))
        # Click on Account Settings->My Supports->Topic name->Create New Camp
        self.login_to_canonizer_app()
        self.assertIn("camp/create/53-Canonizer-Algorithms/1-Agreement", CanonizerAlgorithmInformation(
            self.driver).check_camp_create_new_camp_page_from_algo_info_loaded().get_url())

    # 181
    def test_check_turn_off_settings(self):
        print("\n" + str(test_cases(180)))
        # Click on Account Settings->My Supports->Topic name->Create New Camp
        self.login_to_canonizer_app()
        # Hit URL https://staging.canonizer.com/robots.txt
        url = self.driver.current_url
        newurl = url + "/robots.txt"
        self.driver.get(newurl)
        self.assertIn('Disallow: /', CanonizerHomePage(self.driver).robots_txt_page_should_have_disallow_settings())

    # 182
    def test_check_turn_off_settings_without_login(self):
        print("\n" + str(test_cases(181)))
        # Hit URL https://staging.canonizer.com/robots.txt
        url = self.driver.current_url
        newurl = url + "/robots.txt"
        self.driver.get(newurl)
        self.assertIn('Disallow: /', CanonizerHomePage(self.driver).robots_txt_page_should_have_disallow_settings())
        # self.assertIn('Disallow: /settings', CanonizerHomePage(self.driver).robots_txt_page_should_have_disallow_settings())

    # 183
    def test_upload_file_without_user_login(self):
        print("\n" + str(test_cases(182)))
        # Click on Upload File link
        self.assertIn("/login", CanonizerUploadFilePage(self.driver).upload_file_without_user_login().get_url())

    # 184
    def test_load_camp_user_supports_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(183)))
        result = CanonizerEditCampPage(self.driver).load_camp_user_supports_page()
        if result:
            self.assertIn("user/supports/347?topicnum=173&campnum=2&namespace=16#camp_173_2", result.get_url())

    # 185
    def test_load_camp_agreement_from_user_supports_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(184)))
        self.assertIn("topic/173-Software-Testing",
                      CanonizerEditCampPage(self.driver).load_camp_agreement_from_user_supports_page().get_url())

    # TC_LOAD_FOOTER_PRIVACY_POLICY
    def test_load_privacy_policy_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_FOOTER_PRIVACY_POLICY')))
        self.assertIn("privacypolicy", CanonizerTermsAndPrivacyPolicy(self.driver).load_privacy_policy_page().get_url())

    # TC_LOAD_FOOTER_TERMS_SERVICES
    def test_load_terms_services_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_FOOTER_TERMS_SERVICES')))
        self.assertIn("termservice", CanonizerTermsAndPrivacyPolicy(self.driver).load_terms_services_page().get_url())

    # TC_FOOTER_PRIVACYPOLICY
    def test_footer_should_have_privacy_policy(self):
        print("\n" + str(test_cases('TC_FOOTER_PRIVACYPOLICY')))
        self.login_to_canonizer_app()
        result = CanonizerHomePage(self.driver).footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn('/', result)

    # TC_FOOTER_TERMS_SERVICES
    def test_footer_should_have_terms_services(self):
        print("\n" + str(test_cases('TC_FOOTER_TERMS_SERVICES')))
        self.login_to_canonizer_app()
        self.assertIn('Terms & Services',
                      CanonizerHomePage(self.driver).footer_should_have_privacy_policy_and_terms_services())

    # TC_FOOTER_PRIVACY_POLICY
    def test_verify_footer_for_privacy_policy(self):
        print("\n" + str(test_cases('TC_FOOTER_PRIVACY_POLICY')))
        result = CanonizerHomePage(self.driver).verify_footer_for_privacy_policy().get_url()
        self.assertIn("/", result)

    # TC_FOOTER_COPY_RIGHT
    def test_verify_footer_for_copy_right_year(self):
        print("\n" + str(test_cases('TC_FOOTER_COPY_RIGHT')))
        result = CanonizerHomePage(self.driver).verify_footer_for_copy_right_year().get_url()
        self.assertIn("/", result)

    # TC_FOOTER_TERMS_AND_SERVICES
    def test_verify_footer_for_terms_and_services(self):
        print("\n" + str(test_cases('TC_FOOTER_TERMS_AND_SERVICES')))
        result = CanonizerHomePage(self.driver).verify_footer_for_terms_and_services().get_url()
        self.assertIn("/", result)

    # TC_FOOTER_SUPPORT_CANONIZER
    def test_verify_footer_for_support_canonizer(self):
        print("\n" + str(test_cases('TC_FOOTER_SUPPORT_CANONIZER')))
        result = CanonizerHomePage(self.driver).verify_footer_for_support_canonizer().get_url()
        self.assertIn("/", result)

    # TC_VERIFY_FOOTER_COPYRIGHTYEAR
    def test_footer_should_have_copyright_year(self):
        print("\n" + str(test_cases('TC_VERIFY_FOOTER_COPYRIGHTYEAR')))
        self.login_to_canonizer_app()
        result = CanonizerHomePage(self.driver).footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn("/", result)

    # 191
    def test_check_garbage_url(self):
        print("\n" + str(test_cases(190)))
        # Click on Account Settings->My Supports->Topic name->Create New Camp
        self.login_to_canonizer_app()
        # Hit URL https://staging.canonizer.com/robots.txt
        url = self.driver.current_url
        newurl = url + "/garbage"
        self.driver.get(newurl)
        self.assertIn('Sorry, the page you are looking for could not be found or have been removed.',
                      CanonizerHomePage(self.driver).check_garbage_url())

    # 192
    def test_load_agreement_page_from_bread_crumb_agreement_camp_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(191)))
        self.assertIn("topic/173-Software-Testing/1-Agreement", CanonizerCampPage(
            self.driver).load_agreement_page_from_bread_crumb_agreement_camp_link().get_url())

    # 193
    def test_load_agreement_page_from_bread_crumb_child_camp_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(192)))
        self.assertIn("topic/173-Software-Testing/2-Types-Of-Testing",
                      CanonizerCampPage(self.driver).load_agreement_page_from_bread_crumb_child_camp_link().get_url())

    # 194
    def test_load_agreement_page_from_bread_crumb_forum_agreement_camp_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(193)))
        result = CanonizerCampPage(
            self.driver).load_agreement_page_from_bread_crumb_forum_agreement_camp_link().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # 195
    def test_load_agreement_page_from_bread_crumb_camp_statement_history_agreement_camp_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(194)))
        result = CanonizerCampPage(
            self.driver).load_agreement_page_from_bread_crumb_camp_statement_history_agreement_camp_link().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # 196
    def test_load_agreement_page_from_bread_crumb_camp_history_agreement_camp_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(195)))
        result = CanonizerCampPage(
            self.driver).load_agreement_page_from_bread_crumb_camp_history_agreement_camp_link().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # 197
    def test_load_agreement_page_from_bread_crumb_create_new_camp_agreement_camp_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(196)))
        result = CanonizerCampPage(
            self.driver).load_agreement_page_from_bread_crumb_create_new_camp_agreement_camp_link().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # 198
    def test_load_agreement_page_from_bread_crumb_topic_history_topic_name_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(197)))
        result = CanonizerCampPage(
            self.driver).load_agreement_page_from_bread_crumb_topic_history_topic_name_link().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # 199
    def test_load_create_camp_page_from_bread_crumb_link(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(198)))
        result = CanonizerCampPage(self.driver).load_create_camp_page_from_bread_crumb_link().get_url()
        self.assertIn("camp/create/173-Software-Testing/1-Agreement", result)

    # 200
    def test_create_camp_with_invalid_camp_name(self):
        print("\n" + str(test_cases(199)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_invalid_camp_name(
            CREATE_CAMP_LIST_3)
        self.assertIn("camp/create/173-Software-Testing", result.get_url())

    # 201
    def test_submit_camp_update_with_invalid_camp_name(self):
        print("\n" + str(test_cases(200)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_invalid_camp_name(
            CAMP_LIST_8)

        self.assertIn("manage/camp", result.get_url())

    # 202
    def test_submit_camp_update_with_blank_camp_name(self):
        print("\n" + str(test_cases(201)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_blank_camp_name(
            CAMP_LIST_10)
        self.assertIn("manage/camp", result.get_url())

    # TC_UPDATE_CAMP_WITH_BLANK_FIELDS
    def test_update_camp_with_blank_fields(self):
        print("\n" + str(test_cases('TC_UPDATE_CAMP_WITH_BLANK_FIELDS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_blank_camp_name(
            CREATE_CAMP_LIST_1)
        self.assertIn("manage/camp", result.get_url())

    # 203
    def test_check_garbage_url_without_login(self):
        print("\n" + str(test_cases(202)))
        # Hit URL https://staging.canonizer.com/robots.txt
        url = self.driver.current_url
        newurl = url + "garbage"
        self.driver.get(newurl)
        self.assertIn('Sorry, the page you are looking for could not be found or have been removed.',
                      CanonizerHomePage(self.driver).check_garbage_url())

    # TC_BLOG_PAGE_FOOTER_COPYRIGHT_YEAR_WITH_LOGIN
    def test_check_blog_page_footer_should_have_copyright_year_with_login(self):
        print("\n" + str(test_cases('TC_BLOG_PAGE_FOOTER_COPYRIGHT_YEAR_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Blog link
        result = CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn("blog", result)

    # TC_BLOG_PAGE_FOOTER_COPYRIGHT_YEAR_WITHOUT_YEAR
    def test_check_blog_page_footer_should_have_copyright_year_without_login(self):
        print("\n" + str(test_cases('TC_BLOG_PAGE_FOOTER_COPYRIGHT_YEAR_WITHOUT_YEAR')))
        # Click on the Blog link
        currentyear = datetime.now().year
        result = CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn("blog", result)

    # TC_BLOG_PAGE_FOOTER_PRIVACY_POLICY
    def test_blog_footer_should_have_privacy_policy(self):
        print("\n" + str(test_cases('TC_BLOG_PAGE_FOOTER_PRIVACY_POLICY')))
        self.login_to_canonizer_app()
        self.assertIn('Privacy Policy',
                      CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services().get_url())

    # TC_BLOG_PAGE_FOOTER_TERMS_SERVICES
    def test_blog_footer_should_have_terms_services(self):
        print("\n" + str(test_cases('TC_BLOG_PAGE_FOOTER_TERMS_SERVICES')))
        self.login_to_canonizer_app()
        self.assertIn('Terms & Services',
                      CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services().get_url())

    # 208
    def test_click_account_settings_crypto_verification_page_button(self):
        print("\n" + str(test_cases(207)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/Social Oauth Verification in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_crypto_verification_page_button()
        self.assertIn("settings/blockchain", result.get_url())

    # 209
    def test_select_by_value_void(self):
        print("\n" + str(test_cases(208)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        result = CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_void().get_url()
        self.assertIn("/browse", result)

    # 210
    def test_select_by_value_mormon_canon_project(self):
        print("\n" + str(test_cases(209)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=24", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_mormon_canon_project().get_url())

    # 211
    def test_select_by_value_organizations_united_utah_party(self):
        print("\n" + str(test_cases(210)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=25", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_united_utah_party().get_url())

    # 212
    def test_create_new_topic_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(211)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_new_topic_page_should_have_add_new_nick_name_link_for_new_users()
        print("result", result)
        if result == 1:
            self.assertIn("Add New Nick Name", result)

    # 213
    def test_create_new_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(212)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampPage(
            self.driver).load_create_new_camp_page().create_new_camp_page_should_have_add_new_nick_name_link_for_new_users()
        if result == 1:
            self.assertIn("Add New Nick Name", result)

    # 214
    def test_request_otp_with_invalid_user_email(self):
        print("\n" + str(test_cases(213)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        result = loginPage.request_otp_with_invalid_user_email(DEFAULT_INVALID_USER).get_url()
        self.assertIn("login", result)

    # 215
    def test_request_otp_with_invalid_user_phone_number(self):
        print("\n" + str(test_cases(214)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        result = loginPage.request_otp_with_invalid_user_phone_number(DEFAULT_INVALID_PHONE_NUMBER).get_url()
        self.assertIn("login", result)

    # 216
    def test_request_otp_with_valid_user_email(self):
        print("\n" + str(test_cases(215)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        result = loginPage.request_otp_with_valid_user_email(DEFAULT_USER)
        self.assertIn("/verify-otp?user=", result.get_url())

    # 217
    def test_request_otp_with_valid_user_phone_number(self):
        print("\n" + str(test_cases(216)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        result = loginPage.request_otp_with_valid_user_phone_number(DEFAULT_VALID_PHONE_NUMBER)
        self.assertIn("/verify-otp?user=", result.get_url())

    # 218
    def test_select_by_value_void_only_my_topics(self):
        print("\n" + str(test_cases(217)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=22&my=22",
                      CanonizerBrowsePage(self.driver).select_by_value_void_only_my_topics().get_url())

    # 219
    def test_select_by_value_mormon_canon_project_only_my_topics(self):
        print("\n" + str(test_cases(218)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=24&my=24",
                      CanonizerBrowsePage(self.driver).select_by_value_mormon_canon_project_only_my_topics().get_url())

    # 220
    def test_select_by_value_organizations_united_utah_party_only_my_topics(self):
        print("\n" + str(test_cases(219)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=25&my=25", CanonizerBrowsePage(
            self.driver).select_by_value_organizations_united_utah_party_only_my_topics().get_url())

    # ----- Open source Test Cases Start -----
    # 221
    def test_check_open_source_should_open_with_login(self):
        print("\n" + str(test_cases(220)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the open source link
        CanonizerOpenSource(self.driver).check_open_source_should_open().open(
            "https://github.com/the-canonizer/canonizer.2.0")

    # 222
    def test_check_open_source_should_open_without_login(self):
        print("\n" + str(test_cases(221)))
        # Click on the open source link
        CanonizerOpenSource(self.driver).check_open_source_should_open().open(
            "https://github.com/the-canonizer/canonizer.2.0")

    # 223
    def test_canonizer_home_page_load_with_user_login(self):
        print("\n" + str(test_cases(222)))
        self.login_to_canonizer_app()
        result = CanonizerMainPage(self.driver).check_home_page_loaded().get_url()
        self.assertIn("", result)

    # 224
    def test_what_is_canonizer_page_loaded_properly_with_user_login(self):
        print("\n" + str(test_cases(223)))
        self.login_to_canonizer_app()
        self.assertTrue(
            CanonizerMainPage(self.driver).click_what_is_canonizer_page_link().check_what_is_canonizer_page_loaded())

    # 225
    def test_check_home_page_loaded_logo_click(self):
        print("\n" + str(test_cases(224)))
        self.login_to_canonizer_app()
        result = CanonizerMainPage(self.driver).check_home_page_loaded_logo_click().get_url()
        self.assertIn("/", result)

    # 226
    def test_check_register_page_open_click_signup_now_link(self):
        print("\n" + str(test_cases(225)))
        loginpage = CanonizerLoginPage(
            self.driver).click_login_page_button().check_register_page_open_click_signup_now_link()
        self.assertIn("/signup", loginpage.get_url())

    # ----- LOGIN Test Cases Start -----
    # TC_VERIFY_UNVERIFIED_ACCOUNT
    def test_verify_unverified_account(self):
        print("\n", str(test_cases('TC_VERIFY_UNVERIFIED_ACCOUNT')))
        result = CanonizerLoginPage(self.driver).verify_unverified_account(
            UNVERIFY_EMAIL, UNVERIFY_PASS
        ).get_url()
        self.assertIn("/login", result)

    # TC_VERIFY_ACCOUNT_LOCK_AFTER_5_UNSUCCESSFUL_ATTEMPT
    def test_verify_account_lock(self):
        print("\n", str(test_cases('TC_VERIFY_ACCOUNT_LOCK_AFTER_5_UNSUCCESSFUL_ATTEMPT')))
        result = CanonizerLoginPage(self.driver).verify_account_lock(VERIFY_EMAIL, VERIFY_PASS).get_url()
        self.assertIn("/login", result)

    # TC_VERIFY_LOGIN_PLACEHOLDERS
    def test_verify_login_placeholders(self):
        print("\n", str(test_cases('TC_VERIFY_LOGIN_PLACEHOLDERS')))
        result = CanonizerLoginPage(self.driver).verify_login_placeholders().get_url()
        self.assertIn("/login", result)

    # TC_VERIFY_LOGIN_CASE_SENSITIVE_UPPERCASE
    def test_verify_login_case_sensitive_01(self):
        print("\n", str(test_cases('TC_VERIFY_LOGIN_CASE_SENSITIVE_UPPERCASE')))
        result = CanonizerLoginPage(self.driver).verify_login_case_sensitive(DEFAULT_USER, PASS_UPPERCASE).get_url()
        self.assertIn("/login", result)

    # TC_VERIFY_LOGIN_CASE_SENSITIVE_LOWERCASE
    def test_verify_login_case_sensitive_02(self):
        print("\n", str(test_cases('TC_VERIFY_LOGIN_CASE_SENSITIVE_LOWERCASE')))
        result = CanonizerLoginPage(self.driver).verify_login_case_sensitive(DEFAULT_USER, PASS_LOWERCASE).get_url()
        self.assertIn("/login", result)

    # TC_VERIFY_LOGIN_PAGE_OPEN_CLICK_LOGIN_HERE_LINK
    def test_check_login_page_open_click_login_here_link(self):
        print("\n" + str(test_cases('TC_VERIFY_LOGIN_PAGE_OPEN_CLICK_LOGIN_HERE_LINK')))
        registerpage = CanonizerRegisterPage(
            self.driver).click_register_button().check_login_page_open_click_login_here_link()
        self.assertIn("/login", registerpage.get_url())

    # TC_CHECK_SCROLL_TO_TOP_CLICK
    def test_check_scroll_to_top_click(self):
        print("\n" + str(test_cases('TC_CHECK_SCROLL_TO_TOP_CLICK')))
        self.login_to_canonizer_app()
        self.assertTrue(CanonizerMainPage(self.driver).check_scroll_to_top_click())

    # TC_LOGIN_WITH_BLANK_OTP
    def test_login_with_blank_otp(self):
        print("\n" + str(test_cases('TC_LOGIN_WITH_BLANK_OTP')))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        result = CanonizerLoginPage(self.driver).request_otp_with_valid_user_email(
            DEFAULT_USER).login_with_blank_otp().get_url()
        self.assertIn("/verify-otp?", result)

    # 230
    def test_login_otp_verification_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(229)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        self.assertTrue(CanonizerLoginPage(self.driver).request_otp_with_valid_user_email(
            DEFAULT_USER).login_otp_verification_page_mandatory_fields_are_marked_with_asterisk())

    # 231
    def test_login_with_invalid_otp(self):
        print("\n" + str(test_cases(230)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        result = CanonizerLoginPage(self.driver).request_otp_with_valid_user_email(DEFAULT_USER).login_with_invalid_otp(
            DEFAULT_INVALID_OTP).get_url()
        self.assertIn("/verify-otp?", result)

    # 232
    def test_registration_with_blank_captcha(self):
        print("\n" + str(test_cases(231)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_captcha(
            REG_LIST_9).get_url()
        self.assertIn("/signup", result)

    # 233
    def test_registration_with_invalid_first_name(self):
        print("\n" + str(test_cases(232)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_first_name(
            REG_LIST_10).get_url()
        self.assertIn("/signup", result)

    # 234
    def test_registration_with_invalid_last_name(self):
        print("\n" + str(test_cases(233)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_last_name(
            REG_LIST_11).get_url()
        self.assertIn("/signup", result)

    # 235
    def test_registration_with_invalid_middle_name(self):
        print("\n" + str(test_cases(234)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_middle_name(
            REG_LIST_12).get_url()
        self.assertIn("/signup", result)

    # 236
    def test_open_uploaded_file(self):
        print("\n" + str(test_cases(235)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerUploadFilePage(self.driver).click_upload_file_page_button().open_uploaded_file()

    # 237
    def test_load_create_camp_page_without_login(self):
        print("\n" + str(test_cases(236)))
        result = CanonizerCampPage(self.driver).load_create_camp_page_without_login().get_url()
        self.assertIn("/login", result)

    # 238
    def test_create_topic_with_invalid_topic_name(self):
        print("\n" + str(test_cases(237)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_invalid_topic_name(
            DEFAULT_NICK_NAME,
            INVALID_TOPIC_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # 239
    def test_verify_phone_number_with_invalid_length_phone_number(self):
        print("\n" + str(test_cases(238)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).verify_phone_number_with_invalid_length_phone_number().get_url()
        self.assertIn("/settings", result)

    # TC_PHONE_NUMBER_02
    def test_verify_phone_number_with_characters(self):
        print("\n", str(test_cases("TC_PHONE_NUMBER_02")))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).verify_phone_number_with_characters().get_url()
        self.assertIn("/settings", result)

    # 240
    def test_update_profile_with_invalid_first_name(self):
        print("\n" + str(test_cases(239)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_invalid_first_name(
            INVALID_NAME_REGISTER,
            DEFAULT_MIDDLE_NAME,
            DEFAULT_LAST_NAME,
        ).get_url()
        self.assertIn("/settings", result)

    # 241
    def test_update_profile_with_invalid_middle_name(self):
        print("\n" + str(test_cases(240)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_invalid_middle_name(
            DEFAULT_FIRST_NAME,
            INVALID_NAME_REGISTER,
            DEFAULT_LAST_NAME,
        ).get_url()
        self.assertIn("/settings", result)

    # 242
    def test_update_profile_with_invalid_last_name(self):
        print("\n" + str(test_cases(241)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_invalid_last_name(
            DEFAULT_FIRST_NAME,
            DEFAULT_MIDDLE_NAME,
            INVALID_NAME_REGISTER,
        ).get_url()
        self.assertIn("/settings", result)

    # TC_UPDATE_PROFILE_WITH_VALID_DATA_WITH_ENTER_KEY
    def test_update_profile_with_valid_data_with_enter_key(self):
        print("\n", str(test_cases('TC_UPDATE_PROFILE_WITH_VALID_DATA_WITH_ENTER_KEY')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).update_profile_with_valid_data_with_enter_key(
            "Pooja",
            "  ",
            " Khatri "
        ).get_url()
        self.assertIn("/settings", result)

    # TC_UPDATE_PROFILE_WITH_MANDATORY_FIELDS
    def test_update_profile_with_mandatory_fields(self):
        print("\n", str(test_cases('TC_UPDATE_PROFILE_WITH_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).update_profile_with_mandatory_fields(
            "Pooja",
            "Khatri "
        ).get_url()
        self.assertIn("/settings", result)

    # TC_UPDATE_PROFILE_WITH_BLANK_MANDATORY_FIELDS
    def test_update_profile_with_blank_mandatory_fields(self):
        print("\n", str(test_cases('TC_UPDATE_PROFILE_WITH_BLANK_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).update_profile_with_blank_mandatory_fields(
        ).get_url()
        self.assertIn("/settings", result)

    # TC_VERIFY_DOB_ON_PROFILE_INFO
    def test_verify_dob_on_profile_info(self):
        print("\n", str(test_cases('TC_VERIFY_DOB_ON_PROFILE_INFO')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).verify_dob_on_profile_info(
        ).get_url()
        self.assertIn("/settings", result)

    # TC_SUPPORT_PAGE_01
    def test_verify_delete_support_confirmation_alertbox(self):
        print("\n", str(test_cases('TC_SUPPORT_PAGE_01')))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsMySupportsPage(
            self.driver).verify_delete_support_confirmation_alertbox(
        ).get_url()
        self.assertIn("/support'", result)

    # TC_SUBMIT_UPDATE_WITH_INVALID_TOPIC_NAME
    def test_submit_update_with_invalid_topic_name(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WITH_INVALID_TOPIC_NAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_invalid_topic_name(
            "Test",
            INVALID_TOPIC_NAME,
            "",
            "").get_url()
        self.assertIn("manage/topic", result)

    # TC_SUBMIT_UPDATE_WITH_TRAILING_SPACES
    def test_submit_update_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WITH_TRAILING_SPACES')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_trailing_spaces(
            "",
            "",
            "",
            "     Trailing sapces testing").get_url()
        self.assertIn("topic-history", result)

    # TC_SUBMIT_UPDATE_WITH_ENTER_KEY
    def test_submit_update_with_enter_key(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WITH_ENTER_KEY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_enter_key(
            "",
            "",
            "",
            "submit data with enter_key").get_url()
        self.assertIn("topic-history", result)

    # TC_SUBMIT_UPDATE_WITH_MANDATORY_FIELDS_ONLY
    def test_submit_update_with_manadatory_fields_only(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WITH_MANDATORY_FIELDS_ONLY')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().submit_update_with_manadatory_fields_only(
            "",
            "Software Testing",
            "/General/",
            ""
        ).get_url()
        self.assertIn("topic-history", result)

    # TC_SUBMIT_UPDATE_WITH_DUPLICATE_DATA
    def test_submit_update_with_duplicate_data(self):
        print("\n" + str(test_cases('TC_SUBMIT_UPDATE_WITH_DUPLICATE_DATA')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().submit_update_with_duplicate_data(
            "",
            "Testing Topic 1",
            "/General/",
            ""
        ).get_url()
        self.assertIn("manage/topic", result)

    # TC_NICKNAME_PAGE_SHOULD_OPEN_CREATE_TOPIC_ADD_NEW_NICKNAME
    def test_nick_name_page_should_open_create_topic_add_new_nick_name(self):
        print("\n" + str(test_cases('TC_NICKNAME_PAGE_SHOULD_OPEN_CREATE_TOPIC_ADD_NEW_NICKNAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().nick_name_page_should_open_create_topic_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # TC_NICKNAME_PAGE_SHOULD_OPEN_CREATE_CAMP_ADD_NEW_NICKNAME
    def test_nick_name_page_should_open_create_camp_add_new_nick_name(self):
        print("\n" + str(test_cases('TC_NICKNAME_PAGE_SHOULD_OPEN_CREATE_CAMP_ADD_NEW_NICKNAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampPage(
            self.driver).load_create_new_camp_page().nick_name_page_should_open_create_camp_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # TC_NICKNAME_PAGE_SHOULD_OPEN_UPDATE_TOPIC_ADD_NEW_NICKNAME
    def test_nick_name_page_should_open_update_topic_add_new_nick_name(self):
        print("\n" + str(test_cases('TC_NICKNAME_PAGE_SHOULD_OPEN_UPDATE_TOPIC_ADD_NEW_NICKNAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().nick_name_page_should_open_update_topic_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # TC_NICKNAME_PAGE_SHOULD_OPEN_UPDATE_CAMP_ADD_NEW_NICKNAME
    def test_nick_name_page_should_open_update_camp_add_new_nick_name(self):
        print("\n" + str(test_cases('TC_NICKNAME_PAGE_SHOULD_OPEN_UPDATE_CAMP_ADD_NEW_NICKNAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().nick_name_page_should_open_update_camp_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # TC_LOAD_JOIN_SUPPORT_CAMP_PAGE_WITH_LOGIN
    def test_load_join_support_camp_page_with_login(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases('TC_LOAD_JOIN_SUPPORT_CAMP_PAGE_WITH_LOGIN')))
        self.assertIn("support/", CanonizerJoinSupportCampPage(self.driver).load_join_support_camp_page().get_url())

    # TC_JOIN_SUPPORT_CAMP_PAGE_SHOULD_HAVE_ADD_NEW_NICKNAME_LINK_FOR_NEW_USERS
    def test_join_support_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases('TC_JOIN_SUPPORT_CAMP_PAGE_SHOULD_HAVE_ADD_NEW_NICKNAME_LINK_FOR_NEW_USERS')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(
            self.driver).load_join_support_camp_page().join_support_camp_page_should_have_add_new_nick_name_link_for_new_users()
        if result == 1:
            self.assertIn("Add New Nick Name", result)

    # TC_NICKNAME_PAGE_SHOULD_OPEN_JOIN_SUPPORT_CAMP_ADD_NEW_NICKNAME
    def test_nick_name_page_should_open_join_support_camp_add_new_nick_name(self):
        print("\n" + str(test_cases('TC_NICKNAME_PAGE_SHOULD_OPEN_JOIN_SUPPORT_CAMP_ADD_NEW_NICKNAME')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(
            self.driver).load_join_support_camp_page().nick_name_page_should_open_join_support_camp_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # TC_REGISTRATION_WITH_INVALID_CAPTCHA
    def test_registration_with_invalid_captcha(self):
        print("\n" + str(test_cases('TC_REGISTRATION_WITH_INVALID_CAPTCHA')))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_captcha(
            REG_LIST_13).get_url()
        self.assertIn("/signup", result)

    # TC_VERIFY_PHONE_NUMBER_WITH_VALID_LENGTH_PHONE_NUMBER
    def test_verify_phone_number_with_valid_length_phone_number(self):
        print("\n" + str(test_cases('TC_VERIFY_PHONE_NUMBER_WITH_VALID_LENGTH_PHONE_NUMBER')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).verify_phone_number_with_valid_length_phone_number().get_url()
        self.assertIn("/settings", result)

    # TC_CHECK_JOBS_PAGE_SHOULD_OPEN_WITH_LOGIN
    def test_check_jobs_page_should_open_with_login(self):
        print("\n" + str(test_cases('TC_CHECK_JOBS_PAGE_SHOULD_OPEN_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Jobs link
        result = CanonizerJobs(self.driver).check_jobs_page_should_open().get_url()
        self.assertIn("topic/6-Canonizer-Jobs/1-Agreement", result)

    # TC_CHECK_SERVICES_PAGE_SHOULD_OPEN_WITH_LOGIN
    def test_check_services_page_should_open_with_login(self):
        print("\n" + str(test_cases('TC_CHECK_SERVICES_PAGE_SHOULD_OPEN_WITH_LOGIN')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Services link
        self.assertIn("topic/37-Canonizer-Services/1-Agreement",
                      CanonizerServices(self.driver).check_services_page_should_open().get_url())

    # TC_VERIFY_JOBS_PAGE_SHOULD_OPEN_WITHOUT_LOGIN
    def test_check_jobs_page_should_open_without_login(self):
        print("\n" + str(test_cases('TC_VERIFY_JOBS_PAGE_SHOULD_OPEN_WITHOUT_LOGIN')))
        # Click on the Jobs link
        self.assertIn("topic/6-Canonizer-Jobs/1-Agreement",
                      CanonizerJobs(self.driver).check_jobs_page_should_open().get_url())

    # TC_VERIFY_SERVICES_PAGE_SHOULD_OPEN_WITHOUT_LOGIN
    def test_check_services_page_should_open_without_login(self):
        print("\n" + str(test_cases('TC_VERIFY_SERVICES_PAGE_SHOULD_OPEN_WITHOUT_LOGIN')))
        # Click on the Services link
        self.assertIn("topic/37-Canonizer-Services/1-Agreement",
                      CanonizerServices(self.driver).check_services_page_should_open().get_url())

    # TC_SUBMIT_CAMP_UPDATE_WITH_INVALID_LENGTH_CAMP_ABOUT_URL
    def test_submit_camp_update_with_invalid_length_camp_about_URL(self):
        print("\n" + str(test_cases('TC_SUBMIT_CAMP_UPDATE_WITH_INVALID_LENGTH_CAMP_ABOUT_URL')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().submit_camp_update_with_invalid_length_camp_about_url(
            CAMP_LIST_9).get_url()
        self.assertIn("/manage/camp", result)

    # 258
    def test_request_otp_with_unverified_user_phone_number(self):
        print("\n" + str(test_cases(257)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        result = loginPage.request_otp_with_unverified_user_phone_number(DEFAULT_UNVERIFIED_PHONE_NUMBER).get_url()
        self.assertIn("/login", result)

    # 259
    def test_statement_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(258)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(
                self.driver).statement_update_page_should_have_add_new_nick_name_link_for_new_users()
            if result == 1:
                self.assertIn("Add New Nick Name", result)

    # 260
    def test_nick_name_page_should_open_update_camp_statement_add_new_nick_name(self):
        print("\n" + str(test_cases(259)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(
                self.driver).nick_name_page_should_open_update_camp_statement_add_new_nick_name()
            if result:
                self.assertIn("settings/nickname", result.get_url())

    # 261
    def test_nick_ame_page_should_open_add_camp_statement_add_new_nick_name(self):
        print("\n" + str(test_cases(260)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(self.driver).nick_name_page_should_open_add_camp_statement_add_new_nick_name()
            if result:
                self.assertIn("settings/nickname", result.get_url())

    # 262
    def test_forgot_password_with_invalid_email_format(self):
        print("\n" + str(test_cases(261)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and check with invalid email
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_invalid_email_format(
            DEFAULT_INVALID_EMAIL_FORMAT).get_url()
        self.assertIn("/forgotpassword", result)

    # 263
    def test_registration_with_invalid_email(self):
        print("\n" + str(test_cases(262)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_email(
            REG_LIST_14).get_url()
        self.assertIn("/signup", result)

    # TC_VERIFY_REGISTRATION_PLACEHOLDERS
    def test_verify_registration_placeholders(self):
        print("\n" + str(test_cases('TC_VERIFY_REGISTRATION_PLACEHOLDERS')))
        result = CanonizerRegisterPage(self.driver).click_register_button().verify_registration_placeholders().get_url()
        self.assertIn("/signup", result)

    # 264
    def test_select_by_value_government(self):
        print("\n" + str(test_cases(263)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=26", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_government().get_url())

    # 265
    def test_select_by_value_government_only_my_topics(self):
        print("\n" + str(test_cases(264)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=26&my=26", CanonizerBrowsePage(
            self.driver).select_by_value_government_only_my_topics().get_url())

    # 266
    def test_select_by_value_government_sandy_city(self):
        print("\n" + str(test_cases(265)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=27", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_government_sandy_city().get_url())

    # 267
    def test_select_by_value_government_sandy_city_only_my_topics(self):
        print("\n" + str(test_cases(266)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=27&my=27", CanonizerBrowsePage(
            self.driver).select_by_value_government_sandy_city_only_my_topics().get_url())

    def test_select_menu_items_with_only_my_topics(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerBrowsePage(self.driver).select_menu_item_with_only_my_topics(
            DEF_MENU_ITEM
        )
        self.assertIn("browse?namespace=3&my=3", result.get_url())

    def test_select_menu_items_without_only_my_topics(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerBrowsePage(self.driver).select_menu_item_without_only_my_topics(
            DEF_MENU_ITEM
        )
        self.assertIn("browse?namespace=3", result.get_url())

    # TC_VERIFYING_NAMEPSACE_ONE_BY_ONE
    def test_select_menu_items_one_by_one(self):
        print("\n", str(test_cases('TC_VERIFYING_NAMEPSACE_ONE_BY_ONE')))
        self.login_to_canonizer_app()
        self.assertTrue(CanonizerBrowsePage(self.driver).select_menu_items_one_by_one())

    # TC_LOAD_ADD_CAMP_FORUM_PAGE
    def test_load_add_camp_forum_page(self):
        # Click on the Login Page
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page()
        self.assertIn("/forum/", result.get_url())

    # TC_LOAD_CAMP_FORUM_PAGE
    def test_load_add_camp_forum_page_with_page_crash(self):
        print("\n", str(test_cases('TC_LOAD_CAMP_FORUM_PAGE')))
        # Click on the Login Page
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page_with_page_crash().get_url()
        self.assertIn('forum/173-Software-Testing/1-Agreement/threads', result)

    # TC_LOAD_CREATE_THREAD_PAGE
    def test_load_create_thread_page(self):
        print("\n", str(test_cases('TC_LOAD_CREATE_THREAD_PAGE')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().load_create_thread_page_with_page_crash().get_url()
        self.assertIn('forum', result)

    # TC_CREATE_THREAD_WITH_DUPLICATE_TITLE
    def test_create_thread_with_duplicate_name(self):
        print("\n", str(test_cases('TC_CREATE_THREAD_WITH_DUPLICATE_TITLE')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_duplicate_title_name(
            DEFAULT_NICK_NAME,
            'Test thread demo 11111'
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_CREATE_THREAD_WITH_BLANK_TITLE
    def test_create_thread_with_blank_title(self):
        print("\n", str(test_cases('TC_CREATE_THREAD_WITH_BLANK_TITLE')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_blank_title_name(
            DEFAULT_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_CREATE_THREAD_WITH_SPECIAL_CHAR
    def test_create_thread_with_special_char(self):
        print("\n", str(test_cases('TC_CREATE_THREAD_WITH_SPECIAL_CHAR')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_special_char(
            DEFAULT_NICK_NAME,
            DEFAULT_THREAD_WITH_SPECIAL_CHAR
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_UPDATE_THREAD_01
    def test_update_thread(self):
        print("\n", str(test_cases('TC_UPDATE_THREAD_01')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().update_thread(
            "Updating thread title"
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_UPDATE_THREAD_02
    def test_edit_thread_with_special_char(self):
        print("\n", str(test_cases('TC_UPDATE_THREAD_01')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().edit_thread_title_with_special_char(
            UPDATED_THREAD_WITH_SPECIAL_CHAR
        ).get_url()
        self.assertIn("/edit", result)

    # TC_LOAD_MY_THREAD_PAGE
    def test_load_my_threads_page(self):
        print("\n", str(test_cases('TC_LOAD_MY_THREAD_PAGE')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().load_my_thread_page().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads?by=me", result)

    # TC_LOAD_TOP_10_THREAD
    def test_load_top_10_thread_page(self):
        print("\n", str(test_cases('TC_LOAD_TOP_10_THREAD')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().load_top_10_thread_page().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads?by=most_replies", result)

    # TC_LOAD_MY_PARTICIPANTS
    def test_load_my_participation(self):
        print("\n", str(test_cases('TC_LOAD_MY_PARTICIPANTS')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().load_my_participation().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads?by=participate", result)

    # TC_LOAD_ALL_THREADS
    def test_load_all_threads(self):
        print("\n", str(test_cases('TC_LOAD_ALL_THREADS')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().load_all_threads().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CHECK_NO_THREAD_AVAILABILITY
    def test_check_no_thread_availability(self):
        print("\n", str(test_cases('TC_CHECK_NO_THREAD_AVAILABILITY')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).check_no_thread_availability().get_url()
        self.assertIn("/1-Agreement/threads", result)

    # TC_VERIFY_MY_THREADS_CREATED_BY_LOGGED_USER
    def test_verify_my_threads_created_by_logged_user(self):
        print("\n", str(test_cases('TC_VERIFY_MY_THREADS_CREATED_BY_LOGGED_USER')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().verify_my_threads_created_by_logged_user().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CAMP_FORM_COUNT_OF_THREADS_ON_ALL_THREADS_PAGE
    def test_camp_form_count_of_threads_on_all_threads_page(self):
        print("\n", str(test_cases('TC_CHECK_NO_THREAD_AVAILABILITY')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().camp_form_count_of_threads_on_all_threads_page().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CHECK_ALL_REPLIES_TO_THREAD
    def test_check_all_replies_to_thread(self):
        print("\n", str(test_cases('TC_CHECK_ALL_REPLIES_TO_THREAD')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().check_all_replies_to_thread().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CREATE_THREAD_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK
    def test_create_thread_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK')))
        self.login_to_canonizer_app()
        self.assertTrue(
            AddForumsPage(self.driver).load_camp_forum_page().create_thread_mandatory_fields_are_marked_with_asteris())

    # TC_EDIT_REPLY_TO_THREAD
    def test_edit_reply_to_thread(self):
        print("\n" + str(test_cases('TC_EDIT_REPLY_TO_THREAD')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().edit_reply_to_thread(
            "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CREATE_THREAD_WITH_VALID_DATA
    def test_create_thread_with_valid_data(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_WITH_VALID_DATA')))
        self.login_to_canonizer_app()
        add_name = ''.join(random.choices(string.ascii_uppercase +
                                          string.digits, k=7))
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_correct_title_name(
            "Testing thread " + add_name,
            DEF_NICK_NAME

        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CREATE_THREAD_WITH_INVALID_DATA
    def test_create_thread_with_invalid_data(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_WITH_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_invalid_data(
            "*******************",
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_CREATE_THREAD_WITH_BLANK_MANDATORY_FIELDS
    def test_create_thread_with_blank_mandatory_fields(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_WITH_BLANK_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_blank_mandatory_fields(
            "",
            ""
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_CREATE_THREAD_WITH_ONLY_MANDATORY_FIELDS
    def test_create_thread_with_only_mandatory_fields(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_WITH_ONLY_MANDATORY_FIELDS')))
        self.login_to_canonizer_app()
        add_name = ''.join(random.choices(string.ascii_uppercase +
                                          string.digits, k=7))
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_only_mandatory_fields(
            "Checking Data with only Mandatory Fields" + add_name,
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_EDIT_THREAD_WITH_DUPLICATE_TITLE
    def test_edit_thread_with_duplicate_title(self):
        print("\n" + str(test_cases('TC_EDIT_THREAD_WITH_DUPLICATE_TITLE')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().edit_thread_title_with_duplicate_title(
            DUPLICATE_THREAD_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/574/edit", result)

    # TC_CREATE_THREAD_WITH_INVALID_DATA_WITH_ENTER_KEY
    def test_create_thread_with_invalid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_WITH_INVALID_DATA_WITH_ENTER_KEY')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_invalid_data_with_enter_key(
            "Checking thread with Invalid data *** with enter key",
            DEF_NICK_NAME
        ).get_url()
        self.assertIn('forum/173-Software-Testing/1-Agreement/threads/create', result)

    # TC_CREATE_THREAD_WITH_TRAILING_SPACES
    def test_create_thread_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_CREATE_THREAD_WITH_TRAILING_SPACES')))
        self.login_to_canonizer_app()
        add_name = ''.join(random.choices(string.ascii_uppercase +
                                          string.digits, k=7))
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_trailing_spaces(
            "          Creating Thread With Trailing spaces" + add_name,
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_VERIFY_LINK_TO_CAMP_NAME
    def test_verify_camp_link_form(self):
        print("\n" + str(test_cases('TC_VERIFY_LINK_TO_CAMP_NAME')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().verify_camp_link_form().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_LOAD_THREAD_POSTS_PAGE
    def test_load_thread_posts_page(self):
        print("\n" + str(test_cases('TC_LOAD_THREAD_POSTS_PAGE')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_thread_posts_page().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/509", result)

    # TC_THREAD_POSTS_MANDATORY_FIELDS_MARKED_WITH_ASTERISK
    def test_thread_posts_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_THREAD_POSTS_MANDATORY_FIELDS_MARKED_WITH_ASTERISK')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().thread_posts_mandatory_fields_are_marked_with_asterisk().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_THREAD_POST_WITH_VALID_DATA
    def test_thread_post_with_valid_data(self):
        print("\n" + str(test_cases('TC_THREAD_POST_WITH_VALID_DATA')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().thread_post_with_valid_data("testing data 123").get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_THREAD_POST_WITH_INVALID_DATA
    def test_thread_post_with_valid_data(self):
        print("\n" + str(test_cases('TC_THREAD_POST_WITH_INVALID_DATA')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().thread_post_with_invalid_data("").get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_THREAD_POST_WITH_INVALID_DATA_WITH_ENTER_KEY
    def test_thread_post_with_invalid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_THREAD_POST_WITH_INVALID_DATA_WITH_ENTER_KEY')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().thread_post_with_invalid_data_with_enter_key("").get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_THREAD_POST_WITH_TRAILING_SPACES
    def test_thread_post_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_THREAD_POST_WITH_TRAILING_SPACES')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().thread_post_with_valid_data("              testing data 123").get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_POST_REPLY_TO_THREAD
    def test_post_reply_to_thread(self):
        print("\n" + str(test_cases('TC_POST_REPLY_TO_THREAD')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().post_reply_to_thread(
            "Test Reply 1"
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/", result)

    # TC_VERIFY_THREAD_PAGINATION
    def test_thread_pagination(self):
        print("\n" + str(test_cases('TC_VERIFY_THREAD_PAGINATION')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().thread_pagination().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_VERIFY_NICK_NAME_LINK
    def test_verify_nickname_on_thread_title(self):
        print("\n" + str(test_cases('TC_VERIFY_NICK_NAME_LINK')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().verify_nickname_on_thread_title().get_url()
        self.assertIn("user/supports/", result)

    # TC_SUPPORT_01
    def test_verifying_one_person_one_vote_01(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).verifying_one_person_one_vote_01().get_url()
        self.assertIn("topic/598-Testing-Topic-14/1-Agreement", result)

    # TC_SUPPORT_02
    def test_verifying_one_person_one_vote_02(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).verifying_one_person_one_vote_02().get_url()
        self.assertIn("topic/598-Testing-Topic-14/3-Child-camp-2", result)

    # TC_VERIFY_LIVE_TOPIC_NAME_WITH_TOPIC_NAME
    def test_verify_live_topic_name_with_topic_name(self):
        print("\n", str(test_cases('TC_VERIFY_LIVE_TOPIC_NAME_WITH_TOPIC_NAME')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_live_topic_name_with_topic_name().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_VERIFY_LIVE_TOPIC_NAME_WITH_CAMP_TREE_TOPIC_NAME
    def test_verify_live_topic_name_with_camp_tree_topic_name(self):
        print("\n", str(test_cases('TC_VERIFY_LIVE_TOPIC_NAME_WITH_CAMP_TREE_TOPIC_NAME')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).test_verify_live_topic_name_with_camp_tree_topic_name().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_VERIFY_LIVE_TOPIC_NAME_WITH_CURRENT_TOPIC_NAME
    def test_verify_live_topic_name_with_current_topic_name(self):
        print("\n", str(test_cases('TC_VERIFY_LIVE_TOPIC_NAME_WITH_CURRENT_TOPIC_NAME')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).test_verify_live_topic_name_with_current_topic_name().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_SORTED_TREE_NAME_WITH_LIVE_CAMP_NAME
    def test_verify_sorted_tree_name_with_live_name(self):
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_sorted_tree_name_with_live().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_BREADCRUM_CAMP_NAME_WITH_LIVE_CAMP_NAME
    def test_verify_breadcrum_camp_name_with_live_name(self):
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_breadcrum_camp_name_with_live_name().get_url()
        self.assertIn("topic/173-Software-Testing", result)

    # TC_SUPPORT_TREE_CAMP_NAME_WITH_LIVE_CAMP_NAME
    def test_verify_support_tree_camp_name_with_live_name(self):
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_support_tree_camp_name_with_live_name().get_url()
        self.assertIn("topic/173-Software-Testing", result)

    # TC_CURRENT_CAMP_NAME_WITH_LIVE_CAMP_NAME
    def test_verify_current_camp_name_with_live_name(self):
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).verify_current_camp_name_with_live_name().get_url()
        self.assertIn("topic/173-Software-Testing", result)

    def test_page_crash(self):
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).check_page_crash().get_url()
        self.assertIn("/forum", result)

    def tearDown(self):
        self.driver.close()


if __name__ == "__main__":
    suite = unittest.TestLoader().loadTestsFromTestCase(TestPages)
    unittest.TextTestRunner(verbosity=2).run(suite)
