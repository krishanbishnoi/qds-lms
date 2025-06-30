<?php

return [

    'TRAINING_DOCUMENT_URL' => 'http://lms.test/training_document',
    // 'TRAINING_DOCUMENT_URL' => env('APP_URL') . '/training_document',
    
    'STATUS_LIST'                   => [
        1 => 'Active',
        0 => 'Inactive',
    ],

    'QuestionType' => [
        'MCQ' => 'Multiple Choice',
        'SCQ' => 'Single Choice',
        'T/F' => 'True / False',
        'FreeText' => 'Free Text',
    ],

    'ADDTEST' => [
        'existing' => 'Select from existing tests',
        'new' => 'Create new test'
    ],

    'STEP_FORM' => [
        'course_info' => 1,
        'test_info' => 2,
        'questions' => 3
    ],

    'QuestionType' => [
        'MCQ' => 'Multiple Choice (Multiple Answers)',
        'SCQ' => 'Single Choice (Single Answer)',
        'T/F' => 'True/False',
        'FITB' => 'Fill in the Blank',
        'Essay' => 'Essay Question'
    ],

    'FLASH_TRY_CATCH'               => '🚧 Uh-oh! Something unexpected happened. We’re on it! 🛠️',

    'REC_NOT_FOUND'            => 'Record not found',
    'REC_ADD_SUCCESS'          => 'Record successfully added!',
    'REC_ADD_FAILED'           => 'Record not added.Try again!',
    'REC_UPDATE_SUCCESS'       => 'Record successfully updated!',
    'REC_UPDATE_FAILED'        => 'Record not updated.Try again!',
    'REC_DELETE_SUCCESS'       => 'Record successfully deleted!',
    'REC_FETCHED_SUCCESS'      => 'Record successfully fetched!',
    'REC_DELETE_FAILED'        => 'Record not deleted.Try again!',
    'REC_REMOVE_SUCCESS'       => 'Record successfully removed!',
    'REC_REMOVE_FAILED'        => 'Record not removed.Try again!',


    'API_MSG'                       => [
        'CALL_SUCCESS'             => 'Success',
        'CALL_FAILED'              => 'Failed',
        'SERVER_ERROR'             => 'Server Error',
        'UNAUTHORISED'             => 'User not authorized',
        'NOT_FOUND_USER'           => 'User not found',
        'VALIDATION_ERROR'         => 'Validation Error',
        'ALREADY_ACCOUNT_ERROR'         => 'You already have an account. Please log in to proceed',
        'INVALLID_TOKEN'           => 'Invallid Token',
        'USER_NOT_FOUND'           => 'User not exist',

        'REQ_USER_ID'              => 'User id is required',

        'SUCCESS_LOGOUT'           => 'Successfully logged out !',
        'FAILED_LOGOUT'            => 'Logged out fialed !',
        'EMAIL_ADDRESS_EXIST'      => 'Email address already exist.',
        'MOBILE_NO_EXIST'          => 'Mobile no already exist.',
        'MOBILE_NO_VERIFY'         => 'Please verify your Mobile no',
        'ACCOUNT_CREATE_SUCCESS'   => 'Your account created successfully',
        'ACCOUNT_LOGGIN_SUCCESS'   => 'Loggedin successfully',
        'ACCOUNT_LOGGIN_FAILED'    => 'Loggedin failed',
        'ADMIN_ACCOUNT_LOGGIN_FAILED'    => 'Super Admin needs to log in through the web portal',
        'OTP_VERIFIED_SUCCESS'     => 'OTP verified successfully',
        'OTP_SENT_SUCCESS'         => 'OTP sent successfully on your ##OTP_ON##',
        'OTP_INVALID_EXPIRED'      => 'OTP invalid or expired',
        'OTP_SENT_FAILED'          => 'Failed to sent otp',
        'REGISTRAION_ERROR'        => 'Failed to register user',
        'INVALID_CREDENTIAL'       => 'Invalid login credentials',
        'PASSWORD_RESET_SUCCESS'   => 'Password reset successfully',
        'PASSWORD_INCORRECT'       => 'Password is incorrect',

        'FEEDBACK_ADD_SUCCESS'     => 'Your feedback submitted successfully',
        'FEEDBACK_ADD_FAILED'      => 'Your feedback submission failed',

        'REC_NOT_FOUND'            => 'Record not found',
        'REC_ADD_SUCCESS'          => 'Record successfully added!',
        'REC_ADD_FAILED'           => 'Record not added.Try again!',
        'REC_UPDATE_SUCCESS'       => 'Record successfully updated!',
        'REC_UPDATE_FAILED'        => 'Record not updated.Try again!',
        'REC_DELETE_SUCCESS'       => 'Record successfully deleted!',
        'REC_FETCHED_SUCCESS'      => 'Record successfully fetched!',
        'REC_DELETE_FAILED'        => 'Record not deleted.Try again!',
        'REC_REMOVE_SUCCESS'       => 'Record successfully removed!',
        'REC_REMOVE_FAILED'        => 'Record not removed.Try again!',

        'ACCESS_CODE_ALREADY_USED' => 'You are already using an Access Code.',
        'ACCESS_CODE_VALLIDATED'   => 'Access Code validated and activated successfully.',
        'ACCESS_CODE_INVALLID'     => 'Invalid or already used Access Code.',
    ],
];
