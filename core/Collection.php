<?php

declare(strict_types=1);

namespace core;

class Collection implements \IteratorAggregate
{
    private $items;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function filter(callable $callback)
    {
        return new self(array_filter($this->items, $callback));
    }

    public function map(callable $callback)
    {
        return new self(array_map($callback, $this->items));
    }

    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function toArray()
    {
        // map items
        return array_map(function ($item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                return $item->toArray();
            }

            return $item;
        }, $this->items);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function toJson()
    {
        // return json_encode($this->items);

        // map items
        return array_map(function ($item) {
            if (is_object($item) && method_exists($item, 'toJson')) {
                return $item->toJson();
            }

            return $item;
        }, $this->items);
    }

    public function toObject()
    {
        return (object) $this->items;
    }

    public function toCollection()
    {
        return new self($this->items);
    }

    /**
     * How many items are in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get the first item from the collection.
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * Get the last item from the collection.
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->items);
    }

    /**
     * Is collection empty?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

}


/*

// Create a collection
$collection = new Collection([1, 2, 3, 4, 5]);

// Filter even numbers
$evenNumbers = $collection->filter(function($item) {
    return $item % 2 == 0;
})->toArray();
// Output: [2, 4]

// Map to square of each item
$squares = $collection->map(function($item) {
    return $item * $item;
})->toArray();
// Output: [1, 4, 9, 16, 25]

// Reduce to sum of all items
$sum = $collection->reduce(function($carry, $item) {
    return $carry + $item;
}, 0);
// Output: 15

// Loop over the collection using foreach
foreach ($collection as $item) {
    echo $item . ' ';
}
// Output: 1 2 3 4 5


 */
