# What is This Project

This project is an **online shop** that is created by **Laravel 10.x** and **Vue3** stack with some magic😂.

# How To Get Up and Running

## First Things First

To begin with this project to development environment, follow below structure:

- Run below command in `project's root` first

```
composer install
```

- Then run below command in `frontend` directory

```
npm install
```

- You need to run migrations with seeds to have some data to see by this command

```
php artisan migrate --seed
```

- **After setting needed variables in `.env` of `root` directory and `frontend` directory**, go
  to `frontend` directory with your terminal and run below command

```
npm run dev
```

- Also in another terminal run the laravel server

```
php artisan serve
```

### If You Need (Optional)

If you want to run scheduler an queue, you need to run laravel schedule worker and queue worker by following commands

```
php artisan queue:listen [your_queue_driver]
```

*recommended to run this command*

```
php artisan schedule:work
```

## License

This software licensed under the [Apache2 license](https://www.apache.org/licenses/LICENSE-2.0.html).
