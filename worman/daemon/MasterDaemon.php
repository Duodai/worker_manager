<?php

namespace app\worman\daemon;

use app\worman\config\WorkerConfig;
use app\worman\helpers\ConsoleHelper;
use app\worman\interfaces\DaemonInterface;
use app\worman\interfaces\SystemScannerInterface;

class MasterDaemon implements DaemonInterface
{

    /**
     * @var SystemScannerInterface
     */
    protected $systemScanner;

    /**
     * @var
     */
    protected $sid;

    /**
     * @var
     */
    protected $masterConfig;

    /**
     * @var WorkerConfig
     */
    protected $workerConfig;

    protected $communicator;

    public function __construct()
    {

    }
    
    public function start()
    {
        $pid = getmypid();
        ConsoleHelper::msg("Master daemon started. PID: $pid");
        $this->systemScanner->init();

        // запуск воркеров: количество должно браться: в порядке приоритетнеости:
        // 1. конфиг из контрол центра(принудительная настройка) 2. авторегулировка 3. дефолты
        // вопрос: как будет авторегулировка взаимодействовать с дефолтами?

        /*

         какие вообще могут быть настройки?

        1. кулдаун
        2. количество воркеров
        3. метод выбора воркерами задач



        надо куда-то сейвить пид чтобы можно было кильнуть процесс

        надо решить, как перезапускать воркера при изменении настроек или кода.

        флоу:

        1. запуск
        2. получить свой пид
        3. запустить скан системы
        4. отправить данные на сервер // по итогам обкатки и профилирования возможно понадобится сократить количество запросов
        5. получить сид с сервера
        6. получить настройки с сервера
        7. собрать результирующие настройки
        8. применить настройки
        9. проверить сигналы
        10. поспать


         */
    }

}
