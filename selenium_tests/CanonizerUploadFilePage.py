from CanonizerBase import Page
from Identifiers import UploadFileIdentifiers


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

    def upload(self, originalfilename):
         self.enter_originalfile_name(originalfilename)
         self.click_upload_button()

    def upload_file_with_blank_file(self):
        self.upload('')
        return self.find_element(*UploadFileIdentifiers.ERROR_FILE_NAME).text

    def upload_file_with_size_file_more_than_5mb(self, originalfilename):
        self.upload(originalfilename)
        return self.find_element(*UploadFileIdentifiers.ERROR_FILE_SIZE).text

    def upload_file_with_valid_format(self, originalfilename):
        self.upload(originalfilename)
        return self

    def upload_file_with_same_file_name(self, originalfilename):
        self.upload(originalfilename)
        return self.find_element(*UploadFileIdentifiers.SAME_FILE_NAME_ERROR).text

    def upload_file_with_size_zero_bytes(self, originalfilename):
        self.upload(originalfilename)
        return self.find_element(*UploadFileIdentifiers.ERROR_ZERO_FILE_SIZE).text









