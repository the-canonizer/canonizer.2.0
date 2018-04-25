import unittest
from CanonizerHomePage import *
from CanonizerTestCases import test_cases
from Config import *
from selenium import webdriver


class TestPages(unittest.TestCase):

    def setUp(self):
        driver_location = DEFAULT_CHROME_DRIVER_LOCATION
        self.driver = webdriver.Chrome(driver_location)
        self.driver.get(DEFAULT_BASE_URL)

    def test_canonizer_home_page_load(self):
        print("\n" + str(test_cases(0)))
        page = CanonizerMainPage(self.driver)
        self.assertTrue(page.check_home_page_loaded())

    def test_canonizer_register_button(self):
        print("\n" + str(test_cases(1)))
        page = CanonizerMainPage(self.driver)
        registerPage = page.click_register_button()
        self.assertIn("/register", registerPage.get_url())

    def test_canonizer_login_button(self):
        print("\n" + str(test_cases(2)))
        page = CanonizerMainPage(self.driver)
        loginPage = page.click_login_button()
        self.assertIn("/login", loginPage.get_url())

    def test_canonizer_login_with_valid_user(self):
        print ("\n" + str(test_cases(3)))
        mainPage = CanonizerMainPage(self.driver)
        loginPage = mainPage.click_login_button()
        result = loginPage.login_with_valid_user(DEFAULT_USER, DEFAULT_PASS)
        self.assertIn("", result.get_url())

    def test_login_with_invalid_user(self):
        print ("\n" + str(test_cases(4)))
        mainPage = CanonizerMainPage(self.driver)
        loginPage = mainPage.click_login_button()
        result = loginPage.login_with_invalid_user(DEFAULT_INVALID_USER, DEFAULT_INVALID_PASSWORD)
        self.assertIn("These credentials do not match our records.", result)

    def test_registration_with_blank_first_name(self):
        print ("\n" + str(test_cases(5)))
        mainPage = CanonizerMainPage(self.driver)
        registrationPage = mainPage.click_register_button()

        result = registrationPage.registration_with_blank_first_name(
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            DEFAULT_PASS,
            DEFAULT_PASS
        )
        self.assertIn("The firstname field is required.", result)

    def test_registration_with_blank_last_name(self):
        print("\n" + str(test_cases(6)))
        mainPage = CanonizerMainPage(self.driver)
        registrationPage = mainPage.click_register_button()

        result = registrationPage.registration_with_blank_last_name(
            DEFAULT_FIRST_NAME,
            DEFAULT_USER,
            DEFAULT_PASS,
            DEFAULT_PASS
        )
        self.assertIn("The lastname field is required", result)

    def test_registration_with_blank_email(self):
        print ("\n" + str(test_cases(7)))
        mainPage = CanonizerMainPage(self.driver)
        registrationPage = mainPage.click_register_button()

        result = registrationPage.registration_with_blank_email(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_PASS,
            DEFAULT_PASS
        )
        self.assertIn("The email field is required", result)

    def test_registration_with_blank_password(self):
        print ("\n" + str(test_cases(8)))
        mainPage = CanonizerMainPage(self.driver)
        registrationPage = mainPage.click_register_button()

        result = registrationPage.registration_with_blank_password(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER
        )
        self.assertIn('The password field is required.', result)

    def test_registration_with_invalid_password_length(self):
        print ("\n" + str(test_cases(9)))
        mainPage = CanonizerMainPage(self.driver)
        registrationPage = mainPage.click_register_button()

        result = registrationPage.registration_with_invalid_password_length(
            DEFAULT_FIRST_NAME,
            DEFAULT_LAST_NAME,
            DEFAULT_USER,
            '12345',
            '12345'
        )
        self.assertIn('The password must be at least 6 characters.', result)

    def test_registration_with_different_confirmation_password(self):
        print ("\n" + str(test_cases(10)))
        mainPage = CanonizerMainPage(self.driver)
        registrationPage = mainPage.click_register_button()
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

    def tearDown(self):
        self.driver.close()


if __name__ == "__main__":
    suite = unittest.TestLoader().loadTestsFromTestCase(TestPages)
    unittest.TextTestRunner(verbosity=2).run(suite)