from selenium.webdriver.common.action_chains import ActionChains
from Config import DEFAULT_BASE_URL


class Page(object):
    def __init__(self, driver, base_url=DEFAULT_BASE_URL):
        self.base_url = base_url
        self.driver = driver
        self.timeout = 30

    def find_element(self, *locator):
        return self.driver.find_element(*locator)

    def open(self, url):
        url = self.base_url + url
        self.driver.get(url)

    def get_title(self):
        return self.driver.title

    def get_url(self):
        return self.driver.current_url

    def hover(self, *locator):
        element = self.find_element(*locator)
        hover = ActionChains(self.driver).move_to_element(element)
        hover.perform()

    def get_attribute(self):
        return self.driver.attribute








