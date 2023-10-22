<p align="center"><a href="https://autoklose.com" target="_blank"><img src="https://app.autoklose.com/images/svg/autoklose-logo-white.svg" width="400"></a></p>

## Instructions
The repository for the assignment is public and Github does not allow the creation of private forks for public repositories.

The correct way of creating a private fork by duplicating the repo is documented here.

For this assignment the commands are:

Create a bare clone of the repository.

git clone --bare git@github.com:autoklose/laravel-9.git
Create a new private repository on Github and name it laravel-9.

Mirror-push your bare clone to your new repository.

Replace <your_username> with your actual Github username in the url below.

cd laravel-9.git
git push --mirror git@github.com:<your_username>/laravel-9.git
Remove the temporary local repository you created in step 1.

cd ..
rm -rf laravel-9.git
You can now clone your laravel-9 repository on your machine (in my case in the code folder).

cd ~/code
git clone git@github.com:<your_username>/laravel-9.git

## TODO
1. [x] Send Email
1. [x] Store in ES
1. [x] Cache in Redis
1. [x] Queued Job for Email sending
1. [x] List Email
1. [x] Test for Send API and Job
1. [ ] Test for Listing API


## Response Sample
Endpoints
```
POST /api/{user}/send
GET /api/{user}/list
```

### Send Email
Request
```
http://localhost:8000/api/1/send?api_token=005feeea152f6861a295afdb3314e017bc4d9af8


{
    "data": [
        {
            "email": "toemail1@gmail.com",
            "subject": "Subject 1",
            "body": "Body 1"
        },
        {
            "email": "toemail2@gmail.com",
            "subject": "Subject 2",
            "body": "Body 2"
        }
    ]
}
```

Response
```
{
    "message": "Emails sent successfully"
}
```

### List Email
Request
```
http://localhost:8000/api/1/list?api_token=005feeea152f6861a295afdb3314e017bc4d9af8
```

Response
```
{
    "data": [
        {
            "id": "uN09V4sBeF8P419eGrh7",
            "email": "toemail1@gmail.com",
            "subject": "Subject 1",
            "body": "Body 1",
            "timestamp": "2023-10-22T11:55:03.585266Z"
        },
        {
            "id": "wd0-V4sBeF8P419ezrgV",
            "email": "toemail1@gmail.com",
            "subject": "Subject 1",
            "body": "Body 1",
            "timestamp": "2023-10-22T11:56:55.113742Z"
        },
        {
            "id": "LhhEV4sBnMlb8uehu-EP",
            "email": "toemail1@gmail.com",
            "subject": "Subject 1",
            "body": "Body 1",
            "timestamp": "2023-10-22T12:03:23.441811Z"
        },
        {
            "id": "LxhEV4sBnMlb8uehwuFl",
            "email": "toemail2@gmail.com",
            "subject": "Subject 2",
            "body": "Body 2",
            "timestamp": "2023-10-22T12:03:25.319380Z"
        }
    ]
}
```
