def test_cases(index):
    return testCases[index]


critical = "Critical"
major = "Major"
moderate = "Moderate"
low = "Low"

testCases = {

    0: [critical, 'When user goes to Canonizer main page, page should be loaded Properly'],
    1: [critical, 'In Home page, when user click "Register" button, User should see User Registration Page'],
    2: [critical, 'In Home page, when user click "Login" button,  User should see Login Page'],
    3: [critical, 'In Login Page, when user login with a valid user, he should see Home Page'],
    4: [critical, 'In Login Page, when user login with a in-valid user, user must see Error Message'],
    5: [moderate, 'In User Registration Page, When user doesn\'t put First Name, user must see Error Message'],
    6: [moderate, 'In User Registration Page, When user doesn\'t put Last Name, user must see Error Message'],
    7: [moderate, 'In User Registration Page, When user doesn\'t put Email, user must see Error Message'],
    8: [moderate, 'In User Registration Page, When user puts blank password, user must see Error Message'],
    9: [moderate, 'In User Registration Page, when user puts password less than 6 characters, '
                  'user must see Error Message'],
    10: [moderate, 'In User Registration Page, when user puts different password for confirmation, '
                   'user must see Error Message'],
    11: [critical, 'When user clicks on What is Canonizer.com, page should be loaded.'],
    12: [critical, 'If any unauthenticated user wants to join the camp, User should be directed to the login page'],
    13: [low, 'When the main page loads, Text on button to upload all topic is "Load All Topics"'],
    14: [low, 'Register page should have "Login" option/button for existing users'],
    15: [low, 'Login Page should have "Register" Option/button for new users']
}

