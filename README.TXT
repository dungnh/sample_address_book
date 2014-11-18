### Introduction about this simple app.

A tiny version of an address-book developed by using pure OOP PHP 5 (5.3+) & MySQL 5.x.x with MVC pattern (there are not using any PHP framework also Javascript frameworks).
Well, It's simple contacts list but it can be multiple inherited and the structure can be very complex. The senarial is possible to create groups and add contacts to these groups. Furthermore it can be possible to connect groups. Connected groups inherit contacts. Inherited contacts are deleted if the contact is deleted from the parent group. The structure can be very complex. There is no limit to number of groups, or number of connections. One has to be able to generate n-generations of groups.

### Source code folders structure ####
\root
	\assets: content resources of app as: CSS, Images (img), Javascript (js)
	\controllers: content controllers of app
	\database: content sql script for database structure & initial data
	\models: content models of app
	\partial: content a part of view which requested via Ajax

### The install instruments ####

1. Create database by executing SQL script in \database\address_books_structure.sql. Execute \database\address_books_data.sql to create some initial sample data of app
2. Modify configuration at \config.php with database connection parameter as database hosting, database name, database user, database password
3. Copy all source code to hosting and testing. Sample as: http://localhost/address_book/