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
        title = self.find_element(*RegistrationPageIdentifiers.TITLE).text
        if title == 'Create Account':
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

    def register(self, *args):
        self.enter_first_name(args[0])
        self.enter_middle_name(args[1])
        self.enter_last_name(args[2])
        self.enter_email(args[3])
        self.enter_password(args[4])
        self.enter_confirm_password(args[5])
        self.enter_captcha(args[6])
        self.click_create_account_button()

    def registration_with_blank_first_name(self, REG_LIST_3):
        self.register(REG_LIST_3[0], REG_LIST_3[1], REG_LIST_3[2], REG_LIST_3[3], REG_LIST_3[4], REG_LIST_3[5],
                      REG_LIST_3[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text
        if error == 'The first name field is required.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_blank_last_name(self, REG_LIST_4):
        self.register(REG_LIST_4[0], REG_LIST_4[1], REG_LIST_4[2], REG_LIST_4[3], REG_LIST_4[4], REG_LIST_4[5],
                      REG_LIST_4[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_LAST_NAME).text
        if error == 'The last name field is required.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_blank_email(self, REG_LIST_5):
        self.register(REG_LIST_5[0], REG_LIST_5[1], REG_LIST_5[2], REG_LIST_5[3], REG_LIST_5[4], REG_LIST_5[5],
                      REG_LIST_5[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_EMAIL).text
        if error == 'The email field is required.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_blank_password(self, REG_LIST_6):
        self.register(REG_LIST_6[0], REG_LIST_6[1], REG_LIST_6[2], REG_LIST_6[3], REG_LIST_6[4], REG_LIST_6[5],
                      REG_LIST_6[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text
        if error == 'The password field is required.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_invalid_password_length(self, REG_LIST_7):
        self.register(REG_LIST_7[0], REG_LIST_7[1], REG_LIST_7[2], REG_LIST_7[3], REG_LIST_7[4], REG_LIST_7[5],
                      REG_LIST_7[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_PASSWORD).text
        if error == 'Password must be at least 8 characters, including at least one digit, one lower case letter and one special character(@,# !,$..).':
            return CanonizerRegisterPage(self.driver)

    def registration_with_different_confirmation_password(self, REG_LIST_8):
        self.register(REG_LIST_8[0], REG_LIST_8[1], REG_LIST_8[2], REG_LIST_8[3], REG_LIST_8[4], REG_LIST_8[5],
                      REG_LIST_8[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_CONFIRMATION).text
        if error == 'The password confirmation does not match.':
            return CanonizerRegisterPage(self.driver)

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

    def registration_with_duplicate_email(self, REG_LIST_2):
        self.register(REG_LIST_2[0], REG_LIST_2[1], REG_LIST_2[2], REG_LIST_2[3], REG_LIST_2[4], REG_LIST_2[5],
                      REG_LIST_2[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_DUPLICATE_EMAIL).text
        if error == 'The email has already been taken.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_blank_spaces_first_name(self, REG_LIST_1):
        self.register(REG_LIST_1[0], REG_LIST_1[1], REG_LIST_1[2], REG_LIST_1[3], REG_LIST_1[4], REG_LIST_1[5],
                      REG_LIST_1[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text
        if error == 'The first name field is required.':
            return CanonizerRegisterPage(self.driver)

    def check_login_page_open_click_login_here_link(self):
        self.hover(*RegistrationPageIdentifiers.LOGINOPTION)
        self.find_element(*RegistrationPageIdentifiers.LOGINOPTION).click()

    def registration_with_blank_captcha(self, firstname, middlename, lastname, user, password, confirmpassword):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, '')
        return self.find_element(*RegistrationPageIdentifiers.ERROR_CAPTCHA).text

    def registration_with_invalid_first_name(self, firstname, middlename, lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text

    def registration_with_invalid_last_name(self, firstname,middlename, lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_LAST_NAME).text

    def registration_with_invalid_middle_name(self, firstname,middlename, lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_MIDDLE_NAME).text

    def registration_with_invalid_captcha(self, firstname, middlename, lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_CAPTCHA).text

    def registration_with_invalid_email(self, firstname, middlename , lastname, user, password, confirmpassword, captcha):
        self.register(firstname, middlename, lastname, user, password, confirmpassword, captcha)
        return self.find_element(*RegistrationPageIdentifiers.ERROR_EMAIL).text

        title = self.find_element(*RegistrationPageIdentifiers.TITLE).text
        if title == 'Log in':
            return CanonizerRegisterPage(self.driver)

    def registration_with_blank_captcha(self, REG_LIST_9):
        self.register(REG_LIST_9[0], REG_LIST_9[1], REG_LIST_9[2], REG_LIST_9[3], REG_LIST_9[4], REG_LIST_9[5],
                      REG_LIST_9[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_CAPTCHA).text
        if error == 'The captcha code field is required.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_invalid_first_name(self, REG_LIST_10):
        self.register(REG_LIST_10[0], REG_LIST_10[1], REG_LIST_10[2], REG_LIST_10[3], REG_LIST_10[4], REG_LIST_10[5],
                      REG_LIST_10[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_FIRST_NAME).text
        if error == 'The first name must be in alphabets and space only.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_invalid_last_name(self, REG_LIST_11):
        self.register(REG_LIST_11[0], REG_LIST_11[1], REG_LIST_11[2], REG_LIST_11[3], REG_LIST_11[4], REG_LIST_11[5],
                      REG_LIST_11[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_LAST_NAME).text
        if error == 'The last name must be in alphabets and space only.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_invalid_middle_name(self, REG_LIST_12):
        self.register(REG_LIST_12[0], REG_LIST_12[1], REG_LIST_12[2], REG_LIST_12[3], REG_LIST_12[4], REG_LIST_12[5],
                      REG_LIST_12[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_MIDDLE_NAME).text
        if error == 'The middle name must be in alphabets and space only.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_invalid_captcha(self, REG_LIST_13):
        self.register(REG_LIST_13[0], REG_LIST_13[1], REG_LIST_13[2], REG_LIST_13[3], REG_LIST_13[4], REG_LIST_13[5],
                      REG_LIST_13[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_CAPTCHA).text
        if error == 'CAPTCHA validation failed, please try again.':
            return CanonizerRegisterPage(self.driver)

    def registration_with_invalid_email(self, REG_LIST_14):
        self.register(REG_LIST_14[0], REG_LIST_14[1], REG_LIST_14[2], REG_LIST_14[3], REG_LIST_14[4], REG_LIST_14[5],
                      REG_LIST_14[6])
        error = self.find_element(*RegistrationPageIdentifiers.ERROR_EMAIL).text
        if error == 'The email must be a valid email address.':
            return CanonizerRegisterPage(self.driver)

    def verify_registration_placeholders(self):
        first_name = self.find_element(*RegistrationPageIdentifiers.FIRST_NAME)
        middle_name = self.find_element(*RegistrationPageIdentifiers.MIDDLE_NAME)
        last_name = self.find_element(*RegistrationPageIdentifiers.LAST_NAME)
        email = self.find_element(*RegistrationPageIdentifiers.EMAIL)
        password = self.find_element(*RegistrationPageIdentifiers.PASSWORD)
        confirm_password = self.find_element(*RegistrationPageIdentifiers.CONFIRM_PASSWORD)
        first_name_placeholder = first_name.get_attribute('placeholder')
        middle_name_placeholder = middle_name.get_attribute('placeholder')
        last_name_placeholder = last_name.get_attribute('placeholder')
        email_placeholder = email.get_attribute('placeholder')
        password_placeholder = password.get_attribute('placeholder')
        confirm_password_placeholder = confirm_password.get_attribute('placeholder')
        if first_name_placeholder == 'First Name' and middle_name_placeholder == 'Middle Name' \
                and last_name_placeholder == 'Last Name' and email_placeholder == 'Email' \
                and password_placeholder == 'Password' and confirm_password_placeholder == 'Confirm Password':
            return CanonizerRegisterPage(self.driver)

