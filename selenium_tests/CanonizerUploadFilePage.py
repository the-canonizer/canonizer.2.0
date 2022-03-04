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
        title = self.find_element(*UploadFileIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.hover(*UploadFileIdentifiers.UPLOADFILE)
            self.find_element(*UploadFileIdentifiers.UPLOADFILE).click()
            upload_title = self.find_element(*UploadFileIdentifiers.UPLOAD_TITLE).text
            if upload_title in 'Upload Files, Max size 5 MB':
                time.sleep(3)
                return CanonizerUploadFilePage(self.driver)

    def upload_file_without_user_login(self):
        title = self.find_element(*UploadFileIdentifiers.TITLE).text
        if title == 'Canonizer Main Page':
            self.hover(*UploadFileIdentifiers.UPLOADFILE)
            self.find_element(*UploadFileIdentifiers.UPLOADFILE).click()
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

    def upload(self, originalfilename, file_name):
        self.enter_originalfile_name(originalfilename)
        time.sleep(5)
        self.enter_newfile_name(file_name)
        self.click_upload_button()

    def upload_file_with_blank_file(self):
        self.click_upload_button()
        error = self.find_element(*UploadFileIdentifiers.ERROR_FILE_NAME).text
        if error == 'Error! The file field is required':
            return CanonizerUploadFilePage(self.driver)

    def upload_file_with_size_file_more_than_5mb(self, originalfilename, file_name):
        self.enter_originalfile_name(originalfilename)
        time.sleep(5)
        self.enter_newfile_name(file_name)
        error = self.find_element(*UploadFileIdentifiers.ERROR_FILE_SIZE).text
        if error == 'Error! The file may not be greater than 5 MB':
            return CanonizerUploadFilePage(self.driver)

    def upload_file_with_valid_format(self, originalfilename, file_name):
        self.upload(originalfilename, file_name)
        success_message = self.find_element(*UploadFileIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Success! File uploaded successfully!':
            return CanonizerUploadFilePage(self.driver)

    def upload_file_with_same_file_name(self, originalfilename, file_name):
        self.upload(originalfilename, file_name)
        error = self.find_element(*UploadFileIdentifiers.SAME_FILE_NAME_ERROR).text
        if 'Error! There is already a file with name' in error:
            return CanonizerUploadFilePage(self.driver)

    def upload_file_with_size_zero_bytes(self, originalfilename, file_name):
        self.upload(originalfilename, file_name)
        error = self.find_element(*UploadFileIdentifiers.ERROR_ZERO_FILE_SIZE).text
        if 'Error! The file must be at least 1 kilobytes.' == error:
            return CanonizerUploadFilePage(self.driver)

    def verify_recent_upload_file_name_from_list_of_files(self, originalfilename, file_name):
        self.upload(originalfilename, file_name)
        success_message = self.find_element(*UploadFileIdentifiers.SUCCESS_MESSAGE).text
        if success_message == 'Success! File uploaded successfully!':
            recent_file_name = self.find_element(*UploadFileIdentifiers.RECENT_FILE_NAME).text
            if file_name in recent_file_name:
                return CanonizerUploadFilePage(self.driver)

    def verify_uploaded_image_file_format(self, originalfilename, file_name):
        self.upload(originalfilename, file_name)
        error_message = self.find_element(*UploadFileIdentifiers.ERROR_INVALID_FILE_FORMAT).text
        if error_message  in 'Error! The type of the uploaded file should be an image.(jpeg,jpg,png,bmp,gif)':
            return CanonizerUploadFilePage(self.driver)

    def open_uploaded_file(self):
        try:
            self.hover(*UploadFileIdentifiers.UPLOADED_FILE)
            self.find_element(*UploadFileIdentifiers.UPLOADED_FILE).click()
            time.sleep(2)
            return CanonizerUploadFilePage(self.driver)
        except NoSuchElementException:
            return False

    def upload_file_with_invalid_file_name_format(self, originalfilename):
        self.upload(originalfilename)
        error = self.find_element(*UploadFileIdentifiers.SAME_FILE_NAME_ERROR).text
        if error == 'Error! The type of the uploaded file should be an image.(jpeg,jpg,png,bmp,gif)':
            return CanonizerUploadFilePage(self.driver)
