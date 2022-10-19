<?php

namespace Transaction\Service;

use Transaction\LoggingService\PsvTransactionLoggingService;

class ProcessPsvTransactionService
{
    public function processTransaction()
    {
        $this->log();
    }

    protected function log()
    {
        $logginService = new PsvTransactionLoggingService();
        $logginService->psvTransactionHasBeenCompleted();
    }
}
