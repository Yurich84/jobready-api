Laravel jobready-api
=================
[![Laravel 6](https://img.shields.io/badge/Laravel-6-orange.svg?style=flat-square)](http://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

  
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

License
[MIT](https://raw.github.com/bigperson/laravel-vk-geo/master/LICENSE)
