ZSSN (Zombie Survival Social Network)
================

ZSSN API Development using Laravel 8 with MySQL database.

## How to install

First of all, you need to configure composer and php (7.4 or more) in your environment.<br>
Now you need to clone this project to your pc and extract it to your project directory.<br>
After clone this project run composer to install all dependencies.

```
composer install
```
Ok, let's configurate now the database, first you need copy .env.example to .env in the root of project using this command with command prompt.<br>
```
copy .env.example .env
```
After you finish configure database like user, password, and database name you can just start generate new project key and migrate the database.

```
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Then, run `php artisan serve`, and it will be available at http://localhost:8000/api. <br>
Or if you using `Laragon` like me, it will be available at `project_name.test` and no need to run `php artisan serve`. <br>

-------------------------------------------------------------------------

## API Documentation

### GET /survivors/{survivor_id}

```
GET /:survivors/{survivor_id}
Content-Type: "application/json"
```

Attribute | Description
----------| -----------
**survivor_id**    | Survivor id

#### Returns

```
200 Ok
Content-Type: "application/json"
{
    "status": 1,
    "message": "Successfully get survivor",
    "data": {
        "id": 1,
        "name": "Noval Dwi Putra",
        "age": 26,
        "latitude": "-6.191490",
        "longitude": "106.794290",
        "is_infected": "0",
        "gender_id": 1,
        "created_at": "2022-05-22T09:20:28.000000Z",
        "updated_at": "2022-05-22T10:11:25.000000Z",
        "items": [
            {
                "id": 1,
                "name": "Water",
                "point": 4,
                "created_at": "2022-05-22T11:10:07.000000Z",
                "updated_at": "2022-05-22T11:10:07.000000Z",
                "pivot": {
                    "survivor_id": 1,
                    "item_id": 1,
                    "amount": 6
                }
            },
            {
                "id": 2,
                "name": "Food",
                "point": 3,
                "created_at": "2022-05-22T11:10:07.000000Z",
                "updated_at": "2022-05-22T11:10:07.000000Z",
                "pivot": {
                    "survivor_id": 1,
                    "item_id": 2,
                    "amount": 8
                }
            },
            {
                "id": 3,
                "name": "Medication",
                "point": 2,
                "created_at": "2022-05-22T11:10:07.000000Z",
                "updated_at": "2022-05-22T11:10:07.000000Z",
                "pivot": {
                    "survivor_id": 1,
                    "item_id": 3,
                    "amount": 1
                }
            },
            {
                "id": 4,
                "name": "Ammunition",
                "point": 1,
                "created_at": "2022-05-22T11:10:07.000000Z",
                "updated_at": "2022-05-22T11:10:07.000000Z",
                "pivot": {
                    "survivor_id": 1,
                    "item_id": 4,
                    "amount": 25
                }
            }
        ]
    }
}
```

### POST /survivors

```
POST /survivors
Content-Type: "application/json"

{
    "name": "Noval Dwi Putra",
    "age": 26,
    "latitude": "1522255.09052",
    "longitude": "1336523.1439",
    "is_infected": 0, // you can skip this, because it default as 0 if you insert to database.
    "gender_id": 1, // this is id in table gender, if you using my db seed 1 => Male, 2 => Female.
    "items[]": 1, // you can fill this value using column id in table items.
    "amount[]": 10, // amount of item when you register.
    "items[]": 2, // you can register survivors with many items, just follow the previous example.
    "amount[]": 5
}
```
##### Returns:

```
200 OK
Content-Type: "application/json"

{
    "status": 1,
    "message": "Successfully create a new Survivor"
}
```

### POST /survivors/{survivor_id}/last_location?_method=PATCH
Attribute | Description
----------| -----------
**survivor_id**    | Survivor id

```
POST /survivors/{survivor_id}/last_location?_method=PATCH // add method PATCH because we only replace certain field
Content-Type: "application/json"

{
    "latitude": "1522255.09052",
    "longitude": "1336523.1439"
}
```

#### Returns

```
200 OK
Content-Type: "application/json"

{
    "status": 1,
    "message": "Successfully updating last location"
}
```

### POST /survivors/createContaminationSurvivor

```
POST /:survivors/createContaminationSurvivor
Content-Type: "application/json"

{
    "survivor_id": 5, // suspected survivor, fill the value using column id from table survivor
    "report_by": 8 // survivor who report other survivor if he get contaminated, can't using same value survivor_id above
}
```

#### Returns

```
200 Ok
Content-Type: "application/json"

{
    "status": 1,
    "message": "Successfully create new contamination survivor"
}
```

```
404 Error Not Found
Content-Type: "application/json"

{
    "status": 0,
    "message": "Survivor Not Found."
}
```

```
500 Internal Server Error
Content-Type: "application/json"

{
    "status": 0,
    "message": "You can't report yourself."
}
```

```
500 Internal Server Error
Content-Type: "application/json"

{
    "status": 0,
    "message": "Reporter already report this survivor."
}
```

```
500 Internal Server Error
Content-Type: "application/json"

{
    "status": 0,
    "message": "You can't report other survivor, because you already infected."
}
```

```
500 Internal Server Error
Content-Type: "application/json"

{
    "status": 0,
    "message": "Unable to create new contamination Survivor."
}
```

```
500 Internal Server Error
Content-Type: "application/json"

{
    "status": 0,
    "message": "Unable to update is infected value to Survivor."
}
```

### POST /trades
```
POST /trades
Content-Type: "application/json"

{
    "survivor_id": 1, // fill using value column id from table survivor
    "other_survivor": 3, // fill using value column id from table survivor, need to have different value from survivor_id
    "want[]": 1, // fill using value column id from table items
    "wantamount[]": 1, // amount of item
    "want[]": 3, // you were able to trade more than one item
    "wantamount[]": 1,
    "give[]": 2, // fill using value column id from table items
    "giveamount[]": 2 // amount of item
}
```

#### Returns
```
200 OK
Content-Type: "application/json"

{
    "status": 1,
    "message": "Successfully trade items"
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "You can't trade with yourself."
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "Failed to make an exchange, because one of both survivor are infected."
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "Other survivors don't have one or more of the items you want."
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "You don't have one or more of the items you want to give."
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "Survivors want more items than other survivors have."
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "Amount of items that you want to give is more than amount of items you have."
}
```

```
400 Bad Request
Content-Type: "application/json"

{
    "status": 0,
    "message": "Exchange failed, amounts of points of the two survivors is not the same."
}
```

```
500 Internal Server Error
Content-Type: "application/json"

{
    "status": 0,
    "message": "Unable to trade items."
}
```

### GET /reports
```
GET /reports/percentInfected
Content-Type: "application/json"
```

#### Returns
```
200 Ok
Content-Type: "application/json"

{
    "status": 1,
    "message": "Successfully get reports",
    "data": {
        "survivors": {
            "infectedSurvivors": "24.1%",
            "notInfectedSurvivors": "75.9%"
        },
        "items": {
            "averageItem": {
                "Water": 1.9,
                "Food": 1,
                "Medication": 0.3,
                "Ammunition": 1
            },
            "sumInfectedPoint": 183
        }
    }
}
```





