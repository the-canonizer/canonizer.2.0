import os

"""
Set All Basic Configuration required for testing Framework
"""

DEFAULT_BASE_URL = "https://canonizer.com/canonizer2.0/public/"

DEFAULT_CHROME_DRIVER_LOCATION = os.getcwd() + "/Webdrivers/chromedriver_mac"

DEFAULT_USER = "kukreti.ashutosh@gmail.com"
DEFAULT_PASS = "123456"
DEFAULT_INVALID_USER = "invaliduser@gmail.com"
DEFAULT_INVALID_PASSWORD = "invalid_password"

# Registration Page Configuration Paramters
DEFAULT_FIRST_NAME = "Ashutosh"
DEFAULT_MIDDLE_NAME = ""
DEFAULT_LAST_NAME = "Kukreti"

print (DEFAULT_CHROME_DRIVER_LOCATION)
