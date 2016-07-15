# SlimCMS
Lightweight CMS(CMF) based on: php framework slim 3, laravel eloquent, symfony event dispatcher, Twig templater and other libraries.

The fast creation of a new website.

[![Latest Unstable Version](https://poser.pugx.org/andrey900/slimcms/v/unstable)](https://packagist.org/packages/andrey900/slimcms)
[![Total Downloads](https://poser.pugx.org/andrey900/slimcms/downloads)](https://packagist.org/packages/andrey900/slimcms)
[![License](https://poser.pugx.org/andrey900/slimcms/license)](https://packagist.org/packages/andrey900/slimcms)

#
Admin panel based on template: SB-admin v2;
#

| Project use additional library | Implemented modules |
|---|---|
| Slim v3 | Options |
|  |  |
|  |  |
|  |  |
|  |  |
|  |  |


| Group Options | Slim Twig Templater v2 |
| Auth system | Slim Flash |
| Create visual page, and create route | Monolog - save log in file or DB(mysql, sqlite) |
| Create sections(categories) and hierarchical sections(categories) | Slim http cache(don't use this time) |
| Frendly admin panel | Slim CSRF protection |
| User customize show field and sortable fields from tables(your settings for every page type) | Portable DB sqlite |
| Admin panel table pagination(your settings for every page type) | Illuminate database v5.2 |
| Admin panel count show items in table(your settings for every page type) | Illuminate pagination v5.2 |
| Many types show field from admin panel(hidden, checkbox, select, wysiwyg html, text) |  |
| Logging system |  |
| Create new module |  |
| Installer system |  |

 
If you are interested in this system, **place a star** )))
If the project attains **more than 30 stars**, the official website of the documentation will be created.
 
Installation:

      git clone https://github.com/andrey900/SlimCMS.git
      php composer install
or

       mkdir ~/slimcms && cd ~/slimcms
       composer create-project -s dev andrey900/slimcms .
       mkdir cache && chmod a+w cache && mkdir log && chmod a+w log
       php -S 127.0.0.1:8080 -t public/
       open browser url: http://127.0.0.1:8080
 
 After install create folder: **cache, log**. Set permittion from write this folders.

Enter admin panel:
 - url: /auth/login
 - login*: admin
 - password: admin

*if use email for login: admin@admin.net

#### Screenshots
| Sign In       | Users page    | Column config  |
| ------------- |:-------------:| --------------:|
| ![alt tag](http://ipic.su/img/img7/fs/ScreenShot2016-03-26at13.1458989450.png) | ![alt tag](http://ipic.su/img/img7/fs/ScreenShot2016-03-26at13.1458989486.png) | ![alt tag](http://ipic.su/img/img7/fs/ScreenShot2016-03-26at13.1458989510.png) |

Supported versions of php:
 - php: ^5.5
 - php: ^7.0

## If You Need Help
If you have problems using or install system, please write in new issue or email(andrey@avgz.net), and I will try to help you

## License
The SlimCMS platform is free software distributed under the terms of the [MIT license](http://opensource.org/licenses/MIT).

## Donations
Bitcoin address for donation: 18ERsiXpvrkGMwcvLmCNVBrfJwmM8hqurY

### Social Links
[Official facebook](https://www.facebook.com/groups/997922036987106/)
