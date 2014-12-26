LicenceProject
==============

Licence project by [Nicolas BASCON](https://github.com/nicobascon) and [Yannis RICHARD](https://github.com/yannisrichard).

Proposed by [Sebastien SALVA](http://sebastien.salva.free.fr/), we had to create web services for a teacher-searcher like website. The whole website, using our web services, is [here](https://github.com/mickaelstorm2703/site_chercheur_licence). A Windows 8 application for manage the administration was created [here](https://github.com/YearDegree2/LicenceProjectWindows8).

All paths are in the folder controllers in app. If you want to change the database, it's in the file config.php in app/config.

All functional tests are in the folder Functional in tests/Appli/Tests.

The database we used is the file webchercheur.sql. You just have to import this file in your MySQL application.

All functional tests are only ok with this database only filled by one user. If you want functional tests works, you have to test with this database. If we filled this database tests don't work because we test if tables are empty.

We used the user in the database to made our tests, the couple login / password is admin / admin.

If you want to add other administrators to the user table, you just have to go in MySQL, in the user table, and add the login you choosed and in the field password, you put the result of $this->encoder->encodePassword($password, 'A654FSDFSD54FDSF45D$SFSD4FDS5£'); , for example by create another path and use Appli\PasswordEncoder;
