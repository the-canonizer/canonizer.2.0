from selenium.webdriver.common.keys import Keys
from CanonizerBase import Page
from Identifiers import *


#
# Depends on the page functionality we can have more functions for new classes
#

##########################
#  Tests For Main Page   #
##########################

class CanonizerMainPage(Page):
    """
    Class Name: CanonizerMainPage
    Description: To load the main page and to navigate or load load pages for other URLs.

    Attributes: None
    """
    def check_home_page_loaded(self):
        """ To check if the Canonizer Home page loads properly"""

        return True if self.find_element(*HomePageIdentifiers.BODY) else False

    def check_load_all_topic_text(self):
        """
        Verify the text to load all the Topics should be "Load All Topics"

        :return:
            "Load All Topics" String to the main program
        """
        return self.find_element(*HomePageIdentifiers.LOADALLTOPICS).text

    def click_register_button(self):
        """
        -> Hover the control towards the register button. Identifiers are loaded from Identifiers Class
        -> Find the register button and Click on it.

        :return:
            Return the control to the main Program
        """

        self.hover(*HomePageIdentifiers.REGISTER)
        self.find_element(*HomePageIdentifiers.REGISTER).click()
        return CanonizerRegisterPage(self.driver)

    def click_login_button(self):
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

    def click_what_is_canonizer_page_link(self):
        """
        This Function is to verify if the canonizer main page loads properly
        :return:
            Return the result to the main page.
        """
        self.hover(*HomePageIdentifiers.WHATISCANONIZER)
        self.find_element(*HomePageIdentifiers.WHATISCANONIZER).click()
        return CanonizerHomePage(self.driver)

##########################
#  Tests For Login Page  #
##########################

class CanonizerLoginPage(Page):
    """
    Class Name : CanonizerLoginPage
    Description : Test the functionality of the Login Page.

    Attributes: None
    """

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
        return CanonizerHomePage(self.driver)

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


class CanonizerHomePage(Page):
    """
    Class Name: CanonizerHomePage
    Description: Verify test cases for Canonizer Home Page.

    Attributes: None
    """
    def check_what_is_canonizer_page_loaded(self):
        """
        This function verifies if the canonizer home page loads properly.
        :return:
        """
        return True if self.find_element(*HomePageIdentifiers.WHATISCANONIZERHEADING) else False


#################################
#  Tests For Registration Page  #
#################################

class CanonizerRegisterPage(Page):
    def enter_first_name(self, firstname):
        self.find_element(*RegistrationPageIdentifiers.FIRST_NAME).send_keys(firstname)

    def enter_middle_name(self, middlename):
        self.find_element(*RegistrationPageIdentifiers.MIDDLE_NAME).send_keys(middlename)

    def enter_last_name(self, lastname):
        self.find_element(*RegistrationPageIdentifiers.LAST_NAME).send_keys(lastname)

    def enter_email(self, user):
        self.find_element(*RegistrationPageIdentifiers.EMAIL).send_keys(user)

    def enter_password(self, password):
        self.find_element(*RegistrationPageIdentifiers.PASSWORD).send_keys(password)

    def enter_confirm_password(self, confirmpassword):
        self.find_element(*RegistrationPageIdentifiers.CONFIRM_PASSWORD).send_keys(confirmpassword)

    def click_create_account_button(self):
        self.find_element(*RegistrationPageIdentifiers.CREATE_ACCOUNT).click()

    def register(self, firstname, lastname, email, password, confirmpassword):
        self.enter_first_name(firstname)
        self.enter_last_name(lastname)
        self.enter_email(email)
        self.enter_password(password)
        self.enter_confirm_password(confirmpassword)
        self.click_create_account_button()

    def registration_with_blank_first_name(self, lastname, email, password, confirmpassword):
        self.register('', lastname, email, password, confirmpassword)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text

    def registration_with_blank_last_name(self, firstname, email, password, confirmpassword):
        self.register(firstname, '', email, password, confirmpassword)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_LAST_NAME).text

    def registration_with_blank_email(self, firstname, lastname, password, confirmpassword):
        self.register(firstname, lastname,'', password, confirmpassword)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_EMAIL).text

    def registration_with_blank_password(self, firstname, lastname, user):
        self.register(firstname, lastname, user, '', '')
        return self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text

    def registration_with_invalid_password_length(self, firstname, lastname, user, password, confirmpassword):
        self.register(firstname, lastname, user, password, confirmpassword)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text

    def registration_with_different_confirmation_password(self, firstname, lastname, user, password, confirmpassword):
        self.register(firstname, lastname, user, password, confirmpassword)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text

    def registration_should_have_login_option_for_existing_users(self):
        return self.find_element(*RegistrationPageIdentifiers.LOGINOPTION).text


class WhatIsCanonizerPage(Page):
    def join_or_support_camp_without_user_registration(self):
        self.find_element(*WhatIsCanonizerPageIdentifiers.JOINORSUPPORTCAMP)
