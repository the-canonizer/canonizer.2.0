from CanonizerBase import Page
from Identifiers import RegistrationPageIdentifiers
from selenium.webdriver.common.keys import Keys
from selenium.webdriver import ActionChains


class CanonizerRegisterPage(Page):
    """
    Class Name: CanonizerRegisterPage

    """

    def click_register_button(self):
        """
        -> Hover the control towards the register button. Identifiers are loaded from Identifiers Class
        -> Find the register button and Click on it.

        :return:
            Return the control to the main Program
        """

        self.hover(*RegistrationPageIdentifiers.REGISTER)
        self.find_element(*RegistrationPageIdentifiers.REGISTER).click()
        return CanonizerRegisterPage(self.driver)

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

    def enter_captcha(self, captcha):
        self.find_element(*RegistrationPageIdentifiers.CAPTCHA).send_keys(captcha)

    def click_create_account_button(self):
        """
        This function clicks the Create Account Button
        :return:
        """
        self.find_element(*RegistrationPageIdentifiers.CREATE_ACCOUNT).click()

    def register(self, firstname, middlename, lastname, email, password, confirmpassword, captcha):
        self.enter_first_name(firstname)
        self.enter_middle_name(middlename)
        self.enter_last_name(lastname)
        self.enter_email(email)
        self.enter_password(password)
        self.enter_confirm_password(confirmpassword)
        self.enter_captcha(captcha)
        self.click_create_account_button()

    def registration_with_blank_first_name(self, middlename, lastname, email, password, confirmpassword, captcha):
        self.register('', middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text

    def registration_with_blank_last_name(self, firstname, middlename , email, password, confirmpassword, captcha):
        self.register(firstname, middlename, '', email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_LAST_NAME).text

    def registration_with_blank_email(self, firstname, middlename , lastname, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, '', password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_EMAIL).text

    def registration_with_blank_password(self, firstname, middlename , lastname, user, captcha):
        self.register(firstname, middlename, lastname, user, '', '', captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text

    def registration_with_invalid_password_length(self, firstname, middlename, lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text

    def registration_with_different_confirmation_password(self, firstname, middlename, lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text

    def registration_should_have_login_option_for_existing_users(self):
        return self.find_element(*RegistrationPageIdentifiers.LOGINOPTION).text

    def register_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Register Page Marked with *
        First Name, Last Name, Email, Password, Confirm Password are Mandatory Fields

        :return: the element value
        """
        return \
            self.find_element(*RegistrationPageIdentifiers.FIRST_NAME_ASTRK) and \
            self.find_element(*RegistrationPageIdentifiers.LAST_NAME_ASTRK) and \
            self.find_element(*RegistrationPageIdentifiers.EMAIL_ASTRK) and \
            self.find_element(*RegistrationPageIdentifiers.PASSWORD_ASTRK) and \
            self.find_element(*RegistrationPageIdentifiers.CNFM_PSSWD_ASTRK)

    def registration_with_duplicate_email(self, firstname, middlename , lastname, email, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_DUPLICATE_EMAIL).text

    def registration_with_blank_spaces_first_name(self, firstname, middlename, lastname, email, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text

    def check_login_page_open_click_login_here_link(self):

        self.hover(*RegistrationPageIdentifiers.LOGINOPTION)
        self.find_element(*RegistrationPageIdentifiers.LOGINOPTION).click()
        return CanonizerRegisterPage(self.driver)

    def registration_with_blank_captcha(self, firstname, middlename, lastname, email, password, confirmpassword):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, '')
        return self.find_element(*RegistrationPageIdentifiers.ERROR_CAPTCHA).text

    def registration_with_invalid_first_name(self, firstname, middlename, lastname, email, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text

    def registration_with_invalid_last_name(self, firstname,middlename, lastname, email, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_LAST_NAME).text

    def registration_with_invalid_middle_name(self, firstname,middlename, lastname, email, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_MIDDLE_NAME).text

    def registration_with_invalid_captcha(self, firstname,middlename, lastname, email, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, email, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_CAPTCHA).text



