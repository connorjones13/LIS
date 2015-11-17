
# LIS

## When Pulling Changes

When you pull down new changes, there are a few things you need to do before you begin coding.
- Run any new queries in the `query.log`
- Update composer packages

#### Running The Queries - (This will require Vagrant to be up)
Copy any quires you have not run from the `query.log` folder. In PHPStorm, on the right side of the
screen should be a tab called _Database_. Open that tab and right click on the MySQL - Vagrant item.
Select _New_ -> _Console File_ and paste the queries into this tab. In the top left corner of the tab
should be a Play Button. Click this to run the queries.

#### Updating Composer
In PHPStorm, there should be a _Terminal_ tab across the bottom of the screen. Open that tab and run
the following command.
- `php ./composer.phar update`