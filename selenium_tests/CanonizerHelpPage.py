from CanonizerBase import Page
from Identifiers import HelpIdentifiers


class CanonizerHelpPage(Page):
    title = 'Canonizer Main Page'
    help = 'Help'
    sub_heading = 'Camp Statement'

    def check_what_is_canonizer_help_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """
        title = self.find_element(*HelpIdentifiers.TITTLE).text
        if title == self.title:
            self.hover(*HelpIdentifiers.HELP)
            self.find_element(*HelpIdentifiers.HELP).click()
            heading = self.find_element(*HelpIdentifiers.TITTLE_HELP).text
            if heading == self.help:
                return CanonizerHelpPage(self.driver)

    def check_steps_to_create_a_new_topic_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """
        window_before = self.driver.window_handles[0]
        self.hover(*HelpIdentifiers.STEPS_TO_CREATE_A_NEW_TOPIC)
        self.find_element(*HelpIdentifiers.STEPS_TO_CREATE_A_NEW_TOPIC).click()
        window_after = self.driver.window_handles[1]
        self.driver.switch_to.window(window_after)
        return CanonizerHelpPage(self.driver)

    def check_dealing_with_disagreements_page_loaded_with_login(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.DEALING_WITH_DISAGREEMENTS)
        self.find_element(*HelpIdentifiers.DEALING_WITH_DISAGREEMENTS).click()
        window_after = self.driver.window_handles[1]
        self.driver.switch_to.window(window_after)
        return CanonizerHelpPage(self.driver)

    def check_wiki_markup_information_page_loaded_with_login(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.WIKI_MARKUP_INFORMATION)
        self.find_element(*HelpIdentifiers.WIKI_MARKUP_INFORMATION).click()
        window_after = self.driver.window_handles[1]
        self.driver.switch_to.window(window_after)
        return CanonizerHelpPage(self.driver)

    def check_adding_the_canonizer_feedback_camp_outline_to_internet_articles_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.ADDING_CANO_FEEDBACK)
        self.find_element(*HelpIdentifiers.ADDING_CANO_FEEDBACK).click()
        window_after = self.driver.window_handles[1]
        self.driver.switch_to.window(window_after)
        return CanonizerHelpPage(self.driver)

    def check_canonizer_is_the_final_word_on_everything_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING)
        self.find_element(*HelpIdentifiers.CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING).click()
        window_after = self.driver.window_handles[1]
        self.driver.switch_to.window(window_after)
        return CanonizerHelpPage(self.driver)

    def check_consensus_out_of_controversy_use_case_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE)
        self.find_element(*HelpIdentifiers.CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE).click()
        window_after = self.driver.window_handles[1]
        self.driver.switch_to.window(window_after)
        return CanonizerHelpPage(self.driver)



