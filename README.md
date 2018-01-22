# FAQ Package APIs with Lumen 5.5

# Installation & Usage
You can install the package via Composer:
```
composer require nikunjkumar.kabariya/faq
```

Copy the required files:
```
cp vendor/nikunjkumar.kabariya/faq/src/config/faq.php config/faq.php
cp vendor/nikunjkumar.kabariya/faq/src/routes/routes.php routes/faq.php
```

Now, run your migrations:
```
php artisan migrate
```

Register service provider
```php
$app->register(Nikunjkabariya\Faq\FaqServiceProvider::class);
```
Run
```
composer dump-autoload
```

# Extend / Add new fields
Copy these files in your application if not exist:
- config/content_page.php
- routes/content_page.php

- create new migration file in your application, for ex.:
  ```
  php artisan make:migration add_featured_image_in_content_pages_table --table=content_pages
  ```
  
  then, run this command 
  ```
  php artisan migrate
  ```
  
- If you want to keep validation for new fields, you can do it in your application's faq config file,
  ```
  config/faq.php file  
  ```
  
- You can modify package's route in your application's faq route file, you can add/change prefix, groups, namespace etc...
  ```
  routes/faq.php file  
  ```

- If you want to override any existing method or want to create new method, you can do it by extending FaqController.php from your application's new controller. for ex.:
  ```
  use Nikunjkabariya\Faq\FaqController as FaqControllerPackage;
  class FaqController extends FaqControllerPackage {}
  ```  
  
  You can also override / create new method by extending Faq.php model file from your application's new model. for ex.:
  ```
  use Nikunjkabariya\Faq\Faq as FaqModel;
  class Faq extends FaqModel {}
  ```