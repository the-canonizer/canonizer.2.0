from selenium.webdriver.common.by import By


"""
Objects are Separated in this module
 - ID is the preferable choice as it is unique in a web page
 - XPATH is the next best alternative is also unique but it is cumbersome to fix 
   if the there are any changes in the layout of the Web page.
"""

class HomePageIdentifiers(object):
    BODY            = (By.ID, 'mainNav')
    LOGIN           = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/a[2]/i')
    REGISTER        = (By.XPATH, '//*[@id="navbarResponsive"]/ul[1]/li[2]/a[3]')
    WHATISCANONIZER = (By.XPATH, '//*[@id="exampleAccordion"]/ul[1]/li[2]/a')
    WHATISCANONIZERHEADING = (By.XPATH, '/html/body/div[1]/div[2]/div/div[1]/h3')


class LoginPageIdentifiers(object):
    EMAIL         = (By.ID, 'email')
    PASSWORD      = (By.ID, 'password')
    SUBMIT        = (By.XPATH, '/html/body/div[1]/div[2]/div/form/button')
    ERROR_MESSAGE = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[1]/p')


class RegistrationPageIdentifiers(object):
    FIRST_NAME       = (By.ID, 'firstname')
    MIDDLE_NAME      = (By.ID, 'middlename')
    LAST_NAME        = (By.ID, 'lastname')
    EMAIL            = (By.ID, 'email')
    PASSWORD         = (By.ID, 'pwd')
    CONFIRM_PASSWORD = (By.ID, 'pwd_confirm')
    CREATE_ACCOUNT   = (By.XPATH, '/html/body/div[1]/div[2]/div/form/button')
    ERROR_FIRST_NAME = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[1]/p')
    ERROR_LAST_NAME  = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[3]/p')
    ERROR_EMAIL      = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[4]/p')
    ERROR_PASSWORD   = (By.XPATH, '/html/body/div[1]/div[2]/div/form/div[5]/p')

class WhatIsCanonizerPageIdentifiers(object):
    JOINORSUPPORTCAMP = (By.XPATH, '/html/body/div[1]/div[2]/div/div[3]/div[2]/a')