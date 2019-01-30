import os
import platform

"""
Set All Basic Configuration required for testing Framework
"""

DEFAULT_BASE_URL = "https://staging.canonizer.com/"

"""
    Identify the Default Chrome Binary Location for different OS
"""
DEFAULT_BINARY_LOCATION = ''

if platform.system() == 'Darwin':
    DEFAULT_BINARY_LOCATION = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver_mac"
elif platform.system() == 'Windows':
    #DEFAULT_BINARY_LOCATION = "C:\\Users\\ajay\\AppData\\Local\\Google\\Chrome\\Application"
    DEFAULT_BINARY_LOCATION = "C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver"
elif platform.system() == 'Linux':
    DEFAULT_BINARY_LOCATION = "/usr/bin/google-chrome"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver"
else:
    print("Unknown OS")
    exit(1)

#DEFAULT_USER = "rupali.chavan9860@gmail.com"
DEFAULT_USER = "canonizer_automation_user@yopmail.com"
DEFAULT_PASS = "Rupali@12345"
DEFAULT_INVALID_USER = "invaliduser@gmail.com"
DEFAULT_INVALID_PASSWORD = "invalid_password"

# Registration Page Configuration Parameters
DEFAULT_FIRST_NAME = "Rupali"
DEFAULT_MIDDLE_NAME = ""
DEFAULT_LAST_NAME = "Chavan"

# File Upload Page Configuration Parameters
DEFAULT_ORIGINAL_FILE_NAME = ""
DEFAULT_NEW_FILE_NAME = ""
DEFAULT_FILE_SIZE = ""
FILE_WITH_MORE_THAN_5MB = "PIA00272.jpg"

# Create New Topic Configuration Parameters
DEFAULT_NICK_NAME = ""
DEFAULT_TOPIC_NAME = ""
DEFAULT_NAMESPACE = ""
DEFAULT_NOTE = ""
DUPLICATE_TOPIC_NAME = "26/10/2018"

# Change Password Configuration Parameters
DEFAULT_CURRENT_PASSWORD = "Rupali@12345"
DEFAULT_NEW_PASSWORD = "Test@12345"
DEFAULT_CONFIRM_PASSWORD = "Test@12345"



print(DEFAULT_CHROME_DRIVER_LOCATION)
