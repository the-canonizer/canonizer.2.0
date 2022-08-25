def test_cases(index):
    return testCases[index]


critical = "Critical"
major = "Major"
moderate = "Moderate"
low = "Low"

testCases = {

    'TC_LOAD_CANONIZER_HOME_PAGE': [critical, 'When user goes to Canonizer main page, page should be loaded Properly'],
    'TC_LOAD_REGITSER_PAGE': [critical, 'In Home page, when user click "Register" button, User should see User '
                                        'Registration Page'],
    'TC_LOAD_LOGIN_PAGE': [critical, 'In Home page, when user click "Login" button,  User should see Login Page'],
    'TC_LOGIN_WITH_VALID_USER': [critical, 'In Login Page, when user login with a valid user, he should see Home Page'],
    4: [critical, 'In Login Page, when user login with a in-valid user, user must see Error Message'],
    'TC_REGISTER_WITH_BLANK_FIRST_NAME': [moderate, 'In User Registration Page, When user doesn\'t put First Name, '
                                                    'user must see Error Message'],
    'TC_REGISTER_WITH_BLANK_LAST_NAME': [moderate, 'In User Registration Page, When user doesn\'t put Last Name, '
                                                   'user must see Error Message'],
    'TC_REGISTRATION_WITH_BLANK_EMAIL': [moderate, 'In User Registration Page, When user doesn\'t put Email, '
                                                   'user must see Error Message'],
    'TC_REGISTRATION_WITH_BLANK_PASSWORD': [moderate, 'In User Registration Page, When user puts blank password, '
                                                      'user must see Error Message'],
    'TC_REGISTRATION_WITH_INVALID_PASSWORD_LENGTH': [moderate, 'In User Registration Page, when user puts invalid '
                                                               'password,user must see Error Message'],
    'TC_REGISTRATION_WITH_DIFFERENT_CONFIRM_PASSWORD': [moderate,
                                                        'In User Registration Page, when user puts different password '
                                                        'for confirmation,user must see Error Message'],
    'TC_VERIFY_REGISTRATION_PLACEHOLDERS': [moderate,
                                            'Verifing registration text box placeholders, every text box must have placeholder'],
    'TC_LOAD_WHAT_IS_CANONIZER_PAGE': [critical, 'When user clicks on What is Canonizer.com, page should be loaded.'],
    'TC_JOIN_SUPPORT_WITH_LOGIN': [critical,
                                   'If any unauthenticated user wants to join the camp, User should be directed to '
                                   'the login page'],
    'TC_LOAD_DIRECT_JOIN_AND_SUPPORT': [critical, 'If user is authenticated and wants to support, User should direct '
                                                  'to '
                                                  'Direct Join and Support Page'],
    'TC_VERIFY_SINGLE_SUPPORT_ON_NEW_TOPIC_CREATION': [critical, 'Once User create New Topic, Support should get added '
                                                                 'automatically '
                                                                 'to Agreement camp and  user should see "Manage '
                                                                 'Support Button "'],
    'TC_VERIFY_WARNING_DIRECTLY_SUPPORTING_CHILD_CAMP': [critical,
                                                         'Once user try to support child camp, User must see warning'],
    'TC_VERIFY_SUPPORT_TO_CHILD_CAMP': [critical, 'When user support child camp, Support should get added to child '
                                                  'camp '
                                                  ', Support should get remove from Agreement camp'],
    'TC_REQUEST_OTP_WITH_BLANK_EMAIL': [low, 'In Request OTP Page, When user does\'t put Email/Phone Number, '
                                             'user must see Error Message'],
    'TC_REGISTER_PAGE_SHOULD_HAVE_LOGIN_OPTION_FOR_EXISTING_USER': [low, 'Register page should have "Login" '
                                                                         'option/button for existing users'],
    'TC_LOGIN_PAGE_SHOULD_HAVE_REGISTER_OPTION_FOR_FEW_USERS': [low, 'Login Page should have "Register" Option/button '
                                                                     'for new users'],
    'TC_REGISER_PAGE_MANDATORY_FIELDS_MARKED_WITH_ASTERISK': [low,
                                                              'On Registration Page, All Mandatory Fields are marked '
                                                              'with * Sign'],
    'TC_LOAD_FORGOT_PASSWORD_PAGE': [critical,
                                     'In forgot password page, When user click on "Forgot Password" link ,User should '
                                     'see forgot password page'],
    'TC_LOAD_FORGOT_PASSWORD_PAGE': [critical,
                                     'In forgot password page, When user doesn\'t put Email, user must see error '
                                     'message.'],
    'TC_FORGOT_PASSWORD_WITH_INVALID_EMAIL': [critical,
                                              'In forgot password page, When user put Email which is not in database, '
                                              'user must see error message'],
    'TC_FORGOT_PASSWORD_WITH_VALID_EMAIL': [critical, 'In forgot password page, When user put valid Email,User should '
                                                      'receive reset link '],
    'TC_FORGOT_PASSWORD_PAGE_MANDATORY_FIELDS_WITH_ASTERISK': [critical, 'On forgot password Page, All Mandatory '
                                                                         'Fields are marked with * Sign'],
    'TC_LOAD_BROWSE_PAGE': [critical, 'In Browse page, When user click on "Browse" ,user should see browse page'],
    'TC_CHECK_NAMESPACE_DROPDOWN': [critical, 'In create new topic namespace should have all, which are there in '
                                              'Browse page.'],
    'TC_VERIFYING_NAMEPSACE_ONE_BY_ONE': [critical, 'Verifying each namespace and my topics as well one by one'],
    'TC_CLICK_ONLY_MY_TOPICS_BUTTON': [critical, 'In Browse page, When user click on "Only My Topics", user should '
                                                 'see only topics created by own'],
    'TC_VERFIFY_UPLOAD_BUTTON': [critical, 'In Upload File page, When user click on "Upload File",user should see '
                                           'upload file page'],
    'TC_VERIFY_LOGIN_ON_UPLOAD_FILE_PAGE': [critical, "If user is not logged in, and click on 'Upload File', "
                                                      "user should redirect to login page"],
    'TC_VERIFY_UPLOAD_FILE_BUTTON_IS_CLICKABLE': [critical, "In Upload file page, user should be able to click on "
                                                            "upload button"],
    'TC_VERIFY_CHOOSE_FILE_BUTTON_IS_CLICKABLE': [critical, "In Upload file page, user should be able to click on "
                                                            "Choose File Button"],
    'TC_VERIFY_FILE_UPLOAD_WARNING': [critical, "If user Try to delete the file, user must see warning."],
    'TC_UPLOAD_FILE_WITH_BLANK_FIEL': [critical, 'In Upload File page, When user doesn\'t put File name,user should '
                                                 'see error message'],
    'TC_CREATE_TOPIC_WITH_USER_LOGIN': [critical,
                                        'In Create New Topic page, When user is login and try to click on "Create New '
                                        'Topic", user should see Create '
                                        'New Topic page'],
    'TC_CREATE_TOPIC_WITH_BLANK_NICK_NAME': [critical, 'In Create New Topic page, When user doesn\'t put Nick name,'
                                                       'user should see error message'],
    'TC_CREATE_TOPIC_WITH_BLANK_TOPIC_NAME': [critical, 'In Create New Topic page, When user doesn\'t put Topic name,'
                                                        'user should see error message'],
    'TC_CREATE_TOPIC_WITH_BLANK_SPACES_TOPIC_NAME': [critical,
                                                     'In Create New Topic page, When user put blank spaces in topic name,user should see error message'],
    'TC_CREATE_NEW_TOPIC_MANADATORY_FIELDS_WITH_ASTERIK': [low,
                                                           'On Create New Topic Page, All Mandatory Fields are marked '
                                                           'with * Sign'],
    'TC_CREATE_TOPIC_WITH_DUPLICATE_NAME': [moderate, 'In Create New Topic page, When user enter duplicate Topic '
                                                      'name,user should see error message'],
    'TC_CREATE_TOPIC_WITHOUT_USER_LOGIN': [low, 'When user goes click on Create New Topic without logging in, '
                                                'Login Page page should be loaded'],
    'TC_CLICK_LOGOUT_PAGE_BUTTON': [critical, 'In log out page, user should see home page'],
    'TC_LOAD_ACCOUNT_SETTING_PAGE': [critical,
                                     'In Account Settings page, When user click on "Account Settings" ,user should '
                                     'see Account Settings page'],
    'TC_CLICK_ACCOUNT_SETTINGS_MANAGE_PROFILE_INFO_PAGE_BUTTON': [critical,
                                                                  'In Account Settings page, When user click on '
                                                                  '"Manage Profile Info" subtab,user should see '
                                                                  'Profile page'],
    'TC_CLICK_ACCOUNT_SETTING_ADD_MANAGE_NICKANAMES_PAGE_BUTTON': [critical,
                                                                   'In Account Settings page, When user click on "Add '
                                                                   '& Manage Nick Names" subtab,user should see Nick '
                                                                   'Names page'],
    'TC_CLICK_ACCOUNT_SETTINGS_MY_SUPPORTS_PAGE_BUTTON': [critical,
                                                          'In Account Settings page, When user click on "My Supports" '
                                                          'subtab,user should see Supported Camps page'],
    'TC_CLICK_ACCOUNT_SETTINGS_SOCAIL_OATH_VERIFICATION_PAGE_BUTTON': [critical,
                                                                       'In Account Settings page, When user click on '
                                                                       '"Social Oauth Verification" subtab,'
                                                                       'user should see Social '
                                                                       'Oauth Verification page'],
    'TC_CLICK_ACCOUNT_SETTINGS_CHANGE_PASSWORD_PAGE_BUTTON': [critical,
                                                              'In Account Settings page, When user click on "Change '
                                                              'Password" subtab,user should see Change Password page'],
    'TC_CHANGE_PASSWORD_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [moderate, 'On Change Password Page, All '
                                                                                    'Mandatory Fields are marked with '
                                                                                    '* Sign'],
    'TC_CHANGE_PASSWORD_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [moderate,
                                                                          'On Change Password Page, When user '
                                                                          'doesn\'t put Current Password name,'
                                                                          'user should see error message'],
    'TC_SAVE_WITH_BLANK_NEW_PASSWORD': [moderate, 'On Change Password Page, When user doesn\'t put New Password name,'
                                                  'user should see error message'],
    'TC_SAVE_WITH_BLANK_CONFIRM_PASSWORD': [moderate,
                                            'On Change Password Page, When user doesn\'t put Confirm Password name,'
                                            'user should see error message'],
    'TC_SAVE_WITH_INVALID_CURRENT_PASSWORD': [moderate,
                                              'On Change Password Page, When user puts invalid current password, '
                                              'user must see Error Message '],
    'TC_SAVE_WIH_MISMATCH_NEW_CONFIRM_PASSWORD': [moderate,
                                                  'On Change Password Page, When user puts mismatch password, '
                                                  'user must see Error Message'],
    'TC_SAVE_WITH_SAME_NEW_AND_CURRENT_PASSWORD': [moderate,
                                                   'On Change Password Page, When new password is same as current, '
                                                   'user must see Error Message'],
    'TC_LOAD_WHAT_IS_CANONIZER_HELP_PAGE_WITHOUT_LOGIN': [critical,
                                                          'When user goes click on Help without logging in, Help page '
                                                          'should be loaded Properly'],
    'TC_LOAD_WHAT_IS_CANONIZER_HELP_PAGE_WITHOUT_LOGIN': [critical, 'When user goes click on Help with login, Help '
                                                                    'page should be loaded Properly'],
    'TC_CHECK_STEPS_TO_CREATE_A_NEW_TOPIC_PAGE_LOADED_WITH_LOGIN': [critical, 'When user click on Steps to Create a '
                                                                              'New Topic with login, page should be '
                                                                              'loaded Properly'],
    'TC_CHECK_DEALING_WITH_DISAGREEMENTS_PAGE_LOADED_WITH_LOGIN': [critical, 'When user click on Dealing With '
                                                                             'Disagreements with login, page should '
                                                                             'be loaded Properly'],
    'TC_LOAD_WIKI_MARKUP_INFORMATION_PAGE_LOAD_WITH_LOGIN': [critical, 'When user click on Wiki Markup Information '
                                                                       'with login, page should be loaded Properly'],
    'TC_VERIFY_CANONIZER_FEEDBACK_CAMP_OUTLINE_LOADED_PAGE_LOADED': [critical,
                                                                     'When user click on Adding the Canonizer '
                                                                     'Feedback Camp Outline to Internet Articles with '
                                                                     'login, page should '
                                                                     'be loaded Properly'],
    'TC_CHECK_STEP_TO_CREATE_A_NEW_TOPIC_PAGE_LOADED_WITHOUT_LOGIN': [critical, 'When user click on Steps to Create a '
                                                                                'New Topic without login, page should '
                                                                                'be loaded Properly'],
    'TC_CHECK_DEALING_WITH_DISAGREEMENTS_PAGE_LOADED_WITHOUT_LOGIN': [critical, 'When user click on Dealing With '
                                                                                'Disagreements without login, '
                                                                                'page should be loaded Properly'],
    'TC_VERIFY_WIKI_MARKUP_INFORMATION_PAGE_LOADED_WITH_LOGIN': [critical, 'When user click on Wiki Markup Information '
                                                                           'without login, page should be loaded '
                                                                           'Properly'],
    'TC_VERIFY_ADDING_THE_CANONIZER_FEEDBACK_CAMP_OUTLINE_TO_INTERNET_ARTICLES_PAGE_LOADED': [critical,
                                                                                              'When user click on '
                                                                                              'Adding the Canonizer '
                                                                                              'Feedback Camp Outline '
                                                                                              'to Internet Articles '
                                                                                              'without login, '
                                                                                              'page should be loaded '
                                                                                              'Properly'],
    'TC_NICK_NAMES_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [critical,
                                                                     'On Nick Names Page, All Mandatory Fields are '
                                                                     'marked with * Sign'],
    'TC_CREATE_WITH_BLANK_NICK_NAME': [critical, 'On Nick Names Page, When user doesn\'t put Nick Name , user must '
                                                 'see Error Message'],
    'TC_CREATE_WITH_DUPLICATE_NICK_NAME': [critical,
                                           'On Nick Names Page, When user put duplicate Nick Name , user must see '
                                           'Error Message'],
    'TC_CREATE_WITH_BLANK_SPACES_NICK_NAME': [critical, 'On Nick Names Page, When user put blank spaces in Nick Name, '
                                                        'user must see Error Message'],
    'TC_CREATE_NICK_NAME_WITH_TRAILING_SPACES': [low,
                                                 'On Nick Names Page, When user put trailing spaces before entering '
                                                 'Nick Name, nick name '
                                                 'should get add'],
    'TC_CLICK_BROWSE_PAGE_BUTTON_WITHOUT_LOGIN': [critical, 'In Browse page, When user click on "Browse" ,user should '
                                                            'see browse page'],
    'TC_MANAGE_PROFILE_INFO_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [critical, 'On Manage Profile Info Page, '
                                                                                        'All Mandatory Fields are '
                                                                                        'marked with * Sign'],
    'TC_UPDATE_PROFILE_WITH_BLANK_FIRST_TIME': [critical, 'On Manage Profile Info Page, When user doesn\'t put First '
                                                          'Name , user must see Error Message'],
    'TC_UPDATE_PROFILE_WITH_BLANK_LAST_NAME': [critical, 'On Manage Profile Info Page, When user doesn\'t put Last '
                                                         'Name , user must see Error Message'],
    'TC_SELECT_BY_VALUE_GENERAL': [critical,
                                   'On Browse Page,When user select /general/ namespace from drop down, User must see '
                                   'topics under /general/ '
                                   'namespace'],
    'TC_SELECT_BY_VALUE_CORPORATIONS': [critical,
                                        'On Browse Page,When user select /corporations/ namespace from drop down, '
                                        'User must see topics under '
                                        '/corporations/ namespace'],
    'TC_SELECT_BY_VALUE_CRYPTO_CURRENCY': [critical,
                                           'On Browse Page,When user select /crypto_currency/ namespace from drop '
                                           'down, User must see topics under '
                                           '/crypto_currency/ namespace'],
    'TC_SELECT_BY_VALUE_FAMILY': [critical,
                                  'On Browse Page,When user select /family/ namespace from drop down, User must see '
                                  'topics under /family/ '
                                  'namespace'],
    'TC_SELECT_BY_VALUE_FAMILY_JESPERSON_OSCAR_F': [critical,
                                                    'On Browse Page,When user select /family/Jesperson_Oscar_F/ '
                                                    'namespace from drop down, User must see topics '
                                                    'under /family/Jesperson_Oscar_F/ namespace'],
    'TC_SELECT_BY_VALUE_OCCUPY_WALL_STREET': [critical,
                                              'On Browse Page,When user select /Occupy Wall Street/ namespace from '
                                              'drop down, User must see topics under /Occupy Wall Street/ namespace'],
    'TC_SELECT_BY_VALUE_ORGRANIZATIONS': [critical,
                                          'On Browse Page,When user select /organizations/ namespace from drop down, '
                                          'User must see topics under /organizations/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER': [critical,
                                                   'On Browse Page,When user select /organizations/canonizer/ '
                                                   'namespace from drop down, User must see topics under '
                                                   '/organizations/canonizer/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER_HELP': [critical,
                                                        'On Browse Page,When user select '
                                                        '/organizations/canonizer/help/ namespace from drop down, '
                                                        'User must see topics under /organizations/canonizer/help/ '
                                                        'namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_MTA': [critical,
                                             'On Browse Page,When user select /organizations/mta/ namespace from drop '
                                             'down, User must see topics under /organizations/mta/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_TV07': [critical,
                                              'On Browse Page,When user select /organizations/TV07/ namespace from '
                                              'drop down, User must see topics under /organizations/TV07/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_WTA': [critical,
                                             'On Browse Page,When user select /organizations/wta/ namespace from drop '
                                             'down, User must see topics under /organizations/wta/ namespace'],
    'TC_SELECT_BY_VALUE_PERSONAL_ATTRIBUTES': [critical,
                                               'On Browse Page,When user select /personal_attributes/ namespace from '
                                               'drop down, User must see topics under /personal_attributes/ '
                                               'namespace'],
    'TC_SELECT_BY_VALUE_PERSONAL_REPUTATIONS': [critical,
                                                'On Browse Page,When user select /personal_reputations/ namespace '
                                                'from drop down, User must see topics under /personal_reputations/ '
                                                'namespace'],
    'TC_SELECT_BY_VALUE_PROFESSIONAL_SERVICES': [critical,
                                                 'On Browse Page,When user select /professional_services/ namespace '
                                                 'from drop down, User must see topics under /professional_services/ '
                                                 'namespace'],
    'TC_SELECT_BY_VALUE_SANDBOX': [critical,
                                   'On Browse Page,When user select /sandbox/ namespace from drop down, User must see '
                                   'topics under /sandbox/ namespace'],
    'TC_SELECT_BY_VALUE_TERMINOLOGY': [critical,
                                       'On Browse Page,When user select /terminology/ namespace from drop down, '
                                       'User must see topics under '
                                       '/terminology/ namespace'],
    'TC_SELECT_BY_VALUE_WWW': [critical,
                               'On Browse Page,When user select /www/ namespace from drop down, User must see topics '
                               'under /www/ namespace'],
    'TC_SELECT_BY_VALUE_ALL': [critical,
                               'On Browse Page,When user select All namespace from drop down, User must see topics '
                               'under All namespace'],
    'TC_SELECT_BY_VALUE_ALL_DEFAULT': [critical,
                                       'On Browse Page,When user click on Browse, User must see topics under All '
                                       'namespace'],
    'TC_SELECT_BY_VALUE_GENERAL_ONLY_MY_TOPICS': [critical,
                                                  'On Browse Page,When user select /general/ namespace and Only My '
                                                  'Topics, User must see own topics under '
                                                  '/general/ namespace'],
    'TC_SELECT_BY_VALUE_COOPERATIONS_ONLY_MY_TOPICS': [critical,
                                                       'On Browse Page,When user select /corporations/ namespace and '
                                                       'Only My Topics, User must see own topics under '
                                                       '/corporations/ namespace'],
    'TC_SELECT_VY_VALUE_CRYPTO_CURRENCY_ONLY_MY_TOPICS': [critical,
                                                          'On Browse Page,When user select /crypto_currency/ '
                                                          'namespace and Only My Topics, User must see own topics '
                                                          'under /crypto_currency/ namespace'],
    'TC_SELECT_BY_VALUE_FAMILY_ONLY_MY_TOPICS': [critical,
                                                 'On Browse Page,When user select /family/ namespace and Only My '
                                                 'Topics, User must see own topics under '
                                                 '/family/ namespace'],
    'TC_SELECT_BY_VALUE_FAMILY_JESPERSON_OSCAR_F_ONLY_MY_TOPICS': [critical,
                                                                   'On Browse Page,When user select '
                                                                   '/family/Jesperson_Oscar_F/ namespace and Only My '
                                                                   'Topics, User must see own '
                                                                   'topics under /family/Jesperson_Oscar_F/ namespace'],
    'TC_SELECT_BY_VALUE_OCCUPY_WALL_STREET_ONLY_MY_TOPICS': [critical,
                                                             'On Browse Page,When user select /Occupy Wall Street/ '
                                                             'namespace and Only My Topics, User must see own topics '
                                                             'under /Occupy Wall Street/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZAIIONS_ONLY_MY_TOPICS': [critical,
                                                        'On Browse Page,When user select /organizations/ namespace '
                                                        'and Only My Topics, User must see own topics '
                                                        'under /organizations/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_CNAONIZER_ONLY_MY_TOPICS': [critical,
                                                                  'On Browse Page,When user select '
                                                                  '/organizations/canonizer/ namespace and Only My '
                                                                  'Topics, User must see own topics under '
                                                                  '/organizations/canonizer/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_CANONIZER_HELP_ONLY_MY_TOPICS': [critical,
                                                                       'On Browse Page,When user select '
                                                                       '/organizations/canonizer/help/ namespace and '
                                                                       'Only My Topics, User must see '
                                                                       'own topics under /organizations/canonizer/help/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_MTA_ONLY_MY_TOPICS': [critical,
                                                            'On Browse Page,When user select /organizations/mta/ '
                                                            'namespace and Only My Topics, User must see own topics '
                                                            'under /organizations/mta/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_TV07_ONLY_MY_TOPICS': [critical,
                                                             'On Browse Page,When user select /organizations/TV07/ '
                                                             'namespace and Only My Topics, User must see own topics '
                                                             'under /organizations/TV07/ namespace'],
    'TC_SELECT_BY_VALUE_ORGANIZATIONS_WTA_ONLY_MY_TOPICS': [critical,
                                                            'On Browse Page,When user select /organizations/wta/ '
                                                            'namespace and Only My Topics, User must see own topics '
                                                            'under /organizations/wta/ namespace'],
    'TC_SELECT_BY_VALUE_PERSONAL_ATTRIBUTES_ONLY_MY_TOPICS': [critical,
                                                              'On Browse Page,When user select /personal_attributes/ '
                                                              'namespace and Only My Topics, User must see own '
                                                              'topics under /personal_attributes/ namespace'],
    'TC_SELECT_BY_VALUE_PERSONAL_REPUTATIONS_ONLY_MY_TOPICS': [critical,
                                                               'On Browse Page,When user select '
                                                               '/personal_reputations/ namespace and Only My Topics, '
                                                               'User must see own topics under /personal_reputations/ '
                                                               'namespace'],
    'TC_SELECT_BY_VALUE_PROFESSIONAL_SERVICES_ONLY_MY_TOPICS': [critical,
                                                                'On Browse Page,When user select '
                                                                '/professional_services/ namespace and Only My '
                                                                'Topics, User must see own topics under '
                                                                '/professional_services/ namespace'],
    'TC_SELECT_BY_VALUE_SANDBOX_ONLY_MY_TOPICS': [critical,
                                                  'On Browse Page,When user select /sandbox/ namespace and Only My '
                                                  'Topics, User must see own topics under /sandbox/ namespace'],
    'TC_SELECT_BY_VALUE_TERMINOLOGY_ONLY_MY_TOPICS': [critical,
                                                      'On Browse Page,When user select /terminology/ namespace and '
                                                      'Only My Topics, User must see own topics under /terminology/ '
                                                      'namespace'],
    'TC_SELECT_BY_VALUE_WWW_ONLY_MY_TOPICS': [critical,
                                              'On Browse Page,When user select /www/ namespace and Only My Topics, '
                                              'User must see own topics under /www/ namespace'],
    'TC_SELECT_BY_VALUE_ALL_ONLY_MY_TOPICS': [critical,
                                              'On Browse Page,When user select All namespace and Only My Topics, '
                                              'User must see own topics under All '
                                              'namespace'],
    'TC_LOAD_WHITE_PAPER_WITH_LOGIN': [critical, 'When user logged in and clicks on White Paper, User should see '
                                                 'white paper in new window'],
    'TC_LOAD_WHITE_PAPER_WITHOU_LOGIN': [critical, 'When user without logged in clicks on White Paper, User should '
                                                   'see white paper in new window'],
    'TC_LOAD_BLOG_PAGE_WITH_LOGIN': [critical,
                                     'When user logged in and clicks on Blog Page, User should see blog page '],
    'TC_LOAD_BLOG_PAGE_WITHOUT_LOGIN': [critical,
                                        'When user without logged in clicks on Blog Page, User should see blog page '],
    'TC_VERIFY_ALGORITHM_INFORMATION_PAGE_SHOULD_OPEN': [critical, 'When user click on Algorithm Information link,'
                                                                   'user should see all canonizer algorithm'],
    'TC_VERIFY_INCLUDE_REVIEW_FILTER_APPLIED': [critical, 'When user click on include review radio button,filter '
                                                          'should applied'],
    'TC_CHECK_DEFAULT_FILTER_APPLIED': [critical, 'When user click on default radio button,filter should applied'],
    'TC_VERIFY_AS_OF_DATE_FILTER_APPLIED': [critical, 'When user click on as of (yy/mm/dd) radio button,filter '
                                                      'should applied'],
    'TC_LOAD_TOPIC_UPDATE_PAGE': [critical, 'When user click on Manage/Edit Topic ,user should see topic update page'],
    'TC_LOAD_VIEW_THIS_VERSION_PAGE': [critical,
                                       'When user click on View This Version ,user should see agreement page'],
    'TC_LOAD_TOPIC_OBJECT_PAGE': [critical, 'When user click on Object ,user should see Object to this proposed '
                                            'update page'],
    'TC_TOPIC_UPDATE_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [low, 'On Topic Update, All Mandatory Fields '
                                                                            'are marked with * Sign'],
    'TC_TOPIC_OBJECTION_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [low, 'On Topic objection, All Mandatory '
                                                                               'Fields are marked with * Sign'],
    'TC_TOPIC_UPDATE_PAGE_SHOULD_HAVE_ADD_NEW_NICK_NAME_LINK_FOR_NEW_USERS': [low, 'On Topic Update, Add New Nick '
                                                                                   'Name link should present for '
                                                                                   'users who doesn\'t have nick name '
                                                                                   'yet'],
    'TC_SUBMIT_UPDATE_WITH_BLANK_NICK_NAME': [low, 'On Topic Update page, When user doesn\'t put Nick Name , '
                                                   'user must see Error Message'],
    'TC_LOAD_CREATE_NEW_CAMP_PAGE': [moderate, 'When user click on Create New Camp ,user should see Create New Camp '
                                               'page'],
    'TC_CREATE_NEW_CAMP_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [low, 'On Create New Camp page, All Mandatory '
                                                                          'Fields are marked with * Sign'],
    'TC_CREATE_CAMP_WITH_BLANK_NICK_NAME': [low, 'On Create New Camp page, When user doesn\'t put Nick Name , '
                                                 'user must see Error Message'],
    'TC_CREATE_CAMP_WITH_BLANK_CAMP_NAME': [low,
                                            'On Create New Camp page, When user doesn\'t put Camp Name , user must '
                                            'see Error Message'],
    'TC_CREATE_CAMP_WITH_INVALID_DATA': [critical, 'On Create New Camp Page, if user enter invalid data, user must '
                                                   'see error message'],
    'TC_CREATE_CAMP_WITHOUT_ENTERING_DATA_IN_MANDATORY_FIELDS': [critical, 'On Create New Camp page, if user doesn\'t '
                                                                           'enter data and submit the camp, '
                                                                           'user must see error messages'],
    'TC_CREATE_CAMP_WITH_EXISTING_DATA': [critical, 'On Create New Camp Page, If user enter existing data, use must '
                                                    'see error message'],
    'TC_LOAD_CAMP_UPDATE_PAGE': [moderate, 'When user click on Manage/Edit Camp ,user should see Camp Edit page'],
    'TC_EDIT_CAMP_PAGE_MANDATORY_FIELDS_WITH_ASTERISK': [low, 'On Camp Update, All Mandatory Fields are marked with * '
                                                              'Sign'],
    'TC_SUBMIT_CAMP_UPDATE_WITH_BLANK_NICK_NAME': [low, 'On Camp Update page, When user doesn\'t put Nick Name , '
                                                        'user must see Error Message'],
    'TC_LOAD_EDIT_CAMP_STATEMENT_HISTORY_PAGE': [moderate, 'When user click on Manage/Edit Camp Statement ,user '
                                                           'should see '
                                                           'Statement Update page'],
    'TC_VERIFY_HISTORY_ON_EDIT_CAMP_STATEMENT': [moderate, 'When user click on Manage/Edit Camp Statement, use should '
                                                           'see, object, live, in-review, old data'],
    'TC_LOAD_EDIT_CAMP_STATEMENT_VIEW_THIS_VERSION': [moderate, 'When user click on "View This Version" on Manage/Edit '
                                                                'Camp Statement, Agreement Page should open'],
    'TC_LOAD_EDIT_CAMP_STATEMENT_PAGE': [moderate, 'When user click on "Submit Statement '
                                                   'Update Based on This" on Manage/Edit '
                                                   'Camp Statement, Agreement Page should open'],
    'TC_VERIFY_EDITABLE_FIELDS_ON_EDIT_CAMP_STATEMENT_PAGE': [moderate,
                                                              'In Camp Statment Update Page, fields should be editable.'],
    'TC_EDIT_CAMP_STATEMENT_PAGE_MANDATORY_FIELDS_WITH_ASTERISK': [low, 'On Statement Update page, All Mandatory '
                                                                        'Fields are marked with * Sign'],
    'TC_EDIT_CAMP_STATEMENT_WITHOUT_MANDATORY_FIELDS': [critical,
                                                        'On Statement Update Page, If user submit form without '
                                                        'mandatory data, user must see error message'],
    'TC_SUBMIT_STATEMENT_UPDATE_WITH_BLANK_NICK_NAME': [low, 'On Statement Update page, When user doesn\'t put Nick '
                                                             ''
                                                             'Name , user must see Error Message'],
    'TC_LOAD_ADD_NEWS_FEED_PAGE': [moderate, 'When user click on Add News ,user should see Add News page'],
    'TC_ADD_NEWS_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [moderate, 'On Add News Page, All mandatory fields '
                                                                             'are marked with * sign'],
    'TC_CREATE_NEWS_WITH_BLANK_DISPLAY_TEXT': [moderate, 'On Add News page, When user doesn\'t put Display Text , '
                                                         'user must see Error Message'],
    'TC_CREATE_NEWS_WITH_BLANK_LINK': [moderate, 'On Add News page, When user doesn\'t put Link , user must see Error '
                                                 'Message'],
    'TC_CLICK_ADD_NEWS_CANCEL_BUTTON': [moderate, 'On Add News Page, When user click on cancel button user should see '
                                                  'agreement page'],
    'TC_LOAD_EDIT_NEWS_FEED_PAGE': [moderate, 'When user click on Edit News ,user should see Edit News page'],
    'TC_CLICK_EDIT_NEWS_CANCEL_BUTTON': [moderate, 'On Edit News Page, When user click on cancel button user should '
                                                   'see agreement page'],
    'TC_UPDATE_NEWS_WITH_BLANK_DISPLAY_TEXT': [moderate, 'On Edit News page, When user doesn\'t put Display Text , '
                                                         'user must see Error Message'],
    'TC_UPDATE_NEWS_WITH_BLANK_LINK': [moderate, 'On Edit News page, When user doesn\'t put Link , user must see '
                                                 'Error Message'],
    'TC_UPDATE_NEWS_WITH_INVALID_LINK_FORMAT': [moderate, 'On Edit News page, When user put invalid Link , user must '
                                                          'see Error Message'],
    'TC_CREATE_NEWS_WITH_INVALID_LINK_FORMAT': [moderate, 'On Add News page, When user put invalid Link , user must '
                                                          'see Error Message'],
    'TC_CREATE_NEWS_WITH_VALID_DATA': [moderate, 'On Add News page, When user put valid data , news should get added'],
    'TC_CREATE_NEWS_WITH_MANDATORY_FIELDS_ONLY': [moderate,
                                                  'On Add News page, use enter data only in manadatory fields, '
                                                  'news should get added.'],
    'TC_UPDATE_NEWS_WITH_VALID_DATA': [moderate,
                                       'On Edit News page, When user put valid data , news should get updated'],
    'TC_UPLOAD_FILE_WITH_VALID_FORMAT': [critical,
                                         'On File Upload page, When user upload file other than jpeg,bmp,png,jpg,'
                                         'gif ,user should see error message'],
    'TC_UPLOAD_FILE_WITH_SIZE_FILE_MORE_THAN_5MB': [critical, 'On File Upload page, When user upload file more than '
                                                              '5mb size ,user should see error message'],
    'TC_UPLOAD_FILE_WITH_SAME_FILE_NAME': [critical,
                                           'On File Upload page, When user upload file with same file name ,'
                                           'user should see error message'],
    'TC_UPLOAD_FILE_WITH_SIZE_ZERO_BYTES': [critical,
                                            'On File Upload page, When user upload file with 0 bytes ,user should see '
                                            'error message'],
    'TC_CLICK_SEARCH_BUTTON': [low,
                               'When user check canonizer.com and click on Google Search button, user should redirect '
                               'to canonizer.com search page'],
    'TC_CLICK_SEARCH_BUTTON_WEB': [low,
                                   'When user check web and click on Google Search button, user should redirect to '
                                   'google search page'],
    'TC_CLICK_SEARCH_BUTTON_KEYWORD_WEB': [low,
                                           'When user enters search keyword,check web and click on Google Search '
                                           'button, user should redirect to google search page'],
    'TC_CLICK_SEARCH_BUTTON_KEYWORD_CANONIZER_COM': [low,
                                                     'When user enters search keyword,check canonizer.com and click '
                                                     'on Google Search button, user should '
                                                     'redirect to canonizer.com search page'],
    'TC_VERIFY_PHONE_NUMBER_WITH_BLANK_PHONE_NUMBER': [low,
                                                       'On Manage Profile Info Page, When user doesn\'t put Phone Number , user must see Error Message'],
    'TC_SEARCH_BAR_PLACEHOLDER': [critical, 'On Main page user must see placeholder, once user start typing the '
                                            'placeholder text should get disappear.'],
    'TC_PHONE_NUMBER_02': ['low',
                           'On Manage Profile Info Page, When user enter characters instead of numbers, User must see '
                           'Error Message'],
    'TC_SUPPORT_PAGE_01': [critical, 'On Support Page in Account Setting Page, User should be able to delete any '
                                     'support'],
    'TC_VERIFY_FORGOT_PASSWORD_SAVE_BUTTON': [critical, 'On Change Password when user click on save without entering '
                                                        'anything in the fields, user must see error messages'],
    'TC_VERIFY_FORGOT_PASSWORD_SAVE_BUTTON_ON_ENTER_KEY': [critical,
                                                           'On Change Password when user press Enter Key without entering '
                                                           'anything in the fields, user must see error messages'],
    'TC_SELECT_BY_VALUE_CRYPTO_CURRENCY_ETHEREUM': [low,
                                                    'On Browse Page,When user select /crypto_currency/ethereum/ namespace from drop down, User must see topics '
                                                    'under /crypto_currency/ethereum/ namespace'],
    'TC_SELECT_BY_VALUE_CRYPTO_CURRENCY_ETHEREUM_ONLY_MY_TOPICS': [low,
                                                                   'On Browse Page,When user select /crypto_currency/ethereum/ namespace and Only My Topics, User must see own topics under /crypto_currency/ethereum/ namespace'],
    'TC_VERIFY_CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING_PAGE_LOADED': [low,
                                                                        'When user click on Canonizer is the final word on everything with login, page should be loaded Properly'],
    'TC_VERIFY_CANONIZER_IS_THE_FINAL_WORD_ON_EVERYTHING_PAGE_LOADED_WITHOUT_LOGIN': [low,
                                                                                      'When user click on Canonizer is the final word on everything without login, page should be loaded Properly'],
    'TC_VERIFY_CONSENSUS_OUT_OF_CONTROVERSY_USER_CASE_PAGE_LOADED': [low,
                                                                     'When user click on Consensus out of controversy use case with login, page should be loaded Properly'],
    'TC_VERIFY_CONSENSUS_OUT_OF_CONTROVERSY_USE_CASE_PAGE_LOADED_WITHOUT_LOGIN': [low,
                                                                                  'When user click on Consensus out of controversy use case without login, page should be loaded Properly'],
    'TC_LOAD_CREATE_NEW_CAMP_PAGE': [moderate,
                                     'When user click on Create New Camp link from left menu ,user should see Create New Camp page'],
    'TC_SAVE_WITH_INVALID_NEW_PASSWORD': [moderate,
                                          'On Change Password Page, When user puts invalid new password, user must see Error Message '],
    'TC_LOGIN_WITH_BLANK_EMAIL': [moderate, 'In Login Page, When user doesn\'t put Email, user must see Error Message'],
    'TC_LOGIN_WITH_BLANK_PASSWORD': [moderate,
                                     'In Login Page, When user doesn\'t put Password, user must see Error Message'],
    'TC_LOGIN_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [low,
                                                                'On Login Page, All Mandatory Fields are marked with * Sign'],
    'TC_LOGIN_SHOULD_HAVE_FORGOT_PASSWORD_LINK': [low, 'Login page should have "Forgot Password" link'],
    'TC_REGISTRATION_WITH_DUPLICATE_EMAIL': [moderate,
                                             'In Registration page, When user enter duplicate Email, should see error message'],
    'TC_VERIFY_TOPIC_PAGE_FROM_MY_SUPPORTS_LOADED': [moderate,
                                                     'In My Supports page, When user click on Topic name, user should redirect to respective page'],
    'TC_CHECK_CAMP_PAGE_FROM_MY_SUPPORTS_LOADED': [moderate,
                                                   'In My Supports page, When user click on Camp name, user should redirect to respective page'],
    'TC_SUBMIT_UPDATE_WIH_BLANK_TOPIC_NAME': [low,
                                              'On Topic Update, When user doesn\'t put Topic Name, user must see Error Message'],
    'TC_SUBMIT_TOPIC_UPDATE_WITH_DUPLICATE_TOPIC_NAME': [moderate,
                                                         'In Topic Update page, When user enter duplicate Topic name, should see error message'],
    'TC_CREATE_CAMP_WITH_DUPLICATE_CAMP_NAME': [moderate,
                                                'In Create New Camp page, When user enter duplicate Camp name, should see error message'],
    'TC_UPDATE_CAMP_WITH_EXISTING_DATA': [moderate, 'In Camp Update page, When user enter duplicate Camp name, should '
                                                    'see error message'],
    'TC_UPDATE_CAMP_VALIDATION_OF_CAMP_NAME': [moderate, 'In Camp Update camp, Camp name shoude be disabled.'],
    'TC_VERIFY_CAMP_UPDATE_FIELDS': [moderate, 'In Camp Update camp, Camp name should be disabled, and other fields '
                                               'should be editiable '],
    'TC_EDIT_NEWS_PAGE_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [moderate,
                                                                    'On Edit News Page, All mandatory fields are marked with # sign'],
    'TC_LOAD_ADD_CAMP_STATEMENT_PAGE': [moderate, 'When user click on Add Camp Statement ,user should see Add Camp '
                                                  'Statement page'],
    'TC_ADD_CAMP_STATEMENT_PAGE_MANDATORY_FIELDS_WITH_ASTERISK': [low, 'On Add Camp Statement page, All Mandatory '
                                                                       'Fields are marked with * Sign'],
    'TC_SUBMIT_STATEMENT_WITH_BLANK_NICK_NAME': [low,
                                                 'On Add Camp Statement page, When user doesn\'t put Nick Name, user must see Error Message'],
    'TC_ADD_CAMP_STATEMENT_BLANK_STATEMENT': [low,
                                              'On Add Camp Statement page, When user doesn\'t put Statement, user must see Error Message'],
    'TC_ADD_CAMP_STATEMENT_PAGE_SHOULD_HAVE_ADD_NEW_NICK_NAME_LINK_FOR_NEW_USERS': [low,
                                                                                    'On Add Camp Statement, Add New Nick Name link should present for users who doesn\'t have nick name yet'],
    176: [moderate,
          'In User Registration Page, When user put blank spaces in  First Name, user must see Error Message'],
    177: [moderate,
          'In My Supports page, When user click on Topic name->Create New Camp, user should redirect to respective page'],
    'TC_EDIT_CAMP_STATEMENT_WITH_BLANK_STATEMENT': [moderate, 'On Update Camp Statement page, When user doesn\'t put '
                                                              'Statement, user must see Error Message'],
    'TC_EDIT_CAMP_STATEMENT_WITH_TRAILING_SPACES': [moderate, 'On Update Camp Statement page, When user edit statement '
                                                              'with trailing space, statement should get update'],
    'TC_EDIT_CAMP_STATEMENT_WITH_ONLY_MANDATORY_FIELDS': [moderate, 'On Update Camp Statement page, When user enter '
                                                                    'data only in mandatory fields, data should get '
                                                                    'updated.'],
    'TC_EDIT_CAMP_STATEMENT_WITH_ENTER_KEY': [moderate, 'On Update Camp Statement page, When user update data and '
                                                        'press enter, data should get update.'],
    179: [moderate,
          'When user click on Camp name->Create New Camp from algorithm information, user should redirect to '
          'respective page'],
    180: [moderate, 'With login check disallow: /settings in https://canonizer.com/robots.txt'],
    181: [moderate, 'Without Login check disallow: /settings in https://canonizer.com/robots.txt'],
    182: [low, 'When user goes click on Upload File without logging in, Login Page page should be loaded'],
    183: [low, 'When user click on nick name under support tree for agreement ,user should see user supports page'],
    184: [low, 'When user click on camp name on user supports page ,user should see respective camp history page'],
    'TC_LOAD_FOOTER_PRIVACY_POLICY': [low, 'When user click on privacy policy from footer ,user should see privacy '
                                           'policy page'],
    'TC_LOAD_FOOTER_TERMS_SERVICES': [low, 'When user click on terms & services from footer ,user should see terms & '
                                           'services page'],
    'TC_FOOTER_PRIVACYPOLICY': [low, 'User should see Privacy Policy in Footer contents'],
    'TC_FOOTER_TERMS_SERVICES': [low, 'User should see Terms & Services in Footer contents'],
    'TC_VERIFY_FOOTER_COPYRIGHTYEAR': [low, 'User should see Copyright year in Footer contents'],
    190: [low, 'When user hits any garbage URL , User should proper error message instead of exception'],
    191: [low, 'When user click on Bread crumb agreement camp name,user should see respective agreement page'],
    192: [low, 'When user click on Bread crumb child camp name,user should see respective agreement page'],
    193: [low, 'When user click on Bread crumb on camp forum,user should see respective agreement page'],
    194: [low, 'When user click on Bread crumb on camp statement history,user should see respective agreement page'],
    195: [low, 'When user click on Bread crumb on camp history ,user should see respective agreement page'],
    196: [low, 'When user click on Bread crumb on create new camp ,user should see respective agreement page'],
    197: [low,
          'When user click on Bread crumb on topic history ->topic name ,user should see respective agreement page'],
    198: [low,
          'When user click on Bread crumb on agreement page->agreement link and then create new camp link ,user should see respective agreement page'],
    199: [low, 'On Create New Camp page, When user put invalid Camp Name , user must see Error Message'],
    200: [low, 'On Update Camp page, When user put invalid Camp Name , user must see Error Message'],
    201: [low, 'On Update Camp page, When user doesn\'t put Camp Name , user must see Error Message'],
    202: [low, 'When user hits any garbage URL , User should proper error message instead of exception'],
    'TC_BLOG_PAGE_FOOTER_COPYRIGHT_YEAR_WITH_LOGIN': [low, 'User should see copyright_year in blog Footer contents'],
    'TC_BLOG_PAGE_FOOTER_COPYRIGHT_YEAR_WITHOUT_LOGIN': [low, 'User should see copyright_year in blog Footer contents'],
    'TC_BLOG_PAGE_FOOTER_PRIVACY_POLICY': [low, 'User should see Privacy Policy in blog Footer contents'],
    'TC_BLOG_PAGE_FOOTER_TERMS_SERVICES': [low, 'User should see Terms & Services in blog Footer contents'],
    207: [low,
          'In Account Settings page, When user click on "Crypto Verification (was Metamask Account)" subtab,user should see Crypto Verification (was Metamask Account) page'],
    208: [low,
          'On Browse Page,When user select /void/ namespace from drop down, User must see topics under /void/ namespace'],
    209: [low,
          'On Browse Page,When user select /Mormon_Canon_Project/ namespace from drop down, User must see topics under /Mormon_Canon_Project/ namespace'],
    210: [low,
          'On Browse Page,When user select /organizations/united_utah_party/ namespace from drop down, User must see topics under /organizations/united_utah_party/ namespace'],
    211: [low, 'On Create Topic Page, Add New Nick Name link should present for users who doesn\'t have nick name yet'],
    212: [low,
          'On Create New Camp Page, Add New Nick Name link should present for users who doesn\'t have nick name yet'],
    213: [low, 'In Login Page, when user request otp with a in-valid user, user must see Error Message'],
    214: [low, 'In Login Page, when user request otp with a in-valid phone number, user must see Error Message'],
    215: [low,
          'In Login Page, when user request otp with a valid user email, user should redirect to Login OTP Verification page'],
    216: [low,
          'In Login Page, when user request otp with a valid user phone number, user should redirect to Login OTP Verification page'],
    217: [low,
          'On Browse Page,When user select /void/ namespace from drop down and Only My Topics, User must see own topics under /void/ namespace'],
    218: [low,
          'On Browse Page,When user select /Mormon_Canon_Project/ namespace from drop down and Only My Topics, User must see own topics under /Mormon_Canon_Project/ namespace'],
    219: [low,
          'On Browse Page,When user select /organizations/united_utah_party/ namespace from drop down and Only My Topics, User must see own topics under /organizations/united_utah_party/ namespace'],
    220: [critical, 'When user logged in and clicks on open source , User should see open source in new window'],
    221: [critical, 'When user without logged in clicks on open source, User should see open source in new window'],
    222: [critical, 'When user goes to Canonizer main page, page should be loaded Properly'],
    223: [critical, 'When user clicks on What is Canonizer.com, page should be loaded.'],
    224: [critical, 'When user clicks on Canonizer logo,canonizer main page should be loaded.'],
    225: [critical, 'On Login page, when user click "Signup Now" link, User should see User Registration Page'],
    'TC_VERIFY_LOGIN_PAGE_OPEN_CLICK_LOGIN_HERE_LINK': [critical, 'On Register page, when user click "Login here" link, User should see User Login Page'],
    'TC_CHECK_SCROLL_TO_TOP_CLICK': [critical,
          'On Canonizer Main Page, when user goes to bottom of the page click on UP icon , User should reach to of the page'],
    'TC_LOGIN_WITH_BLANK_OTP': [low, 'On Login OTP Verification page when user doesn\'t put OTP, User should see error message'],
    229: [low, 'On Login OTP Verification page , All Mandatory Fields are marked with * Sign'],
    230: [low, 'On Login OTP Verification page when user put invalid OTP, User should see error message'],
    231: [low, 'In User Registration Page, When user doesn\'t put Captcha, user must see Error Message'],
    232: [low, 'In User Registration Page, When user put invalid first name, user must see Error Message'],
    233: [low, 'In User Registration Page, When user put invalid last name, user must see Error Message'],
    234: [low, 'In User Registration Page, When user put invalid middle name, user must see Error Message'],
    235: [low, 'On Upload file page, User should see uploaded file in new window'],
    236: [low, 'When click on Create New Camp without logging in, Login Page page should be loaded'],
    237: [moderate, 'In Create New Topic page, When user enter invalid topic name,user should see error message'],
    238: [low, 'On Manage Profile Info Page, When user put invalid length Phone Number  , user must see Error Message'],
    239: [low, 'On Manage Profile Info Page, When user put invalid first name, user must see Error Message'],
    240: [low, 'On Manage Profile Info Page, When user put invalid middle name, user must see Error Message'],
    241: [low, 'On Manage Profile Info Page, When user put invalid last name, user must see Error Message'],
    'TC_UPDATE_PROFILE_WITH_VALID_DATA_WITH_ENTER_KEY': [critical, 'On Manage Profile Info Page, When user try to '
                                                                   'update the profile with valid data with enter '
                                                                   'key, Profile should get update.'],
    'TC_UPDATE_PROFILE_WITH_MANDATORY_FIELDS': [critical, 'On Manage Profile Page, When user try to update the '
                                                          'profile with mandatory fields, profile should get '
                                                          'update.'],
    'TC_UPDATE_PROFILE_WITH_BLANK_MANDATORY_FIELDS': [critical, 'On Manage Profile Page, When user try to update the '
                                                                'profile with blank mandatory fields, user must see '
                                                                'error messages'],
    'TC_VERIFY_DOB_ON_PROFILE_INFO': [critical, 'On Manage Profile Page, DOB should be in "DD/MM/YYYY" format'],
    'TC_SUBMIT_UPDATE_WITH_TRAILING_SPACES': [critical, 'In Topic Update page, if user give trailing space while '
                                                        'updating data, The data should get update.'],
    'TC_SUBMIT_UPDATE_WITH_ENTER_KEY': [critical, 'In Topic Update page,if user edit data and press enter key, '
                                                  'The data should get update.'],
    'TC_SUBMIT_UPDATE_WITH_MANDATORY_FIELDS_ONLY': [critical,
                                                    'In Topic Update, If user enter data only in mandatory fields, '
                                                    'Data should get update.'],
    'TC_SUBMIT_UPDATE_WITH_DUPLICATE_DATA': [critical,
                                             'On Topic Update, If user enter already existing data, user must see '
                                             'error message.'],
    'TC_SUBMIT_UPDATE_WITH_INVALID_TOPIC_NAME': [moderate,
                                                 'In Update Topic page, When user enter invalid topic name,'
                                                 'user should see error message'],
    'TC_NICKNAME_PAGE_SHOULD_OPEN_CREATE_TOPIC_ADD_NEW_NICKNAME': [moderate,
                                                                   'When user click on Add New Nick Name link from '
                                                                   'Create New Topic page,user should redirect to '
                                                                   'nick name page'],
    'TC_NICKNAME_PAGE_SHOULD_OPEN_CREATE_CAMP_ADD_NEW_NICKNAME': [moderate,
                                                                  'When user click on Add New Nick Name link from '
                                                                  'Create New Camp page,user should redirect to nick '
                                                                  'name page'],
    'TC_NICKNAME_PAGE_SHOULD_OPEN_UPDATE_TOPIC_ADD_NEW_NICKNAME': [moderate,
                                                                   'When user click on Add New Nick Name link from '
                                                                   'update topic page,user should redirect to nick '
                                                                   'name page'],
    'TC_NICKNAME_PAGE_SHOULD_OPEN_UPDATE_CAMP_ADD_NEW_NICKNAME': [moderate,
                                                                  'When user click on Add New Nick Name link from '
                                                                  'update camp page,user should redirect to nick name '
                                                                  'page'],
    'TC_LOAD_JOIN_SUPPORT_CAMP_PAGE_WITH_LOGIN': [moderate,
                                                  'When user click on Directly Join or Manage Support, user should '
                                                  'redirect to support camp page'],
    'TC_JOIN_SUPPORT_CAMP_PAGE_SHOULD_HAVE_ADD_NEW_NICKNAME_LINK_FOR_NEW_USERS': [moderate,
                                                                                  'When user with no nick name is '
                                                                                  'trying to support any camp,'
                                                                                  'user should see add new nick name '
                                                                                  'link'],
    'TC_NICKNAME_PAGE_SHOULD_OPEN_JOIN_SUPPORT_CAMP_ADD_NEW_NICKNAME': [moderate,
                                                                        'When user click on Add New Nick Name link '
                                                                        'from join support camp page,user should '
                                                                        'redirect to nick name page'],
    'TC_REGISTRATION_WITH_INVALID_CAPTCHA': [low,
                                             'In User Registration Page, When user put invalid captcha , user must '
                                             'see Error Message'],
    'TC_VERIFY_PHONE_NUMBER_WITH_VALID_LENGTH_PHONE_NUMBER': [low,
                                                              'On Manage Profile Info Page, When user put valid '
                                                              'length Phone Number  , user must see Error Message'],
    'TC_CHECK_JOBS_PAGE_SHOULD_OPEN_WITH_LOGIN': [critical,
                                                  'When user logged in and clicks on Jobs link, User should see Jobs '
                                                  'page '],
    'TC_CHECK_SERVICES_PAGE_SHOULD_OPEN_WITH_LOGIN': [critical,
                                                      'When user logged in and clicks on Services link, User should '
                                                      'see Services page '],
    'TC_VERIFY_JOBS_PAGE_SHOULD_OPEN_WITHOUT_LOGIN': [critical,
                                                      'When user clicks on Jobs link without login, User should see '
                                                      'Jobs page '],
    'TC_VERIFY_SERVICES_PAGE_SHOULD_OPEN_WITHOUT_LOGIN': [critical,
                                                          'When user clicks on Services link without login, User '
                                                          'should see Services page '],
    'TC_SUBMIT_CAMP_UPDATE_WITH_INVALID_LENGTH_CAMP_ABOUT_URL': [low, 'On Update Camp page, When user put invalid '
                                                                      'Camp about URL , user must see Error Message'],
    257: [low, 'On Login Page, when user request otp with a unverified phone number, user must see Error Message'],
    258: [low,
          'On Camp Statement Update, Add New Nick Name link should present for users who doesn\'t have nick name yet'],
    259: [moderate,
          'When user click on Add New Nick Name link from update camp statement page,user should redirect to nick '
          'name page'],
    260: [moderate,
          'When user click on Add New Nick Name link from create camp statement page,user should redirect to nick '
          'name page'],
    261: [moderate, 'In forgot password page, When user put invalid Email format, user must see error message'],
    262: [moderate, 'In Registration page, When user enter invalid Email, should see error message'],
    263: [low,
          'On Browse Page,When user select /government/ namespace from drop down, User must see topics under /government/ namespace'],
    264: [low,
          'On Browse Page,When user select /government/ namespace from drop down and Only My Topics, User must see own topics under /government/ namespace'],
    265: [low,
          'On Browse Page,When user select /government/sandy_city/ namespace from drop down, User must see topics under /government/sandy_city/ namespace'],
    266: [low,
          'On Browse Page,When user select /government/sandy_city namespace from drop down and Only My Topics, User must see own topics under /government/sandy_city/ namespace'],
    'TC_CLICK_LOGOUT_PAGE_BUTTON_BEFORE_BROWSE_BACK_BUTTON': [low,
                                                              'Click on Logout button and now press the back button of browser'],
    'TC_LOG_OUT_03': [low,
                      'Open browser and hit the application with URL,open new tab within the same browser,now click on log out from one tab'],
    'TC_VERIFY_UNVERIFIED_ACCOUNT': [critical, 'If user try to login with unverified account, user must see error message.'],
    'TC_VERIFY_ACCOUNT_LOCK_AFTER_5_UNSUCCESSFUL_ATTEMPT': [critical,
                    'If user try to login with valid email and invalid password, user account should get lock for 60 seconds and user must see error message'],
    'TC_VERIFY_LOGIN_PLACEHOLDERS': [critical, 'Placeholder must be displayed for login fields.'],
    'TC_VERIFY_LOGIN_CASE_SENSITIVE_UPPERCASE': [critical,
                    'If user type password in uppercase which original in lowercase, user must see error message'],
    'TC_VERIFY_LOGIN_CASE_SENSITIVE_LOWERCASE': [critical,
                    'If user type password in lowercase which original in uppercase, user must see error message'],
    'TC_NEWS_FEED_INVALID_DATA': [critical,
                                  'On Add News page, When user enter invalid data, user must see Error Message'],
    'TC_NEW_FEED_WITH_BLANK_FIELDS': [critical, 'On Add News page, When user enter blank display text and blank link, '
                                                'user must see Error Message'],
    'TC_NEWS_FEED_DUPLICATE_DATA': [low, 'On Add News page, When user put duplicate data , news should get added'],
    'TC_NEWS_FEED_WITH_ENTER_KEY': [low,
                                    'On Add News page, When user put valid data, and press enter key, new should get added.'],
    'TC_NEWS_FEED_INVALID_DATA_WITH_ENTER_KEY': [critical,
                                                 'On Add News page, When user enter invalid data, user must see Error '
                                                 'Message'],
    'TC_NEWS_FEED_WITH_TRAILING_SPACES': [critical,
                                          'On Add News page, When user put data with trailing spaces , news should '
                                          'get added'],
    'TC_UPDATE_NEWS_FEED_INVALID_DATA': [critical,
                                         'On Edit News page, When user enter invalid data, user must see Error Message'],
    'TC_UPDATE_NEWS_WITH_DUPLICATE_DATA': [critical, 'On Edit News page, When user enter duplicate data, news should '
                                                     'get added'],
    'TC_UPDATE_NEWS_WITH_TRAILING_SPACES': [critical, 'On Edit News page, When user enter data trailing spaces, '
                                                      'news should '
                                                      'get added'],
    'TC_DELETE_NEWS_01': [critical, "On News Feed Page, Delete News Button Should be Visible"],
    'TC_DELETE_NEWS_02': [critical, "On News Feed Page, When user Click Delete News Icon after Click Delete News "
                                    "Button, New should get delete."],
    'TC_DELETE_NEWS_03': [critical, "On News Feed Page, When user Click Child's Delete News Icon after Click Delete "
                                    "News Button of Child Camp "
                                    ", News should get delete."],
    'TC_CREATE_NEW_TOPIC_WITH_VALID_DATA': [critical, "In Create New Topic page, When user enter valid data, topic "
                                                      "should be "
                                                      "created."],
    'TC_CREATE_NEW_TOPIC_WITH_INVALID_DATA': [critical, "In Create New Topic page, When user enter invalid data, "
                                                        "user must see error message."],
    'TC_CREATE_NEW_WITHOUT_MANDATORY_FIELDS_DATA': [critical, "In create New Topic page, When user doesn't enter data "
                                                              "in mandatory fields, "
                                                              "error message should be visible"],
    'TC_CREATE_NEW_TOPIC_ENTERING_DATA_ONLY_IN_MANDATORY_FIELDS': [critical, "In create New Topic page, When user "
                                                                             "enter data in mandatory fields only, "
                                                                             "topic should be created."],
    'TC_VALIDATION_OF_NICK_NAME_DROPDOWN': [critical, "In create New Topic page, When user click on nick name drop, "
                                                      "dropdown "
                                                      "should show all the nick names"],
    'TC_CREATE_NEW_TOPIC_WITH_ENTER_KEY': [critical, "In create New Topic page, When user enter data and "
                                                     "press the enter key "
                                                     "topic should be created."],
    'TC_CREATE_NEW_TOPIC_WITH_ENTER_KEY_AND_VERIFY_HISTORY_PAGE': [critical,
                                                                   "In create New Topic page, When user enter data and "
                                                                   "press the enter key "
                                                                   "topic should be created, and history page should "
                                                                   "be visible"],
    'TC_CREATE_NEW_TOPIC_WITH_TRAILING_SPACES': [critical, "In create New Topic page When user give trailing spaces in "
                                                           "topic name, topic should be created "],
    'TC_VERIFY_TOPIC_NAME_FROM_NAMESPACES_IN_BROWSE': [critical, "In create New Topic page, When user create any "
                                                                 "topic that topic should be "
                                                                 "visible in namespace in Browser page."],
    'TC_VERIFYING_NICKNAME_FROM_DROPDOWN_WHILE_CRAETING_TOPIC': [critical, "In create New Topic page, Select the "
                                                                           "nickname from DropDown "
                                                                           "topic should be created with that nickname"],
    'TC_LOAD_CAMP_FORUM_PAGE': [critical, 'When user click on Camp Forum button, user should see Camp Forum page'],
    'TC_LOAD_CREATE_THREAD_PAGE': [critical, 'When user click on Camp Forum button, user should see Camp Forum page'],
    'TC_UPDATE_THREAD_01': [critical, 'When user update the thread, the thread should get update'],
    'TC_UPDATE_THREAD_02': [critical, 'When suer try to edit the thread with special character, '
                                      'user must see error message'],
    'TC_LOAD_TOP_10_THREAD': [critical, 'When user click on top 10 thread, user should see top 10 threads.'],
    'TC_LOAD_MY_PARTICIPANTS': [critical, 'When user click on My Participant, user should see my participants'],
    'TC_LOAD_ALL_THREADS': [critical, 'When User click on All Threads, user should see all threads.'],
    'TC_LOAD_MY_THREAD_PAGE': [critical, 'When user click on My Thread, it should see all threads created by him.'],
    'TC_CHECK_NO_THREAD_AVAILABILITY': [critical, "When user click on All Threads, if threads are not present, "
                                                  "user should see 'No "
                                                  "threads available for this topic' statement"],
    'TC_VERIFY_MY_THREADS_CREATED_BY_LOGGED_USER': [critical, "When user click on thread, which is created by user "
                                                              "itself, user should see his name."],
    'TC_CAMP_FORM_COUNT_OF_THREADS_ON_ALL_THREADS_PAGE': [critical, "In all threads, user should see 10 threads only"],
    'TC_CHECK_ALL_REPLIES_TO_THREAD': [critical, 'When user click on any thread, '
                                                 'user should see all the replies associated with that thread'],
    'TC_CREATE_THREAD_MANDATORY_FIELDS_ARE_MARKED_WITH_ASTERISK': [critical, 'When user click on create thread page, '
                                                                             'All Mandatory Fields are marked with * '
                                                                             'Sign'],
    'TC_EDIT_REPLY_TO_THREAD': [critical, 'When user click reply,add reply then the reply should get post'],
    'TC_CREATE_THREAD_WITH_VALID_DATA': [critical, 'When user click on create thread page and fill all the fields wih '
                                                   'valid data, '
                                                   'thread should get create.'],
    'TC_CREATE_THREAD_WITH_INVALID_DATA': [critical, 'When user click on create thread page and fill all the fields '
                                                     'wih invalid data, '
                                                     'Error message should get display.'],
    'TC_CREATE_THREAD_WITH_BLANK_MANDATORY_FIELDS': [critical, 'When user click on create thread page and leave empty '
                                                               'the mandatory fields, '
                                                               'Error message should get display.'],
    'TC_CREATE_THREAD_WITH_ONLY_MANDATORY_FIELDS': [critical, 'When user click on create thread page and fill all '
                                                              'mandatory fields only, '
                                                              'Thread should get create.'],
    'TC_CREATE_THREAD_WITH_DUPLICATE_TITLE': [critical, 'When user click on create thread page and Give duplicate '
                                                        'thread title '
                                                        'Error message should get display.'],
    'TC_CREATE_THREAD_WITH_BLANK_TITLE': [critical, 'When user enter duplicate title in create thread, user must see '
                                                    'error message'],
    'TC_CREATE_THREAD_WITH_SPECIAL_CHAR': [critical,
                                           'When user try to create thead with special character, user must see error message'],
    'TC_EDIT_THREAD_WITH_DUPLICATE_TITLE': [critical,
                                            'When user click on create thread page and give duplicate thread title'
                                            'Error message should get display.'],
    'TC_CREATE_THREAD_WITH_INVALID_DATA_WITH_ENTER_KEY': [critical, 'When user click on create thread page and fill '
                                                                    'all mandatory fields with '
                                                                    'invalid data and press enter key, '
                                                                    'Error message should get display.'],
    'TC_CREATE_THREAD_WITH_VALID_DATA_WITH_ENTER_KEY': [critical, 'When user click on create thread page and fill all '
                                                                  'mandatory fields only with '
                                                                  'valid data and press enter key, '
                                                                  'Thread should get create.'],
    'TC_CREATE_THREAD_WITH_TRAILING_SPACES': [critical, 'When user click on create thread page and give title with '
                                                        'trailing spaces, '
                                                        'Thread should get create.'],
    'TC_VERIFY_LINK_TO_CAMP_NAME': [critical, 'When user click on create thread page and click on Agreement, '
                                              'Should redirect to Camp Page.'],
    'TC_LOAD_THREAD_POSTS_PAGE': [critical, 'When user click on thread title on Agreement page, should redirect to '
                                            'thread '
                                            'posts page.'],
    'TC_THREAD_POSTS_MANDATORY_FIELDS_MARKED_WITH_ASTERISK': [critical, 'When user click on thread title on Agreement '
                                                                        'page, All Mandatory Fields are '
                                                                        'marked with * Sign'],
    'TC_THREAD_POST_WITH_VALID_DATA': [critical, 'When user click on any thread on all threads page, and user reply '
                                                 'with valid data, reply should get paste'],
    'TC_THREAD_POST_WITH_INVALID_DATA': [critical, 'When user click on any thread on all threads page, and user reply '
                                                   'with invalid data, user must see error message.'],
    'TC_THREAD_POST_WITH_VALID_DATA_WITH_ENTER_KEY': [critical,
                                                      'If user post valid data in post thread, and press Enter '
                                                      'key, Reply should get submit'],
    'TC_THREAD_POST_WITH_INVALID_DATA_WITH_ENTER_KEY': [critical, 'If user post invalid data in post thread, '
                                                                  'and press Enter key, User must see error message'],
    'TC_THREAD_POST_WITH_TRAILING_SPACES': [critical, 'If user reply data with trailing spaces, trailing spaces '
                                                      'should get trimmed and reply should get post'],
    'TC_POST_REPLY_TO_THREAD': [critical,
                                'When user click on thread title on Agreement page, Post a reply, It should submit.'],
    'TC_VERIFY_THREAD_PAGINATION': [critical, 'When user click on Camp Forum, If thread titles are more than 10, '
                                              'pagination '
                                              'should be visible'],
    'TC_VERIFY_NICK_NAME_LINK': [critical,
                                 'When user click on Camp Forum, Click on any thread title, and click on "Thread '
                                 'Created by user" '
                                 'should be redirected to user supported camp page'],
    'TC_LOAD_CAMP_MANAGE_EDIT_PAGE': [critical, 'When user click on Manage/Edi Camp, should see Camp History page.'],
    'TC_VERIFY_AGREEMENT_PAGE': [critical, 'When user Click on View this version, user should see agreement page'],
    'TC_VERIFY_CAMP_UPDATE_PAGE': [critical, 'When user click on view this version, user should see update camp page'],
    'TC_UPDATE_CAMP_WITH_INVALID_DATA': [critical, 'When user update the camp with invalid data, user must see Error '
                                                   'Message'],
    'TC_UPDATE_CAMP_WITH_BLANK_FIELDS': [critical, 'When user update the camp with blank fields, user must see Error '
                                                   'Message'],
    'TC_UPDATE_CAMP_WITH_VALID_DATA_WITH_ENTER_KEY': [critical,
                                                      'When user update the camp with valid data and Press Enter Key, '
                                                      'user must see Success '
                                                      'Message on Camp History Page'],
    'TC_UPDATE_CAMP_WITH_INVALID_DATA_WITH_ENTER_KEY': [critical,
                                                        'When user update the camp with invalid data and Press Enter '
                                                        'Key, '
                                                        'user must see Error '
                                                        'Message'],

    'TC_UPDATE_CAMP_WITH_MANDATORY_FIELDS_ONLY': [critical,
                                                  'When user update the camp with mandatory fields only user must see '
                                                  'Success Message on Camp History Page'],
    'TC_UPDATE_CAMP_WITH_TRAILING_SPACES': [critical, 'When user update the camp with data with trailing spaces '
                                                      'Success Message on Camp History Page'],
    'TC_UPDATE_CAMP_WITH_INVALID_URL': [critical, 'When user update the invalid url in Camp About Url'
                                                  'User must see error message.'],
    'TC_ADD_CAMP_STATEMENT_WITHOUT_MANDATORY_FIELDS': [critical, 'When user add the Camp Statement without mandatory '
                                                                 'fields, User must see '
                                                                 'Error Message'],
    'TC_ADD_CAMP_STATEMENT_WITH_MANDATORY_FIELDS': [critical, 'When user add the Camp Statement, Use must see Success '
                                                              'message'],
    'TC_ADD_CAMP_STATEMENT_WITH_VALID_DATA': [critical, 'When user entered valid data to Add Camp Statement Page, '
                                                        'Use must see '
                                                        'Success message'],
    'TC_ADD_CAMP_STATEMENT_WITH_INVALID_DATA': [critical, 'When user entered invalid data to Add Camp Statement Page, '
                                                          'User must see error message'],
    'TC_ADD_CAMP_STATEMENT_WITH_VALID_DATA_WITH_ENTER_KEY': [critical, 'When user entered valid data Press Enter Key, '
                                                                       'Use must see '
                                                                       'Success message'],
    'TC_ADD_CAMP_STATEMENT_PAGE_DATA_WITH_TRAILING_SPACES': [critical, 'When user entered data with trailing spaces, '
                                                                       'data should get added and '
                                                                       'User must see Success message'],
    'TC_FOOTER_PRIVACY_POLICY': [critical, 'Without login User should see privacy policy in Footer'],
    'TC_FOOTER_COPY_RIGHT': [critical, 'Without login User should see Copy Right year in Footer'],
    'TC_FOOTER_SUPPORT_CANONIZER': [critical, 'Without login User should see Support Canonizer in Footer'],
    'TC_FOOTER_TERMS_AND_SERVICES': [critical, 'Without login User should see Terms and Services in Footer'],
    'TC_UPLOAD_FILE_16': [critical, 'On File Upload page, When user upload file, user should see file uploaded file '
                                    'on top of the list'],
    'TC_VERIFY_LIVE_TOPIC_NAME_WITH_TOPIC_NAME': [critical, 'Topic name should be similar to live topic name.'],
    'TC_VERIFY_LIVE_TOPIC_NAME_WITH_CAMP_TREE_TOPIC_NAME': [critical, 'Camp Tree Topic name should be similar to live '
                                                                      'topic name.'],
    'TC_VERIFY_LIVE_TOPIC_NAME_WITH_CURRENT_TOPIC_NAME': [critical, 'Topic name in current topic name, should be '
                                                                    'similar to live topic name.'],
    'TC_SORTED_TREE_NAME_WITH_LIVE_CAMP_NAME': [critical, 'Camp name in sorted tree should be '
                                                                    'similar to live topic name.'],
    'TC_BREADCRUM_CAMP_NAME_WITH_LIVE_CAMP_NAME': [critical, 'Camp name in breadcrum , should be '
                                                                    'similar to live topic name.'],
    'TC_SUPPORT_TREE_CAMP_NAME_WITH_LIVE_CAMP_NAME': [critical, 'Camp name in support tree should be '
                                                                        'similar to live topic name.'],
    'TC_CURRENT_CAMP_NAME_WITH_LIVE_CAMP_NAME': [critical, 'Camp name in current Camp, should be '
                                                                        'similar to live topic name.'],



}
