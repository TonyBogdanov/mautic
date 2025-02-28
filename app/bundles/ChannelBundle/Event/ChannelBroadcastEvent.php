<?php

namespace Mautic\ChannelBundle\Event;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ChannelBroadcastEvent.
 */
class ChannelBroadcastEvent extends Event
{
    /**
     * Specific channel.
     *
     * @var null
     */
    protected $channel;

    /**
     * Specific ID of a specific channel.
     *
     * @var null
     */
    protected $id;

    /**
     * Number of contacts successfully processed and/or failed per channel.
     *
     * @var array
     */
    protected $results = [];

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Min contact ID filter can be used for process parallelization.
     *
     * @var int
     */
    private $minContactIdFilter;

    /**
     * Max contact ID filter can be used for process parallelization.
     *
     * @var int
     */
    private $maxContactIdFilter;

    /**
     * How many contacts to load from the database.
     *
     * @var int
     */
    private $limit = 100;

    /**
     * How big batches to use to actually send.
     *
     * @var int
     */
    private $batch = 50;

    /**
     * @var int|null
     */
    private $maxThreads;

    /**
     * @var int|null
     */
    private $threadId;

    /**
     * MaintenanceEvent constructor.
     */
    public function __construct($channel, $channelId, OutputInterface $output)
    {
        $this->channel = $channel;
        $this->id      = $channelId;
        $this->output  = $output;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $channelLabel
     * @param int    $successCount
     * @param int    $failedCount
     */
    public function setResults($channelLabel, $successCount, $failedCount = 0, array $failedRecipientsByList = [])
    {
        $this->results[$channelLabel] = [
            'success'                => (int) $successCount,
            'failed'                 => (int) $failedCount,
            'failedRecipientsByList' => $failedRecipientsByList,
        ];
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param $channel
     *
     * @return bool
     */
    public function checkContext($channel)
    {
        if ($this->channel && $this->channel !== $channel) {
            return false;
        }

        return true;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param int $minContactIdFilter
     */
    public function setMinContactIdFilter($minContactIdFilter)
    {
        $this->minContactIdFilter = $minContactIdFilter;
    }

    /**
     * @return int|null
     */
    public function getMinContactIdFilter()
    {
        return $this->minContactIdFilter;
    }

    /**
     * @param int $maxContactIdFilter
     */
    public function setMaxContactIdFilter($maxContactIdFilter)
    {
        $this->maxContactIdFilter = $maxContactIdFilter;
    }

    /**
     * @return int|null
     */
    public function getMaxContactIdFilter()
    {
        return $this->maxContactIdFilter;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $batch
     */
    public function setBatch($batch)
    {
        $this->batch = $batch;
    }

    /**
     * @return int
     */
    public function getBatch()
    {
        return $this->batch;
    }

    public function getMaxThreads(): ?int
    {
        return $this->maxThreads;
    }

    public function setMaxThreads(?int $maxThreads): void
    {
        $this->maxThreads = $maxThreads;
    }

    public function getThreadId(): ?int
    {
        return $this->threadId;
    }

    public function setThreadId(?int $threadId): void
    {
        $this->threadId = $threadId;
    }
}
