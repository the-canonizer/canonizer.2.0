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

##########################
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
