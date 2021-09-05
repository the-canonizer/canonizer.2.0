from CanonizerBase import Page
from Identifiers import BrowsePageIdentifiers
from selenium.webdriver.support.ui import Select
import time


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
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_general_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_general()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_corporations(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("2")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_corporations_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_corporations()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_crypto_currency(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("3")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_crypto_currency_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_crypto_currency()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_family(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("4")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_family_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_family()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_family_jesperson_oscar_f(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("5")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_family_jesperson_oscar_f_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_family_jesperson_oscar_f()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_occupy_wall_street(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("6")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_occupy_wall_street_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_occupy_wall_street()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("7")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_canonizer(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("8")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_canonizer_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations_canonizer()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_canonizer_help(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("9")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_canonizer_help_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations_canonizer_help()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_mta(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("10")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_mta_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations_mta()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_tv07(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("11")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_tv07_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations_tv07()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_wta(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("12")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_wta_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations_wta()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_personal_attributes(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("13")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_personal_attributes_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_personal_attributes()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_personal_reputations(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("14")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_personal_reputations_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_personal_reputations()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_professional_services(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("15")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_professional_services_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_professional_services()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_sandbox(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("16")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_sandbox_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_sandbox()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_terminology(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("17")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_terminology_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_terminology()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_www(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("18")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_www_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_www()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_all(self):
        self.hover(*BrowsePageIdentifiers.ALL)
        self.find_element(*BrowsePageIdentifiers.ALL).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_all_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_all()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_all_default(self):
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        self.find_element(*BrowsePageIdentifiers.NAMESPACE).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_crypto_currency_ethereum(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("21")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_crypto_currency_ethereum_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_crypto_currency_ethereum()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_void(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("22")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_void_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_void()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_mormon_canon_project(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("24")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_mormon_canon_project_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_mormon_canon_project()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_united_utah_party(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("25")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_organizations_united_utah_party_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_organizations_united_utah_party()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)














