<?php

namespace Core\Validation;

abstract class AbstractValidator
{
    protected $errors = [];
    protected $result = true;
    protected $message;
    protected $success = true;

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
        $this->checkResult();
        $result['errors'] = $this->getErrors();
        $result['success'] = $this->success;

        return $result;
    }

    protected function checkResult()
    {
        if (!$this->result) {
            $this->setError($this->message);
            $this->success = false;
        }
    }
}
