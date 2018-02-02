<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Package Configuration Option
      |--------------------------------------------------------------------------
     */

    'is_admin_permission_manager_enabled' => TRUE,
    
    // Set this to false if you would like to use your own FaqController you then need to setup your faq routes manually in your routes.php file
    'setup_faq_routes' => TRUE,
    //'override_validation' => FALSE,
    
    // Mention form validation array
    'validation' => [
        'faq_topic_id' => 'required',
        'question' => 'required|max:255',
        'answer' => 'required',
        'status' => 'required|in:Active,Inactive',
    ],
];
