Laravel jobready-api
=================
[![Laravel 6](https://img.shields.io/badge/Laravel-6-orange.svg?style=flat-square)](http://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Use a [Official Jobready Plus](https://developer.jobready.io) API Documentation

Install
------------------

```
composer require Yurich84/jobready-api
```


Usage
-------------

Add the following lines to `.env`
```
JOBREADY_USER=your_user_name
JOBREADY_KEY=your_secret_key
```

Available methods
-------------
`where` - adds entity's parameters to request

`find` - gets single entity

`findBulk` - retrieve entities by array of uris

`get` - gets list of entities

`uri` - gets generated uri

`url` - gets generated url

`create`

`update`

`getResponse` - manually sending get request

`postResponse` - manually sending post request


Examples
-------------
**Find**

```php

use Yurich84\JobReadyApi\Entities\Courses;
...
    $course_number = '99AUS999';
    $courses = (new Courses)->find($course_number);
```


**Get list of Events with limit 20**

```php
use Yurich84\JobReadyApi\Entities\Events;
use Yurich84\JobReadyApi\JobReady;
use \Carbon\Carbon;
...
$events = (new Events)
    ->where(Events::PARAMETER_DATE_FROM, Carbon::now()->format(JobReady::DATE_FORMAT))
    ->where(Events::PARAMETER_DATE_TO, Carbon::now()->addWeek()->format(JobReady::DATE_FORMAT))
    ->get(20);
```

**Create**

```php
use Yurich84\JobReadyApi\Entities\Trainers;
...
$data = (new Trainers)->create([
    Trainers::FIELD_PARTY_ID => 'PAUS000000'
]);
```

**Update**

```php
use Yurich84\JobReadyApi\Entities\Trainers;
...
$payload = [
    Trainers::FIELD_ENABLED => true,
    Trainers::FIELD_EMPLOYMENT_BASIS => 'full-time',
    Trainers::FIELD_IND_ASSESSOR => true,
    Trainers::FIELD_IND_COORDINATOR => true,
    Trainers::FIELD_IND_TRAINER => true,
];
$data = (new Trainers)->update('9804', $payload);    
```

**Manually creating**

```php
use Yurich84\JobReadyApi\Entities\Events;
use Yurich84\JobReadyApi\JobReady;
use \Carbon\Carbon;
...

$course_number = '99AUS999';

$date = Carbon::now()->addWeeks(2)->format(JobReady::DATE_FORMAT);

$payload = [
    'event' => [
        Events::FIELD_TITLE => 'Test Event',
        Events::FIELD_EVENT_DATE => $date,
        Events::FIELD_START_TIME => '09:00:00',
        Events::FIELD_END_TIME => '15:00:00',
        Events::FIELD_ALL_STAFF => true,
        Events::FIELD_ALL_STUDENTS => true,
    ]
];

$data = (new Events)->postResponse("courses/{$course_number}/events", $payload);
```

**License**
[MIT](https://raw.github.com/bigperson/laravel-vk-geo/master/LICENSE)
