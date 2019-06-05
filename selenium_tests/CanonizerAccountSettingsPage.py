from CanonizerBase import Page
from Identifiers import AccountSettingsIdentifiers, LogoutIdentifiers, AccountSettingsChangePasswordIdentifiers, AddAndManageNickNamesIdentifiers, AccountSettingsManageProfileInfoIdentifiers, MySupportsPageIdentifiers


class CanonizerAccountSettingsPage(Page):

    def click_account_settings_page_button(self):
        """
        This function is to click on the Account Settings button

        -> Hover to the Account Settings button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*AccountSettingsIdentifiers.ACCOUNT_SETTINGS)
        self.find_element(*AccountSettingsIdentifiers.ACCOUNT_SETTINGS).click()
        return CanonizerAccountSettingsPage(self.driver)

    def click_username_link_button(self):
        """
        This function is to click on the username link

        -> Hover to the username
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*LogoutIdentifiers.USERNAME)
        self.find_element(*LogoutIdentifiers.USERNAME).click()
        return CanonizerAccountSettingsPage(self.driver)

    def click_account_settings_manage_profile_info_page_button(self):
        """
        This function is to click on the Account Settings ->Manage Profile Info tab

        -> Hover to the Account Settings ->Manage Profile Info tab
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*AccountSettingsIdentifiers.MANAGE_PROFILE_INFO)
        self.find_element(*AccountSettingsIdentifiers.MANAGE_PROFILE_INFO).click()
        return CanonizerAccountSettingsPage(self.driver)

    def click_account_settings_add_manage_nick_names_page_button(self):
        """
        This function is to click on the Account Settings ->Add & Manage Nick Names tab

        -> Hover to the Account Settings ->Add & Manage Nick Names tab
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*AccountSettingsIdentifiers.ADD_MANAGE_NICK_NAMES)
        self.find_element(*AccountSettingsIdentifiers.ADD_MANAGE_NICK_NAMES).click()
        return CanonizerAccountSettingsPage(self.driver)

    def click_account_settings_my_supports_page_button(self):
        """
        This function is to click on the Account Settings ->My Supports tab

        -> Hover to the Account Settings ->My Supports tab
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*AccountSettingsIdentifiers.MY_SUPPORTS)
        self.find_element(*AccountSettingsIdentifiers.MY_SUPPORTS).click()
        return CanonizerAccountSettingsPage(self.driver)

    def click_account_settings_default_algorithm_page_button(self):
        """
        This function is to click on the Account Settings ->Default Algorithm tab
        -> Hover to the Account Settings button
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*AccountSettingsIdentifiers.DEFAULT_ALGORITHM)
        self.find_element(*AccountSettingsIdentifiers.DEFAULT_ALGORITHM).click()
        return CanonizerAccountSettingsPage(self.driver)

    def click_account_settings_change_password_page_button(self):
        """
        This function is to click on the Account Settings ->Change Password tab

        -> Hover to the Account Settings ->Change Password tab
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*AccountSettingsIdentifiers.CHANGE_PASSWORD)
        self.find_element(*AccountSettingsIdentifiers.CHANGE_PASSWORD).click()
        return CanonizerAccountSettingsPage(self.driver)


class CanonizerAccountSettingsChangePasswordPage(Page):

    def enter_current_password(self, current_password):
        self.find_element(*AccountSettingsChangePasswordIdentifiers.CURRENT_PASSWORD).send_keys(current_password)

    def enter_new_password(self, new_password):
        self.find_element(*AccountSettingsChangePasswordIdentifiers.NEW_PASSWORD).send_keys(new_password)

    def enter_confirm_password(self, confirm_password):
        self.find_element(*AccountSettingsChangePasswordIdentifiers.CONFIRM_PASSWORD).send_keys(confirm_password)

    def click_save_button(self):
        """
        This function clicks the Save Button
        :return:
        """
        self.find_element(*AccountSettingsChangePasswordIdentifiers.SAVE).click()

    def save(self, current_password, new_password, confirm_password):
        self.enter_current_password(current_password)
        self.enter_new_password(new_password)
        self.enter_confirm_password(confirm_password)
        self.click_save_button()

    def save_with_blank_current_password(self, new_password, confirm_password):
        self.save('', new_password, confirm_password)
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.ERROR_CURRENT_PASSWORD).text

    def save_with_blank_new_password(self, current_password, confirm_password):
        self.save(current_password, '',  confirm_password)
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.ERROR_NEW_PASSWORD).text

    def save_with_blank_confirm_password(self, current_password, new_password):
        self.save(current_password, new_password, '')
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.ERROR_CONFIRM_PASSWORD).text

    def change_password_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Change Password Page Marked with *
        Current Password, New Password ,Confirm Password are Mandatory Fields

        :return: the element value
        """
        return \
            self.find_element(*AccountSettingsChangePasswordIdentifiers.CURRENT_PASSWORD_ASTRK) and \
            self.find_element(*AccountSettingsChangePasswordIdentifiers.NEW_PASSWORD_ASTRK) and \
            self.find_element(*AccountSettingsChangePasswordIdentifiers.CONFIRM_PASSWORD_ASTRK)

    def save_with_invalid_current_password(self, current_password, new_password, confirm_password):
        self.save(current_password, new_password, confirm_password)
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.INVALID_CURRENT_PASSWORD).text

    def save_with_mismatch_new_confirm_password(self, current_password, new_password, confirm_password):
        self.save(current_password, new_password, confirm_password)
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.PASSWORD_MISMATCH).text

    def save_with_same_new_and_current_password(self, current_password, new_password, confirm_password):
        self.save(current_password, new_password, confirm_password)
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.CURRENT_NEW_PASSWORD_MUST_DIFF).text

    def save_with_invalid_new_password(self, current_password, new_password, confirm_password):
        self.save(current_password, new_password, confirm_password)
        return self.find_element(*AccountSettingsChangePasswordIdentifiers.INVALID_NEW_PASSWORD).text


class CanonizerAccountSettingsNickNamesPage(Page):

    def enter_nick_name(self, nick_name):
        self.find_element(*AddAndManageNickNamesIdentifiers.NICK_NAME).send_keys(nick_name)

    def click_create_button(self):
        """
        This function clicks the Create Button
        :return:
        """
        self.find_element(*AddAndManageNickNamesIdentifiers.CREATE).click()

    def create(self, nick_name):
        self.enter_nick_name(nick_name)
        self.click_create_button()

    def nick_names_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Nick Names Page Marked with *
        Nick Name is Mandatory Field

        :return: the element value
        """
        return \
            self.find_element(*AddAndManageNickNamesIdentifiers.NICK_NAME_ASTRK)

    def create_with_blank_nick_name(self):
        self.create('')
        return self.find_element(*AddAndManageNickNamesIdentifiers.ERROR_NICK_NAME).text

    def create_with_duplicate_nick_name(self, nick_name):
        self.create(nick_name)
        return self.find_element(*AddAndManageNickNamesIdentifiers.ERROR_NICK_NAME).text

    def create_with_max_nick_name(self, nick_name):
        self.create(nick_name)
        return self.find_element(*AddAndManageNickNamesIdentifiers.ERROR_NICK_NAME).text

    def create_with_valid_nick_name(self, nick_name):
        self.create(nick_name)
        return self


class AccountSettingsMySupportsPage(Page):
    def check_topic_page_from_my_supports_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*MySupportsPageIdentifiers.TOPIC_NAME)
        self.find_element(*MySupportsPageIdentifiers.TOPIC_NAME).click()
        return AccountSettingsMySupportsPage(self.driver)

    def check_camp_page_from_my_supports_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*MySupportsPageIdentifiers.CAMP_NAME)
        self.find_element(*MySupportsPageIdentifiers.CAMP_NAME).click()
        return AccountSettingsMySupportsPage(self.driver)


class AccountSettingsManageProfileInfoPage(Page):

    def enter_first_name(self, first_name):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.FIRST_NAME).send_keys(first_name)

    def enter_middle_name(self, middle_name):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.MIDDLE_NAME).send_keys(middle_name)

    def enter_last_name(self, last_name):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.LAST_NAME).send_keys(last_name)

    def enter_email(self, email):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.EMAIL).send_keys(email)

    def enter_language(self, language):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.LANGUAGE).send_keys(language)

    def enter_dob(self, dob):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.DOB).send_keys(dob)

    def enter_address_line1(self, address_line1):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.ADDRESS_LINE1).send_keys(address_line1)

    def enter_address_line2(self, address_line2):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.ADDRESS_LINE2).send_keys(address_line2)

    def enter_city(self, city):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.CITY).send_keys(city)

    def enter_state(self, state):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.STATE).send_keys(state)

    def enter_country(self, country):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.COUNTRY).send_keys(country)

    def enter_zip_code(self, zip_code):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.ZIP_CODE).send_keys(zip_code)

    def enter_phone_number(self, phone_number):
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.PHONE_NUMBER).send_keys(phone_number)

    def click_update_button(self):
        """
        This function clicks the Update Button
        :return:
        """
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.UPDATE).click()

    def update(self, first_name, middle_name, last_name, email, language, dob, address_line1, address_line2, city, state, country, zip_code):
        self.enter_first_name(first_name)
        self.enter_middle_name(middle_name)
        self.enter_last_name(last_name)
        self.enter_email(email)
        self.enter_language(language)
        self.enter_dob(dob)
        self.enter_address_line1(address_line1)
        self.enter_address_line2(address_line2)
        self.enter_city(city)
        self.enter_state(state)
        self.enter_country(country)
        self.enter_zip_code(zip_code)
        self.click_update_button()

    def click_verify_button(self):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.VERIFY).click()

    def verify_phone_number(self, phone_number):
        self.enter_phone_number(phone_number)
        self.click_verify_button()

    def manage_profile_info_page_mandatory_fields_are_marked_with_asterisk(self):
        """
        This Function checks, if Mandatory fields on Manage Profile Info Page Marked with *
        Nick Name is Mandatory Field

        :return: the element value
        """
        return \
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.FIRST_NAME_ASTRK) and \
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.LAST_NAME_ASTRK) and \
            self.find_element(*AccountSettingsManageProfileInfoIdentifiers.COUNTRY_ASTRK)

    def update_profile_with_blank_first_name(self, middle_name, last_name, email, language, dob, address_line1, address_line2, city, state, country, zip_code):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.FIRST_NAME).clear()
        self.update('', middle_name, last_name, email, language, dob, address_line1, address_line2, city, state, country, zip_code)
        return self.find_element(*AccountSettingsManageProfileInfoIdentifiers.ERROR_FIRST_NAME).text

    def update_profile_with_blank_last_name(self, first_name, middle_name, email, language, dob, address_line1, address_line2, city, state, country, zip_code):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.LAST_NAME).clear()
        self.update(first_name, middle_name, '', email, language, dob, address_line1, address_line2, city, state, country, zip_code)
        return self.find_element(*AccountSettingsManageProfileInfoIdentifiers.ERROR_LAST_NAME).text

    def verify_phone_number_with_blank_phone_number(self):
        self.find_element(*AccountSettingsManageProfileInfoIdentifiers.PHONE_NUMBER).clear()
        self.verify_phone_number('')
        return self.find_element(*AccountSettingsManageProfileInfoIdentifiers.ERROR_PHONE_NUMBER).text







