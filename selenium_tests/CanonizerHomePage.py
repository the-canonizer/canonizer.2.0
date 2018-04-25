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
    def check_home_page_loaded(self):
        return True if self.find_element(*HomePageIdentifiers.BODY) else False

    def click_register_button(self):
        self.hover(*HomePageIdentifiers.REGISTER)
        self.find_element(*HomePageIdentifiers.REGISTER).click()
        return CanonizerRegisterPage(self.driver)

    def click_login_button(self):
        self.hover(*HomePageIdentifiers.LOGIN)
        self.find_element(*HomePageIdentifiers.LOGIN).click()
        return CanonizerLoginPage(self.driver)

    def click_what_is_canonizer_page_link(self):
        self.hover(*HomePageIdentifiers.WHATISCANONIZER)
        self.find_element(*HomePageIdentifiers.WHATISCANONIZER).click()
        return CanonizerHomePage(self.driver)

##########################
#  Tests For Login Page  #
##########################

class CanonizerLoginPage(Page):
    def enter_email(self, user):
        self.find_element(*LoginPageIdentifiers.EMAIL).send_keys(user)

    def enter_password(self, password):
        self.find_element(*LoginPageIdentifiers.PASSWORD).send_keys(password)

    def click_login_button(self):
        self.find_element(*LoginPageIdentifiers.SUBMIT).click()

    def login(self, user, password):
        self.enter_email(user)
        self.enter_password(password)
        self.click_login_button()

    def login_with_valid_user(self, user, password):
        self.login(user, password)
        return CanonizerHomePage(self.driver)

    def login_with_invalid_user(self, user, password):
        self.login(user, password)
        return self.find_element(*LoginPageIdentifiers.ERROR_MESSAGE).text


class CanonizerHomePage(Page):
    def check_what_is_canonizer_page_loaded(self):
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


class WhatIsCanonizerPage(Page):
    def join_or_support_camp_without_user_registration(self):
        self.find_element(*WhatIsCanonizerPageIdentifiers.JOINORSUPPORTCAMP)
