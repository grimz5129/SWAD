# 20-3110-AI SWAD Coursework
## Quick start
1. Populate the MySQL table using the `database.sql` file in the root directory
2. Edit `coursework_public/index.php` to use the correct include path for `coursework_private/bootstrap.php`
3. Edit `coursework_private/app/settings.php` to contain the correct mysql server name, username, database, password, collation etc. for the database.
4. Chmod the `coursework_private/app/var` folder and the `coursework_private/app/var/monolog.log` file, with sufficient permissions such as 775 to allow the webserver application to write to the monolog log file and the folder. This step is important, the application will not start without completing this step.
