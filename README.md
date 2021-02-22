# LaravelApiService

### Setup ###
* Start docker: Navigate to project folder
```bash
$ docker-compose up -d --build
```
* Sign in to the php container
```bash
$ docker exec -it laravel-api001-app /bin/bash
```
* Install composer packages
```bash
$ composer install
```
* Execute DB migrations
```bash
$ php artisan migrate
```
* Start handling queues
```bash
$ php artisan queue:work --queue=webhooks,requests --delay=60
```
### How to test ###
You can use [Postman](https://www.postman.com/) application
In the repository you can find `laravel-app-001.postman_collection.json` file. Import it to you Postman and you will see `laravel-app-001` collection and requests inside it.
Use `Process POST` endpoint to create a job. It will return the following answer
```json
{
    "jobStatusId": 25
}
```
Use returned `jobStatusId` value (`25` in this particular case) at `Status` endpoint to get the job status
```
http://0.0.0.0:8083/api/status/25
```

Please note that there is ability to setup webhooks for each job. To do it you need to add `webhooks` header to your query with the following value:
```json
[{"m": "GET", "u": "http://laravel-api001-webserver/api/test/409"}]
```
Where `m` - is HTTP method and `u` is the URl of your webhook

