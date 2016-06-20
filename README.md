# SlimCMS
Lightweight CMS based on: php framework slim 3, laravel eloquent, symfony event dispatcher, Twig templater and other libraries

The fast creation of a new website.
#
Admin panel based on template: SB-admin v2;
#
Implemented modules:
 - Options
 - Group Options
 - Auth system
 - Create visual page, and create route
 - Create sections(categories) and hierarchical sections(categories)
 - Frendly admin panel
 - User customize show field and sortable fields from tables(your settings for every page type)
 - Admin panel table pagination(your settings for every page type)
 - Admin panel count show items in table(your settings for every page type)
 - Many types show field from admin panel(hidden, checkbox, select, wysiwyg html, text)
 - Logging system
 - Developer admin panel mode
 - Create new module
 
If you are interested in this system, **place a star** )))
If the project attains **more than 30 stars**, the official website of the documentation will be created.
 
Installation:

      git clone https://github.com/andrey900/SlimCMS.git
      php composer install
 
 After install create folder: cache, log. Set permittion from write this folders.

Enter admin panel:
 - url: /auth/login
 - login*: admin
 - password: admin

*if use email for login: admin@admin.net
 
# Screenshots
![alt tag](http://ipic.su/img/img7/fs/ScreenShot2016-03-26at13.1458989450.png)
![alt tag](http://ipic.su/img/img7/fs/ScreenShot2016-03-26at13.1458989486.png)
![alt tag](http://ipic.su/img/img7/fs/ScreenShot2016-03-26at13.1458989510.png)

Project use additional library:
 - Slim v3;
 - Slim Twig Templater v2;
 - Slim Flash;
 - Monolog - save log in file or DB(mysql, sqlite);
 - Slim http cache(don't use this time);
 - Slim CSRF protection;
 - Portable DB sqlite;
 - Illuminate database v5.2;
 - Illuminate pagination v5.2;

Supported versions of php:
 - php: ^5.5
 - php: ^7.0

## If You Need Help
Please submit all issues and questions using GitHub issues and I will try to help you :)

## License
The SlimCMS platform is free software distributed under the terms of the [MIT license](http://opensource.org/licenses/MIT).

## Donations
Bitcoin address for donation: 18ERsiXpvrkGMwcvLmCNVBrfJwmM8hqurY
