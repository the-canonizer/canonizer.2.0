from CanonizerBase import Page
from Identifiers import ForgotPasswordIdentifiers


class CanonizerForgotPasswordPage(Page):

    def click_forgot_password_page_button(self):
        """
        This function is to click on the login button

        -> Hover to the login button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*ForgotPasswordIdentifiers.FORGOT_PASSWORD)
        self.find_element(*ForgotPasswordIdentifiers.FORGOT_PASSWORD).click()
        return CanonizerForgotPasswordPage(self.driver)

    def enter_email(self, email):
        """
        "Enter User Email to the Email Box."

        Args:
            :param user: Email ID of the User
        :return:
            SEND_KEYS is equivalent to entering keys using keyboard. And control return to the calling program.
        """
        self.find_element(*ForgotPasswordIdentifiers.EMAIL).send_keys(email)

    def click_submit_button(self):
        """
        This function verify if the login page loads properly
        :return:
            Once the page is loaded, return result to the main program.
        """
        self.find_element(*ForgotPasswordIdentifiers.SUBMIT).click()

    def submit(self, email):
        """
        This function is to click the login button and return result to the main program.
        Args:
            :param user: Email ID of the User
            :param password: Password of the User
        :return:
            After Entering the Username and Password, function clicks on the login button and returns the control.
        """
        self.enter_email(email)
        self.click_submit_button()

    def forgot_password_with_blank_email(self):
        self.submit('')
        return self.find_element(*ForgotPasswordIdentifiers.ERROR_MESSAGE_EMAIL).text

    def forgot_password_with_invalid_email(self, email):
        """
        This function is part of test case (test_login_with_invalid_user) and it verifies using invalid username and
        password, application does not take user to the main page.

        Args:
            :param user: Email ID of the User
            :param password: Password of the User
        :return:
            Return the invalid login result to the main program
        """
        self.submit(email)
        return self.find_element(*ForgotPasswordIdentifiers.ERROR_INVALID_EMAIL).text

    def forgot_password_with_valid_email(self, email):
        """
        This function is a part of test case, test_login_with_valid_user and it verifies using valid username and
        password, application works properly and take the user to the home page.

        Args:
            :param user: Email ID of the User
            :param password: Password of User
        :return:
            Retrun the result to the main program
        """
        self.submit(email)
        return self

    def forgot_password_page_mandatory_fields_are_marked_with_astrick(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """
        return \
            self.find_element(*ForgotPasswordIdentifiers.EMAIL_ASTRK)
