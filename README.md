# Created and powered with Laravel
Quotation software developed with Laravel and Angular
## Setup
To run the project locally, first start the BD manager, for this project development MySQL was used with XAMPP. Once you had the DB manger running, open the project and run the migrations
`php artisan migrate`
If everything went okay, you can now populate initial data in the configuration table
`php artisan db:seed`
And finally you are ready to run the test server
`php artisan serve`

## Api Access
First you need to create a new user, you can do it with the next endpoint
[Register User](http://127.0.0.1:8000/api/register)
```
{
	"name": "Dummy",
    "email": "dummy@dummy.com",
    "password": "Dummy"
}
```

The previous process should generate a new token, but if you need a new one you can just login.
[Login](http://127.0.0.1:8000/api/login)
```
{
    "email": "dummy@dummy.com",
    "password": "Dummy"
}
```

If you finished, you can logout.
[Logout](http://127.0.0.1:8000/api/logout)

To get a new quotation.
[Quotaion](http://127.0.0.1:8000/api/quotations/calculate-quotation)
```
Ages must be separated by comma.
Start date and End date must have the format YYYY-MM-DD
{
    "ages": "28,35",
    "currency_id": "COP",
    "start_date": "2023-10-01",
    "end_date": "2023-10-30"
}
```

To get a existing quotation by ID.
[Quotaion](http://127.0.0.1:8000/api/quotations/calculate-quotation)
```
{
    "quotation_id": 1
}
```

## Front End
Once clone the repo, enter `airo_test_front` folder and run
`npm install`
`ng serve --open`

If you want to check the production implementation, you can do it the next way:
`ng build --configuration production --aot`
Once the build is done, copy the `dist/project-name` folder `.js` and `.css` files and put them inside `public/angular-app` folder, then just run the laravel project with a server


## Working application
http://airotest.laprovinciacafe.com/login
http://airotest.laprovinciacafe.com/api/login

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
