# SlimCMS
My cms by based slim 3 framework
#
Admin panel based on template: SB-admin v2;
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

Installation: 
 git clone 
 php composer install

After install create folder: cache, log. Set permittion from write this folders.

Enter admin panel:
 - url: /auth/login
 - login*: admin
 - password: admin

*if use email for login: admin@admin.net

# License

The SlimCMS platform is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
