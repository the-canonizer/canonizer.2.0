from CanonizerBase import Page
from Identifiers import LoginPageIdentifiers, HomePageIdentifiers, ForgotPasswordIdentifiers, \
    LoginOTPVerificationIdentifiers


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
        title = self.find_element(*HomePageIdentifiers.LOGIN_TITLE).text
        if title == 'Log in':
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
        return CanonizerLoginPage(self.driver)

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
        error = self.find_element(*LoginPageIdentifiers.ERROR_MESSAGE).text
        if error == "These credentials do not match our records.":
            return CanonizerLoginPage(self.driver)

    def login_page_should_have_register_option_for_new_users(self):
        """
        This function checks of the login page is loaded, it should have option to register for new users.
        :return:
            Return the result to the main program.
        """
        result = self.find_element(*LoginPageIdentifiers.SIGNUPNOW).text
        if result == "Sign up Now":
            return CanonizerLoginPage(self.driver)

    def login_with_blank_email(self, password):
        self.login('', password)
        error = self.find_element(*LoginPageIdentifiers.ERROR_EMAIL).text
        if error == "The Email/Phone Number field is required.":
            return CanonizerLoginPage(self.driver)

    def login_with_blank_password(self, email):
        self.login(email, '')
        error = self.find_element(*LoginPageIdentifiers.ERROR_BLANK_PASSWORD).text
        if error == "The password is required.":
            return CanonizerLoginPage(self.driver)

    def login_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """
        return \
            self.find_element(*LoginPageIdentifiers.EMAIL_ASTRK) and \
            self.find_element(*LoginPageIdentifiers.PASSWORD_ASTRK)

    def login_should_have_forgot_password_link(self):
        text = self.find_element(*LoginPageIdentifiers.FORGOTPASSWORD).text
        if text == 'Forgot Password':
            return CanonizerLoginPage(self.driver)

    def click_request_otp_button(self):
        self.find_element(*LoginPageIdentifiers.REQUEST_OTP).click()

    def request_otp(self, user):
        self.enter_email(user)
        self.click_request_otp_button()

    def request_otp_with_valid_user_email(self, user):
        self.request_otp(user)
        return self

    def request_otp_with_invalid_user_email(self, user):
        self.request_otp(user)
        error = self.find_element(*LoginPageIdentifiers.ERROR_MESSAGE).text
        if error == 'User does not exists.':
            return CanonizerLoginPage(self.driver)

    def request_otp_with_valid_user_phone_number(self, phone_number):
        self.request_otp(phone_number)
        return self

    def request_otp_with_invalid_user_phone_number(self, phone_number):
        self.request_otp(phone_number)
        error = self.find_element(*LoginPageIdentifiers.ERROR_MESSAGE).text
        if error == 'User does not exists.':
            return CanonizerLoginPage(self.driver)

    def request_otp_with_unverified_user_phone_number(self, phone_number):
        self.request_otp(phone_number)
        error = self.find_element(*LoginPageIdentifiers.ERROR_MESSAGE).text
        if error == 'User mobile number is not verified.':
            return CanonizerLoginPage(self.driver)

    def request_otp_with_blank_email_or_phone_number(self):
        self.request_otp('')
        error =  self.find_element(*LoginPageIdentifiers.ERROR_EMAIL).text
        if error == 'The Email/Phone Number field is required.':
            return CanonizerLoginPage(self.driver)

    def enter_otp(self, otp):
        self.find_element(*LoginOTPVerificationIdentifiers.OTP).send_keys(otp)

    def click_submit_otp_button(self):
        self.find_element(*LoginOTPVerificationIdentifiers.SUBMIT).click()

    def click_submit_otp(self, otp):
        self.enter_otp(otp)
        self.click_submit_otp_button()

    def login_with_valid_otp(self, otp):

        self.click_submit_otp(otp)
        return self

    def login_with_invalid_otp(self, otp):
        self.click_submit_otp(otp)
        error = self.find_element(*LoginOTPVerificationIdentifiers.ERROR_INVALID_OTP).text
        if error == 'Error! Incorrect One Time Verification Code Entered':
            return CanonizerLoginPage(self.driver)

    def login_otp_verification_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *

        :return: the element value
        """
        return \
            self.find_element(*LoginOTPVerificationIdentifiers.OTP_ASTRK)

    def login_with_blank_otp(self):
        self.click_submit_otp('')
        error = self.find_element(*LoginOTPVerificationIdentifiers.ERROR_OTP).text
        if error == "Please enter One Time Verification Code":
            return CanonizerLoginPage(self.driver)

    def check_register_page_open_click_signup_now_link(self):

        self.hover(*LoginPageIdentifiers.SIGNUPNOW)
        self.find_element(*LoginPageIdentifiers.SIGNUPNOW).click()
        heading = self.find_element(*LoginPageIdentifiers.HEADING).text
        if heading == 'Create Account':
            return CanonizerLoginPage(self.driver)
