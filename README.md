# Accounts Management with txt files using Symfony Framework 

Designing and implementing a RESTful API to do a basic token transfer from user id 1 to user id 2.

## Installation

- drop the project in your localhost folder/ docker conainer and access the project with url.

## API Endpoints
### GET
##### get accounts
```php
'GET' accounts-with-txt-file/public/api/accounts
```
##### response
```js
[
    {
        "name": "user1.txt",
        "balance": "9900"
    },
    {
        "name": "user2.txt",
        "balance": "10100"
    }
]
```
##### reset accounts
```php
'GET' accounts-with-txt-file/public/api/resetAccounts
```
##### response
```js
[
    {
        "name": "user1.txt",
        "balance": "10000"
    },
    {
        "name": "user2.txt",
        "balance": "10000"
    }
]
```
### POST
##### amount transfer
```php
'POST' accounts-with-txt-file/public/api/transfer
```

##### response
```js
[
    {
        "name": "user1.txt",
        "balance": "9500"
    },
    {
        "name": "user2.txt",
        "balance": "10500"
    }
]
```



## Features

- Account management sys with txt files
- you can add more accounts in the accounts directory
- get the list of accounts with the balance
- Transfer funds 
- Reset accounts to 10000 (defined amount)
- validations
- unit tests  



