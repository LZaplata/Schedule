<?php


namespace LZaplata\Schedule\Components;


use Nette\Application\UI\Control;
use Nette\Utils\DateTime;

class BlockControl extends Control
{
    /** @var string */
    private $name;

    /** @var \DatePeriod */
    private $datePeriod;

    /** @var int */
    private $dayOfWeek;

    /** @var ScheduleControl */
    private $scheduleControl;

    /** @var string */
    private $note;

    /** @var string */
    private $condition;

    /** @var string */
    const CONDITION_CANCELLED = "cancelled",
        CONDITION_WARNING = "warning";

    /**
     * BlockControl constructor.
     * @param ScheduleControl $scheduleControl
     */
    public function __construct(ScheduleControl $scheduleControl)
    {
        parent::__construct();

        $this->scheduleControl = $scheduleControl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \DatePeriod $datePeriod
     */
    public function setDatePeriod(\DatePeriod $datePeriod)
    {
        $this->datePeriod = $datePeriod;
    }

    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param int $dayOfWeek
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        $hours = $this->datePeriod->getStartDate()->diff($this->datePeriod->getEndDate())->format("%h");
        $minutes = $this->datePeriod->getStartDate()->diff($this->datePeriod->getEndDate())->format("%i");

        return ($hours * 60) + $minutes;
    }

    /**
     * @return int
     */
    public function getStartInterval()
    {
        $hours = $this->scheduleControl->getStartTime()->diff($this->datePeriod->getStartDate())->format("%h");
        $minutes = $this->scheduleControl->getStartTime()->diff($this->datePeriod->getStartDate())->format("%i");

        return ($hours * 60) + $minutes;
    }

    /**
     * @return int
     */
    public function getEndInterval()
    {
        $hours = $this->scheduleControl->getStartTime()->diff($this->datePeriod->getEndDate())->format("%h");
        $minutes = $this->scheduleControl->getStartTime()->diff($this->datePeriod->getEndDate())->format("%i");

        return ($hours * 60) + $minutes;
    }

    /**
     * @return int
     */
    public function getActualInterval()
    {
        $time = DateTime::from("now");
        $interval = $this->scheduleControl->getStartTime()->diff($time);

        if ($interval->invert) {
            return 0;
        } else {
            $hours = $interval->format("%h");
            $minutes = $interval->format("%i");

            return ($hours * 60) + $minutes;
        }
    }

    /**
     * @return int
     */
    public function getAbsoluteEndInterval()
    {
        $hours = $this->scheduleControl->getEndTime()->diff($this->datePeriod->getEndDate())->format("%h");
        $minutes = $this->scheduleControl->getEndTime()->diff($this->datePeriod->getEndDate())->format("%i");

        return ($hours * 60) + $minutes;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return void
     */
    public function render()
    {
        $this->template->name = $this->name;
        $this->template->interval = $this->getInterval();
        $this->template->startInterval = $this->getStartInterval();
        $this->template->endInterval = $this->getEndInterval();
        $this->template->actualInterval = $this->getActualInterval();
        $this->template->absoluteEndInterval = $this->getAbsoluteEndInterval();
        $this->template->datePeriod = $this->datePeriod;
        $this->template->note = $this->note;
        $this->template->condition = $this->condition;
        $this->template->setFile(__DIR__ . "/templates/block.latte");
        $this->template->render();
    }
}

interface IBlockControlFactory
{
    /** @return BlockControl */
    public function create(ScheduleControl $scheduleControl);
}