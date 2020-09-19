from CanonizerBase import Page
from Identifiers import BrowsePageIdentifiers
from selenium.webdriver.support.ui import Select
from selenium import webdriver


class CanonizerBrowsePage(Page):

    def click_browse_page_button(self):
        """
        This function is to click on the Browse link
        -> Hover to the Browse link
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        self.hover(*BrowsePageIdentifiers.BROWSE)
        self.find_element(*BrowsePageIdentifiers.BROWSE).click()
        return CanonizerBrowsePage(self.driver)

    def click_only_my_topics_button(self):
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        return CanonizerBrowsePage(self.driver)

    def select_dropdown_value(self):
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        self.find_element(*BrowsePageIdentifiers.NAMESPACE).click()
        return CanonizerBrowsePage(self.driver)

    def select_by_value_general(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("1")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_general_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_general()
        self.click_only_my_topics_button()
        return CanonizerBrowsePage(self.driver)

    def select_by_value_corporations(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("2")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_crypto_currency(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("3")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_family(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("4")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_family_jesperson_oscar_f(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("5")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_occupy_wall_street(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("6")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("7")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_canonizer(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("8")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_canonizer_help(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("9")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_mta(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("10")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_tv07(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("11")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_wta(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("12")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_personal_attributes(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("13")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_personal_reputations(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("14")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_professional_services(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("15")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_sandbox(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("16")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_terminology(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("17")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_www(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("18")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_all(self):
        self.hover(*BrowsePageIdentifiers.ALL)
        self.find_element(*BrowsePageIdentifiers.ALL).click()
        return CanonizerBrowsePage(self.driver)

    def select_by_value_all_default(self):
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        self.find_element(*BrowsePageIdentifiers.NAMESPACE).click()
        return CanonizerBrowsePage(self.driver)

    def select_by_value_crypto_currency_ethereum(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("21")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_void(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("22")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_mormon_canon_project(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("24")
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_united_utah_party(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("26")
        return CanonizerBrowsePage(self.driver)














