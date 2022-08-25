import time

from CanonizerBase import Page
from Identifiers import ForgotPasswordIdentifiers


class CanonizerForgotPasswordPage(Page):

    def click_forgot_password_page_button(self):
        self.hover(*ForgotPasswordIdentifiers.FORGOT_PASSWORD)
        self.find_element(*ForgotPasswordIdentifiers.FORGOT_PASSWORD).click()
        title = self.find_element(*ForgotPasswordIdentifiers.TITLE).text
        if title == "Forgot Password":
            return CanonizerForgotPasswordPage(self.driver)
        else:
            print("Title does not match")

    def enter_email(self, email):
        self.find_element(*ForgotPasswordIdentifiers.EMAIL).send_keys(email)

    def click_submit_button(self):
        self.find_element(*ForgotPasswordIdentifiers.SUBMIT).click()

    def submit(self, email):
        self.enter_email(email)
        self.click_submit_button()

    def forgot_password_with_blank_email(self):
        self.submit('')
        error = self.find_element(*ForgotPasswordIdentifiers.ERROR_MESSAGE_EMAIL).text
        if error == 'The email field is required.':
            return CanonizerForgotPasswordPage(self.driver)

    def forgot_password_with_invalid_email(self, email):
        self.submit(email)
        error = self.find_element(*ForgotPasswordIdentifiers.ERROR_INVALID_EMAIL).text
        if error == 'Email not found in our record':
            return CanonizerForgotPasswordPage(self.driver)

    def forgot_password_with_invalid_email_format(self, email):
        self.submit(email)
        error = self.find_element(*ForgotPasswordIdentifiers.ERROR_INVALID_EMAIL).text
        if error == 'The email must be a valid email address.':
            return CanonizerForgotPasswordPage(self.driver)

    def forgot_password_with_valid_email(self, email):
        self.submit(email)
        confirmation_text = self.find_element(*ForgotPasswordIdentifiers.CONFIRMATION).text
        if confirmation_text == 'Confirmation':
            return CanonizerForgotPasswordPage(self.driver)

    def forgot_password_page_mandatory_fields_are_marked_with_asterisk(self):
        return \
            self.find_element(*ForgotPasswordIdentifiers.EMAIL_ASTRK)


