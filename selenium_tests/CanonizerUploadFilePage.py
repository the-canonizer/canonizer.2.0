from CanonizerBase import Page
from Identifiers import UploadFileIdentifiers
import time
from selenium.common.exceptions import NoSuchElementException


class CanonizerUploadFilePage(Page):
    """
    Class Name: CanonizerUploadFilePage

    """

    def click_upload_file_page_button(self):
        """
        -> Hover the control towards the upload file button. Identifiers are loaded from Identifiers Class
        -> Find the upload file button and Click on it.

        :return:
            Return the control to the main Program
        """

        self.hover(*UploadFileIdentifiers.UPLOADFILE)
        self.find_element(*UploadFileIdentifiers.UPLOADFILE).click()
        time.sleep(3)
        return CanonizerUploadFilePage(self.driver)

    def enter_originalfile_name(self, originalfilename):
        self.find_element(*UploadFileIdentifiers.FILE_NAME).send_keys(originalfilename)

    def enter_newfile_name(self, newfilename):
        self.find_element(*UploadFileIdentifiers.NEW_FILE_NAME).send_keys(newfilename)

    def click_upload_button(self):
        """
        This function clicks the Upload Button
        :return:
        """
        self.find_element(*UploadFileIdentifiers.UPLOAD).click()

    def upload(self, originalfilename, newfilename):
        self.enter_originalfile_name(originalfilename)
        self.enter_newfile_name(newfilename)
        self.click_upload_button()


    def upload_file_with_blank_file(self):
        self.upload('')
        return self.find_element(*UploadFileIdentifiers.ERROR_FILE_NAME).text

    def upload_file_with_size_file_more_than_5mb(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.ERROR_FILE_SIZE).text

    def upload_file_with_valid_format(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.SUCCESS_UPLOAD).text

    def upload_file_with_same_file_name(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.SAME_FILE_NAME_ERROR).text

    def upload_file_with_size_zero_bytes(self, originalfilename):
        self.upload(originalfilename)
        return self.find_element(*UploadFileIdentifiers.ERROR_ZERO_FILE_SIZE).text

    def upload_file_with_blank_filename(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.ERROR_BLANK_FILENAME).text

    def upload_file_with_blank_file_field(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.ERROR_BLANK_FILE_FIELD).text

    def upload_file_with_invalid_file_format(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.INVALID_FILE_FORMAT_ERROR).text

    def special_characters_are_not_allowed_in_filenames(self, originalfilename, newfilename):
        self.upload(originalfilename, newfilename)
        return self.find_element(*UploadFileIdentifiers.SPECIAL_CHARACTERS_ERROR).text

    def open_uploaded_file(self):
        self.hover(*UploadFileIdentifiers.UPLOADED_FILE)
        self.find_element(*UploadFileIdentifiers.UPLOADED_FILE).click()
        return CanonizerUploadFilePage(self.driver)
    """
    def open_uploaded_file(self):
        try:
            self.hover(*UploadFileIdentifiers.UPLOADED_FILE)
            self.find_element(*UploadFileIdentifiers.UPLOADED_FILE).click()
            time.sleep(2)
            return CanonizerUploadFilePage(self.driver)
        except NoSuchElementException:
            return False
        return True
    """
    def upload_file_with_invalid_file_name_format(self, originalfilename):
        self.upload(originalfilename)
        return self.find_element(*UploadFileIdentifiers.SAME_FILE_NAME_ERROR).text












