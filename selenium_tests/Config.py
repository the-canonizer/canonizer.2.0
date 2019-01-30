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
    DEFAULT_BINARY_LOCATION = "C:\\Users\\UserName\\AppData\\Local\\Google\\Chrome\\Application"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver"
elif platform.system() == 'Linux':
    DEFAULT_BINARY_LOCATION = "/usr/bin/google-chrome"
    DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver"
else:
    print("Unknown OS")
    exit(1)

DEFAULT_USER = "kukreti.ashutosh@gmail.com"
DEFAULT_PASS = "Airtel@123"
DEFAULT_INVALID_USER = "invaliduser@gmail.com"
DEFAULT_INVALID_PASSWORD = "invalid_password"

# Registration Page Configuration Parameters
DEFAULT_FIRST_NAME = "Ashutosh"
DEFAULT_MIDDLE_NAME = ""
DEFAULT_LAST_NAME = "Kukreti"

DEFAULT_TESTING_THREAD_PATH = "https://staging.canonizer.com/forum/189-testing/1/threads"

print(DEFAULT_CHROME_DRIVER_LOCATION)
