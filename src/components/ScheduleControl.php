<?php


namespace LZaplata\Schedule\Components;


use LZaplata\Schedule\f;
use LZaplata\Schedule\Room;
use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;
use Nette\Utils\DateTime;

class ScheduleControl extends Control
{
    /** @var IRoomControlFactory */
    private $roomControlFactory;

    /** @var array */
    private $rooms = [];

    /** @var DateTime */
    private $startTime;

    /** @var DateTime */
    private $endTime;

    /** @var array */
    private $days = [
        1 => "Pondělí",
        2 => "Úterý",
        3 => "Středa",
        4 => "Čtvrtek",
        5 => "Pátek",
        6 => "Sobota",
        7 => "Neděle",
    ];

    /** @var int */
    private $dayOfWeek;

    /**
     * ScheduleControl constructor.
     * @param IRoomControlFactory $roomControlFactory
     */
    public function __construct(IRoomControlFactory $roomControlFactory)
    {
        parent::__construct();

        $this->roomControlFactory = $roomControlFactory;
    }

    /**
     * @param int $id
     * @param string $name
     * @return RoomControl
     */
    public function addRoom($name)
    {
        $room = $this->roomControlFactory->create($this);
        $room->setName($name);

        $this->rooms[] = $room;

        return $room;
    }

    /**
     * @return Multiplier
     */
    public function createComponentRoom()
    {
        return new Multiplier(function ($id) {
            return $this->rooms[$id];
        });
    }

    /**
     * @param DateTime $startTime
     */
    public function setStartTime(DateTime $startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param DateTime $endTime
     */
    public function setEndTime(DateTime $endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        if ($this->getParameter("dayOfWeek")) {
            return $this->getParameter("dayOfWeek");
        } else return date("N");
    }

    /**
     * @return int
     */
    private function getInterval()
    {
        $interval = $this->startTime->diff($this->endTime);
        $hours = $interval->format("%h");
        $minutes = $interval->format("%i");

        if ($minutes > 0) {
            $hours++;
        }

        return $hours;
    }

    /**
     * @return void
     */
    public function handleRefresh()
    {
        $this->redrawControl("rooms");
    }

    /**
     * @return void
     */
    public function render()
    {
        $this->template->rooms = $this->rooms;
        $this->template->startTime = clone $this->startTime;
        $this->template->interval = $this->getInterval();
        $this->template->days = $this->days;
        $this->template->dayOfWeek = $this->getDayOfWeek();
        $this->template->setFile(__DIR__ . "/templates/schedule.latte");
        $this->template->render();
    }
}

interface IScheduleControlFactory
{
    /** @return ScheduleControl */
    public function create();
}