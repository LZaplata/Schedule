<?php


namespace LZaplata\Schedule\Components;


use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;
use Nette\ComponentModel\IContainer;
use Nette\Object;
use Nette\Utils\DateTime;

class RoomControl extends Control
{
    /** @var string */
    private $name;

    /** @var array */
    private $blocks = [];

    /** @var IBlockControlFactory */
    private $blockControlFactory;

    /** @var ScheduleControl */
    private $scheduleControl;

    /**
     * RoomControl constructor.
     * @param IBlockControlFactory $blockControlFactory
     */
    public function __construct(ScheduleControl $scheduleControl, IBlockControlFactory $blockControlFactory)
    {
        parent::__construct();

        $this->scheduleControl = $scheduleControl;
        $this->blockControlFactory = $blockControlFactory;
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
     * @param string $name
     * @param string $startTime
     * @param string $endTime
     * @param int $dayOfWeek
     * @return BlockControl
     */
    public function addBlock($name, DateTime $startTime, DateTime $endTime, $dayOfWeek)
    {
        $block = $this->blockControlFactory->create($this->scheduleControl);
        $block->setName($name);
        $block->setDatePeriod(new \DatePeriod($startTime, new \DateInterval("P1M"), $endTime));
        $block->setDayOfWeek($dayOfWeek);

        $this->blocks[$dayOfWeek][] = $block;

        if ($this->scheduleControl->getStartTime() > $startTime || $this->scheduleControl->getStartTime() === null) {
            $this->scheduleControl->setStartTime($startTime);
        }

        if ($this->scheduleControl->getEndTime() < $endTime || $this->scheduleControl->getEndTime() === null) {
            $this->scheduleControl->setEndTime($endTime);
        }

        return $block;
    }

    /**
     * @return Multiplier
     */
    public function createComponentBlock()
    {
        return new Multiplier(function ($id) {
            return $this->getBlocks()[$id];
        });
    }

    /**
     * @return array
     */
    private function getBlocks()
    {
        if (isset($this->blocks[$this->scheduleControl->getDayOfWeek()])) {
            return $this->blocks[$this->scheduleControl->getDayOfWeek()];
        } else return [];
    }

    /**
     * @return void
     */
    public function render()
    {
        $this->template->name = $this->name;
        $this->template->blocks = $this->getBlocks();
        $this->template->setFile(__DIR__ . "/templates/room.latte");
        $this->template->render();
    }
}

interface IRoomControlFactory
{
    /** @return RoomControl */
    public function create(ScheduleControl $scheduleControl);
}