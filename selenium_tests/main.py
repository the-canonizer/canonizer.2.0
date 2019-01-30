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
import re


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
        options.add_argument('window-size=1200x600')

        self.driver = webdriver.Chrome(driver_location, options=options)
        self.driver.get(DEFAULT_BASE_URL)

    def login_to_canonizer_app(self):
        """
            This Application will allow you to login to canonizer App on need basis
        :param flag:
        :return:
        """
        return CanonizerLoginPage(self.driver).click_login_page_button().login_with_valid_user(DEFAULT_USER, DEFAULT_PASS)

    def test_canonizer_home_page_load(self):
        print("\n" + str(test_cases(0)))
        self.assertTrue(CanonizerMainPage(self.driver).check_home_page_loaded())

    def test_canonizer_register_button(self):
        print("\n" + str(test_cases(1)))
        registerPage = CanonizerRegisterPage(self.driver).click_register_button()
        self.assertIn("/register", registerPage.get_url())

    def test_canonizer_login_button(self):
        print("\n" + str(test_cases(2)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        self.assertIn("/login", loginPage.get_url())

    def test_canonizer_login_with_valid_user(self):
        print ("\n" + str(test_cases(3)))
        result = self.login_to_canonizer_app()
        self.assertIn("", result.get_url())

    def test_login_with_invalid_user(self):
        print ("\n" + str(test_cases(4)))
        loginPage = CanonizerLoginPage(self.driver).click_login_page_button()
        result = loginPage.login_with_invalid_user(DEFAULT_INVALID_USER, DEFAULT_INVALID_PASSWORD)
        self.assertIn("These credentials do not match our records.", result)

    # Register Page Test Cases Start
    # 05
    def test_registration_with_blank_first_name(self):
        print ("\n" + str(test_cases(5)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_first_name(
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            DEFAULT_PASS,
            DEFAULT_PASS)
        self.assertIn("The first name field is required.", result)

    # 06
    def test_registration_with_blank_last_name(self):
        print("\n" + str(test_cases(6)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_last_name(
            DEFAULT_FIRST_NAME,
            DEFAULT_USER,
            DEFAULT_PASS,
            DEFAULT_PASS)
        self.assertIn("The last name field is required.", result)

    # 07
    def test_registration_with_blank_email(self):
        print ("\n" + str(test_cases(7)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_email(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_PASS,
            DEFAULT_PASS)
        self.assertIn("The email field is required.", result)

    # 08
    def test_registration_with_blank_password(self):
        print ("\n" + str(test_cases(8)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_blank_password(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER)
        self.assertIn('The password field is required.', result)

    # 09
    def test_registration_with_invalid_password_length(self):
        print ("\n" + str(test_cases(9)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_invalid_password_length(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            '12345',
            '12345')
        self.assertIn('The password must be at least 6 characters.', result)

    # 10
    def test_registration_with_different_confirmation_password(self):
        print ("\n" + str(test_cases(10)))
        result = CanonizerRegisterPage(self.driver).click_register_button().registration_with_different_confirmation_password(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            'Test@1234567',
            'Test@123456')
        self.assertIn('The password confirmation does not match.', result)

    def test_what_is_canonizer_page_loaded_properly(self):
        print("\n" + str(test_cases(11)))
        self.assertTrue(CanonizerMainPage(self.driver).click_what_is_canonizer_page_link().check_what_is_canonizer_page_loaded())

    def test_user_must_be_signin_to_join_or_support_camp(self):
        print("\n" + str(test_cases(12)))
        mainPage = CanonizerMainPage(self.driver)

    # 14 --> Index of test case is test_number - 1
    def test_load_all_topics_button_text(self):
        print("\n" + str(test_cases(13)))
        self.assertIn('Load All Topics', CanonizerMainPage(self.driver).check_load_all_topic_text())

    # 15
    def test_register_page_should_have_login_option_for_existing_users(self):
        print("\n" + str(test_cases(14)))
        self.assertIn('Login Here',CanonizerRegisterPage(self.driver).click_register_button().registration_should_have_login_option_for_existing_users())

    # 16
    def test_login_page_should_have_register_option_for_new_users(self):
        print("\n" + str(test_cases(15)))
        self.assertIn('Signup Now', CanonizerLoginPage(self.driver).click_login_page_button().login_page_should_have_register_option_for_new_users())

    # 17
    def test_register_page_mandatory_fields_are_marked_with_astrick(self):
        """
        Mandatory Fields in Registration Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(16)))
        self.assertTrue(CanonizerRegisterPage(self.driver).click_register_button().register_page_mandatory_fields_are_marked_with_astrick())

    # ----- FORGOT PASSWORD Test Cases Start -----

    # 18
    def test_click_forgot_password_page_button(self):
        print("\n" + str(test_cases(17)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        self.assertIn("/forgetpassword", CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().get_url())

    # 19
    def test_forgot_password_with_blank_email(self):
        print("\n" + str(test_cases(18)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        result = CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().forgot_password_with_blank_email()
        self.assertIn("The email field is required.", result)

        # 20
    def test_forgot_password_with_invalid_email(self):
        print("\n" + str(test_cases(19)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        result = CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().forgot_password_with_invalid_email(DEFAULT_INVALID_USER)
        self.assertIn("Email not found in our record", result)

    # 21
    def test_forgot_password_with_valid_email(self):
        print("\n" + str(test_cases(20)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        result = CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().forgot_password_with_valid_email(DEFAULT_USER)
        self.assertIn("", result.get_url())

    def test_forgot_password_page_mandatory_fields_are_marked_with_astrick(self):
        """
        Mandatory Fields in Forgot Password Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(21)))
        # Click on the Login Page
        CanonizerLoginPage(self.driver).click_login_page_button()
        # Click on the Forgot Password link
        self.assertTrue(CanonizerForgotPasswordPage(self.driver).click_forgot_password_page_button().forgot_password_page_mandatory_fields_are_marked_with_astrick())
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
        # Click on the Browse link
        self.assertIn("/browse?namespace=&my=1", CanonizerBrowsePage(self.driver).click_browse_page_button().click_only_my_topics_button().get_url())
    # ----- Browse Page Test Cases End -----

    # ----- Upload File Page Test Cases Start -----
    # 25
    def test_click_upload_file_page_button(self):
        """
        This test is to check the upload file button on the our Canonizer Application

        Upload functionality is only available once the user is logged in to the Web
        Application. Hence we need to manage the login session before proceeding

        This function is divided into two parts:
            1. Login to the App.
            2. Click on the Upload Button
        :return:
        """
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
        result = CanonizerUploadFilePage(self.driver).click_upload_file_page_button.upload_file_with_blank_file()
        self.assertIn("Error! The file field is required.", result)

    # ----- Upload File Page Test Cases End -----
    # ----- Create New Topic Test Cases Start -----
    # 27
    def test_click_create_new_topic_page_button(self):
        print("\n" + str(test_cases(26)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check topic/create in URL Name
        self.assertIn("topic/create", CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().get_url())

    # 28
    def test_create_topic_with_blank_nick_name(self):
        print("\n" + str(test_cases(27)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if nick name is blank
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().create_topic_with_blank_nick_name(
            DEFAULT_TOPIC_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE)
        self.assertIn("The nick name field is required.", result)

    # 29
    def test_create_topic_with_blank_topic_name(self):
        print("\n" + str(test_cases(28)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if topic name is blank
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().create_topic_with_blank_topic_name(
            DEFAULT_NICK_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE)
        self.assertIn("The topic name field is required.", result)

    # 29
    def test_create_topic_with_blank_note(self):
        print("\n" + str(test_cases(29)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link and check if topic name is blank
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().create_topic_with_blank_note(
            DEFAULT_NICK_NAME,
            DEFAULT_TOPIC_NAME,
            DEFAULT_NAMESPACE)
        self.assertIn("The note field is required.", result)

        # 30

    def test_create_new_topic_page_mandatory_fields_are_marked_with_astrick(self):
        """
        Mandatory Fields in Registration Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(30)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic  link
        self.assertTrue(CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().create_new_topic_page_mandatory_fields_are_marked_with_astrick())

    def test_create_topic_with_duplicate_topic_name(self):
        print("\n" + str(test_cases(31)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic  link
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().create_topic_with_duplicate_topic_name(
            DEFAULT_NICK_NAME,
            DUPLICATE_TOPIC_NAME,
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE)
        self.assertIn("The topic name has already been taken.", result)

    # 09
    def test_create_topic_with_invalid_topic_name_length(self):
        print("\n" + str(test_cases(32)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic link
        result = CanonizerCreateNewTopicPage(self.driver).click_create_new_topic_page_button().create_topic_with_invalid_topic_name_length(
            DEFAULT_NICK_NAME,
            '123456789012345678901234567890123456789',
            DEFAULT_NAMESPACE,
            DEFAULT_NOTE)
        self.assertIn('The topic name may not be greater than 30 characters.', result)
    # ----- Create New Topic Test Cases End -----

    # ----- Log out Test Cases Start -----
    def test_click_log_out_page_button(self):
        """
        This test is to check log out functionality from Canonizer Application
        """
        print("\n" + str(test_cases(33)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Username and click on Log Out
        self.assertTrue(CanonizerLogoutPage(self.driver).click_username_link_button().click_log_out_page_button().check_home_page_loaded())
    # ----- Log out Test Cases End -----

    # ----- Account Settings Test Cases Start -----
    def test_click_account_settings_page_button(self):
        """
        This test is to check the account settings in the Canonizer Application
        """
        print("\n" + str(test_cases(34)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings in URL Name
        self.assertIn("settings", CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().get_url())

    def test_click_account_settings_manage_profile_info_page_button(self):
        """
        This test is to check the account settings->Manage Profile page load in the Canonizer Application
        """
        print("\n" + str(test_cases(35)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings in URL Name
        self.assertIn("settings", CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button().get_url())

    def test_click_account_settings_add_manage_nick_names_page_button(self):
        """
        This test is to check the account settings->Add & Manage Nick Name page load in the Canonizer Application
        """
        print("\n" + str(test_cases(36)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/nickname in URL Name
        self.assertIn("settings/nickname", CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button().get_url())

    def test_click_account_settings_my_supports_page_button(self):
        """
        This test is to check the account settings->My Supports page load in the Canonizer Application
        """
        print("\n" + str(test_cases(37)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check support in URL Name
        self.assertIn("support", CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_my_supports_page_button().get_url())

    def test_click_account_settings_default_algorithm_page_button(self):
        """
        This test is to check the account settings->Default Algorithm page load in the Canonizer Application
        """
        print("\n" + str(test_cases(38)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/algo-preferences in URL Name
        self.assertIn("settings/algo-preferences", CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_default_algorithm_page_button().get_url())

    def test_click_account_settings_change_password_page_button(self):
        """
        This test is to check the account settings->Change Password page load in the Canonizer Application
        """
        print("\n" + str(test_cases(38)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username and Click on Account Settings and check settings/changepassword in URL Name
        self.assertIn("settings/changepassword", CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button().get_url())
    # ----- Account Settings  Test Cases End -----

    # ----- Change Password Test Cases Start -----

    def test_change_password_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        Mandatory Fields in Change Password Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(40)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        # Check for Mandatory fields in Change Password page
        self.assertTrue(CanonizerAccountSettingsChangePasswordPage(self.driver).change_password_page_mandatory_fields_are_marked_with_asterisk())

    def test_save_with_blank_current_password(self):
        print("\n" + str(test_cases(41)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_current_password(
            DEFAULT_NEW_PASSWORD,
            DEFAULT_CONFIRM_PASSWORD)
        self.assertIn("The current password field is required.", result)

    def test_save_with_blank_new_password(self):
        print("\n" + str(test_cases(42)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_new_password(
            DEFAULT_CURRENT_PASSWORD,
            DEFAULT_CONFIRM_PASSWORD)
        self.assertIn("The new password field is required.", result)

    def test_save_with_blank_confirm_password(self):
        print("\n" + str(test_cases(43)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_blank_confirm_password(
            DEFAULT_CURRENT_PASSWORD,
            DEFAULT_NEW_PASSWORD)
        self.assertIn("The confirm password field is required.", result)

    def test_save_with_invalid_current_password(self):
        print("\n" + str(test_cases(44)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_invalid_current_password(
            'Test@12345',
            'Test@123456',
            'Test@123456')
        self.assertIn("Incorrect Current Password.", result)

    def test_save_with_mismatch_new_confirm_password(self):
        print("\n" + str(test_cases(45)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_mismatch_new_confirm_password(
            DEFAULT_PASS,
            'Test@12345',
            'Test@123456')
        self.assertIn("The confirm password and new password must match.", result)

    def test_save_with_same_new_and_current_password(self):
        print("\n" + str(test_cases(46)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Change Password sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_change_password_page_button()
        result = CanonizerAccountSettingsChangePasswordPage(self.driver).save_with_same_new_and_current_password(
            DEFAULT_PASS,
            DEFAULT_PASS,
            'Test@1234567')
        self.assertIn("The new password and current password must be different.", result)
    # ----- Change Password Test Cases End -----

    # ----- Help Page Test Cases Start -----
    def test_check_what_is_canonizer_help_page_loaded_without_login(self):
        print("\n" + str(test_cases(47)))
        self.assertIn("/topic/132-Help/1", CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().get_url())

    def test_check_what_is_canonizer_help_page_loaded_with_login(self):
        print("\n" + str(test_cases(48)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help
        self.assertIn("/topic/132-Help/1", CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().get_url())

    def test_check_Steps_to_Create_a_New_Topic_page_loaded_with_login(self):
        print("\n" + str(test_cases(49)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on the Steps_to_Create_a_New_Topic
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded()\
            .check_Steps_to_Create_a_New_Topic_page_loaded(). open("/topic/131-Create-Topic-Steps")

    def test_check_Dealing_With_Disagreements_page_loaded_with_login(self):
        print("\n" + str(test_cases(50)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on the Dealing With Disagreements
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Dealing_With_Disagreements_page_loaded().open("/topic/38")

    def test_check_Wiki_Markup_Information_page_loaded_with_login(self):
        print("\n" + str(test_cases(51)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and Click on Wiki Markup Information
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Wiki_Markup_Information_page_loaded().open("topic/73-wiki-text/1")

    def test_check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded_with_login(self):
        print("\n" + str(test_cases(52)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Help and click on Adding the Canonizer Feedback Camp Outline to Internet Articles
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded().open("/topic/58")

    def test_check_Steps_to_Create_a_New_Topic_page_loaded_without_login(self):
        print("\n" + str(test_cases(53)))
        # Click on the Help and Click on the Steps_to_Create_a_New_Topic
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Steps_to_Create_a_New_Topic_page_loaded().open("/login")

    def test_check_Dealing_With_Disagreements_page_loaded_without_login(self):
        print("\n" + str(test_cases(54)))
        # Click on the Help and Click on the Dealing With Disagreements
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Dealing_With_Disagreements_page_loaded(). open("/login")

    def test_check_Wiki_Markup_Information_page_loaded_without_login(self):
        print("\n" + str(test_cases(55)))
        # Click on the Help and Click on Wiki Markup Information
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Wiki_Markup_Information_page_loaded(). open("/login")

    def test_check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded_without_login(self):
        print("\n" + str(test_cases(56)))
        # Click on the Help and Click on Adding the Canonizer Feedback Camp Outline to Internet Articles
        CanonizerHelpPage(self.driver).check_what_is_canonizer_help_page_loaded().check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded(). open("/login")

    # ----- Help Page Test Cases End -----

    # ----- Add & Manage Nick Names Page Test Cases Start -----
    def test_nick_names_page_mandatory_fields_are_marked_with_astrick(self):
        """
        Mandatory Fields in Nick Names Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(57)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertTrue(CanonizerAccountSettingsNickNamesPage(self.driver).nick_names_page_mandatory_fields_are_marked_with_astrick())

    def test_create_with_blank_nick_name(self):
        print("\n" + str(test_cases(58)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        self.assertIn("Nick name is required.", CanonizerAccountSettingsNickNamesPage(self.driver).create_with_blank_nick_name())

    def test_create_with_duplicate_nick_name(self):
        print("\n" + str(test_cases(59)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_duplicate_nick_name('Rupali C')
        self.assertIn("The nick name has already been taken.", result)

    def test_create_with_max_nick_name(self):
        print("\n" + str(test_cases(60)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_max_nick_name('test123456test123456test123456test123456test123456test123456')
        self.assertIn("The nick name may not be greater than 50 characters.", result)

    def test_create_with_valid_nick_name(self):
        print("\n" + str(test_cases(61)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on Username->Account Settings->Nick Names sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_add_manage_nick_names_page_button()
        result = CanonizerAccountSettingsNickNamesPage(self.driver).create_with_valid_nick_name('Rupali Chavan Selenium')
        self.assertIn("/settings/nickname", result.get_url())

    # ----- Add & Manage Nick Names Page Test Cases End -----
    # ----- My Profile Page Test Cases Start -----
    def test_manage_profile_info_page_mandatory_fields_are_marked_with_astrick(self):
        """
        Mandatory Fields in Manage Profile Info Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(62)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        self.assertTrue(AccountSettingsManageProfileInfoPage(self.driver).manage_profile_info_page_mandatory_fields_are_marked_with_astrick())

    def test_update_profile_with_blank_first_name(self):
        print("\n" + str(test_cases(63)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the username->Account Settings->Manage Profile Info sub tab
        CanonizerAccountSettingsPage(self.driver).click_username_link_button().click_account_settings_page_button().click_account_settings_manage_profile_info_page_button()
        result = AccountSettingsManageProfileInfoPage(self.driver).update_profile_with_blank_first_name(
            DEFAULT_MIDDLE_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '')
        self.assertIn("First name is required.", result)

        # ----- My Profile Page Test Cases End -----
        # ----- Browse Page Test Cases Start -----

    def test_select_by_value_general(self):
        print("\n" + str(test_cases(65)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=1", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_general().get_url())

    def test_select_by_value_corporations(self):
        print("\n" + str(test_cases(66)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=2", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_corporations().get_url())

    def test_select_by_value_crypto_currency(self):
        print("\n" + str(test_cases(67)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=3", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_crypto_currency().get_url())

    def test_select_by_value_family(self):
        print("\n" + str(test_cases(68)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=4", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_family().get_url())

    def test_select_by_value_family_jesperson_oscar_f(self):
        print("\n" + str(test_cases(69)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=5", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_family_jesperson_oscar_f().get_url())

    def test_select_by_value_occupy_wall_street(self):
        print("\n" + str(test_cases(70)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=6", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_occupy_wall_street().get_url())

    def test_select_by_value_organizations(self):
        print("\n" + str(test_cases(71)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=7", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations().get_url())

    def test_select_by_value_organizations_canonizer(self):
        print("\n" + str(test_cases(72)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=8", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_canonizer().get_url())

    def test_select_by_value_organizations_canonizer_help(self):
        print("\n" + str(test_cases(73)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=9", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_canonizer_help().get_url())

    def test_select_by_value_organizations_mta(self):
        print("\n" + str(test_cases(74)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=10", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_mta().get_url())

    def test_select_by_value_organizations_tv07(self):
        print("\n" + str(test_cases(75)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=11", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_tv07().get_url())

    def test_select_by_value_organizations_wta(self):
        print("\n" + str(test_cases(76)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=12", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_organizations_wta().get_url())

    def test_select_by_value_personal_attributes(self):
        print("\n" + str(test_cases(77)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=13", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_personal_attributes().get_url())

    def test_select_by_value_personal_reputations(self):
        print("\n" + str(test_cases(78)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=14", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_personal_reputations().get_url())

    def test_select_by_value_professional_services(self):
        print("\n" + str(test_cases(79)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=15", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_professional_services().get_url())

    def test_select_by_value_sandbox(self):
        print("\n" + str(test_cases(80)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=16", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_sandbox().get_url())

    def test_select_by_value_terminology(self):
        print("\n" + str(test_cases(81)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=17", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_terminology().get_url())

    def test_select_by_value_www(self):
        print("\n" + str(test_cases(82)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=18", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_www().get_url())

    def test_select_by_value_all(self):
        print("\n" + str(test_cases(83)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_all().get_url())

    def test_select_by_value_all_default(self):
        print("\n" + str(test_cases(84)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse", CanonizerBrowsePage(self.driver).click_browse_page_button().select_dropdown_value().select_by_value_all_default().get_url())

    def test_select_by_value_general_Only_My_Topics(self):
        print("\n" + str(test_cases(85)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=1&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_general().get_url())

    def test_select_by_value_corporations_Only_My_Topics(self):
        print("\n" + str(test_cases(86)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=2&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_corporations().get_url())

    def test_select_by_value_crypto_currency_Only_My_Topics(self):
        print("\n" + str(test_cases(87)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=3&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_crypto_currency().get_url())

    def test_select_by_value_family_Only_My_Topics(self):
        print("\n" + str(test_cases(88)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=4&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_family().get_url())

    def test_select_by_value_family_jesperson_oscar_f_Only_My_Topics(self):
        print("\n" + str(test_cases(89)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=5&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_family_jesperson_oscar_f().get_url())

    def test_select_by_value_occupy_wall_street_Only_My_Topics(self):
        print("\n" + str(test_cases(90)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=6&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_occupy_wall_street().get_url())

    def test_select_by_value_organizations_Only_My_Topics(self):
        print("\n" + str(test_cases(91)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=7&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_organizations().get_url())

    def test_select_by_value_organizations_canonizer_Only_My_Topics(self):
        print("\n" + str(test_cases(92)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=8&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_organizations_canonizer().get_url())

    def test_select_by_value_organizations_canonizer_help_Only_My_Topics(self):
        print("\n" + str(test_cases(93)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=9&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_organizations_canonizer_help().get_url())

    def test_select_by_value_organizations_mta_Only_My_Topics(self):
        print("\n" + str(test_cases(94)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=10&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_organizations_mta().get_url())

    def test_select_by_value_organizations_tv07_Only_My_Topics(self):
        print("\n" + str(test_cases(95)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=11&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_organizations_tv07().get_url())

    def test_select_by_value_organizations_wta_Only_My_Topics(self):
        print("\n" + str(test_cases(96)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=12&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_organizations_wta().get_url())

    def test_select_by_value_personal_attributes_Only_My_Topics(self):
        print("\n" + str(test_cases(97)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=13&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_personal_attributes().get_url())

    def test_select_by_value_personal_reputations_Only_My_Topics(self):
        print("\n" + str(test_cases(98)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=14&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_personal_reputations().get_url())

    def test_select_by_value_professional_services_Only_My_Topics(self):
        print("\n" + str(test_cases(99)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=15&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_professional_services().get_url())

    def test_select_by_value_sandbox_Only_My_Topics(self):
        print("\n" + str(test_cases(100)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=16&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_sandbox().get_url())

    def test_select_by_value_terminology_Only_My_Topics(self):
        print("\n" + str(test_cases(101)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=17&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_terminology().get_url())

    def test_select_by_value_www_Only_My_Topics(self):
        print("\n" + str(test_cases(102)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=18&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_www().get_url())

    def test_select_by_value_all_Only_My_Topics(self):
        print("\n" + str(test_cases(103)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Browse link
        self.assertIn("/browse?namespace=&my=1", CanonizerBrowsePage(self.driver).click_only_my_topics_button().select_by_value_all().get_url())

        # ----- Browse Page Test Cases End -----
        # ----- White Paper Test Cases Start -----

    def test_check_white_paper_should_open_with_login(self):
        print("\n" + str(test_cases(104)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the White Paper link
        CanonizerWhitePaper(self.driver).check_white_paper_should_open().open("files/2012_amplifying_final.pdf")

    def test_check_white_paper_should_open_without_login(self):
        print("\n" + str(test_cases(105)))
        # Click on the White Paper link
        CanonizerWhitePaper(self.driver).check_white_paper_should_open().open("files/2012_amplifying_final.pdf")
        # ----- White Paper Test Cases End -----

        # ----- Blog Test Cases Start -----

    def test_check_blog_page_should_open_with_login(self):
        print("\n" + str(test_cases(106)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the White Paper link
        self.assertIn("/blog/", CanonizerBlog(self.driver).check_blog_page_should_open().get_url())

    def test_check_blog_page_should_open_without_login(self):
        print("\n" + str(test_cases(107)))
        # Click on the White Paper link
        self.assertIn("/blog/", CanonizerBlog(self.driver).check_blog_page_should_open().get_url())

        # ----- Blog Test Cases End -----

        # ----- Algorithm Information Test Cases Start -----

    def test_check_algorithm_information_page_should_open(self):
        print("\n" + str(test_cases(108)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the White Paper link
        self.assertIn("topic/53-Canonized-Canonizer-Algorithms/2", CanonizerAlgorithmInformation(self.driver).
                      check_algorithm_information_page_should_open().get_url())

        # ----- Algorithm Information Test Cases End -----

        # ----- As Of Filters Test Cases Start -----

    def test_check_include_review_filter_applied(self):
        print("\n" + str(test_cases(109)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on include review
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_include_review_filter_applied().get_url())

    def test_check_default_filter_applied(self):
        print("\n" + str(test_cases(110)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on include review
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_default_filter_applied().get_url())

    def test_check_as_of_date_filter_applied(self):
        print("\n" + str(test_cases(111)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on include review
        self.assertIn(DEFAULT_BASE_URL, CanonizerAsOfFilters(self.driver).check_as_of_date_filter_applied().get_url())
        # ----- As Of Filters Test Cases End -----
        # ----- Update Topic Test Cases Start -----

    def test_load_topic_update_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(112)))
        self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_topic_update_page().get_url())

    def test_load_view_this_version_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(113)))
        self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_view_this_version_page().get_url())

    def test_load_topic_object_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(114)))
        self.assertIn(DEFAULT_BASE_URL, CanonizerTopicUpdatePage(self.driver).load_topic_object_page().get_url())

    def test_topic_update_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(115)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic  link
        self.assertTrue(CanonizerTopicUpdatePage(self.driver).load_topic_update_page().topic_update_page_mandatory_fields_are_marked_with_asterisk())

    def test_topic_objection_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(116)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New Topic  link
        self.assertTrue(CanonizerTopicUpdatePage(self.driver).load_topic_update_page().topic_objection_page_mandatory_fields_are_marked_with_asterisk())

    def test_topic_update_page_should_have_add_new_nick_name_link_for_new_users(self):
        print("\n" + str(test_cases(117)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        self.assertTrue(CanonizerTopicUpdatePage(self.driver).load_topic_update_page().topic_update_page_should_have_add_new_nick_name_link_for_new_users())

    def test_submit_update_with_blank_nick_name(self):
        print("\n" + str(test_cases(118)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Topic update and check if nick name is blank
        result = CanonizerTopicUpdatePage(self.driver).load_topic_update_page().submit_update_with_blank_nick_name(
            "Test",
            "",
            "")
        self.assertIn("The nick name field is required.", result)
    # ----- Update Topic Test Cases End -----
    # ----- Create New Camp and Edit Camp Test Cases Start -----

    def test_load_create_camp_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(119)))
        self.assertIn("/camp/create/88/1", CanonizerCampPage(self.driver).load_create_camp_page().get_url())

    def test_create_new_camp_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(120)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp  link
        self.assertTrue(CanonizerCampPage(self.driver).load_create_camp_page().create_new_camp_page_mandatory_fields_are_marked_with_asterisk())

    def test_create_camp_with_blank_nick_name(self):
        print("\n" + str(test_cases(121)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if nick name is blank
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_blank_nick_name(
            "Test",
            "",
            "",
            "",
            "")
        self.assertIn("The nick name field is required.", result)

    # 29
    def test_create_camp_with_blank_camp_name(self):
        print("\n" + str(test_cases(122)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Create New camp link and check if topic name is blank
        result = CanonizerCampPage(self.driver).load_create_camp_page().create_camp_with_blank_camp_name(
            "Test",
            "",
            "",
            "",
            "")
        self.assertIn("The camp name field is required.", result)

    def test_load_camp_update_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(123)))
        self.assertIn("/manage/camp/545", CanonizerEditCampPage(self.driver).load_camp_update_page().get_url())

    def test_camp_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(124)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp  link
        self.assertTrue(CanonizerEditCampPage(self.driver).load_camp_update_page().camp_edit_page_mandatory_fields_are_marked_with_asterisk())

    def test_submit_camp_update_with_blank_nick_name(self):
        print("\n" + str(test_cases(125)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = CanonizerEditCampPage(self.driver).load_camp_update_page().submit_camp_update_with_blank_nick_name(
            "Test",
            "",
            "",
            "",
            "")
        self.assertIn("The nick name field is required.", result)
        # ----- Create New Camp Test Cases End -----

    # ----- Create New Camp Statement and Edit Camp Statement Test Cases Start -----
    def test_load_edit_camp_statement_page(self):
            # Click on the Login Page and Create a Login Session and for further actions.
            self.login_to_canonizer_app()
            print("\n" + str(test_cases(126)))
            self.assertIn("manage/statement/1438", CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page().get_url())

    def test_camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(127)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp Statement  link
        self.assertTrue(CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page().camp_statement_edit_page_mandatory_fields_are_marked_with_asterisk())

    def test_submit_statement_update_with_blank_nick_name(self):
        print("\n" + str(test_cases(128)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = CanonizerCampStatementPage(self.driver).load_edit_camp_statement_page().submit_statement_update_with_blank_nick_name(
            "Test",
            "",)
        self.assertIn("The nick name field is required.", result)

    # ----- Add News and Edit News Test Cases Start -----
    def test_load_add_news_feed_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(129)))
        self.assertIn("addnews/88-Theories-of-Consciousness/1", CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().get_url())

    def test_add_news_page_mandatory_fields_are_marked_with_asterisk(self):
        print("\n" + str(test_cases(130)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Click on the Manage/Edit Camp Statement  link
        self.assertTrue(CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().add_news_page_mandatory_fields_are_marked_with_asterisk())

    def test_create_news_with_blank_display_text(self):
        print("\n" + str(test_cases(131)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_blank_display_text(
            "Test",
            "")
        self.assertIn("The display text field is required.", result)

    def test_create_news_with_blank_link(self):
        print("\n" + str(test_cases(132)))
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        # Go to Manage/Edit Camp and check if nick name is blank
        result = CanonizerAddNewsFeedsPage(self.driver).load_add_news_feed_page().create_news_with_blank_link(
            "Test",
            "")
        self.assertIn("The link field is required.", result)

    def test_load_edit_news_feed_page(self):
        # Click on the Login Page and Create a Login Session and for further actions.
        self.login_to_canonizer_app()
        print("\n" + str(test_cases(133)))
        self.assertIn("editnews/88-Theories-of-Consciousness/1", CanonizerEditNewsFeedsPage(self.driver).load_edit_news_feed_page().get_url())

# ----- Add News and Edit News Test Cases End -----
    def tearDown(self):
        self.driver.close()


if __name__ == "__main__":
    suite = unittest.TestLoader().loadTestsFromTestCase(TestPages)
    unittest.TextTestRunner(verbosity=2).run(suite)