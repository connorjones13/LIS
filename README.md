
# LIS

## When Pulling Changes

When you pull new changes, the first thing you should check is the query.log file. It is located in the project's root
directory and contains all the changes made to the database. If you do not run these first, the program will likely
crash. This is not really an issue since it doesn't actually break anything, but it will not run until your database
is up to date.