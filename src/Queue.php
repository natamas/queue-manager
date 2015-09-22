<?php namespace Teach;
use Beanstalk\Client,
    \Configure;

class Queue {

    public $client;
    public $config;
    protected $tube;

    public function __construct(Client $client,$tube){
        $this->client = $client;
        $this->tube = $tube;
    }

    public function put($worker,$payload,$delay=0,$priority=1,$ttr=0){
        $job = array(
            'type' => $worker,
            'payload' => $payload
        );

        $this->client->connect();
        $this->client->useTube($this->tube);
        $this->client->put(
            $priority,
            $delay,  // Do not wait to put job into the ready queue.
            $ttr, // Give the job 1 minute to run.
            json_encode($job) // The job's body.
        );

        $this->client->disconnect();
    }
}


