<?php

declare(strict_types=1);

namespace core\router;

class Route
{
    private $name;


    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

}