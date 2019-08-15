<?php


namespace LZaplata\Schedule\DI;


use Nette\DI\CompilerExtension;

class ScheduleExtension extends CompilerExtension
{
    /**
     * @return void
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix("service"))
            ->setFactory("LZaplata\Schedule\Schedule");

        $builder->addDefinition($this->prefix("schedule"))
            ->setFactory("LZaplata\Schedule\Components\ScheduleControl")
            ->setImplement("LZaplata\Schedule\Components\IScheduleControlFactory");

        $builder->addDefinition($this->prefix("room"))
            ->setFactory("LZaplata\Schedule\Components\RoomControl")
            ->setImplement("LZaplata\Schedule\Components\IRoomControlFactory");

        $builder->addDefinition($this->prefix("block"))
            ->setFactory("LZaplata\Schedule\Components\BlockControl")
            ->setImplement("LZaplata\Schedule\Components\IBlockControlFactory");
    }
}