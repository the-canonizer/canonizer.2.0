from CanonizerBase import Page
from Identifiers import LoginPageIdentifiers, HomePageIdentifiers
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import WebDriverWait


class CanonizerLoginPage(Page):
    """
    Class Name : CanonizerLoginPage
    Description : Test the functionality of the Login and Logout Page
                  Forgot Password Functionality also needs to be added on this Page.

    Attributes: None
    """

    def click_login_page_button(self):
        """
        This function is to click on the login button

        -> Hover to the login button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*HomePageIdentifiers.LOGIN)
        self.find_element(*HomePageIdentifiers.LOGIN).click()
        return CanonizerLoginPage(self.driver)

    def enter_email(self, user):
        """
        "Enter User Email to the Email Box."

        Args:
            :param user: Email ID of the User
        :return:
            SEND_KEYS is equivalent to entering keys using keyboard. And control return to the calling program.
        """
        self.find_element(*LoginPageIdentifiers.EMAIL).send_keys(user)

    def enter_password(self, password):
        """
        This function is to entering the user password to the password field and return control.

        Args:
            :param password: Password of the User
        :return:
            After entering the password to the Password field. Function return control.
        """
        self.find_element(*LoginPageIdentifiers.PASSWORD).send_keys(password)

    def click_login_button(self):
        """
        This function verify if the login page loads properly
        :return:
            Once the page is loaded, return result to the main program.
        """
        self.find_element(*LoginPageIdentifiers.SUBMIT).click()

    def click_logout_button(self):
        """
            Function is to logout from the canonizer application
        :return:
        """
        self.find_element(*LoginPageIdentifiers.PROFILE).click()

        self.find_element(*LoginPageIdentifiers.LOGOUT_OPTION).click()

    def login(self, user, password):
        """
        This function is to click the login button and return result to the main program.

        Args:
            :param user: Email ID of the User
            :param password: Password of the User
        :return:
            After Entering the Username and Password, function clicks on the login button and returns the control.
        """
        self.enter_email(user)
        self.enter_password(password)
        self.click_login_button()

    def login_with_valid_user(self, user, password):
        """
        This function is a part of test case, test_login_with_valid_user and it verifies using valid username and
        password, application works properly and take the user to the home page.

        Args:
            :param user: Email ID of the User
            :param password: Password of User
        :return:
            Retrun the result to the main program
        """
        self.login(user, password)
        return self

    def login_with_invalid_user(self, user, password):
        """
        This function is part of test case (test_login_with_invalid_user) and it verifies using invalid username and
        password, application does not take user to the main page.

        Args:
            :param user: Email ID of the User
            :param password: Password of the User
        :return:
            Return the invalid login result to the main program
        """
        self.login(user, password)
        return self.find_element(*LoginPageIdentifiers.ERROR_MESSAGE).text

    def login_page_should_have_register_option_for_new_users(self):
        """
        This function checks of the login page is loaded, it should have option to register for new users.
        :return:
            Return the result to the main program.
        """
        return self.find_element(*LoginPageIdentifiers.SIGNUPNOW).text
