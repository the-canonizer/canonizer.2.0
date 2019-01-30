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


class LoginPageIdentifiers(object):
    """
    Class to holds the Login, Logout and Forgot Password Page Identifiers Path
    """
    EMAIL         = (By.ID, 'email')
    PASSWORD      = (By.ID, 'password')
    SUBMIT        = (By.ID, 'submit')
    ERROR_MESSAGE = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[1]/p')
    SIGNUPNOW     = (By.XPATH, '/html/body/div[1]/div[2]/div[3]/div/a')
    PROFILE       = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/div/a/span')
    LOGOUT_OPTION = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/div/ul/li[2]/a')


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
    LOGINOPTION      = (By.XPATH, '/html/body/div[1]/div[2]/div[3]/div/a')
    CREATE_ACCOUNT   = (By.XPATH, '/html/body/div[1]/div[2]/div/form/button')
    ERROR_FIRST_NAME = (By.XPATH, '/html/body/div[1]/div[2]/div[1]/form/div[1]/p')
    ERROR_LAST_NAME  = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[3]/p')
    ERROR_EMAIL      = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[4]/p')
    ERROR_PASSWORD   = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[5]/p')


class WhatIsCanonizerPageIdentifiers(object):
    JOINORSUPPORTCAMP = (By.XPATH, '/html/body/div[1]/div[2]/div/div[3]/div[2]/a')


class CanonizerSearchPageIdentifiers(object):
    DummySearch = (By.XPATH, '')


class CanonizerUploadFileIdentifiers(object):
    """
    Class to hold the Upload File Element Path
    """
    UPLOADIDENTIFIERS = (By.ID, '')


class UserProfileIdentifiers(object):
    """
    Class to hold the User Profile Identifers
    """
    PROFILEIDENTIFIERS = (By.ID, '')


class CampForumIdentifiers(object):
    """
    Class to hold the CampForums Identifiers
    """
    CAMP_IDENTIFIER          = (By.XPATH, '//*[@id="tree_88_1_1"]/div/a')
    CAMP_FORUM_IDENTIFIER    = (By.XPATH, '//*[@id="camp_forum"]')
    CAMP_FORUM_TOPIC_NAME    = (By.XPATH, '/html/body/div[1]/div[1]/h3[2]/b')
    CAMP_FORUM_CAMP_NAME     = (By.XPATH, '/html/body/div[1]/div[1]/h3[3]/a')
    CAMP_FORUM_THREAD_1      = (By.XPATH, '/html/body/div[1]/div[2]/div/div[2]/article[1]/h5/ul/li/a')
    CAMP_FORUM_THREAD_2      = (By.XPATH, '/html/body/div[1]/div[2]/div/div[2]/article[10]/h5/ul/li/a')
    CAMP_FORUM_THREAD_3      = (By.XPATH, '/html/body/div[1]/div[2]/div/div[2]/article[6]/h5/ul/li/a')
    CAMP_FORUM_CREATE_NEW    = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[3]/a')
    CAMP_FORUM_THREAD_TITLE  = (By.ID, 'title')
    CAMP_FORUM_THREAD_SUBMIT = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[3]/button')
    # Only Valid for forums having more than 10 threads
    CAMP_FORUM_THREAD_PAGTN  = (By.XPATH, '/html/body/div[1]/div[2]/div/div[2]/ul/li[3]/a')
    CAMP_FORUM_THREAD_PATH   = (By.XPATH, '/html/body/div[1]/div[2]/div/div[2]/article[2]/h5/ul/li/a')
    CAMP_FORUM_POST_BODY     = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[1]/div/div[3]/div[3]')
    CAMP_FORUM_POST_SUBMIT   = (By.XPATH, '/html/body/div[1]/div[2]/div/form/button')
    CAMP_FORUM_ERR_MESSAGE   = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/ul/li')

    CAMP_FORUM_THREAD_NICK_NAME       = (By.ID, 'nick_name')
    CAMP_FORUM_THREAD_TITLE_ASTRK     = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[1]/label/span')
    CAMP_FORUM_THREAD_NICK_NAME_ASTRK = (By.XPATH, '/html/body/div[1]/div[2]/div/div/form/div[2]/label/span')


