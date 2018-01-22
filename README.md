# FAQ Package APIs with Lumen 5.5

# Installation & Usage
You can install the package via Composer:
```
composer require nikunjkumar.kabariya/faq
```

Run
```
composer dump-autoload --optimize
```

Create blank config folder on root of your application if not exist.

Copy the required files:
```
cp vendor/nikunjkumar.kabariya/faq/src/config/faq.php config/faq.php
cp vendor/nikunjkumar.kabariya/faq/src/routes/routes.php routes/faq.php
```

Modify the bootstrap flow (bootstrap/app.php file) & Register service provider
```
// Enable Facades
$app->withFacades();

// Enable Eloquent
$app->withEloquent();
```

```php
$app->register(Nikunjkabariya\Faq\FaqServiceProvider::class);
```

Now, run your migrations:
```
php artisan migrate
```

# Installed routes

This package mounts the following routes after you call routes() method (see instructions below):

Verb | Path | NamedRoute | Controller | Action | Middleware
--- | --- | --- | --- | --- | ---
GET    | /api/faq/list                     |            | \NikunjKabariya\Faq\FaqController | index                 | admin_api
GET    | /api/faq/show/{id}                |            | \NikunjKabariya\Faq\FaqController | show                  | admin_api
POST   | /api/faq/create                   |            | \NikunjKabariya\Faq\FaqController | store                 | admin_api
DELETE | /api/faq/delete/{id}              |            | \NikunjKabariya\Faq\FaqController | destroy               | admin_api
PUT    | /api/faq/update/{id}              |            | \NikunjKabariya\Faq\FaqController | update                | admin_api
PUT    | /api/faq/change_status            |            | \NikunjKabariya\Faq\FaqController | changeStatus          | admin_api
GET    | /api/faq_topic/list               |            | \NikunjKabariya\Faq\FaqController | faqTopicList          | -
GET    | /api/faqs/{faqTopicSlug}          |            | \NikunjKabariya\Faq\FaqController | getAllFaqsByFaqTopic  | -


# Extend / Add new fields
Copy these files in your application if not exist:
- config/faq.php
- routes/faq.php

- create new migration file in your application, for ex.:
  ```
  php artisan make:migration add_featured_image_in_faqs_table --table=faqs
  ```
  
  then, run this command 
  ```
  php artisan migrate
  ```
  
- If you want to keep validation for new fields, you can do it in your application's faq config file,
  ```
  config/faq.php file  
  ```
  
- You can modify package's route in your application's faq route file, you can add/change prefix, groups, namespace, middleware etc. If you override method in controller then make sure to change namespace for that particular route in this file.
  ```
  routes/faq.php file  
  ```

- If you want to override any existing method or want to create new method, you can do it by extending FaqController.php from your application's new controller. for ex.:
  ```php
  use Nikunjkabariya\Faq\FaqController as FaqControllerPackage;
  class FaqController extends FaqControllerPackage {}
  ```  
  
  You can also override / create new method by extending Faq.php model file from your application's new model. for ex.:
  ```php
  use Nikunjkabariya\Faq\Faq as FaqModel;
  class Faq extends FaqModel {}
  ```