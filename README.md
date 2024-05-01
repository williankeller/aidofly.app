# Aidofly

### File permissions

1. Change the owner of the directories to the web server user. Open your terminal and run the following commands:

```bash
sudo chown -R www-data:www-data /var/www/aidofly/storage
sudo chown -R www-data:www-data /var/www/aidofly/bootstrap/cache
```

2. Set the correct permissions on these directories to allow writing by the web server. Execute these commands:

```bash
sudo chmod -R 775 /var/www/aidofly/storage
sudo chmod -R 775 /var/www/aidofly/bootstrap/cache
```

### Database

1. Create a new database for the project.
2. Run the migrations to create the tables in the database.

```bash
php artisan migrate:fresh --seed
```
