<?php

namespace Transaction\Validation;

use Core\Validation\AbstractValidator;
use Core\Configuration\TransactionConfiguration;

class UserFundsValidator extends AbstractValidator
{
    protected $result = true;
    protected $dto = true;

    public function validate($dto)
    {
        $this->dto = $dto;
        $this->hasUserSufficientFunds();
        
        return $this->generateResult();
    }

    protected function hasUserSufficientFunds()
    {
        $upperDebtLimit = TransactionConfiguration::UPPER_DEBT_LIMIT;

        if ($this->dto['result_amount'] < $upperDebtLimit && $this->dto['transaction_type'] == 'promotion') {
            $this->result = false;
        } elseif ($this->dto['result_amount'] < $upperDebtLimit + $this->dto['amount'] && $this->dto['transaction_type'] == 'psv') {
            $this->result = false;
        }
    }
}
