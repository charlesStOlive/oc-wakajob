<?php namespace Waka\Wakajob\Contracts;

use Illuminate\Contracts\Queue\Factory as FactoryContract;

interface Clearer
{
    /**
     * @param FactoryContract $manager
     */
    public function __construct(FactoryContract $manager);

    /**
     * Deletes all the jobs in the queue for connection. Returns
     * how many jobs were deleted
     *
     * @param $connection
     * @param $queue
     * @return int
     */
    public function clear($connection, $queue);
}
