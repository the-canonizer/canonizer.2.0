from selenium.webdriver.common.keys import Keys
from CanonizerBase import Page
from Identifiers import *
import time
from selenium import webdriver
from selenium.webdriver.support.ui import Select


#
# Depends on the page functionality we can have more functions for new classes
#

##########################
#  Tests For Main Page   #
##########################

class CanonizerMainPage(Page):
    """
    Class Name: CanonizerMainPage
    Description: To load the main page and to navigate or load load pages for other URLs.

    Attributes: None
    """
    def check_home_page_loaded(self):
        """ To check if the Canonizer Home page loads properly"""

        return True if self.find_element(*HomePageIdentifiers.BODY) else False

    # def check_load_all_topic_text(self):
    #     """
    #     Verify the text to load all the Topics should be "Load All Topics"
    #
    #     :return:
    #         "Load All Topics" String to the main program
    #     """
    #     return self.find_element(*HomePageIdentifiers.LOADALLTOPICS).text

    def click_what_is_canonizer_page_link(self):
        """
        This Function is to verify if the canonizer main page loads properly
        :return:
            Return the result to the main page.check_home_page_loaded
        """
        self.hover(*HomePageIdentifiers.WHATISCANONIZER)
        self.find_element(*HomePageIdentifiers.WHATISCANONIZER).click()
        return CanonizerHomePage(self.driver)

    def check_home_page_loaded_logo_click(self):

        self.hover(*HomePageIdentifiers.CANONIZER_LOGO)
        self.find_element(*HomePageIdentifiers.CANONIZER_LOGO).click()
        return True if self.find_element(*HomePageIdentifiers.BODY) else False

    def check_scroll_to_top_click(self):
        self.driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        self.hover(*HomePageIdentifiers.CANONIZER_LOGO)
        self.find_element(*HomePageIdentifiers.CANONIZER_LOGO).click()
        return CanonizerHomePage(self.driver)


##########################\
#  Tests For Login Page  #
##########################


class CanonizerHomePage(Page):
    """
    Class Name: CanonizerHomePage
    Description: Verify test cases for Canonizer Home Page.

    Attributes: None
    """
    def check_what_is_canonizer_page_loaded(self):
        """
        This function verifies if the canonizer home page loads properly.
        :return:
        """
        return True if self.find_element(*HomePageIdentifiers.WHATISCANONIZERHEADING) else False

    def robots_txt_page_should_have_disallow_settings(self):
        """

        :return:
        """

        return self.find_element(*HomePageIdentifiers.TURNOFFSETTINGS).text

    def footer_should_have_privacy_policy_and_terms_services(self):
        """

        :return:
        """

        return self.find_element(*HomePageIdentifiers.FOOTER).text

    def check_garbage_url(self):
        """

        :return:
        """

        return self.find_element(*HomePageIdentifiers.GARBAGE_URL).text


class WhatIsCanonizerPage(Page):
    def join_or_support_camp_without_user_registration(self):
        self.find_element(*WhatIsCanonizerPageIdentifiers.JOINORSUPPORTCAMP)


class CanonizerWhitePaper(Page):
    def check_white_paper_should_open(self):
        self.hover(*HomePageIdentifiers.WHITE_PAPER)
        self.find_element(*HomePageIdentifiers.WHITE_PAPER).click()
        return CanonizerWhitePaper(self.driver)


class CanonizerBlog(Page):
    def check_blog_page_should_open(self):
        self.hover(*HomePageIdentifiers.BLOG)
        self.find_element(*HomePageIdentifiers.BLOG).click()
        return CanonizerBlog(self.driver)

    def blog_footer_should_have_privacy_policy_and_terms_services(self):
        """

        :return:
        """
        self.hover(*HomePageIdentifiers.BLOG)
        self.find_element(*HomePageIdentifiers.BLOG).click()
        return self.find_element(*HomePageIdentifiers.BLOG_FOOTER).text


class CanonizerAlgorithmInformation(Page):
    def check_algorithm_information_page_should_open(self):
        self.hover(*AlgorithmInformationIdentifiers.ALGORITHM_INFORMATION)
        self.find_element(*AlgorithmInformationIdentifiers.ALGORITHM_INFORMATION).click()
        return CanonizerAlgorithmInformation(self.driver)

    def check_from_algo_info_topic_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """
        self.hover(*AlgorithmInformationIdentifiers.ALGORITHM_INFORMATION)
        self.find_element(*AlgorithmInformationIdentifiers.ALGORITHM_INFORMATION).click()

        return CanonizerAlgorithmInformation(self.driver)

    def check_camp_create_new_camp_page_from_algo_info_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """
        self.check_from_algo_info_topic_loaded()
        self.hover(*HomePageIdentifiers.CREATE_NEW_CAMP)
        self.find_element(*HomePageIdentifiers.CREATE_NEW_CAMP).click()

        return CanonizerAlgorithmInformation(self.driver)


class CanonizerAsOfFilters(Page):
    def check_include_review_filter_applied(self):
        self.hover(*AsOfIdentifiers.INCLUDE_REVIEW)
        self.find_element(*AsOfIdentifiers.INCLUDE_REVIEW).click()
        return CanonizerAsOfFilters(self.driver)

    def check_default_filter_applied(self):
        self.hover(*AsOfIdentifiers.DEFAULT)
        self.find_element(*AsOfIdentifiers.DEFAULT).click()
        return CanonizerAsOfFilters(self.driver)

    def check_as_of_date_filter_applied(self):
        self.hover(*AsOfIdentifiers.AS_OF_DATE)
        self.find_element(*AsOfIdentifiers.AS_OF_DATE).click()
        return CanonizerAsOfFilters(self.driver)


class CanonizerTermsAndPrivacyPolicy(Page):
    def load_privacy_policy_page(self):
        self.hover(*TermsAndPrivacyPolicyIdentifiers.PRIVACY_POLICY)
        self.find_element(*TermsAndPrivacyPolicyIdentifiers.PRIVACY_POLICY).click()
        return CanonizerTermsAndPrivacyPolicy(self.driver)

    def load_terms_services_page(self):
        self.hover(*TermsAndPrivacyPolicyIdentifiers.TERMS_SERVICES)
        self.find_element(*TermsAndPrivacyPolicyIdentifiers.TERMS_SERVICES).click()
        return CanonizerTermsAndPrivacyPolicy(self.driver)


class CanonizerOpenSource(Page):
    def check_open_source_should_open(self):
        self.hover(*HomePageIdentifiers.OPEN_SOURCE)
        self.find_element(*HomePageIdentifiers.OPEN_SOURCE).click()
        return CanonizerOpenSource(self.driver)






