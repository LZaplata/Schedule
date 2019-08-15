<?php


namespace LZaplata\Schedule;


use LZaplata\Schedule\Components\IScheduleControlFactory;
use Nette\Object;

class Schedule extends Object
{
    /** @var IScheduleControlFactory */
    private $scheduleControlFactory;

    /**
     * Schedule constructor.
     * @param IScheduleControlFactory $scheduleControlFactory
     */
    public function __construct(IScheduleControlFactory $scheduleControlFactory)
    {
        $this->scheduleControlFactory = $scheduleControlFactory;
    }

    /**
     * @return Components\ScheduleControl
     */
    public function create()
    {
        return $this->scheduleControlFactory->create();
    }
}