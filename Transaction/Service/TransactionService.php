<?php

namespace Transaction\Service;

use Core\Configuration\TransactionConfiguration;
use Core\Exception\ValidationException;
use Core\Service\AbstractService;
use DateTime;
use Transaction\LoggingService\PsvTransactionLoggingService;
use UserTransaction\CommunicationService\UserGracePeriodCommunicationService;

class TransactionService extends AbstractService
{
    protected $resultAccount = [
        'status' => 'ok',
        'grace_date' => 'null'
    ];

    public function postTransaction()
    {
        $this->populateDto();
        if ($this->validate($this->dto)) {
            $this->generateResult();
            $this->log();
        } else {
            throw new ValidationException($this->errors);
        }

        return $this->resultAccount;
    }

    public function populateDto()
    {
        return $this->dto;
    }

    public function generateResult()
    {
        $this->resultAccount['amount'] = $this->dto['result_amount'];

        if ($this->hasUserSufficientFunds()) {
            $this->processGracePeriod();
        }
    }

    protected function processGracePeriod()
    {
        if ($this->dto['account_status'] != 'grace') {
            $this->resultAccount['status'] = 'grace';
            $this->resultAccount['grace_date'] = new DateTime();
            $this->communicate();
        }
    }

    protected function communicate()
    {
        $communicationService = new UserGracePeriodCommunicationService();
        $communicationService->userGracePeriodHasStarted();
    }

    protected function log()
    {
        if ($this->dto['transaction_type'] == 'psv') {
            $logginService = new PsvTransactionLoggingService();
            $logginService->psvTransactionHasBeenCompleted();
        }
    }

    protected function hasUserSufficientFunds(): bool
    {
        return $this->dto['result_amount'] > TransactionConfiguration::LOWER_DEBT_LIMIT;
    }
}
