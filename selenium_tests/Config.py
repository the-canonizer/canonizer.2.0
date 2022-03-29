import os
import platform

"""
Set All Basic Configuration required for testing Framework
"""

DEFAULT_BASE_URL = "https://staging.canonizer.com/"
#DEFAULT_BASE_URL = "https://canonizer.com/"

"""
    Identify the Default Chrome Binary Location for different OS
"""
DEFAULT_BINARY_LOCATION = ''

if platform.system() == 'Darwin':
    DEFAULT_BINARY_LOCATION = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver_mac_m1"
elif platform.system() == 'Windows':
    #DEFAULT_BINARY_LOCATION = "C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe"
    DEFAULT_BINARY_LOCATION = "C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe"

    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver"
elif platform.system() == 'Linux':
    DEFAULT_BINARY_LOCATION = "/usr/bin/google-chrome"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver"
else:
    print("Unknown OS")
    exit(1)

# Login Page Configuration Parameters
UNVERIFY_EMAIL = "meghana@zibtek.in"
UNVERIFY_PASS = "Pooja@123456"

DEFAULT_USER = "pooja.khatri@zibtek.in"
DEFAULT_PASS = "Pooja@123456"
#DEFAULT_USER = "r_canonizer_user@yopmail.com"
#DEFAULT_PASS = "Rupali@12345"
DEFAULT_INVALID_USER = 'invaliduser@gmail.com'
DEFAULT_INVALID_PASSWORD = "invalid_password"
DEFAULT_UNVERIFIED_PHONE_NUMBER = "1234567890"
DEFAULT_INVALID_PHONE_NUMBER = "1212121212"
DEFAULT_VALID_PHONE_NUMBER = ""
DEFAULT_INVALID_OTP = "123456789"
DEFAULT_INVALID_EMAIL_FORMAT = "test@test"
VERIFY_EMAIL = "anil.podi@zibtek.in"
VERIFY_PASS = "Zibtek#2455"
PASS_UPPERCASE = "POOJA@123456"
PASS_LOWERCASE = "pooja@123456"

# Registration Page Configuration Parameters
DEFAULT_FIRST_NAME = "Rupali"
DEFAULT_MIDDLE_NAME = "A"
DEFAULT_LAST_NAME = "Chavan"
INVALID_NAME_REGISTER = "invalidname!@#$%1"

REG_LIST_1 = [
    "      ",
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_2 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_3 = [
    '',
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_4 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    '',
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_5 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    '',
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_6 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    '',
    '',
    ''
]
REG_LIST_7 =[
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    '12345',
    '12345',
    ''
]
REG_LIST_8 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    'Test@1234567',
    'Test@123456',
    ''
]
REG_LIST_9 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_10 = [
    INVALID_NAME_REGISTER,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_11 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    INVALID_NAME_REGISTER,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_12 = [
    DEFAULT_FIRST_NAME,
    INVALID_NAME_REGISTER,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]
REG_LIST_13 = [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_USER,
    DEFAULT_PASS,
    DEFAULT_PASS,
    'INVALID'
]
REG_LIST_14= [
    DEFAULT_FIRST_NAME,
    DEFAULT_MIDDLE_NAME,
    DEFAULT_LAST_NAME,
    DEFAULT_INVALID_EMAIL_FORMAT,
    DEFAULT_PASS,
    DEFAULT_PASS,
    ''
]

# Account Setting

# File Upload Page Configuration Parameters
# DEFAULT_ORIGINAL_FILE_NAME = "H:\\Astronomy\\Images\\venera.gif"
DEFAULT_ORIGINAL_FILE_NAME = "/home/bhavya/Pictures/sample.gif"
DEFAULT_NEW_FILE_NAME = ""
DEFAULT_FILE_SIZE = ""
# FILE_WITH_MORE_THAN_5MB = "H:\\Astronomy\\Images\\PIA00272.jpg"
FILE_WITH_MORE_THAN_5MB = "/home/bhavya/Pictures/image_5mb.jpg"
FILE_WITH_SAME_NAME = "/home/bhavya/Pictures/sample.gif"
FILE_WITH_ZERO_BYTES ="/home/bhavya/Pictures/zero.jpg"
RECENT_FILE = "/home/bhavya/Pictures/sample.gif"
OTHER_FILE_TYPE = "/home/bhavya/Pictures/text.txt"

# Create New Topic Configuration Parameters
DEFAULT_NICK_NAME = "Pooja"
DEFAULT_TOPIC_NAME = ""
DEFAULT_NAMESPACE = ""
DEFAULT_NOTE = ""
DUPLICATE_TOPIC_NAME = "Theories of Consciousness"
DUPLICATE_CAMP_NAME = "Levels Of Testing"
INVALID_TOPIC_NAME = "Test@1234567"
INVALID_CAMP_NAME = "Test@1234567"
INVALID_CAMP_ABOUT_URL = "TestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestA1"
CAMP_LIST_1 = [
    "Test", "", "", "", "", "",""
]
CAMP_LIST_2 = [
    "Agreement",
    "Types Of Testing 1",
    "Test",
    "Testing Keywords, Keywords",
    "Just Note for testing",
    "www.google.com",
    "Pooja"
]
CAMP_LIST_3 = [
    "Agreement",
    "Types Of Testing 1",
    "Test",
    "Testing Keywords, Keywords",
    "Just Note for testing",
    "invalid url",
    "Pooja",
]
CAMP_LIST_4= [
    "Agreement",
    "Types Of Testing 1",
    "",
    "",
    "",
    "",
    "",
]
CAMP_LIST_5 = [
    "Agreement",
    "Types Of Testing 1",
    "",
    "           keywords with trailing spaces.",
    "          notes with trailing spaces.",
    "      www.google.com",
    "",
]
CAMP_LIST_6 = [
    "",
    "",
    "",
    "",
    "",
    "invalid url",
    "",
]
CAMP_LIST_7 = [
    "",
    "",
    DUPLICATE_CAMP_NAME,
    "",
    "",
    "",
    ""
]
CAMP_LIST_8 = [
    "",
    "",
    INVALID_CAMP_NAME,
    "",
    "",
    "",
    ""
]
CAMP_LIST_9 = [
    "",
    "",
    "",
    "",
    "",
    INVALID_CAMP_ABOUT_URL,
    ""
]
CAMP_LIST_10 = [
    "Test",
    "",
    "",
    "",
    "",
    "",
    ""
]
CREATE_CAMP_LIST_1 = [
    "",
    "",
    "",
    "",
    "",
    "",
    ""
]
CREATE_CAMP_LIST_2 = [
    "",
    "",
    DUPLICATE_CAMP_NAME,
    "",
    "",
    "",
    ""
]
CREATE_CAMP_LIST_3 = [
    "",
    "",
    INVALID_CAMP_NAME,
    "",
    "",
    "",
    ""
]

# Change Password Configuration Parameters
DEFAULT_CURRENT_PASSWORD = "Pooja@123456"
DEFAULT_NEW_PASSWORD = "Test@12345"
DEFAULT_CONFIRM_PASSWORD = "Test@12345"

# Add New Nick Name Configuration Parameters
DEF_NICK_NAME = "Pooja"

# Browse Page
DEF_MENU_ITEM = "/crypto_currency/"
NAME_SPACE_1 = "https://staging.canonizer.com/browse?namespace="

# Create New Thread Configure Parameters
DEFAULT_THREAD_NAME = "Test Thread Name 2"
DUPLICATE_THREAD_NAME = "Test Thread Name 1"
DEFAULT_THREAD_WITH_SPECIAL_CHAR = "Thread With $pecial char"
UPDATED_THREAD_TITLE = "Updated Test Thread Name 3"
UPDATED_THREAD_WITH_SPECIAL_CHAR = "Thread With $pecial char &&&"

DEFAULT_REPLY = "Test Reply 1"
DEFAULT_EDIT_REPLY = "Edited Reply 1"
print(DEFAULT_CHROME_DRIVER_LOCATION)
