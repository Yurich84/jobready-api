<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\JobReady;
use Yurich84\JobReadyApi\JobReadyService;
use Yurich84\JobReadyApi\EntityInterface;
use Carbon\Carbon;

/**
 * Class Events
 * @package Yurich84\JobReadyApi\Entities
 */
class Events extends JobReadyService implements EntityInterface
{
    const ENTITY = 'events';

    /*
    |--------------------------------------------------------------------------
    | Request Parameters
    |--------------------------------------------------------------------------
    */
    const PARAMETER_COURSE_NUMBER = 'course_number';
    const PARAMETER_EVENT_ID = 'event_id';
    const PARAMETER_PARTY_IDENTIFIER = 'party_identifier';
    const PARAMETER_EVENT_TYPE = 'event_type';
    const PARAMETER_DATE_FROM = 'date_from';
    const PARAMETER_DATE_TO = 'date_to';
    const PARAMETER_CREATED_SINCE = 'created_since';
    const PARAMETER_UPDATED_SINCE = 'updated_since';

    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ID = 'id';
    const FIELD_COURSE_NUMBER = 'course-number';
    const FIELD_TITLE = 'title';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_ENABLED = 'enabled';
    const FIELD_EVENT_DATE = 'event-date';
    const FIELD_EVENT_TYPE = 'event-type';

    const FIELD_START_TIME = 'start-time';
    const FIELD_END_TIME = 'end-time';
    const FIELD_ALL_STAFF = 'all-staff';
    const FIELD_ALL_STUDENTS = 'all-students';

    /**
     * @return string
     */
    public function entity(): string
    {
        return self::ENTITY;
    }

    /**
     * @param string $courseCode
     * @return object
     */
    public function getFutureEvents(string $courseCode = '')
    {
        $events = $this->getUpcomingQuery();
        if(!empty($courseCode)) {
            $events->where(self::PARAMETER_COURSE_NUMBER, $courseCode);
        }
        $events = $events->get();

        if(!is_array($events->data)) {
            $events->data = $events->data->toArray();
        }

        $events->data = array_filter($events->data, function ($event) {
            $now = Carbon::now();
            return $now->lessThan(Carbon::createFromFormat('Y-m-d H:i:s', $event['event-date'] . ' ' . $event['end-time']));
        });
        $events->total = count($events->data);

        return $events;
    }

    private function getUpcomingQuery()
    {
        return $this
            ->where(self::PARAMETER_DATE_FROM, Carbon::now()->format(self::DATE_FORMAT))
            ->where(self::PARAMETER_DATE_TO, Carbon::now()->addDay()->format(self::DATE_FORMAT));
    }


    /**
     * @param $events
     * @return array
     */
    public static function groupedByCourses($events) : array
    {
        $grouped_by_course = [Courses::INDEX_UNDEFINED => []];
        foreach ($events as $event) {
            $course_code = $event[self::FIELD_COURSE_NUMBER];

            if($course_code == '') {
                if(is_array($event) && is_array($event['event-links']) && key_exists('event-links', $event) && key_exists('event-link', $event['event-links'])) {
                    // looking for course in event-links
                    foreach ($event['event-links']['event-link'] as $key => $link) {
                        if(is_array($link) && key_exists('course', $link)) {
                            $grouped_by_course[$link['course']][] = $event;
                        } elseif (is_string($link) && $key == 'course') {
                            $grouped_by_course[$link][] = $event;
                        }
                    }
                } else {
                    $grouped_by_course[Courses::INDEX_UNDEFINED][] = $event;
                }
            } else {
                $grouped_by_course[$course_code][] = $event;
            }
        }

        return $grouped_by_course;
    }


    /**
     * @param $month_number
     * @return mixed
     */
    public static function getEventsByMonth($month_number = 0)
    {
        $start_date = Carbon::now()->startOfDay()->addWeeks($month_number);
        $start_date_formatted = $start_date->format(JobReady::DATE_TIME_FORMAT);
        $finish_date_formatted = $start_date->addDay()->format(JobReady::DATE_TIME_FORMAT);
        $events = (new Events)
            ->where(self::PARAMETER_DATE_FROM, $start_date_formatted)
            ->where(self::PARAMETER_DATE_TO, $finish_date_formatted)
            ->get();

        return $events->data;
    }
}