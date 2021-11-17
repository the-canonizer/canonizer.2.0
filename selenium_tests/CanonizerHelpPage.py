from CanonizerBase import Page
from Identifiers import HelpIdentifiers


class CanonizerHelpPage(Page):

    def check_what_is_canonizer_help_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.HELP)
        self.find_element(*HelpIdentifiers.HELP).click()
        return CanonizerHelpPage(self.driver)

    def check_Steps_to_Create_a_New_Topic_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.STEPS_TO_CREATE_A_NEW_TOPIC)
        self.find_element(*HelpIdentifiers.STEPS_TO_CREATE_A_NEW_TOPIC).click()
        return CanonizerHelpPage(self.driver)

    def check_Dealing_With_Disagreements_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.DEALING_WITH_DISAGREEMENTS)
        self.find_element(*HelpIdentifiers.DEALING_WITH_DISAGREEMENTS).click()
        return CanonizerHelpPage(self.driver)

    def check_Wiki_Markup_Information_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.WIKI_MARKUP_INFORMATION)
        self.find_element(*HelpIdentifiers.WIKI_MARKUP_INFORMATION).click()
        return CanonizerHelpPage(self.driver)

    def check_Adding_the_Canonizer_Feedback_Camp_Outline_to_Internet_Articles_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.ADDING_CANO_FEEDBACK)
        self.find_element(*HelpIdentifiers.ADDING_CANO_FEEDBACK).click()
        return CanonizerHelpPage(self.driver)

    def check_Canonizer_is_the_final_word_on_everything_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING)
        self.find_element(*HelpIdentifiers.CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING).click()
        return CanonizerHelpPage(self.driver)

    def check_consensus_out_of_controversy_use_case_page_loaded(self):
        """
        This function verifies if the canonizer help page loads properly.
        :return:
        """

        self.hover(*HelpIdentifiers.CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE)
        self.find_element(*HelpIdentifiers.CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE).click()
        return CanonizerHelpPage(self.driver)



