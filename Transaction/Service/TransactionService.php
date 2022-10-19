<?php

namespace Transaction\Service;

use Core\Configuration\TransactionConfiguration;
use Core\Exception\ValidationException;
use Core\Service\AbstractService;
use Transaction\LoggingService\PsvTransactionLoggingService;
use UserTransaction\Service\ProcessUserGracePeriodService;

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
            $this->processPsvTransaction();
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
            $result = new ProcessUserGracePeriodService();
            $this->resultAccount['status'] = $result['status'];
            $this->resultAccount['grace_date'] = $result['grace_date'];
        }
    }

    protected function processPsvTransaction()
    {
        if ($this->dto['transaction_type'] == 'psv') {
            $psvTransaction = new ProcessPsvTransactionService();
            $psvTransaction->processTransaction();
        }
    }

    protected function hasUserSufficientFunds(): bool
    {
        return $this->dto['result_amount'] > TransactionConfiguration::LOWER_DEBT_LIMIT;
    }
}
