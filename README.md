# What is This Project

This project is an **online shop** that is created by **Laravel 10.x** and **Vue3** stack with some magic😂.

# How To Get Up and Running

## First Things First

To begin with this project to development environment, follow below instruction:

### Install `Node Js` on your machine

You need **Node Js** to be installed on your machine, so install it!

### Install `composer` on your machine

To manage PHP packages, you need `composer`, install it too.

---

After install those, do below steps:

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

#### Images

To have a working frontend that show images of products and other parts, you need to create structure that mentioned
in `FileManagerSeeder.php` under `database/seeders` directory with `filemanager` in **admin** panel.

To enter **admin** panel, you need to create a user for yourself (Do it somehow✌️).

After create needed directory structure in filemanager, you need files to upload there. Go to `frontend/src/assets` then
for each directory you created, there is an equivalent folder, add them by uploading them to each directory.

---

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

## Generating Sitemap

To generate sitemap *automatically* you need to run below command under `frontend` directory:

```
npm run build-routes-json
```

After run this, you have your sitemap routes in `frontend/src/assets/temp` directory.

### Customize Sitemap Routes

- To customize your json routes, you can go under `frontend/src/service/extra` directory and `RoutesToJson.js` file.
  Read
  the documentation of it and generate your sitemap as you need(**Of course according to your frontend routes**).

- Then move generated file to `resources/sitemap` directory of laravel structure.

It'll run and generate sitemap for both frontend and backend parts when you run the scheduler(**Dig in codes and
understand it**).

## License

This software licensed under the [Apache2 license](https://www.apache.org/licenses/LICENSE-2.0.html).
