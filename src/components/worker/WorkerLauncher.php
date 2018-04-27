<?php
declare(strict_types=1);


namespace duodai\worman\components\worker;

use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\interfaces\WorkerInterface;
use duodai\worman\interfaces\WorkerLauncherInterface;
use Psr\Log\LoggerInterface;

/**
 * Class WorkerLauncher
 * @package duodai\worman\worker
 */
class WorkerLauncher implements WorkerLauncherInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected $worker;

    /**
     * WorkerLauncher constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(WorkerInterface $worker, LoggerInterface $logger)
    {
        $this->worker = $worker;
        $this->logger = $logger;
    }

    /**
     *
     */
    public function run():void
    {
        try{
            $response = $this->worker->execute();
        }catch (\Error $e){
            $this->logger->critical($e->getMessage(), [$e->getFile(), $e->getLine(), $e->getTraceAsString()]);
            $response = new WorkerResponse(WorkerResponse::ERROR);
        }
        catch (\Exception $e){
            $this->logger->error($e->getMessage(), [$e->getFile(), $e->getLine(), $e->getTraceAsString()]);
            $response = new WorkerResponse(WorkerResponse::ERROR);;
        }
        exit($response->val());
    }
}