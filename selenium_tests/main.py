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

    # 01
    def test_canonizer_home_page_load(self):
        print("\n" + str(test_cases(0)))
        self.assertTrue(CanonizerMainPage(self.driver).check_home_page_loaded())

    # 02
    def test_canonizer_register_button(self):
        print("\n" + str(test_cases(1)))
        registerPage = CanonizerRegisterPage(self.driver).click_register_button()
        self.assertIn("/signup", registerPage.get_url())

    # 03
    def test_canonizer_login_button(self):
        print("\n" + str(test_cases(2)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        self.assertIn("/login", loginPage.get_url())

    # 04
    def test_canonizer_login_with_valid_user(self):
        print("\n" + str(test_cases(3)))
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
    # 06
    def test_registration_with_blank_first_name(self):
        print("\n" + str(test_cases(5)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_first_name(
            REG_LIST_3).get_url()
        print(result)
        self.assertIn("/signup", result)

    # 07
    def test_registration_with_blank_last_name(self):
        print("\n" + str(test_cases(6)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_last_name(
            REG_LIST_4).get_url()
        self.assertIn("/signup", result)

    # 08
    def test_registration_with_blank_email(self):
        print("\n" + str(test_cases(7)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_email(
            REG_LIST_5).get_url()
        self.assertIn("/signup", result)

    # 09
    def test_registration_with_blank_password(self):
        print("\n" + str(test_cases(8)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_password(
            REG_LIST_6).get_url()
        self.assertIn("/signup", result)

    # 10
    def test_registration_with_invalid_password_length(self):
        print("\n" + str(test_cases(9)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_password_length(
            REG_LIST_7).get_url()
        self.assertIn("/signup", result)

    # 11
    def test_registration_with_different_confirmation_password(self):
        print("\n" + str(test_cases(10)))
        result = CanonizerRegisterPage(
            self.driver).click_register_button().registration_with_different_confirmation_password(
            REG_LIST_8).get_url()
        self.assertIn("/signup", result)

    # 12
    def test_what_is_canonizer_page_loaded_properly(self):
        print("\n" + str(test_cases(11)))
        result = CanonizerMainPage(
            self.driver).click_what_is_canonizer_page_link().check_what_is_canonizer_page_loaded().get_url()
        self.assertIn("/browse", result)

    # 13
    def test_user_must_be_signin_to_join_or_support_camp(self):
        print("\n" + str(test_cases(12)))
        self.assertIn("login", CanonizerJoinSupportCampPage(self.driver).load_join_support_camp_page().get_url())

    # TC_DIRECT_JOIN_AND_SUPPORT_01
    def test_load_direct_join_and_support_page(self):
        print("\n", str(test_cases('TC_DIRECT_JOIN_AND_SUPPORT_01')))
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).load_direct_join_and_support_page()
        if result:
            self.assertIn("support/589-Supporting-Camp-Testing-1/1-Agreement", result.get_url())

    # TC_DIRECT_JOIN_AND_SUPPORT_02
    def test_verify_single_support_on_new_topic_creation(self):
        print("\n" + str(test_cases('TC_DIRECT_JOIN_AND_SUPPORT_02')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .verify_single_support_on_new_topic_creation(
            DEFAULT_NICK_NAME,
            "Verify Single Support Camp 4",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        )
        self.assertIn("/topic", result.get_url())

    # TC_DIRECT_JOIN_AND_SUPPORT_03
    def test_verify_warning_directly_supporting_child_camp(self):
        print("\n" + str(test_cases('TC_DIRECT_JOIN_AND_SUPPORT_03')))
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).verify_warning_directly_supporting_child_camp()
        self.assertIn('support/591-Supporting-Camp-Testing-3/2-Supporting-Camp-Child-Camp', result.get_url())

    # TC_DIRECT_JOIN_AND_SUPPORT_04
    def test_verify_support_to_child_camp(self):
        print("\n" + str(test_cases('TC_DIRECT_JOIN_AND_SUPPORT_04')))
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(self.driver).verify_support_to_child_camp()
        self.assertIn("/topic", result.get_url())

    # 14
    def test_request_otp_with_blank_email_or_phone_number(self):
        print("\n" + str(test_cases(13)))
        result = CanonizerLoginPage(
            self.driver).click_login_page_button().request_otp_with_blank_email_or_phone_number().get_url()
        self.assertIn("/login", result)

    # 15
    def test_register_page_should_have_login_option_for_existing_users(self):
        print("\n" + str(test_cases(14)))
        self.assertIn('Log in Here', CanonizerRegisterPage(
            self.driver).click_register_button().registration_should_have_login_option_for_existing_users())

    # 16
    def test_login_page_should_have_register_option_for_new_users(self):
        print("\n" + str(test_cases(15)))
        result = CanonizerLoginPage(
            self.driver).click_login_page_button().login_page_should_have_register_option_for_new_users().get_url()
        print(result)
        self.assertIn("/login", result)

    # 17
    def test_register_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(16)))
        self.assertTrue(CanonizerRegisterPage(
            self.driver).click_register_button().register_page_mandatory_fields_are_marked_with_asterisk())

    # ----- FORGOT PASSWORD Test Cases Start -----

    # 18
    def test_click_forgot_password_page_button(self):
        print("\n" + str(test_cases(17)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        self.assertIn("/forgotpassword",
                      CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().get_url())

    # 19
    def test_forgot_password_with_blank_email(self):
        print("\n" + str(test_cases(18)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and put email as blank
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_blank_email().get_url()
        self.assertIn("forgotpassword", result)

    # 20
    def test_forgot_password_with_invalid_email(self):
        print("\n" + str(test_cases(19)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and check with invalid email
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_invalid_email(
            DEFAULT_INVALID_USER).get_url()
        self.assertIn("forgotpassword", result)

    # 21
    def test_forgot_password_with_valid_email(self):
        print("\n" + str(test_cases(20)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link and check with valid email
        result = CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_with_valid_email(DEFAULT_USER)
        self.assertIn("resetlinksent", result.get_url())

    # 22
    def test_forgot_password_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(21)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        self.assertTrue(CanonizerForgotPasswordPage(
            self.driver).click_forgot_password_page_button().forgot_password_page_mandatory_fields_are_marked_with_asterisk())

    # ----- FORGOT PASSWORD Test Cases End -----

    # ----- Browse Page Test Cases Start -----
    # 23
    def test_click_browse_page_button(self):
        print("\n" + str(test_cases(22)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(self.driver).click_browse_page_button().get_url())

    # 24
    def test_click_only_my_topics_button(self):
        print("\n" + str(test_cases(23)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link and click on "Only My Topics"
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().click_only_my_topics_button().get_url())

    # ----- Browse Page Test Cases End -----

    # ----- Upload File Page Test Cases Start -----
    # 25
    def test_click_upload_file_page_button(self):
        print("\n" + str(test_cases(24)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Upload File link and check upload in URL Name
        self.assertIn("upload", CanonizerUploadFilePage(self.driver).click_upload_file_page_button().get_url())

    # 26
    def test_upload_file_with_blank_file(self):
        print("\n" + str(test_cases(25)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerUploadFilePage(self.driver).click_upload_file_page_button().upload_file_with_blank_file().get_url()
        self.assertIn("/upload", result)

    # ----- Upload File Page Test Cases End -----
    # ----- Create New Topic Test Cases Start -----
    # 27
    def test_click_create_new_topic_page_button(self):
        print("\n" + str(test_cases(26)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check topic/create in URL Name
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button()
        self.assertIn("create/topic", result.get_url())

    # 28
    def test_create_topic_with_blank_nick_name(self):
        print("\n" + str(test_cases(27)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if nick name is blank
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_blank_nick_name(
            DEFAULT_TOPIC_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # 29
    def test_create_topic_with_blank_topic_name(self):
        print("\n" + str(test_cases(28)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if topic name is blank
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_topic_with_blank_topic_name(
            DEFAULT_NICK_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE).get_url()
        self.assertIn("create/topic", result)

    # 30
    def test_create_topic_with_blank_spaces_topic_name(self):
        print("\n" + str(test_cases(29)))
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

    # 31
    def test_create_new_topic_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(30)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link
        self.assertTrue(CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().create_new_topic_page_mandatory_fields_are_marked_with_asterisk())

    # 32
    def test_create_topic_with_duplicate_topic_name(self):
        print("\n" + str(test_cases(31)))
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

    # TC_CREATE_NEW_TOPIC_05
    def test_create_topic_with_valid_data(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_05')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_with_valid_data(
            DEFAULT_NICK_NAME,
            "Valid Topic Name Testing 1",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("topic", result)

    # TC_CREATE_NEW_TOPIC_07
    def test_create_topic_without_entering_mandatory_fields(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_07')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_without_entering_mandatory_fields(
            "",
            "",
            "",
            "",
        ).get_url()
        self.assertIn("create/topic", result)

    # TC_CREATE_NEW_TOPIC_08
    def test_create_topic_with_entering_data_only_in_mandatory_fields(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_08')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_with_entering_data_only_in_mandatory_fields(
            DEFAULT_NICK_NAME,
            "New Topic Testing 1",
            DEFAULT_NAMESPACE,
            "",
        ).get_url()
        self.assertIn("topic", result)

    # TC_CREATE_NEW_TOPIC_11
    def test_validation_of_nick_name_dropdown(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_11')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .validation_of_nick_name_dropdown().get_url()
        print(result)
        self.assertIn("create/topic", result)

    # TC_CREATE_NEW_TOPIC_17
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

    # TC_CREATE_NEW_TOPIC_18
    def test_create_topic_name_with_enter_key_verifying_history_page(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_18')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_name_with_enter_key_verifying_history_page(
            DEFAULT_NICK_NAME,
            "Topic Name Testing 7",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        )
        self.assertTrue("Success! Topic created successfully", result)

    # TC_CREATE_NEW_TOPIC_20
    def test_create_topic_name_with_trailing_space(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_20')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .create_topic_name_with_trailing_space(
            DEFAULT_NICK_NAME,
            "          Topic Topic Name with Trailing spaces",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("topic", result)

    # TC_CREATE_NEW_TOPIC_25
    def test_verifying_nick_name_from_dropdown_while_creating_topic(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_25')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .verifying_nick_name_from_dropdown_while_creating_topic(
            "pooja_zibtek",
            "Verifying Nick Name 4",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_CREATE_NEW_TOPIC_21
    def test_verifying_topic_name_from_namespaces_in_browse(self):
        print("\n", str(test_cases('TC_CREATE_NEW_TOPIC_25')))
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button() \
            .verifying_topic_name_from_namespaces_in_browse(
            "poo_",
            "Verifying Topic Name in Namespace",
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE
        )
        self.assertTrue("Success! Topic created successfully", result)

    # 33
    def test_create_topic_without_user_login(self):
        print("\n" + str(test_cases(32)))
        # Click on the Create New Topic link
        self.assertIn("/login", CanonizerCreateNewTopicPage(self.driver).create_topic_without_user_login().get_url())

    # ----- Create New Topic Test Cases End ----
    # ----- Log out Test Cases Start -----
    # 34
    def test_click_log_out_page_button(self):
        print("\n" + str(test_cases(33)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Username and click on Log Out
        result = CanonizerLogoutPage(
            self.driver).click_username_link_button().click_log_out_page_button().check_home_page_loaded().get_url()
        self.assertIn("/", result)

    # TC_LOG_OUT_02
    def test_click_log_out_page_button_before_browser_back_button(self):
        print("\n", str(test_cases('TC_LOG_OUT_02')))
        self.login_to_canonizer_app()
        result = CanonizerLogoutPage(self.driver).click_log_out_page_button_before_browser_back_button().get_url()
        self.assertIn("/", result)

    # ----- Log out Test Cases End -----

    # ----- Account Settings Test Cases Start -----
    # 35
    def test_click_account_settings_page_button(self):
        print("\n" + str(test_cases(34)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings in URL Name
        self.assertIn("settings", CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().get_url())

    # 36
    def test_click_account_settings_manage_profile_info_page_button(self):
        print("\n" + str(test_cases(35)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        self.assertIn("settings", result.get_url())

    # 37
    def test_click_account_settings_add_manage_nick_names_page_button(self):
        print("\n" + str(test_cases(36)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/nickname in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertIn("settings/nickname", result.get_url())

    # 38
    def test_click_account_settings_my_supports_page_button(self):
        print("\n" + str(test_cases(37)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check support in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        self.assertIn("support", result.get_url())

    # 39
    def test_click_account_settings_social_oauth_verification_page_button(self):
        print("\n" + str(test_cases(38)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/Social Oauth Verification in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_social_oauth_verification_page_button()
        self.assertIn("settings/sociallinks", result.get_url())

    # 40
    def test_click_account_settings_change_password_page_button(self):
        print("\n" + str(test_cases(39)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/changepassword in URL Name
        result = CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        self.assertIn("settings/changepassword", result.get_url())

    # ----- Account Settings  Test Cases End -----

    # ----- Change Password Test Cases Start -----
    # 41
    def test_change_password_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(40)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab & Check for Mandatory fields on Change Password page
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        self.assertTrue(CanonizerAccountSettingsChangePasswordPage(
            self.driver).change_password_page_mandatory_fields_are_marked_with_asterisk())

    # 42
    def test_save_with_blank_current_password(self):
        print("\n" + str(test_cases(41)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_current_password(
            DEFAULT_NEW_PASSWORD,
            DEFAULT_CONFIRM_PASSWORD)
        self.assertIn("The current password field is required.", result)

    # 43
    def test_save_with_blank_new_password(self):
        print("\n" + str(test_cases(42)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_new_password(
            DEFAULT_CURRENT_PASSWORD,
            DEFAULT_CONFIRM_PASSWORD)
        self.assertIn("The new password field is required.", result)

    # 44
    def test_save_with_blank_confirm_password(self):
        print("\n" + str(test_cases(43)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_confirm_password(
            DEFAULT_CURRENT_PASSWORD,
            DEFAULT_NEW_PASSWORD)
        self.assertIn("The confirm password field is required.", result)

    # 45
    def test_save_with_invalid_current_password(self):
        print("\n" + str(test_cases(44)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_invalid_current_password(
            'Test@12345',
            'Test@123456',
            'Test@123456')
        self.assertIn("Incorrect Current Password.", result)

    # 46
    def test_save_with_mismatch_new_confirm_password(self):
        print("\n" + str(test_cases(45)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_mismatch_new_confirm_password(
            DEFAULT_PASS,
            'Test@12345',
            'Test@123456')
        self.assertIn("The confirm password and new password must match.", result)

    # 47
    def test_save_with_same_new_and_current_password(self):
        print("\n" + str(test_cases(46)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_same_new_and_current_password(
            DEFAULT_PASS,
            DEFAULT_PASS,
            'Test@1234567')
        self.assertIn("The new password and current password must be different.", result)

    # ----- Change Password Test Cases End -----

    # ----- Help Page Test Cases Start -----
    # 48
    def test_check_what_is_canonizer_help_page_loaded_without_login(self):
        print("\n" + str(test_cases(47)))
        self.assertIn("topic/132-Help/1-Agreement",
                      CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().get_url())

    # 49
    def test_check_what_is_canonizer_help_page_loaded_with_login(self):
        print("\n" + str(test_cases(48)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help
        self.assertIn("topic/132-Help/1-Agreement",
                      CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().get_url())

    # 50
    def test_check_Steps_to_Create_a_New_Topic_page_loaded_with_login(self):
        print("\n" + str(test_cases(49)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on the Steps_to_Create_a_New_Topic
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Steps_to_Create_a_New_Topic_page_loaded().open(
            "topic/132-Steps-to-Create-a-New-Topic/3")

    # 51
    def test_check_Dealing_With_Disagreements_page_loaded_with_login(self):
        print("\n" + str(test_cases(50)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on the Dealing With Disagreements
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Dealing_With_Disagreements_page_loaded().open(
            "topic/132-Dealing-with-disagreement/4")

    # 52
    def test_check_Wiki_Markup_Information_page_loaded_with_login(self):
        print("\n" + str(test_cases(51)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on Wiki Markup Information
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Wiki_Markup_Information_page_loaded().open(
            "topic/132-Canonizer-wiki-text-formatting/5")

    # 53
    def test_check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded_with_login(self):
        print("\n" + str(test_cases(52)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Adding the Canonizer Feedback Camp Outline to Internet Articles
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded().open(
            "topic/132-Adding-feedback-outline/6")

    # 54
    def test_check_Steps_to_Create_a_New_Topic_page_loaded_without_login(self):
        print("\n" + str(test_cases(53)))
        # Click on the Help and Click on the Steps_to_Create_a_New_Topic
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Steps_to_Create_a_New_Topic_page_loaded().open(
            "topic/132-Steps-to-Create-a-New-Topic/3")

    # 55
    def test_check_Dealing_With_Disagreements_page_loaded_without_login(self):
        print("\n" + str(test_cases(54)))
        # Click  on the Help and Click on the Dealing With Disagreements
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Dealing_With_Disagreements_page_loaded().open(
            "topic/132-Dealing-with-disagreement/4")

    # 56
    def test_check_Wiki_Markup_Information_page_loaded_without_login(self):
        print("\n" + str(test_cases(55)))
        # Click on the Help and Click on Wiki Markup Information
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Wiki_Markup_Information_page_loaded().open(
            "topic/132-Canonizer-wiki-text-formatting/5")

    # 57
    def test_check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded_without_login(self):
        print("\n" + str(test_cases(56)))
        # Click on the Help and Click on Adding the Canonizer Feedback Camp Outline to Internet Articles
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded().open(
            "topic/132-Adding-feedback-outline/6")

    # ----- Help Page Test Cases End -----
    # ----- Add & Manage Nick Names Page Test Cases Start -----
    # 58
    def test_nick_names_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(57)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertTrue(CanonizerAccountSettingsNickNamesPage(
            self.driver).nick_names_page_mandatory_fields_are_marked_with_asterisk())

    # 59
    def test_create_with_blank_nick_name(self):
        print("\n" + str(test_cases('TC_NICK_NAME_01')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertIn("Nick name is required.",
                      CanonizerAccountSettingsNickNamesPage(self.driver).create_with_blank_nick_name())

    # 60
    def test_create_with_duplicate_nick_name(self):
        print("\n" + str(test_cases('TC_NICK_NAME_02')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_duplicate_nick_name(DEF_NICK_NAME)
        self.assertIn("The nick name has already been taken.", result)

    # 61
    def test_create_with_blank_spaces_nick_name(self):
        print("\n" + str(test_cases(60)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_blank_spaces_nick_name('       ')
        self.assertIn("Nick name is required.", result)

    # TC_NICK_NAME_03
    def test_create_nick_name_with_trailing_spaces(self):
        print("\n" + str(test_cases("TC_NICK_NAME_03")))
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button() \
            .click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_nick_name_with_trailing_spaces('       pkk')
        self.assertIn("Nick name created successfully.", result)

    # 62
    def test_click_browse_page_button_without_login(self):
        print("\n" + str(test_cases(61)))
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(self.driver).click_browse_page_button().get_url())

    # ----- Add & Manage Nick Names Page Test Cases End -----
    # ----- My Profile Page Test Cases Start -----
    # 63
    def test_manage_profile_info_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(62)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        self.assertTrue(AccountSettingsManageProfileInfoPage(
            self.driver).manage_profile_info_page_mandatory_fields_are_marked_with_asterisk())

    # 64
    def test_update_profile_with_blank_first_name(self):
        print("\n" + str(test_cases(63)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_blank_first_name(
            DEFAULT_MIDDLE_NAME,
            DEFAULT_LAST_NAME).get_url()
        print(result)
        self.assertIn("/settings", result)

    # 65
    def test_update_profile_with_blank_last_name(self):
        print("\n" + str(test_cases(64)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_blank_last_name(
            DEFAULT_FIRST_NAME,
            DEFAULT_MIDDLE_NAME,
        ).get_url()
        print(result)
        self.assertIn("/settings", result)

    # ----- My Profile Page Test Cases End -----

    # ----- Browse Page Test Cases Start -----
    # 66
    def test_select_by_value_general(self):
        print("\n" + str(test_cases(65)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_general().get_url())

    # 67
    def test_select_by_value_corporations(self):
        print("\n" + str(test_cases(66)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=2", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_corporations().get_url())

    # 68
    def test_select_by_value_crypto_currency(self):
        print("\n" + str(test_cases(67)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=3", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_crypto_currency().get_url())

    # 69
    def test_select_by_value_family(self):
        print("\n" + str(test_cases(68)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=4", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_family().get_url())

    # 70
    def test_select_by_value_family_jesperson_oscar_f(self):
        print("\n" + str(test_cases(69)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=5", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_family_jesperson_oscar_f().get_url())

    # 71
    def test_select_by_value_occupy_wall_street(self):
        print("\n" + str(test_cases(70)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=6", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_occupy_wall_street().get_url())

    # 72
    def test_select_by_value_organizations(self):
        print("\n" + str(test_cases(71)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=7", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations().get_url())

    # 73
    def test_select_by_value_organizations_canonizer(self):
        print("\n" + str(test_cases(72)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=8", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_canonizer().get_url())

    # 74
    def test_select_by_value_organizations_canonizer_help(self):
        print("\n" + str(test_cases(73)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=9", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_canonizer_help().get_url())

    # 75
    def test_select_by_value_organizations_mta(self):
        print("\n" + str(test_cases(74)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=10", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_mta().get_url())

    # 76
    def test_select_by_value_organizations_tv07(self):
        print("\n" + str(test_cases(75)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=11", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_tv07().get_url())

    # 77
    def test_select_by_value_organizations_wta(self):
        print("\n" + str(test_cases(76)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=12", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_wta().get_url())

    # 78
    def test_select_by_value_personal_attributes(self):
        print("\n" + str(test_cases(77)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=13", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_personal_attributes().get_url())

    # 79
    def test_select_by_value_personal_reputations(self):
        print("\n" + str(test_cases(78)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=14", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_personal_reputations().get_url())

    # 80
    def test_select_by_value_professional_services(self):
        print("\n" + str(test_cases(79)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=15", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_professional_services().get_url())

    # 81
    def test_select_by_value_sandbox(self):
        print("\n" + str(test_cases(80)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=16", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_sandbox().get_url())

    # 82
    def test_select_by_value_terminology(self):
        print("\n" + str(test_cases(81)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=17", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_terminology().get_url())

    # 83
    def test_select_by_value_www(self):
        print("\n" + str(test_cases(82)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=18", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_www().get_url())

    # 84
    def test_select_by_value_all(self):
        print("\n" + str(test_cases(83)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_all().get_url())

    # 85
    def test_select_by_value_all_default(self):
        print("\n" + str(test_cases(84)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_all_default().get_url())

    # 86
    def test_select_by_value_general_only_my_topics(self):
        print("\n" + str(test_cases(85)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=1&my=1",
                      CanonizerBrowsePage(self.driver).select_by_value_general_only_my_topics().get_url())

    # 87
    def test_select_by_value_corporations_only_my_topics(self):
        print("\n" + str(test_cases(86)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=2&my=2",
                      CanonizerBrowsePage(self.driver).select_by_value_corporations_only_my_topics().get_url())

    # 88
    def test_select_by_value_crypto_currency_only_my_topics(self):
        print("\n" + str(test_cases(87)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=3&my=3",
                      CanonizerBrowsePage(self.driver).select_by_value_crypto_currency_only_my_topics().get_url())

    # 89
    def test_select_by_value_family_only_my_topics(self):
        print("\n" + str(test_cases(88)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=4&my=4",
                      CanonizerBrowsePage(self.driver).select_by_value_family_only_my_topics().get_url())

    # 90
    def test_select_by_value_family_jesperson_oscar_f_only_my_topics(self):
        print("\n" + str(test_cases(89)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=5&my=5", CanonizerBrowsePage(
            self.driver).select_by_value_family_jesperson_oscar_f_only_my_topics().get_url())

    # 91
    def test_select_by_value_occupy_wall_street_only_my_topics(self):
        print("\n" + str(test_cases(90)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=6&my=6",
                      CanonizerBrowsePage(self.driver).select_by_value_occupy_wall_street_only_my_topics().get_url())

    # 92
    def test_select_by_value_organizations_only_my_topics(self):
        print("\n" + str(test_cases(91)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=7&my=7",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_only_my_topics().get_url())

    # 93
    def test_select_by_value_organizations_canonizer_only_my_topics(self):
        print("\n" + str(test_cases(92)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=8&my=8", CanonizerBrowsePage(
            self.driver).select_by_value_organizations_canonizer_only_my_topics().get_url())

    # 94
    def test_select_by_value_organizations_canonizer_help_only_my_topics(self):
        print("\n" + str(test_cases(93)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=9&my=9", CanonizerBrowsePage(
            self.driver).select_by_value_organizations_canonizer_help_only_my_topics().get_url())

    # 95
    def test_select_by_value_organizations_mta_only_my_topics(self):
        print("\n" + str(test_cases(94)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=10&my=10",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_mta_only_my_topics().get_url())

    # 96
    def test_select_by_value_organizations_tv07_only_my_topics(self):
        print("\n" + str(test_cases(95)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=11&my=11",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_tv07_only_my_topics().get_url())

    # 97
    def test_select_by_value_organizations_wta_only_my_topics(self):
        print("\n" + str(test_cases(96)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=12&my=12",
                      CanonizerBrowsePage(self.driver).select_by_value_organizations_wta_only_my_topics().get_url())

    # 98
    def test_select_by_value_personal_attributes_only_my_topics(self):
        print("\n" + str(test_cases(97)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=13&my=13",
                      CanonizerBrowsePage(self.driver).select_by_value_personal_attributes_only_my_topics().get_url())

    # 99
    def test_select_by_value_personal_reputations_only_my_topics(self):
        print("\n" + str(test_cases(98)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=14&my=14",
                      CanonizerBrowsePage(self.driver).select_by_value_personal_reputations_only_my_topics().get_url())

    # 100
    def test_select_by_value_professional_services_only_my_topics(self):
        print("\n" + str(test_cases(99)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=15&my=15",
                      CanonizerBrowsePage(self.driver).select_by_value_professional_services_only_my_topics().get_url())

    # 101
    def test_select_by_value_sandbox_only_my_topics(self):
        print("\n" + str(test_cases(100)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=16&my=16",
                      CanonizerBrowsePage(self.driver).select_by_value_sandbox_only_my_topics().get_url())

    # 102
    def test_select_by_value_terminology_only_my_topics(self):
        print("\n" + str(test_cases(101)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=17&my=17",
                      CanonizerBrowsePage(self.driver).select_by_value_terminology_only_my_topics().get_url())

    # 103
    def test_select_by_value_www_only_my_topics(self):
        print("\n" + str(test_cases(102)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=18&my=18",
                      CanonizerBrowsePage(self.driver).select_by_value_www_only_my_topics().get_url())

    # 104
    def test_select_by_value_all_only_my_topics(self):
        print("\n" + str(test_cases(103)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=&my=",
                      CanonizerBrowsePage(self.driver).select_by_value_all_only_my_topics().get_url())

    # ----- Browse Page Test Cases End -----

    # ----- White Paper Test Cases Start -----
    # 105
    def test_check_white_paper_should_open_with_login(self):
        print("\n" + str(test_cases(104)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the White Paper link
        CanonizerWhitePaper(self.driver).check_white_paper_should_open().open("files/2012_amplifying_final.pdf")

    # 106
    def test_check_white_paper_should_open_without_login(self):
        print("\n" + str(test_cases(105)))
        # Click on the White Paper link
        CanonizerWhitePaper(self.driver).check_white_paper_should_open().open("files/2012_amplifying_final.pdf")

    # ----- White Paper Test Cases End -----

    # ----- Blog Test Cases Start -----
    # 107
    def test_check_blog_page_should_open_with_login(self):
        print("\n" + str(test_cases(106)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Blog link
        self.assertIn("/blog/", CanonizerBlog(self.driver).check_blog_page_should_open().get_url())

    # 108
    def test_check_blog_page_should_open_without_login(self):
        print("\n" + str(test_cases(107)))
        # Click on the Blog link
        self.assertIn("/blog/", CanonizerBlog(self.driver).check_blog_page_should_open().get_url())

    # ----- Blog Test Cases End -----

    # ----- Algorithm Information Test Cases Start -----
    # 109
    def test_check_algorithm_information_page_should_open(self):
        print("\n" + str(test_cases(108)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Algorithm Information link
        self.assertIn("topic/53-Canonizer-Algorithms/1", CanonizerAlgorithmInformation(
            self.driver).check_algorithm_information_page_should_open().get_url())

    # ----- Algorithm Information Test Cases End -----

    # ----- As Of Filters Test Cases Start -----
    # 110
    def test_check_include_review_filter_applied(self):
        print("\n" + str(test_cases(109)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on include review filter
        self.assertIn(DEFAULT_BASE_URL,
                      CanonizerAsOfFilters(self.driver).check_include_review_filter_applied().get_url())

    # 111
    def test_check_default_filter_applied(self):
        print("\n" + str(test_cases(110)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on default filter
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_default_filter_applied().get_url())

    # 112
    def test_check_as_of_date_filter_applied(self):
        print("\n" + str(test_cases(111)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on as of (yy/mm/dd) and select date
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_as_of_date_filter_applied().get_url())

    # ----- As Of Filters Test Cases End -----

    # ----- Update Topic Test Cases Start -----
    # 113
    def test_load_topic_update_page(self):
        print("\n" + str(test_cases(112)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Check Topic Update page load
        self.assertIn("manage/topic", CanonizerTopicUpdatePage(self.driver).load_topic_update_page().get_url())

    # 114
    def test_load_view_this_version_page(self):
        print("\n" + str(test_cases(113)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_view_this_version_page().get_url())

    # 115
    def test_load_topic_object_page(self):
        print("\n" + str(test_cases(114)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_object_page()
        if result == 1:
            self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_topic_object_page().get_url())

    # 116
    def test_topic_update_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(115)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        self.assertTrue(CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().topic_update_page_mandatory_fields_are_marked_with_asterisk())

    # 117
    def test_topic_objection_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(116)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_object_page()
        if result == 1:
            self.assertTrue(CanonizerTopicUpdatePage(
                self.driver).load_topic_object_page().topic_objection_page_mandatory_fields_are_marked_with_asterisk())

    # 118
    def test_topic_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(117)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().topic_update_page_should_have_add_new_nick_name_link_for_new_users()
        if result == 1:
            self.assertIn("Add New Nick Name", result)

    # 119
    def test_submit_update_with_blank_nick_name(self):
        print("\n" + str(test_cases(118)))
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
    # 120
    def test_load_create_camp_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(119)))
        self.assertIn("camp/create/173-Software-Testing/1-Agreement",
                      CanonizerCampPage(self.driver).load_create_camp_page().get_url())

    # 121
    def test_create_new_camp_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(120)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        self.assertTrue(CanonizerCampPage(
            self.driver).load_create_camp_page().create_new_camp_page_mandatory_fields_are_marked_with_asterisk())

    # 122
    def test_create_camp_with_blank_nick_name(self):
        print("\n" + str(test_cases(121)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if nick name is blank
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_blank_nick_name(
            CREATE_CAMP_LIST_1)
        self.assertIn("", result.get_url())

    # 123
    def test_create_camp_with_blank_camp_name(self):
        print("\n" + str(test_cases(122)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_blank_camp_name(
            CREATE_CAMP_LIST_2).get_url()
        print(result)
        self.assertIn("", result)

    # 124
    def test_load_camp_update_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(123)))
        self.assertIn("/manage/camp", CanonizerEditCampPage(self.driver).load_camp_update_page().get_url())

    # TC_EDIT_CAMP_STATEMENT_01
    def test_load_camp_manage_edit_page(self):
        print("\n" + str(test_cases("TC_EDIT_CAMP_STATEMENT_01")))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_manage_edit_page().get_url()
        self.assertIn("camp/history/173-Software-Testing", result)

    # TC_EDIT_CAMP_STATEMENT_02
    def test_verify_agreement_page(self):
        print("\n" + str(test_cases("TC_EDIT_CAMP_STATEMENT_02")))
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

    # 125
    def test_camp_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(124)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp  link
        self.assertTrue(CanonizerEditCampPage(
            self.driver).load_camp_update_page().camp_edit_page_mandatory_fields_are_marked_with_asterisk())

    # 126
    def test_submit_camp_update_with_blank_nick_name(self):
        print("\n" + str(test_cases(125)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_blank_nick_name(
            CAMP_LIST_1).get_url()
        print(result)
        self.assertIn("", result)

    # TC_EDIT_CAMP_STATEMENT_03
    def test_submit_camp_update_with_valid_data(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_03')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_valid_data(
            CAMP_LIST_2
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_EDIT_CAMP_STATEMENT_04
    def test_submit_camp_update_with_invalid_data(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_04')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_invalid_data(
            CAMP_LIST_3
        ).get_url()
        self.assertIn("manage/camp", result)

    # TC_EDIT_CAMP_STATEMENT_05
    def test_submit_camp_update_with_valid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_05')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().submit_camp_update_with_valid_data_with_enter_key(
            CAMP_LIST_2
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_EDIT_CAMP_STATEMENT_06
    def test_submit_camp_update_with_invalid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_06')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().submit_camp_update_with_invalid_data_with_enter_key(
            CAMP_LIST_3
        ).get_url()
        self.assertIn("manage/camp", result)

    # TC_EDIT_CAMP_STATEMENT_07
    def test_submit_camp_update_with_mandatory_fields_only(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_07')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().test_submit_camp_update_with_mandatory_fields_only(
            CAMP_LIST_4
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_EDIT_CAMP_STATEMENT_08
    def test_submit_camp_update_with_tailing_spaces(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_08')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_tailing_spaces(
            CAMP_LIST_5
        ).get_url()
        self.assertIn("camp/history", result)

    # TC_EDIT_CAMP_STATEMENT_09
    def test_submit_camp_update_with_invalid_url(self):
        print("\n" + str(test_cases('TC_EDIT_CAMP_STATEMENT_09')))
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_invalid_url(
            CAMP_LIST_6
        ).get_url()
        print(result)
        self.assertIn("", result)

    # ----- Create New Camp Test Cases End -----

    # ----- Create New Camp Statement and Edit Camp Statement Test Cases Start -----
    # 127
    def test_load_edit_camp_statement_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(126)))
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            self.assertIn("manage/statement", result.get_url())

    #
    def test_verify_previous_data_on_edit_camp_statement_page(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).verify_previous_data_on_edit_camp_statement_page()

    # 128
    def test_camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(127)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp Statement  link
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            self.assertTrue(CanonizerCampStatementPage(
                self.driver).load_edit_camp_statement_page().camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk())

    # 129
    def test_submit_statement_update_with_blank_nick_name(self):
        print("\n" + str(test_cases(128)))
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
    # 130
    def test_load_add_news_feed_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(129)))
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page()
        self.assertIn("addnews/", result.get_url())

    # 131
    def test_add_news_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(130)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Add News link
        self.assertTrue(CanonizerAddNewsFeedsPage(
            self.driver).load_add_news_feed_page().add_news_page_mandatory_fields_are_marked_with_asterisk())

    # 132
    def test_create_news_with_blank_display_text(self):
        print("\n" + str(test_cases(131)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check if display text is blank
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_blank_display_text(
            "Test",
            "").get_url()
        self.assertIn("addnews/173-Software-Testing/1-Agreement", result)

    # 133
    def test_create_news_with_blank_link(self):
        print("\n" + str(test_cases(132)))
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

    # 134
    def test_click_add_news_cancel_button(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(133)))
        self.assertIn("topic/", CanonizerAddNewsFeedsPage(self.driver).click_add_news_cancel_button().get_url())

    # 135
    def test_load_edit_news_feed_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(134)))
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            self.assertIn("editnews/", result.get_url())

    # 136
    def test_click_edit_news_cancel_button(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(135)))
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            self.assertIn("topic/", CanonizerEditNewsFeedsPage(self.driver).click_edit_news_cancel_button().get_url())

    # 137
    def test_update_news_with_blank_display_text(self):
        print("\n" + str(test_cases(136)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check if display text is blank
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().update_news_with_blank_display_text(
                "Test",
                "").get_url()
        self.assertIn("/editnews/173-Software-Testing/1-Agreement", result)

    # 138
    def test_update_news_with_blank_link(self):
        print("\n" + str(test_cases(137)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check if link is blank
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().update_news_with_blank_link(
                "Test",
                "").get_url()
        self.assertIn("editnews/173-Software-Testing/1-Agreement", result)

    # 139
    def test_update_news_with_invalid_link_format(self):
        print("\n" + str(test_cases(138)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check entered link is invalid
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().update_news_with_invalid_link_format(
                "Test",
                "Test",
                "").get_url()
        self.assertIn("editnews/173-Software-Testing/1-Agreement", result)

    # 140
    def test_create_news_with_invalid_link_format(self):
        print("\n" + str(test_cases(139)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check entered link is invalid
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_invalid_link_format(
            "Test",
            "Test",
            "").get_url()
        self.assertIn("addnews/", result)

    # 141
    def test_create_news_with_valid_data(self):
        print("\n" + str(test_cases(140)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Add News and check update news with valid data
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_valid_data(
            "Testing news 1",
            "https://www.google.com/",
            "").get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

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

    # 142
    def test_update_news_with_valid_data(self):
        print("\n" + str(test_cases(141)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Edit News and check update news with valid data
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            result = CanonizerEditNewsFeedsPage(self.driver).update_news_with_valid_data(
                "Updpate News Testt 1",
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
        print(result)
        self.assertIn("editnews/173-Software-Testing/1-Agreement", result.get_url())

    # ----- Delete News Test Cases Start  -----
    #  TC_DELETE_NEWS_01
    def test_delete_news_button_visibility(self):
        print("\n", str(test_cases('TC_DELETE_NEWS_01')))
        self.login_to_canonizer_app()
        result = CanonizerDeleteNewsFeedsPage(self.driver).click_delete_news_feed().delete_news_button_visibility().get_url()
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
    # 143
    def test_upload_file_with_valid_format(self):
        print("\n" + str(test_cases(142)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with invalid file format
        result = CanonizerUploadFilePage(self.driver).click_upload_file_page_button().upload_file_with_valid_format(
            DEFAULT_ORIGINAL_FILE_NAME,
            "Testfilethree"
        )
        self.assertIn("upload", result.get_url())

    # 144
    def test_upload_file_with_size_file_more_than_5mb(self):
        print("\n" + str(test_cases(143)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with file size more than 5MB
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().upload_file_with_size_file_more_than_5mb(
            FILE_WITH_MORE_THAN_5MB,
            'testfiletwooooppp'
        ).get_url()
        self.assertIn("/upload", result)

    # 145
    def test_upload_file_with_same_file_name(self):
        print("\n" + str(test_cases(144)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with existing file name
        result = CanonizerUploadFilePage(
            self.driver).click_upload_file_page_button().upload_file_with_same_file_name(
            FILE_WITH_SAME_NAME,
            "Testfilethree"
        ).get_url()
        self.assertIn("/upload", result)

    # 146
    def test_upload_file_with_size_zero_bytes(self):
        print("\n" + str(test_cases(145)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Upload File and check upload file with size zero bytes
        result = CanonizerUploadFilePage(self.driver).click_upload_file_page_button().upload_file_with_size_zero_bytes(
            FILE_WITH_ZERO_BYTES,
            "testpngfile"
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
    # 147
    def test_click_search_button(self):
        print("\n" + str(test_cases(146)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button()
        self.assertIn("", result.get_url())

    # 148
    def test_click_search_button_web(self):
        print("\n" + str(test_cases(147)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button_web()
        self.assertIn("", result.get_url())

    # 149
    def test_click_search_button_keyword_web(self):
        print("\n" + str(test_cases(148)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button_keyword_web('Testing')
        self.assertIn("", result.get_url())

    # 150
    def test_click_search_button_keyword_canonizer_com(self):
        print("\n" + str(test_cases(149)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerSearchPage(self.driver).click_search_button_keyword_canonizer_com('Testing')
        self.assertIn("", result.get_url())

    # ----- Search Test Cases End -----

    # 151
    def test_verify_phone_number_with_blank_phone_number(self):
        print("\n" + str(test_cases(150)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).verify_phone_number_with_blank_phone_number()
        self.assertIn("Phone number is required.", result)

    # 152
    def test_select_by_value_crypto_currency_ethereum(self):
        print("\n" + str(test_cases(151)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).click_browse_page_button().select_dropdown_value().select_by_value_crypto_currency_ethereum().get_url())

    # 153
    def test_select_by_value_crypto_currency_ethereum_only_my_topics(self):
        print("\n" + str(test_cases(152)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(
            self.driver).select_by_value_crypto_currency_ethereum_only_my_topics().get_url())

    # 154
    def test_check_Canonizer_is_the_final_word_on_everything_page_loaded(self):
        print("\n" + str(test_cases(153)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Canonizer is the final word on everything
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Canonizer_is_the_final_word_on_everything_page_loaded().open(
            "https://vimeo.com/307590745")

    # 155
    def test_check_Canonizer_is_the_final_word_on_everything_page_loaded_without_login(self):
        print("\n" + str(test_cases(154)))
        # Click on the Help and Click on Canonizer is the final word on everything
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_Canonizer_is_the_final_word_on_everything_page_loaded().open(
            "https://vimeo.com/307590745")

    # 156
    def test_check_consensus_out_of_controversy_use_case_page_loaded(self):
        print("\n" + str(test_cases(155)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Consensus out of controversy use case
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_consensus_out_of_controversy_use_case_page_loaded().open(
            "topic/132-Consensus-out-of-controversy/2")

    # 157
    def test_check_consensus_out_of_controversy_use_case_page_loaded_without_login(self):
        print("\n" + str(test_cases(156)))
        # Click on the Help and Click on Consensus out of controversy use case
        CanonizerHelpPage(
            self.driver).check_what_is_canonizer_help_page_loaded().check_consensus_out_of_controversy_use_case_page_loaded().open(
            "topic/132-Consensus-out-of-controversy/2")

    # 158
    def test_load_create_new_camp_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(157)))
        self.assertIn("camp/create/173-Software-Testing/1-Agreement",
                      CanonizerCampPage(self.driver).load_create_new_camp_page().get_url())

    # 159
    def test_save_with_invalid_new_password(self):
        print("\n" + str(test_cases(158)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_invalid_new_password(
            'Test@12345',
            'TEST@123456',
            'TEST@123456')
        self.assertIn(
            "Password must be at least 8 characters, including at least one digit, one lower case letter and one special character(@,# !,$..)",
            result)

    # 160
    def test_login_with_blank_email(self):
        print("\n" + str(test_cases(159)))
        result = CanonizerLoginPage(self.driver).click_login_page_button().login_with_blank_email(
            DEFAULT_PASS).get_url()
        self.assertIn("/login", result)

    # 161
    def test_login_with_blank_password(self):
        print("\n" + str(test_cases(160)))
        result = CanonizerLoginPage(self.driver).click_login_page_button().login_with_blank_password(
            DEFAULT_USER).get_url()
        self.assertIn("/login", result)

    # 162
    def test_login_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(161)))
        self.assertTrue(CanonizerLoginPage(
            self.driver).click_login_page_button().login_page_mandatory_fields_are_marked_with_asterisk())

    # 163
    def test_login_should_have_forgot_password_link(self):
        print("\n" + str(test_cases(162)))
        self.assertIn('login', CanonizerLoginPage(
            self.driver).click_login_page_button().login_should_have_forgot_password_link().get_url())

    # 164
    def test_registration_with_duplicate_email(self):
        print("\n" + str(test_cases(163)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_duplicate_email(
            REG_LIST_2).get_url()
        self.assertIn("/signup", result)

    # 165
    def test_check_topic_page_from_my_supports_loaded(self):
        print("\n" + str(test_cases(164)))
        # Click on Account Settings->My Supports->Topic name
        self.login_to_canonizer_app()
        # Click on the Account Settings->My Supports->Topic name link
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        result = AccountSettingsMySupportsPage(self.driver).check_topic_page_from_my_supports_loaded()
        self.assertIn("topic/631-Verify-Single-Support-Camp-4/1-Agreement", result.get_url())

    # 166
    def test_check_camp_page_from_my_supports_loaded(self):
        print("\n" + str(test_cases(165)))
        # Click on Account Settings->My Supports->Topic name
        self.login_to_canonizer_app()
        # Click on the Account Settings->My Supports->Camp name link
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button()
        result = AccountSettingsMySupportsPage(self.driver).check_camp_page_from_my_supports_loaded()
        self.assertIn("topic/631-Verify-Single-Support-Camp-4/1-Agreement", result.get_url())

    # 167
    def test_submit_update_with_blank_topic_name(self):
        print("\n" + str(test_cases(166)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Topic update and check if topic name is blank
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_blank_topic_name(
            "Test",
            "",
            "")
        self.assertIn("manage/topic/", result.get_url())

    # 168
    def test_submit_topic_update_with_duplicate_topic_name(self):
        print("\n" + str(test_cases(167)))
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

    # 169
    def test_create_camp_with_duplicate_camp_name(self):
        print("\n" + str(test_cases(168)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if camp name is duplicate
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_duplicate_camp_name(
            CREATE_CAMP_LIST_3)
        self.assertIn("camp/create", result.get_url())

    # 170
    def test_submit_camp_update_with_duplicate_camp_name(self):
        print("\n" + str(test_cases(169)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit This Camp link and check if camp name is duplicate
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_duplicate_camp_name(
            CAMP_LIST_7).get_url()
        self.assertIn("manage/camp", result)

    # 171
    def test_edit_news_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(170)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Edit News link
        result = CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page()
        if result:
            self.assertTrue(
                CanonizerEditNewsFeedsPage(self.driver).edit_news_page_mandatory_fields_are_marked_with_asterisk())

    # ----- Add Camp Statement Test Cases Start -----

    # 172
    def test_load_add_camp_statement_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(171)))
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        self.assertIn("create/statement/", result.get_url())

    # 173
    def test_add_camp_statement_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(172)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            self.assertTrue(
                AddCampStatementPage(self.driver).add_camp_statement_page_mandatory_fields_are_marked_with_asterisk())

    # TC_ADD_CAMP_STATEMENT_01
    def test_add_camp_statement_page_without_mandatory_fields(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_01')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).add_camp_statement_page_without_mandatory_fields(" ").get_url()
        self.assertIn("create/statement/391-new/1-Agreement", result)

    # TC_ADD_CAMP_STATEMENT_02
    def test_add_camp_statement_page_mandatory_fields_only(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_02')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(
            self.driver).load_add_camp_statement_page().add_camp_statement_page_mandatory_fields_only(
            "Test statement").get_url()
        self.assertIn("statement/history", result)

    # TC_ADD_CAMP_STATEMENT_03
    def test_add_camp_statement_page_valid_data(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_03')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page().add_camp_statement_page_valid_data(
            "Test statement", "Testing for note").get_url()
        print(result, "result")
        self.assertIn("statement/history", result)

    # TC_ADD_CAMP_STATEMENT_04
    def test_add_camp_statement_page_valid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_04')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            self.assertTrue("Success! Statement submitted successfully.",
                            AddCampStatementPage(self.driver).add_camp_statement_page_valid_data_with_enter_key(
                                "Test statement with Enter Key", "Testing for note 11111"))

    # TC_ADD_CAMP_STATEMENT_05
    def test_add_camp_statement_page_data_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_ADD_CAMP_STATEMENT_05')))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            self.assertTrue("Success! Statement submitted successfully.",
                            AddCampStatementPage(self.driver).add_camp_statement_page_data_with_trailing_spaces(
                                "             Test statement Trailing spaces",
                                "        Testing for with trailing spaces"))

    # 174
    def test_submit_statement_with_blank_nick_name(self):
        print("\n" + str(test_cases(173)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(self.driver).submit_statement_with_blank_nick_name("", "")
            if result == 1:
                self.assertIn("The nick name field is required.", result)

    # 175
    def test_submit_statement_with_blank_statement(self):
        print("\n" + str(test_cases(174)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(self.driver).submit_statement_with_blank_statement(
                "Test",
                "")
            self.assertIn("The statement field is required.", result)

    # 176
    def test_add_camp_statement_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(175)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = AddCampStatementPage(self.driver).load_add_camp_statement_page()
        if result:
            result = AddCampStatementPage(
                self.driver).add_camp_statement_page_should_have_add_new_nick_name_link_for_new_users()
            if result == 1:
                self.assertIn("Add New Nick Name", result)

    # 177
    def test_registration_with_blank_spaces_first_name(self):
        print("\n" + str(test_cases(176)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_spaces_first_name(
            REG_LIST_1).get_url()
        self.assertIn("/signup", result)

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

    # 179
    def test_submit_statement_update_with_blank_statement(self):
        print("\n" + str(test_cases(178)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page()
        if result:
            result = CanonizerCampStatementPage(self.driver).submit_statement_update_with_blank_statement(
                "",
                "")
            self.assertIn("The statement field is required.", result)

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

    # 186
    def test_load_privacy_policy_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(185)))
        self.assertIn("privacypolicy", CanonizerTermsAndPrivacyPolicy(self.driver).load_privacy_policy_page().get_url())

    # 187
    def test_load_terms_services_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(186)))
        self.assertIn("termservice", CanonizerTermsAndPrivacyPolicy(self.driver).load_terms_services_page().get_url())

    # 188
    def test_footer_should_have_privacy_policy(self):
        print("\n" + str(test_cases(187)))
        self.login_to_canonizer_app()
        result = CanonizerHomePage(self.driver).footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn('/', result)

    # 189
    def test_footer_should_have_terms_services(self):
        print("\n" + str(test_cases(188)))
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

    # 190
    def test_footer_should_have_copyright_year(self):
        print("\n" + str(test_cases(189)))
        self.login_to_canonizer_app()
        result = CanonizerHomePage(self.driver).footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn("", result)

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
            CREATE_CAMP_LIST_4)
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

    # 203
    def test_check_garbage_url_without_login(self):
        print("\n" + str(test_cases(202)))
        # Hit URL https://staging.canonizer.com/robots.txt
        url = self.driver.current_url
        newurl = url + "garbage"
        self.driver.get(newurl)
        self.assertIn('Sorry, the page you are looking for could not be found or have been removed.',
                      CanonizerHomePage(self.driver).check_garbage_url())

    # 204
    def test_check_blog_page_footer_should_have_copyright_year_with_login(self):
        print("\n" + str(test_cases(203)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Blog link
        result = CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn("blog", result)

    # 205
    def test_check_blog_page_footer_should_have_copyright_year_without_login(self):
        print("\n" + str(test_cases(204)))
        # Click on the Blog link
        currentyear = datetime.now().year
        result = CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services().get_url()
        self.assertIn("blog", result)

    # 206
    def test_blog_footer_should_have_privacy_policy(self):
        print("\n" + str(test_cases(205)))
        self.login_to_canonizer_app()
        self.assertIn('Privacy Policy',
                      CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services())

    # 207
    def test_blog_footer_should_have_terms_services(self):
        print("\n" + str(test_cases(206)))
        self.login_to_canonizer_app()
        self.assertIn('Terms & Services',
                      CanonizerBlog(self.driver).blog_footer_should_have_privacy_policy_and_terms_services())

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

    # 227
    def test_check_login_page_open_click_login_here_link(self):
        print("\n" + str(test_cases(226)))
        registerpage = CanonizerRegisterPage(
            self.driver).click_register_button().check_login_page_open_click_login_here_link()
        self.assertIn("/login", registerpage.get_url())

    # 228
    def test_check_scroll_to_top_click(self):
        print("\n" + str(test_cases(227)))
        self.login_to_canonizer_app()
        self.assertTrue(CanonizerMainPage(self.driver).check_scroll_to_top_click())

    # 229
    def test_login_with_blank_otp(self):
        print("\n" + str(test_cases(228)))
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

    # 243
    def test_submit_update_with_invalid_topic_name(self):
        print("\n" + str(test_cases(242)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_invalid_topic_name(
            "Test",
            INVALID_TOPIC_NAME,
            "",
            "").get_url()
        self.assertIn("manage/topic", result)

    # 244
    def test_nick_name_page_should_open_create_topic_add_new_nick_name(self):
        print("\n" + str(test_cases(243)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCreateNewTopicPage(
            self.driver).click_create_new_topic_page_button().nick_name_page_should_open_create_topic_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # 245
    def test_nick_name_page_should_open_create_camp_add_new_nick_name(self):
        print("\n" + str(test_cases(244)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerCampPage(
            self.driver).load_create_new_camp_page().nick_name_page_should_open_create_camp_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # 246
    def test_nick_name_page_should_open_update_topic_add_new_nick_name(self):
        print("\n" + str(test_cases(245)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerTopicUpdatePage(
            self.driver).load_topic_update_page().nick_name_page_should_open_update_topic_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # 247
    def test_nick_name_page_should_open_update_camp_add_new_nick_name(self):
        print("\n" + str(test_cases(246)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerEditCampPage(
            self.driver).load_camp_update_page().nick_name_page_should_open_update_camp_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # 248
    def test_load_join_support_camp_page_with_login(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(247)))
        self.assertIn("support/", CanonizerJoinSupportCampPage(self.driver).load_join_support_camp_page().get_url())

    # 249
    def test_join_support_camp_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(248)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(
            self.driver).load_join_support_camp_page().join_support_camp_page_should_have_add_new_nick_name_link_for_new_users()
        if result == 1:
            self.assertIn("Add New Nick Name", result)

    # 250
    def test_nick_name_page_should_open_join_support_camp_add_new_nick_name(self):
        print("\n" + str(test_cases(249)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        result = CanonizerJoinSupportCampPage(
            self.driver).load_join_support_camp_page().nick_name_page_should_open_join_support_camp_add_new_nick_name()
        if result:
            self.assertIn("settings/nickname", result.get_url())

    # 251
    def test_registration_with_invalid_captcha(self):
        print("\n" + str(test_cases(250)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_captcha(
            REG_LIST_13).get_url()
        self.assertIn("/signup", result)

    # 252
    def test_verify_phone_number_with_valid_length_phone_number(self):
        print("\n" + str(test_cases(251)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        CanonizerAccountSettingsPage(
            self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(
            self.driver).verify_phone_number_with_valid_length_phone_number().get_url()
        self.assertIn("/settings", result)

    # 253
    def test_check_jobs_page_should_open_with_login(self):
        print("\n" + str(test_cases(252)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Jobs link
        result = CanonizerJobs(self.driver).check_jobs_page_should_open().get_url()
        self.assertIn("topic/6-Canonizer-Jobs/1-Agreement", result)

    # 254
    def test_check_services_page_should_open_with_login(self):
        print("\n" + str(test_cases(253)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Services link
        self.assertIn("topic/37-Canonizer-Services/1-Agreement",
                      CanonizerServices(self.driver).check_services_page_should_open().get_url())

    # 255
    def test_check_jobs_page_should_open_without_login(self):
        print("\n" + str(test_cases(254)))
        # Click on the Jobs link
        self.assertIn("topic/6-Canonizer-Jobs/1-Agreement",
                      CanonizerJobs(self.driver).check_jobs_page_should_open().get_url())

    # 256
    def test_check_services_page_should_open_without_login(self):
        print("\n" + str(test_cases(255)))
        # Click on the Services link
        self.assertIn("topic/37-Canonizer-Services/1-Agreement",
                      CanonizerServices(self.driver).check_services_page_should_open().get_url())

    # 257
    def test_submit_camp_update_with_invalid_length_camp_about_URL(self):
        print("\n" + str(test_cases(256)))
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

    def test_select_menu_items_one_by_one(self):
        print("\n")
        self.login_to_canonizer_app()
        result = CanonizerBrowsePage(self.driver).select_menu_items_one_by_one()

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
        self.assertIn('forum/173-Software-Testing/1-Agreement/threads', result)

    # 270
    def test_create_thread_with_duplicate_name(self):
        print("\n")
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_duplicate_title_name(
            DEFAULT_NICK_NAME,
            'Test thread demo 11111'
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # 271
    def test_create_thread_with_blank_title(self):
        print("\n")
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_blank_title_name(
            DEFAULT_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # 272
    def test_create_thread_with_special_char(self):
        print("\n")
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
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/569/edit", result)

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

    # TC_CAMP_FORM_01
    def test_check_no_thread_availability(self):
        print("\n", str(test_cases('TC_CAMP_FORM_01')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().check_no_thread_availability().get_url()
        self.assertIn("forum/638-faiqa-riaz-new-topic-2/1-Agreement/threads", result)

    # TC_CAMP_FORM_02
    def test_check_all_replies_to_thread(self):
        print("\n", str(test_cases('TC_CAMP_FORM_02')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().check_all_replies_to_thread().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/569", result)

    # TC_CAMP_FORM_03
    def test_create_thread_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_03')))
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
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/569", result)

    # TC_CAMP_FORM_04
    def test_create_thread_with_valid_data(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_04')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_correct_title_name(
            "Testing thread abc",
            DEF_NICK_NAME

        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CAMP_FORM_05
    def test_create_thread_with_invalid_data(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_05')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_invalid_data(
            "*******************",
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_CAMP_FORM_06
    def test_create_thread_with_blank_mandatory_fields(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_06')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_blank_mandatory_fields(
            "",
            ""
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/create", result)

    # TC_CAMP_FORM_07
    def test_create_thread_with_only_mandatory_fields(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_07')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_only_mandatory_fields(
            "Checking Data with only Mandatory Fields",
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CAMP_FORM_09
    def test_edit_thread_with_duplicate_title(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_09')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().edit_thread_title_with_duplicate_title(
            DUPLICATE_THREAD_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/574/edit", result)

    # TC_CAMP_FORM_10
    def test_create_thread_with_invalid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_10')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_invalid_data_with_enter_key(
            "Checking thread with Invalid data *** with enter key",
            DEF_NICK_NAME
        )
        self.assertIn("Title can only contain space and alphanumeric characters.", result)

    # TC_CAMP_FORM_11
    def test_create_thread_with_valid_data_with_enter_key(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_11')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_valid_data_with_enter_key(
            "Creating a thread with enter key",
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CAMP_FORM_12
    def test_create_thread_with_trailing_spaces(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_12')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().create_thread_with_trailing_spaces(
            "          Creating thread with trailing spaces",
            DEF_NICK_NAME
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_CAMP_FORM_13
    def test_verify_camp_link_form(self):
        print("\n" + str(test_cases('TC_CAMP_FORM_13')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_create_thread_page().verify_camp_link_form().get_url()
        self.assertIn("topic/173-Software-Testing/1-Agreement", result)

    # TC_POST_THREAD_01
    def test_load_thread_posts_page(self):
        print("\n" + str(test_cases('TC_POST_THREAD_01')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().load_thread_posts_page().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/576", result)

    # TC_POST_THREAD_02
    def test_thread_posts_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases('TC_POST_THREAD_02')))
        self.login_to_canonizer_app()
        result = AddForumsPage(
            self.driver).load_camp_forum_page().thread_posts_mandatory_fields_are_marked_with_asterisk().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/576", result)

    # TC_POST_THREAD_03
    def test_post_reply_to_thread(self):
        print("\n" + str(test_cases('TC_POST_THREAD_03')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().post_reply_to_thread(
            "Test Reply 1"
        ).get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads/576", result)

    # TC_POST_THREAD_04
    def test_thread_pagination(self):
        print("\n" + str(test_cases('TC_POST_THREAD_04')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().thread_pagination().get_url()
        self.assertIn("forum/173-Software-Testing/1-Agreement/threads", result)

    # TC_POST_THREAD_05
    def test_verify_nickname_on_thread_title(self):
        print("\n" + str(test_cases('TC_POST_THREAD_05')))
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).load_camp_forum_page().verify_nickname_on_thread_title().get_url()
        self.assertIn("user/supports/", result)

    def test_page_crash(self):
        self.login_to_canonizer_app()
        result = AddForumsPage(self.driver).check_page_crash()
        # print("result", result)
        # result ['https://staging.canonizer.com/forum/411-Demo-Topic/1-Agreement/threads', 'Canonizer Forum Details']
        self.assertTrue(['/forum/411-Demo-Topic/1-Agreement/threads', 'Canonizer Forum Details'], result)

    # ----- Direct Join and Support  Start -----

    # TC_DIRECT_JOIN_AND_SUPPORT_01
    # def test_load_direct_join_and_support_page(self):
    #     # Click on the Login Page and Create a Login Session and for further actions.
    #     self.login_to_canonizer_app()
    #     print("\n")
    #     result = CanonizerEditCampPage(self.driver).load_direct_join_and_support_page()
    #     if result:
    #         self.assertIn("support/173-Software-Testing/1-Agreement", result.get_url())

    # ----- Direct Join & Support  End -----

    def tearDown(self):
        self.driver.close()


if __name__ == "__main__":
    suite = unittest.TestLoader().loadTestsFromTestCase(TestPages)
    unittest.TextTestRunner(verbosity=2).run(suite)
