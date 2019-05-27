from selenium.webdriver.common.keys import Keys
from CanonizerBase import Page
from Identifiers import *
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

    def check_load_all_topic_text(self):
        """
        Verify the text to load all the Topics should be "Load All Topics"

        :return:
            "Load All Topics" String to the main program
        """
        return self.find_element(*HomePageIdentifiers.LOADALLTOPICS).text

    def click_what_is_canonizer_page_link(self):
        """
        This Function is to verify if the canonizer main page loads properly
        :return:
            Return the result to the main page.
        """
        self.hover(*HomePageIdentifiers.WHATISCANONIZER)
        self.find_element(*HomePageIdentifiers.WHATISCANONIZER).click()
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


class CanonizerAlgorithmInformation(Page):
    def check_algorithm_information_page_should_open(self):
        self.hover(*AlgorithmInformationIdentifiers.ALGORITHM_INFORMATION)
        self.find_element(*AlgorithmInformationIdentifiers.ALGORITHM_INFORMATION).click()
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
