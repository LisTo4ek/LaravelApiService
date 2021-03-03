# LaravelApiService (LAS)

A HTTP proxy-like service which retries requests on failure for a specific amount of attempts with a specific delay 
and returns responses to a specified webhook (also with retries).
The role of the service - to smooth temporary unavailability of 3rd party services.

## How it works.
Imagine you want to send a request to https://some-service.com/api/rosource.

Instead, you'll send it to LaravelApiService (LAS) containing `https://some-service.com/api/resource` as a parameter 
in the header, and a webhook to be triggered when request is successfully executed.
A queue job is created at LAS. LAS will try to access `https://some-service.com/api/resource`.

If `https://some-service.com/api/resource` is unavailable or its response code is >= 500 then LAS will make 
another attempt in 60 seconds.
LAS will make up to 15 attempts if needed. 
If response is successful then another job will be created to trigger webhook.

Webhooks will be handled the same way (using queue jobs).

## Setup
* Start docker: Navigate to project folder
```bash
$ docker-compose up -d --build
```
* Sign in to the php container
```bash
$ docker-compose exec app bash
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
$ php artisan queue:work --queue=webhooks,requests --tries=15 --delay=60
```
where:

`--tries[=TRIES]` is the number of times to attempt a job before logging it failed

`--delay[=DELAY]` is the number of seconds between attempts

## How to test
Service can be accessed at http://0.0.0.0:8083/

### Endpoints
#### /api/process
This endpoint can be requested using any HTTP method.
The following specific headers are available:

`request-uri` - Endpoint URI of 3rd party service that should be accessed

`webhooks` - Webhooks for each 3rd party endpoint. When successful request to 3rd party endpoint is made then webhooks will be triggered.
Example of `webhooks` header value:
```json
[{"m": "GET", "u": "http://laravel-api001-webserver/api/test/409"}]
```
Where `m` - is HTTP method and `u` is the URl of your webhook.
All other header and parameters should be setup as if you are making request 3rd party endpoint.

It will return the following response:
```json
{
    "jobStatusId": 25
}
```
#### /api/status/{jobStatusId}
Use returned `jobStatusId` value (`25` in this particular case) at `Status` endpoint to get the job status

### Postman
You can use [Postman](https://www.postman.com/) application.
In the repository you can find `laravel-app-001.postman_collection.json` file. Import it to you Postman and you will see `laravel-app-001` collection and requests inside it.
Use `Process POST` endpoint to create a job. Please note that `request-uri` and `webhooks` headers are already specified there.
Use returned `jobStatusId` value at `Status` endpoint to get the job status
```
http://0.0.0.0:8083/api/status/25
```

Please check video: https://drive.google.com/file/d/13I45mDlY5XJdjO8qae94HHrV7JPb4aSR/view?usp=sharing

## ToDo ##
* Add logging
* Think about encrypting requests in queue storage because requests can contain security stuff
* Improve `/api/status/{jobStatusId}` endpoint to display webhooks triggering information
* Think about using Redis or RabbitMQ instead of database for queues
* Use Supervisor process manager to run queue handler job
* Implement this service using `Nodejs` or `Go` 
