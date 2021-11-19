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

DEFAULT_USER = "rupali.chavan9860@gmail.com"
DEFAULT_PASS = "Rupali@12345"
#DEFAULT_USER = "r_canonizer_user@yopmail.com"
#DEFAULT_PASS = "Rupali@12345"
DEFAULT_INVALID_USER = 'invaliduser@gmail.com'
DEFAULT_INVALID_PASSWORD = "invalid_password"
DEFAULT_UNVERIFIED_PHONE_NUMBER = "1234567890"
DEFAULT_INVALID_PHONE_NUMBER = "1212121212"
DEFAULT_VALID_PHONE_NUMBER = ""
DEFAULT_INVALID_OTP = "123456789"
DEFAULT_INVALID_EMAIL_FORMAT = "test@test"

# Registration Page Configuration Parameters
DEFAULT_FIRST_NAME = "Rupali"
DEFAULT_MIDDLE_NAME = "A"
DEFAULT_LAST_NAME = "Chavan"
INVALID_NAME_REGISTER = "invalidname!@#$%1"

# File Upload Page Configuration Parameters
DEFAULT_ORIGINAL_FILE_NAME = "H:\\Astronomy\\Images\\venera.gif"
DEFAULT_NEW_FILE_NAME = ""
DEFAULT_FILE_SIZE = ""
FILE_WITH_MORE_THAN_5MB = "H:\\Astronomy\\Images\\PIA00272.jpg"
FILE_WITH_SAME_NAME = "H:\\Astronomy\\Images\\venera.gif"
FILE_WITH_ZERO_BYTES = "C:\\Users\\Ajay\\Desktop\\Canonizer\\New Bitmap Image.bmp"

# Create New Topic Configuration Parameters
DEFAULT_NICK_NAME = ""
DEFAULT_TOPIC_NAME = ""
DEFAULT_NAMESPACE = ""
DEFAULT_NOTE = ""
DUPLICATE_TOPIC_NAME = "Theories of Consciousness"
DUPLICATE_CAMP_NAME = "Levels Of Testing"
INVALID_TOPIC_NAME = "Test@1234567"
INVALID_CAMP_NAME = "Test@1234567"
INVALID_CAMP_ABOUT_URL = "TestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestATestA1"

# Change Password Configuration Parameters
DEFAULT_CURRENT_PASSWORD = "Rupali@12345"
DEFAULT_NEW_PASSWORD = "Test@12345"
DEFAULT_CONFIRM_PASSWORD = "Test@12345"

# Add New Nick Name Configuration Parameters
DEF_NICK_NAME = "Rupali C"



print(DEFAULT_CHROME_DRIVER_LOCATION)
