Hyper Admin - README

Hyper Admin
===========

Admin Panel for Hyper MVT Framework

Introduction
------------

**Hyper Admin** is a simple and powerful admin panel designed for the Hyper MVT framework. It offers a user-friendly interface for managing your application's data, similar to Django's contrib admin panel.

Installation
------------

It is recommended to use [Composer](https://getcomposer.org/) to install Hyper Admin.

To install, run the following command:
```shell
composer require vulcanphp/hyper-admin
```

Setup Hyper Admin
-----------------

### Register Hyper Admin in Your Application

To set up Hyper Admin, you need to merge it with your application's bootstrap file.

Open your `app/bootstrap.php` file and add the following code:
```php
use admin\admin;

return new application(
    providers: [[new admin(), 'setup']],
    env: [
        'admin' => __DIR__ . '/web/admin.php',
    ],
);
```    

### Configure Admin Settings

Create a new file `app/web/admin.php` and configure your admin settings. This file will define the users, models, and menus for your admin panel.

Example configuration:
```php
# app/web/admin.php
return [
    'user' => ['name' => 'admin', 'password' => 'admin'],
    'models' => [
        // Register models to manage
    ],
    'menus' => [
        // Register admin menu with template or callback
        // Example 1: ['settings' => __DIR__ . '/admin/settings.php']
        // Example 2: ['cms' => function() {}]
        // Example 3: ['api' => 'Hello From Api Key']
    ],
    'settings' => [
        // Register all grouped settings
        'general' => [
            // Register inputs for this group.
            // Example 1: ['type' => 'text', 'name' => 'title']
            // Example 2: ['type' => 'file', 'name' => 'logo']
        ],
    ],
];
```
### Settings Usage

You can get setting value that registered into `app/bootstrap.php` file under **settings** array.

```php
// get a setting with default value
var_dump(setting('general', 'title', 'My Default Title'));

// get settings instance
var_dump(settings());

```

ModelView Usage
---------------

When registering models in the admin panel, you need to configure additional model settings to customize their behavior and appearance.

### Basic Model Registration

For a basic model registration, simply include the model class:
```php
# app/web/admin.php
use models\subject;

return [
    'models' => [
        subject::class,
    ],
];
```   

### Customized Model Registration

For a more customized setup, use the `modelView` class. Below is a complete example:

```php
# app/web/admin.php
use models\subject;
use models\student;
use admin\core\modelView;

return [
    'models' => [
        subject::class, // Basic model
        new modelView(
            model: student::class,
            name: 'student',
            name_plural: 'students',
            fields: ['id', 'name', 'age', 'department'],
            search: ['name'],
            filter: ['gender' => ['M' => 'Male', 'F' => 'Female']],
            with: ['department'],
            where: ['deleted' => false],
            order: 'id DESC',
            actions: ['Export Selected Students Result Sheet' => url('admin/student/export')]
        ), // Customized model
    ],
];
```    

### Explanation of `modelView` Parameters:

*   `model`: The model class to be managed.
*   `name`: Singular name for the model (e.g., 'student').
*   `name_plural`: Plural name for the model (e.g., 'students').
*   `fields`: Fields to be displayed in the admin panel.
*   `search`: Fields to be included in the search functionality.
*   `filter`: Filters to apply (e.g., gender filter with options).
*   `with`: Relationships to include (e.g., 'department').
*   `where`: Conditions to apply (e.g., only include non-deleted records).
*   `order`: Default ordering of records (e.g., 'id DESC').
*   `actions`: Custom actions available for the model (e.g., export functionality).