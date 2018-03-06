<?php
declare(strict_types=1);


namespace duodai\worman\worker;


use duodai\worman\dto\WorkerResponse;
use duodai\worman\interfaces\WorkerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class WorkerLauncher
 * @package duodai\worman\worker
 */
class WorkerLauncher
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * WorkerLauncher constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param WorkerInterface $worker
     */
    public function run(WorkerInterface $worker)
    {
        try{
            $response = $worker->execute();
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