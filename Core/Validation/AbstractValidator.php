<?php

namespace Core\Validation;

abstract class AbstractValidator
{
    protected $errors = [];

    abstract public function validate($dto);

    public function getErrors()
    {
        return $this->errors;
    }

    public function setError($error)
    {
        $this->errors[] = $error;
    }

    protected function generateResult()
    {
        return;
    }
}
