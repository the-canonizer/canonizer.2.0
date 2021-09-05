from builtins import object

from selenium.webdriver.common.by import By


"""
Objects are Separated in this module
 - ID is the preferable choice as it is unique in a web page
 - XPATH is the next best alternative and is also unique but it is cumbersome to fix 
   if the there are any changes in the layout of the Web page.
- Add the Identifiers in Tuple.
"""


class HomePageIdentifiers(object):
    """
    This Class holds the Home Page Element Identifiers.
    """
    BODY            = (By.ID, 'mainNav')
    LOGIN           = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/a[2]/i')
    REGISTER        = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/a[3]')
    WHATISCANONIZER = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[2]/a')
    WHATISCANONIZERHEADING = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[1]/a/span')
    LOADALLTOPICS   = (By.ID, 'loadtopic')
    CREATE_NEW_CAMP = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[4]/a/span')
    UPLOADFILE = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[4]/a/span')
    #UPLOADFILE = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[5]/a/span')
    WHITE_PAPER = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[6]/a/span')
    #WHITE_PAPER = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[7]/a/span')
    BLOG = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[7]/a/span')
    #BLOG = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[8]/a/span')
    GOOGLESEARCH = (By.ID, 'sbi')
    GOOGLESEARCHSUBMIT = (By.ID, 'sbb')
    # Broken URL XPATH
    BROKENURL_1 = (By.XPATH, '//*[@id="rso"]/div[1]/div/div/div[1]/a/h3')
    BROKENURL_2 = (By.XPATH, '//*[@id="rso"]/div[2]/div/div/div[1]/a/h3')
    BROKENURL_3 = (By.XPATH, '//*[@id="rso"]/div[3]/div/div/div[1]/a/h3')
    BROKENURL_4 = (By.XPATH, '//*[@id="rso"]/div[3]/div/div/div[1]/a/h3')
    TURNOFFSETTINGS = (By.XPATH, '/html/body/pre')
    FOOTER = (By.XPATH, '/html/body/footer')
    GARBAGE_URL = (By.XPATH, '/html/body/div')
    BLOG_FOOTER = (By.XPATH, '/html/body/div/footer/div[2]/div')
    OPEN_SOURCE = (By.XPATH, '/html/body/div[1]/div[1]/small/a')
    CANONIZER_LOGO = (By.XPATH, '//*[@id="mainNav"]/a/img')
    SCROLL_TO_TOP = (By.XPATH, '/html/body/div[1]/a/i')
    JOBS = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[8]/a/span')
    SERVICES = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[9]/a/span')


class LoginPageIdentifiers(object):
    """
    Class to holds the Login, Logout and Forgot Password Page Identifiers Path
    """
    EMAIL         = (By.ID, 'email')
    PASSWORD      = (By.ID, 'password')
    SUBMIT        = (By.ID, 'submit')
    ERROR_MESSAGE = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[1]/p')
    SIGNUPNOW     = (By.XPATH, '/html/body/div/div[2]/div[3]/div/a')
    ERROR_EMAIL = (By.XPATH, '//*[@id="login_form"]/div[1]/p')
    ERROR_PASSWORD = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[2]/p')
    EMAIL_ASTRK = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[1]/label/span')
    PASSWORD_ASTRK = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[2]/label/span')
    FORGOTPASSWORD = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[3]/a')
    REQUEST_OTP = (By.ID, 'request_opt')


class LoginOTPVerificationIdentifiers(object):
    """
    Class to holds the Login OTP Verification page Identifiers Path
    """
    OTP = (By.ID, 'otp')
    SUBMIT = (By.ID, 'submit')
    ERROR_OTP = (By.XPATH, '/html/body/div/div[2]/div/form/div/p')
    ERROR_INVALID_OTP = (By.XPATH, '/html/body/div/div[2]')
    OTP_ASTRK = (By.XPATH, '/html/body/div/div[2]/div/form/div/label/span')


class RegistrationPageIdentifiers(object):
    """
    This class holds the User Registration Page Identifiers
    """
    FIRST_NAME       = (By.ID, 'firstname')
    FIRST_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[1]/label/span')
    MIDDLE_NAME      = (By.ID, 'middle_name')
    LAST_NAME        = (By.ID, 'lastname')
    LAST_NAME_ASTRK  = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[3]/label/span')
    REGISTER         = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/a[3]')
    EMAIL            = (By.ID, 'email')
    EMAIL_ASTRK      = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[4]/label/span')
    PASSWORD         = (By.ID, 'password')
    PASSWORD_ASTRK   = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[5]/label/span')
    CONFIRM_PASSWORD = (By.ID, 'pwd_confirm')
    CNFM_PSSWD_ASTRK = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[6]/label/span')
    LOGINOPTION      = (By.XPATH, '/html/body/div/div[2]/div[3]/div/a')
    CREATE_ACCOUNT   = (By.XPATH, '/html/body/div[1]/div[2]/div/form/button')
    ERROR_FIRST_NAME = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[1]/p')
    ERROR_MIDDLE_NAME = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[2]/p')
    ERROR_LAST_NAME  = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[3]/p')
    ERROR_EMAIL      = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[4]/p')
    ERROR_PASSWORD   = (By.XPATH, '/html/body/div/div[2]/div/form/div[5]/p')
    ERROR_DUPLICATE_EMAIL = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[4]/p')
    CAPTCHA = (By.ID, 'CaptchaCode')
    ERROR_CAPTCHA = (By.XPATH, '/html/body/div/div[2]/div[1]/form/div[7]/p')


class WhatIsCanonizerPageIdentifiers(object):
    JOINORSUPPORTCAMP = (By.XPATH, '/html/body/div[1]/div[2]/div/div[3]/div[2]/a')


class CanonizerSearchPageIdentifiers(object):
    """
    Class to hold the Search page Identifiers
    """
    DummySearch = (By.XPATH, '')
    SEARCH_KEYWORD = (By.ID, 'sbi')
    WEB = (By.ID, 'ss0')
    WEB_LABEL = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[1]/form/div/table/tbody/tr/td[1]/label/font')
    CANONIZER_COM = (By.ID, 'ss1')
    SEARCH_BUTTON = (By.ID, 'sbb')


class UploadFileIdentifiers(object):
    """
    Class to hold the Upload File page Identifiers
    """
    UPLOADFILE = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[4]/a/span')
    FILE_NAME = (By.NAME, 'file')
    ERROR_FILE_NAME = (By.XPATH, '/html/body/div[1]/div[1]')
    UPLOAD = (By.ID, 'upload_file')
    NEW_FILE_NAME = (By.ID, 'file_name')
    ERROR_FILE_SIZE = (By.XPATH, '/html/body/div/div[1]')
    CHOOSE_FILE = (By.XPATH, '/html/body/div[1]/div[1]/div/div/div/form/div[1]/input')
    INVALID_FILE_FORMAT_ERROR = (By.XPATH, '/html/body/div/div[1]')
    SAME_FILE_NAME_ERROR = (By.XPATH, '/html/body/div/div[1]')
    ERROR_ZERO_FILE_SIZE = (By.XPATH, '/html/body/div/div[1]')
    UPLOADED_FILE = (By.XPATH, '/html/body/div/div[1]/div/div/div/div/table/tbody/tr[2]/td[1]/a/i')


class HelpIdentifiers(object):
    """
    Class to hold the Help Page Identifiers
    """
    HELP = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[5]/a/span')
    #HELP = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[6]/a/span')
    STEPS_TO_CREATE_A_NEW_TOPIC = (By.LINK_TEXT, 'Steps to create a new topic')
    DEALING_WITH_DISAGREEMENTS = (By.LINK_TEXT, 'Dealing with disagreement')
    WIKI_MARKUP_INFORMATION = (By.LINK_TEXT, 'Wiki markup and file upload information')
    ADDING_CANO_FEEDBACK = (By.LINK_TEXT, 'Adding the canonizer feedback camp outline to internet articles')
    CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING = (By.LINK_TEXT, 'Canonizer is the final word on everything')
    CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE = (By.LINK_TEXT, 'Consensus out of controversy use case')


class ForgotPasswordIdentifiers(object):
    """
    Class to hold the Forgot Password Page Identifiers
    """
    FORGOT_PASSWORD = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[3]/a')
    EMAIL = (By.ID, 'email')
    SUBMIT = (By.ID, 'submit')
    ERROR_MESSAGE_EMAIL = (By.XPATH, '/html/body/div/div[2]/div/form/div/p')
    ERROR_INVALID_EMAIL = (By.XPATH, '/html/body/div/div[2]/div/form/div/p')
    EMAIL_ASTRK = (By.XPATH, '/html/body/div/div[2]/div/form/div/label/span')


class BrowsePageIdentifiers(object):
    """
    Class to hold the Browse Page Identifiers
    """
    BROWSE = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[2]/a/span')
    ONLY_MY_TOPICS = (By.XPATH, '/html/body/div[1]/div[1]/div/div/h3/div/form/div[2]/label/input')
    NAMESPACE = (By.ID, 'namespace')
    GENERAL = (By.XPATH, '//*[@id="namespace"]/option[2]')
    ALL = (By.XPATH, '//*[@id="namespace"]/option[1]')


class CreateNewTopicPageIdentifiers(object):
    """
    Class to hold the Create New Topic Page Identifiers
    """
    CREATE_NEW_TOPIC    = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[3]/a/span')
    NICK_NAME           = (By.ID, 'nick_name')
    TOPIC_NAME          = (By.ID, 'topic_name')
    NAMESPACE           = (By.ID, 'namespace')
    NOTE                = (By.ID, 'note')
    ERROR_NICK_NAME     = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[1]/p')
    ERROR_TOPIC_NAME    = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[2]/p')
    CREATETOPIC         = (By.ID, 'submit')
    ADDNEWNICKNAME      = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[1]/a')
    NICK_NAME_ASTRK     = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[1]/label/span')
    TOPIC_NAME_ASTRK    = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[2]/label/span')
    NAMESPACE_ASTRK     = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[3]/label/span')
    ERROR_DUPLICATE_TOPIC_NAME = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[2]/p')
    INVALID_TOPIC_NAME = (By.XPATH, '/html/body/div/div[2]/div/div/form/div[2]/p')
    OTHER_NAMESPACE_NAME = (By.ID, 'create_namespace')
    OTHER_NAMESPACE_NAME_ASTRK = (By.XPATH, '//*[@id="other-namespace"]/label/span')
    ERROR_OTHER_NAMESPACE_NAME = (By.XPATH, '')


class LogoutIdentifiers(object):
    """
    Class to hold the Logout Identifiers
    """
    USERNAME  = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/div/a/span')
    LOGOUT    = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/div/ul/li[2]/a')


class AccountSettingsIdentifiers(object):
    """
    Class to hold the Account Settings Page Identifiers
    """
    ACCOUNT_SETTINGS = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/div/ul/li[1]/a')
    MANAGE_PROFILE_INFO = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[1]/a')
    ADD_MANAGE_NICK_NAMES = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[4]/a')
    MY_SUPPORTS = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[5]/a')
    DEFAULT_ALGORITHM = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[4]/a')
    CHANGE_PASSWORD = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[3]/a')
    SOCIAL_OAUTH_VERIFICATION = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[2]/a')
    CRYPTO_VERIFICATION = (By.XPATH, '/html/body/div[1]/div[2]/div/div/div/ul/li[6]/a')


class AccountSettingsChangePasswordIdentifiers(object):
    """
    Class to hold the Account Settings->Change Password Page Identifiers
    """
    CURRENT_PASSWORD = (By.NAME, 'current_password')
    NEW_PASSWORD = (By.NAME, 'new_password')
    CONFIRM_PASSWORD = (By.NAME, 'confirm_password')
    ERROR_CURRENT_PASSWORD = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/p')
    ERROR_NEW_PASSWORD = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[2]/p')
    ERROR_CONFIRM_PASSWORD = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[3]/p')
    CURRENT_PASSWORD_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/label/span')
    NEW_PASSWORD_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[2]/label/span')
    CONFIRM_PASSWORD_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[3]/label/span')
    SAVE = (By.XPATH, '//*[@id="submit_create"]')
    INVALID_CURRENT_PASSWORD = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/p')
    PASSWORD_MISMATCH = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[3]/p')
    CURRENT_NEW_PASSWORD_MUST_DIFF = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[2]/p')
    INVALID_NEW_PASSWORD = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[2]/p')


class AddAndManageNickNamesIdentifiers(object):
    """
    Class to hold the Account Settings->Add And Manage Nick Names Page Identifiers
    """
    NICK_NAME = (By.XPATH, '//*[@id="nick_name"]')
    NICK_NAME_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/label/span')
    ERROR_NICK_NAME = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/p')
    ERROR_DUPLICATE_NICK_NAME = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/p')
    ERROR_NICK_NAME_MAX_LENGTH = (By.XPATH, '//*[@id="myTabContent"]/form/div/div[1]/p')
    VISIBILITY_STATUS = (By.XPATH, '//*[@id="visibility_status"]')
    CREATE = (By.ID, 'submit_create')


class AccountSettingsManageProfileInfoIdentifiers(object):
    """
    Class to hold the Account Settings->Manage Profile Info Page Identifiers
    """
    FIRST_NAME = (By.ID, 'first_name')
    MIDDLE_NAME = (By.ID, 'middle_name')
    LAST_NAME = (By.ID, 'last_name')
    EMAIL = (By.ID, 'email')
    LANGUAGE = (By.ID, 'language')
    DOB = (By.ID, 'birthday')
    ADDRESS_LINE1 = (By.ID, 'address_1')
    ADDRESS_LINE2 = (By.ID, 'address_2')
    CITY = (By.ID, 'city')
    STATE = (By.ID, 'state')
    COUNTRY = (By.ID, 'country_select')
    ZIP_CODE = (By.ID, 'postal_code')
    FIRST_NAME_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form[2]/div/div[1]/label/span')
    LAST_NAME_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form[2]/div/div[3]/label/span')
    COUNTRY_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form[2]/div/div[12]/label/span')
    ERROR_FIRST_NAME = (By.XPATH, '//*[@id="myTabContent"]/form[2]/div/div[1]/div[1]/p')
    ERROR_MIDDLE_NAME = (By.XPATH, '//*[@id="myTabContent"]/form[2]/div/div[2]/div[1]/p')
    ERROR_LAST_NAME = (By.XPATH, '//*[@id="myTabContent"]/form[2]/div/div[3]/div[1]/p')
    UPDATE = (By.ID, 'update_profile')
    PHONE_NUMBER = (By.ID, 'phone_number')
    ERROR_PHONE_NUMBER = (By.XPATH, '//*[@id="myTabContent"]/form[1]/div/div[1]/div/p')
    VERIFY = (By.ID, 'verifyButton')
    PHONE_NUMBER_ASTRK = (By.XPATH, '//*[@id="myTabContent"]/form[1]/div/div[1]/label/span')
    ERROR_PHONE_NUMBER_VERIFICATION = (By.XPATH, '//*[@id="myTabContent"]/form[1]/div/div[3]')


class AlgorithmInformationIdentifiers(object):
    """
    Class to hold the Algorithm Information Page Identifiers
    """
    ALGORITHM_INFORMATION = (By.XPATH, '//*[@id="canoalgo"]/li[1]/a/span')
    MIND_EXPERTS = (By.XPATH, '//*[@id="tree_53_1_11"]/div/a')
    MIND_EXPERTS_TOPIC = (By.XPATH, '//*[@id="camp_statement"]/a[2]')


class BrowseTopicsIdentifiers(object):
    """
    Class to hold the Browse Topics Page Identifiers
    """
    MAINOUTER = (By.ID, '//*[@id="load-data"]/li')
    AHREF = (By.TAG_NAME, 'a')


class AsOfIdentifiers(object):
    """
    Class to hold the As Of Filters Identifiers
    """
    INCLUDE_REVIEW = (By.XPATH, '//*[@id="as_of"]/div[1]/label')
    DEFAULT = (By.XPATH, '//*[@id="as_of"]/div[2]/label')
    AS_OF_DATE = (By.XPATH, '//*[@id="as_of"]/div[3]/label')
    DATE = (By.ID, 'asofdate')


class TopicUpdatePageIdentifiers(object):
    """
    Class to hold the Topic Update Page Identifiers
    """
    #TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_224"]/a')
    TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_173"]/a')
    MANAGE_EDIT_TOPIC = (By.XPATH, '//*[@id="edit_topic"]')
    SUBMIT_TOPIC_UPDATE_BASED_ON_THIS = (By.XPATH, '//*[@id="update"]')
    NICK_NAME = (By.ID, 'nick_name')
    TOPIC_NAME = (By.ID, 'topic_name')
    NAMESPACE = (By.ID, 'namespace')
    OTHER_NAMESPACE_NAME = (By.ID, 'create_namespace')
    NOTE = (By.ID, 'note')
    OTHER_NAMESPACE_NAME_ASTRK = (By.XPATH, '//*[@id="other-namespace"]/label/span')
    NICK_NAME_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[1]/label/span')
    TOPIC_NAME_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[2]/label/span')
    NAMESPACE_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[3]/label/span')
    ERROR_NICK_NAME = (By.XPATH, '//*[@id="topicForm"]/div[1]/p')
    ERROR_TOPIC_NAME = (By.XPATH, '//*[@id="topicForm"]/div[2]/p')
    ERROR_OTHER_NAMESPACE_NAME = (By.XPATH, '')
    SUBMIT_UPDATE = (By.ID, 'submit')
    VIEW_THIS_VERSION = (By.ID, 'version')
    OBJECT = (By.ID, 'object')
    ADDNEWNICKNAME = (By.XPATH, '//*[@id="topicForm"]/div[1]/a')
    SELECTED_NICK_NAME = (By.XPATH, '//*[@id="nick_name"]/option[1]')
    ERROR_DUPLICATE_TOPIC_NAME = (By.XPATH, '//*[@id="topicForm"]/div[2]/p')


class TopicObjectPageIdentifiers(object):
    """
    Class to hold the Topic Object Page Identifiers
    """
    NICK_NAME = (By.ID, 'nick_name')
    TOPIC_NAME = (By.ID, 'topic_name')
    OBJECTION_REASON = (By.ID, 'objection_reason')
    SUBMIT_OBJECTION = (By.ID, 'submit-objection')
    NICK_NAME_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[1]/label/span')
    TOPIC_NAME_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[2]/label/span')
    OBJECTION_REASON_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[3]/label/span')


class CreateNewCampPageIdentifiers(object):
    """
    Class to hold the Create New Camp Page Identifiers
    """
    #CREATE_CAMP = (By.XPATH, '//*[@id="tree_88_1-Agreement_1"]/ul/li[1]/span/a')
    CREATE_CAMP = (By.XPATH, '//*[@id="tree_173_1-Agreement_1"]/ul/li[1]/span/a')
    NICK_NAME = (By.ID, 'nick_name')
    CAMP_NAME = (By.ID, 'camp_name')
    KEYWORDS = (By.ID, 'keywords')
    ADDITIONAL_NOTE = (By.ID, 'note')
    CAMP_ABOUT_URL = (By.ID, 'camp_about_url')
    CAMP_ABOUT_NICK_NAME = (By.ID, 'camp_about_nick_id')
    NICK_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[1]/label/span')
    CAMP_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[2]/label/span')
    CREATE_CAMP_BUTTON = (By.ID, 'submit')
    ERROR_NICK_NAME = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[1]/p')
    ERROR_CAMP_NAME = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[3]/p')
    ADDNEWNICKNAME = (By.LINK_TEXT, 'Add New Nick Name')
    ERROR_DUPLICATE_CAMP_NAME = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[3]/p')
    ERROR_INVALID_CAMP_NAME = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[3]/p')
    PARENT_CAMP = (By.ID, 'parent_camp_num')


class CampEditPageIdentifiers(object):
    """
    Class to hold the Edit Camp Page Identifiers
    """
    MANAGE_EDIT_CAMP = (By.ID, 'edit_camp')
    SUBMIT_CAMP_UPDATE_BASED_ON_THIS = (By.XPATH, '//*[@id="update"]')
    VIEW_THIS_VERSION = (By.ID, 'version')
    OBJECT = (By.ID, 'object')
    NICK_NAME = (By.ID, 'nick_name')
    CAMP_NAME = (By.ID, 'camp_name')
    KEYWORDS = (By.ID, 'keywords')
    ADDITIONAL_NOTE = (By.ID, 'note')
    CAMP_ABOUT_URL = (By.ID, 'camp_about_url')
    CAMP_ABOUT_NICK_NAME = (By.ID, 'camp_about_nick_id')
    NICK_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[1]/label/span')
    CAMP_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[2]/label/span')
    CREATE_CAMP_BUTTON = (By.ID, 'submit')
    ERROR_NICK_NAME = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[1]/p')
    ERROR_CAMP_NAME = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[3]/p')
    ADDNEWNICKNAME = (By.LINK_TEXT, 'Add New Nick Name')
    SUBMIT_UPDATE = (By.ID, 'submit')
    ERROR_DUPLICATE_CAMP_NAME = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[3]/p')
    CAMP_IDENTIFIER = (By.XPATH, '//*[@id="tree_173_1_2"]/div/a')
    USER_NICK_NAME = (By.XPATH, '//*[@id="support-tree"]/li[2]/a')
    USER_SUPPORTS_CAMP_NAME = (By.XPATH, '//*[@id="camp_173_2"]/a')
    ERROR_CAMP_ABOUT_URL = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[6]/p[2]')


class CampStatementEditPageIdentifiers(object):
    """
    Class to hold the Camp Statement Page Identifiers
    """
    EDIT_CAMP_STATEMENT = (By.ID, 'edit_camp_statement')
    SUBMIT_STATEMENT_UPDATE_BASED_ON_THIS = (By.ID, 'update')
    VIEW_THIS_VERSION = (By.ID, 'version')
    OBJECT = (By.ID, 'object')
    NICK_NAME = (By.ID, 'nick_name')
    STATEMENT = (By.ID, 'name')
    NOTE = (By.ID, 'note')
    NICK_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[1]/label/span')
    STATEMENT_ASTRK = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[2]/label/span')
    ERROR_NICK_NAME = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[1]/p')
    ERROR_STATEMENT = (By.XPATH, '/html/body/div[1]/div[3]/div/div/form/div[2]/p')
    SUBMIT_UPDATE = (By.ID, 'submit')
    ADDNEWNICKNAME = (By.XPATH, '//*[@id="add_new_nickname"]')
    #TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_224"]/a')
    TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_173"]/a')


class AddNewsPageIdentifiers(object):
    """
    Class to hold the Add News Page Identifiers
    """
    #ADD_NEWS = (By.XPATH, '/html/body/div[1]/div[3]/div/div[1]/h3/div[2]/a[2]')
    ADD_NEWS = (By.LINK_TEXT, 'Add News')
    DISPLAY_TEXT = (By.ID, 'display_text')
    DISPLAY_TEXT_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[1]/label/span')
    LINK = (By.ID, 'link')
    LINK_ASTRK = (By.XPATH, '//*[@id="topicForm"]/div[2]/label/span')
    AVAILABLE_FOR_CHILD_CAMPS = (By.XPATH, '//*[@id="topicForm"]/div[3]/input')
    CREATENEWS = (By.ID, 'submit')
    ERROR_DISPLAY_TEXT = (By.XPATH, '//*[@id="topicForm"]/div[1]/p')
    ERROR_LINK = (By.XPATH, '//*[@id="topicForm"]/div[2]/p')
    CANCEL = (By.XPATH, '//*[@id="topicForm"]/a')
    ERROR_INVALID_LINK = (By.XPATH, '//*[@id="topicForm"]/div[2]/p')


class EditNewsPageIdentifiers(object):
    """
    Class to hold the Edit News Page Identifiers
    """
    #EDIT_NEWS = (By.XPATH, '/html/body/div[1]/div[3]/div[1]/div[1]/h3/a')
    EDIT_NEWS = (By.LINK_TEXT, 'Edit News')
    DISPLAY_TEXT = (By.ID, 'display_text')
    LINK = (By.ID, 'link')
    CANCEL = (By.XPATH, '//*[@id="topicForm"]/a')
    DISPLAY_TEXT_ASTRK = (By.XPATH, '//*[@id="sortable"]/div[1]/div[1]/label/span')
    LINK_ASTRK = (By.XPATH, '//*[@id="sortable"]/div[1]/div[2]/label/span')
    SUBMIT = (By.ID, 'submit')
    ERROR_DISPLAY_TEXT = (By.XPATH, '//*[@id="sortable"]/div[1]/div[1]/p')
    ERROR_LINK = (By.XPATH, '//*[@id="sortable"]/div[1]/div[2]/p')
    ERROR_INVALID_LINK = (By.XPATH, '//*[@id="sortable"]/div[1]/div[2]/p')


class DeleteNewsPageIdentifiers(object):
    """
        Class to hold the Delete News Page Identifiers
    """
    DELETE_NEWS = (By.LINK_TEXT, 'Delete News')


class MySupportsPageIdentifiers(object):
    """
    Class to hold the My Supports Page Identifiers
    """
    TOPIC_NAME = (By.LINK_TEXT, 'Theories of Consciousness')
    CAMP_NAME = (By.XPATH, '//*[contains(@href,"https://staging.canonizer.com/topic/88-Theories-of-Consciousness/1-Agreement")]')


class AddCampStatementPageIdentifiers(object):
    """
    Class to hold the Camp Statement Page Identifiers
    """
    ADD_CAMP_STATEMENT = (By.ID, 'add_camp_statement')
    NICK_NAME = (By.ID, 'nick_name')
    STATEMENT = (By.ID, 'name')
    NOTE = (By.ID, 'note')
    NICK_NAME_ASTRK = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[1]/label/span')
    STATEMENT_ASTRK = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[2]/label/span')
    ERROR_NICK_NAME = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[1]/p')
    ERROR_STATEMENT = (By.XPATH, '/html/body/div/div[3]/div/div/form/div[2]/p')
    SUBMIT_STATEMENT = (By.ID, 'submit')
    ADDNEWNICKNAME = (By.XPATH, '//*[@id="add_new_nickname"]')
    #TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_173"]/a')
    #TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_281"]/a')
    TOPIC_IDENTIFIER = (By.XPATH, '//*[@id="outline_224"]/a')


class TermsAndPrivacyPolicyIdentifiers(object):
    """
    Class to hold the Terms & Services and Privacy Policy Page Identifiers
    """
    PRIVACY_POLICY = (By.XPATH, '/html/body/footer/div/div/div/small[3]/a[1]')
    TERMS_SERVICES = (By.XPATH, '/html/body/footer/div/div/div/small[3]/a[2]')


class BreadCrumbsLinksIdentifiers(object):
    """
    Class to hold the bread crumbs links Identifiers
    """
    AGREEMENT_CAMP = (By.XPATH, '/html/body/div[1]/div[2]/h3[2]/a')
    CHILD_CAMP_TYPES_OF_TESTING = (By.XPATH, '//*[@id="tree_173_1_2"]/div/a')
    UP = (By.XPATH, '/html/body/div[1]/a')
    CHILD_CAMP_TYPES_OF_TESTING_CAMP = (By.XPATH, '/html/body/div[1]/div[2]/h3[2]/a[2]')
    CAMP_FORUM = (By.ID, 'camp_forum')
    CAMP_FORUM_AGREEMENT = (By.XPATH, '/html/body/div/div[1]/h3[3]/a')
    CAMP_STATEMENT_HISTORY_AGREEMENT = (By.XPATH, '/html/body/div/div[1]/h3[2]/a')
    JOIN_SUPPORT_CAMP = (By.ID, 'join_support_camp')
    SUPPORTED_CAMPS_AGREEMENT = (By.XPATH, '//*[@id="myTabContent"]/h5/a')
    CAMP_HISTORY_AGREEMENT = (By.XPATH, '/html/body/div/div[1]/h3[2]/a')
    CREATE_NEW_CAMP_AGREEMENT = (By.XPATH, '/html/body/div/div[1]/h3[2]/a')
    TOPIC_HISTORY_TOPIC_NAME = (By.XPATH, '/html/body/div/div[1]/h3/a')


class JoinSupportCampPageIdentifiers(object):
    """
    Class to hold the Join/manage support crumbs links Identifiers
    """
    JOINSUPPORTCAMP= (By.ID, 'join_support_camp')
    ADDNEWNICKNAME = (By.ID, 'add_new_nickname')





