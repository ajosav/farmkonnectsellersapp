
## Setting Up

After cloning the repo, run the fillowing commands

**composer Install**

**npm install**

**npm run watch**

// Set up you database in the .env and the run
**php artisan key:generate**

**php artisan migrate --seed**


## Note
Event Listeners, Notifications and observers are being used, please check them out

## More Information

Whenever a new user is registered, the application looks uses the position chosen during registration to give permission to user, changing the position id in the database does not have any effect on the users' permission.
In case you need to change the users' permission, you need to change the position id in the users table (you can look up to the users positions table for the positions id), the go to '/' endpoint to change the users permission to match the selected id (I have set up the method to change the permission in this route).


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
