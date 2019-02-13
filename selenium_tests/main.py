import unittest
from CanonizerHomePage import *
from CanonizerRegistrationPage import  *
from CanonizerLoginPage import *
from CanonizerForumsPage import *
from CanonizerTestCases import test_cases
from Config import *
from selenium import webdriver
import time
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
        #options.add_argument('headless')
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
        page = CanonizerMainPage(self.driver)
        self.assertTrue(page.check_home_page_loaded())

    def test_canonizer_register_button(self):
        print("\n" + str(test_cases(1)))
        page = CanonizerRegisterPage(self.driver)
        registerPage = page.click_register_button()
        self.assertIn("/register", registerPage.get_url())

    def test_canonizer_login_button(self):
        print("\n" + str(test_cases(2)))
        page = CanonizerLoginPage(self.driver)
        loginPage = page.click_login_page_button()
        self.assertIn("/login", loginPage.get_url())

    def test_canonizer_login_with_valid_user(self):
        print ("\n" + str(test_cases(3)))
        page = CanonizerLoginPage(self.driver)
        loginPage = page.click_login_page_button()
        result = loginPage.login_with_valid_user(DEFAULT_USER, '123456')
        self.assertIn("", result.get_url())

    def test_login_with_invalid_user(self):
        print ("\n" + str(test_cases(4)))
        page = CanonizerLoginPage(self.driver)
        loginPage = page.click_login_page_button()
        result = loginPage.login_with_invalid_user(DEFAULT_INVALID_USER, DEFAULT_INVALID_PASSWORD)
        self.assertIn("These credentials do not match our records.", result)

    # Register Page Test Cases Start
    # 05
    def test_registration_with_blank_first_name(self):
        print ("\n" + str(test_cases(5)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()

        result = registrationPage.registration_with_blank_first_name(
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            DEFAULT_PASS,
            DEFAULT_PASS
        )
        self.assertIn("The first name field is required.", result)

    # 06
    def test_registration_with_blank_last_name(self):
        print("\n" + str(test_cases(6)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()

        result = registrationPage.registration_with_blank_last_name(
            DEFAULT_FIRST_NAME,
            DEFAULT_USER,
            DEFAULT_PASS,
            DEFAULT_PASS
        )
        self.assertIn("The last name field is required.", result)

    # 07
    def test_registration_with_blank_email(self):
        print ("\n" + str(test_cases(7)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()

        result = registrationPage.registration_with_blank_email(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_PASS,
            DEFAULT_PASS
        )
        self.assertIn("The email field is required.", result)

    # 08
    def test_registration_with_blank_password(self):
        print ("\n" + str(test_cases(8)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()

        result = registrationPage.registration_with_blank_password(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER
        )
        self.assertIn('The password field is required.', result)

    # 09
    def test_registration_with_invalid_password_length(self):
        print ("\n" + str(test_cases(9)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()

        result = registrationPage.registration_with_invalid_password_length(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            '12345',
            '12345'
        )
        self.assertIn('The password must be at least 6 characters.', result)

    # 10
    def test_registration_with_different_confirmation_password(self):
        print ("\n" + str(test_cases(10)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()
        result = registrationPage.registration_with_different_confirmation_password(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            '1234567',
            '123456'
        )
        self.assertIn('The password confirmation does not match.', result)

    def test_what_is_canonizer_page_loaded_properly(self):
        print("\n" + str(test_cases(11)))
        mainPage = CanonizerMainPage(self.driver)
        whatisCaonizerPage = mainPage.click_what_is_canonizer_page_link()
        self.assertTrue(whatisCaonizerPage.check_what_is_canonizer_page_loaded())

    def test_user_must_be_signin_to_join_or_support_camp(self):
        print("\n" + str(test_cases(12)))
        mainPage = CanonizerMainPage(self.driver)

    # 14 --> Index of test case is test_number - 1
    def test_load_all_topics_button_text(self):
        print("\n" + str(test_cases(13)))
        mainPage = CanonizerMainPage(self.driver)
        self.assertIn('Load All Topics', mainPage.check_load_all_topic_text())

    # 15
    def test_register_page_should_have_login_option_for_existing_users(self):
        print("\n" + str(test_cases(14)))
        registerPage = CanonizerRegisterPage(self.driver)
        registrationPage = registerPage.click_register_button()
        self.assertIn('Login Here',
                      registrationPage.registration_should_have_login_option_for_existing_users())

    # 16
    def test_login_page_should_have_register_option_for_new_users(self):
        print("\n" + str(test_cases(15)))
        page = CanonizerLoginPage(self.driver)
        loginPage = page.click_login_page_button()
        self.assertIn('Signup Now', loginPage.login_page_should_have_register_option_for_new_users())

    # 17
    def test_register_page_mandatory_fields_are_marked_with_astrick(self):
        """
        Mandatory Fields in Registration Page Marked with *
        :return:
        """
        print("\n" + str(test_cases(16)))
        registerPage = CanonizerRegisterPage(self.driver)
        registationPage = registerPage.click_register_button()
        self.assertTrue(registationPage.register_page_mandatory_fields_are_marked_with_astrick())

    #Thread_1
    def test_load_forum_page_from_camp(self):
        """
        Load Forum Page From Camp Page
        :return:
        """
        print("\n" + str(test_cases('t_1')))

        self.assertTrue(CanonizerForumsPage(self.driver).load_camp_forum_page())

    # Thread_2
    def test_forum_must_have_topic_camp_name(self):
        """
        Forum Page Must Have:
            - Valid Topic Name
            - Valid Camp Name
        :return:
        """
        print("\n" + str(test_cases('t_2')))

        topic, camp = CanonizerForumsPage(self.driver).verify_topic_and_camp_name()

        if topic == 2 and camp == 'Agreement':
            self.assertTrue("Success")
        else:
            self.assertFalse("Blank Topic Or Camp Name")

    # Thread_3
    def test_forum_threads_must_be_clickable(self):
        """
            All The Threads of the forum must be clickable
        :return:
        """
        print("\n" + str(test_cases('t_3')))

        self.assertTrue(CanonizerForumsPage(self.driver))

    # Thread 4
    def test_forums_load_create_new_thread_page(self):
        """
        Automated Test For Load Create New Thread Page In Forums
        :return:
        """
        print("\n" + str(test_cases('t_4')))

        # Only if Login is Required
        self.login_to_canonizer_app()

        result = CanonizerForumsPage(self.driver).forums_load_create_new_thread_page()

        flag = re.search('create', result.current_url, re.M)

        self.assertTrue(flag)

    # Thread 5
    def test_forums_create_new_thread(self):
        """
            Automated Test For Create New Thread In Forums
        :return:
        """
        print("\n" + str(test_cases('t_5')))

        self.assertIn('Threads Created SuccessFully', CanonizerForumsPage(self.driver).forums_create_new_thread())

    # Thread 6
    def test_forums_threads_have_pagination(self):
        """
            Automated Test For Pagination of Forums

            Note:- This test will only be valid for forums having more than 10 threads.
                   If any forum doesn't have sufficient threads then it will fail.
                   Make Sure to chose forums to load having more than 10 threads.
        :return:
        """
        print("\n" + str(test_cases('t_6')))

        self.assertTrue(CanonizerForumsPage(self.driver).forums_threads_have_pagination())

    # Thread 7
    def test_forums_authenticated_user_post_reply_to_threads(self):
        """
            Automated Test for post reply on thread
        :return:
        """
        print("\n" + str(test_cases('t_7')))

        self.login_to_canonizer_app()

        self.assertTrue(
            CanonizerForumsPage(self.driver).forums_authenticated_user_can_reply_to_threads(DEFAULT_TESTING_THREAD_PATH)
        )

    # Thread 8
    def test_forums_guest_user_cannot_post_reply_to_thread(self):
        """
            Automated Test Case For Guest User Can't Reply To Thread
        :return:
        """
        print("\n" + str(test_cases('t_8')))

        self.assertFalse(
            CanonizerForumsPage(self.driver).forums_guest_user_cannot_reply_to_threads(DEFAULT_TESTING_THREAD_PATH))

    # Thread 9
    def test_forums_threads_must_have_title_field(self):
        """
            Automated Test Case To check if the thread has title
        :return:
        """
        print("\n" + str(test_cases('t_9')))

        self.login_to_canonizer_app()

        self.assertTrue(
            CanonizerForumsPage(self.driver).forums_threads_must_have_title_field(DEFAULT_TESTING_THREAD_PATH))

    # Thread 10
    def test_forums_thread_title_cannot_be_left_blank(self):
        """
            Automated Test Case to raise error if user submit empty thread.
        :return:
        """

        print("\n" + str(test_cases('t_10')))

        self.login_to_canonizer_app()

        self.assertIn("The title field is required.", CanonizerForumsPage(
            self.driver).forums_thread_title_cannot_be_left_blank(DEFAULT_TESTING_THREAD_PATH))

    # Thread 11
    def test_forums_user_can_read_all_post_assosiated_with_thread(self):
        """

        :return:
        """
        print("\n" + str(test_cases('t_11')))

        self.assertTrue(
            CanonizerForumsPage(self.driver).forums_user_can_read_all_post_assosiated_with_thread(
                DEFAULT_TESTING_THREAD_PATH))

    # Thread 12
    def test_forums_create_thread_page_has_nick_name_field(self):
        """
            Automated test case to check if the thread has nickname field
        :return:
        """
        print("\n" + str(test_cases('t_13')))

        self.login_to_canonizer_app()
        self.assertTrue(
            CanonizerForumsPage(self.driver).forums_create_thread_page_has_nick_name_field(DEFAULT_TESTING_THREAD_PATH))

    def test_forums_post_to_thread_has_nick_name_field(self):
        """
            Automated test case to check of post has nick name
        :return:
        """

        print("\n" + str(test_cases('t_11')))

        self.login_to_canonizer_app()
        self.assertTrue(
            CanonizerForumsPage(self.driver).forums_post_to_thread_has_nick_name_field(DEFAULT_TESTING_THREAD_PATH))

    def test_forum_thread_title_marked_with_asterisk(self):
        """
            Automated Test Case to check if the thread title (Mandatory) is marked with asterick
        :return:
        """

        self.login_to_canonizer_app()
        self.assertTrue(
            CanonizerForumsPage(self.driver).forum_thread_title_marked_with_asterisk(DEFAULT_TESTING_THREAD_PATH))

    def test_forum_thread_nick_name_marked_with_asterisk(self):
        """
            Automated Test Case to check if the thread nick name field (Mandatory) is marked with asterick
        :return:
        """

        self.login_to_canonizer_app()
        self.assertTrue(
            CanonizerForumsPage(self.driver).forum_thread_nick_name_marked_with_asterisk(DEFAULT_TESTING_THREAD_PATH))

    def tearDown(self):
        self.driver.close()


if __name__ == "__main__":
    suite = unittest.TestLoader().loadTestsFromTestCase(TestPages)
    unittest.TextTestRunner(verbosity=2).run(suite)