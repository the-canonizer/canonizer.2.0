from CanonizerBase import Page
from Identifiers import BrowsePageIdentifiers, CreateNewTopicPageIdentifiers
from selenium.webdriver.support.ui import Select
import time
from Config import NAME_SPACE_1


class CanonizerBrowsePage(Page):

    def click_browse_page_button(self):
        """
        This function is to click on the Browse link
        -> Hover to the Browse link
        -> Find the element and click it

        :return:
            Return the result to the main page.
        """

        title = self.find_element(*BrowsePageIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.hover(*BrowsePageIdentifiers.BROWSE)
            self.find_element(*BrowsePageIdentifiers.BROWSE).click()
            heading = self.find_element(*BrowsePageIdentifiers.HEADING).text
            if heading == 'Select Namespace':
                return CanonizerBrowsePage(self.driver)

    def click_only_my_topics_button(self):
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        return CanonizerBrowsePage(self.driver)

    def check_namespace_dropdown(self):
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        topics = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        options = topics.options
        index = []
        topic_list = []
        for ele in options:
            index.append(ele.get_attribute("value"))
            topic_list.append(ele.text)
        self.hover(*CreateNewTopicPageIdentifiers.CREATE_NEW_TOPIC)
        self.find_element(*CreateNewTopicPageIdentifiers.CREATE_NEW_TOPIC).click()
        name_spaces = Select(self.find_element(*CreateNewTopicPageIdentifiers.NAMESPACE))
        options = name_spaces.options
        index = []
        topic_list2 = []
        for ele in options:
            index.append(ele.get_attribute("value"))
            topic_list2.append(ele.text)
        topic_list.pop(0)
        if topic_list == topic_list2:
            return CanonizerBrowsePage(self.driver)

    def check_topic_namespace(self):
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        topics = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        options = topics.options
        index = []
        topic_list = []
        for ele in options:
            index.append(ele.get_attribute("value"))
            topic_list.append(ele.text)
        list_ = self.find_element(*BrowsePageIdentifiers.LOAD_DATA)
        items = list_.find_elements_by_tag_name("li")
        for item in items:
            text = item.text
            print(text,"Text")
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

    def select_by_value_government(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("26")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_government_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_government()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_government_sandy_city(self):
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_value("27")
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_by_value_government_sandy_city_only_my_topics(self):
        self.click_browse_page_button()
        self.select_dropdown_value()
        self.select_by_value_government_sandy_city()
        time.sleep(3)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_menu_item(self, menu_item):
        self.click_browse_page_button()
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_visible_text(menu_item)
        self.hover(*BrowsePageIdentifiers.TOPIC_NAME)
        return CanonizerBrowsePage(self.driver)

    def select_menu_item_with_only_my_topics(self, menu_item):
        self.select_menu_item(menu_item)
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_menu_item_without_only_my_topics(self, menu_item):
        self.select_menu_item(menu_item)
        return CanonizerBrowsePage(self.driver)

    def one_by_one(self, menu_item):
        element = self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        if element.is_selected():
            self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        select = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        select.select_by_visible_text(menu_item)
        time.sleep(4)
        return CanonizerBrowsePage(self.driver)

    def one_by_one_only_my_topics(self):
        self.hover(*BrowsePageIdentifiers.ONLY_MY_TOPICS)
        self.find_element(*BrowsePageIdentifiers.ONLY_MY_TOPICS).click()
        time.sleep(3)
        return CanonizerBrowsePage(self.driver)

    def select_menu_items_one_by_one(self):
        self.click_browse_page_button()
        self.hover(*BrowsePageIdentifiers.NAMESPACE)
        topics = Select(self.find_element(*BrowsePageIdentifiers.NAMESPACE))
        options = topics.options
        index = []
        topic_list = []
        for ele in options:
            index.append(ele.get_attribute("value"))
            topic_list.append(ele.text)
        index.pop(0)
        topic_list.pop(0)
        for i in range(len(topic_list)):
            result1 = self.one_by_one(topic_list[i])
            name_space1 = NAME_SPACE_1 + str(index[i])
            if name_space1 != result1.get_url():
                return False

            name_space2 = NAME_SPACE_1 + str(index[i])+"&my="+str(index[i])
            result2 = self.one_by_one_only_my_topics()
            if name_space2 != result2.get_url():
                return False

        return CanonizerBrowsePage(self.driver)




