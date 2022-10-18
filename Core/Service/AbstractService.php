<?php

namespace Core\Service;

class AbstractService
{
    protected $dto;
    protected $errors = [];

    public function validate($dto)
    {
        $validator = $this->getValidatorInstance();
        $validator->validate($dto);
        $this->errors = $validator->getErrors();

        return $this->isSuccess();
    }

    protected function getValidatorInstance()
    {
    }

    public function isSuccess()
    {
        return count($this->errors) == 0;
    }
}
