<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\ValidatorInterface;

class AndValidatorChain extends AbstractValidatorChain
{
    public const OPERATOR = ' && ';

    public function validate($value, ...$params) : void
    {
        $this->lastExecuted = null;
        $this->failed = [];
        $this->succeeded = [];

        if(0 === $this->count()){
            return;
        }

        $this->config->isNegated() ? $this->assertFalse($value, ...$params) : $this->assertTrue($value, ...$params);
    }

    public function assertTrue($value, ...$params): void
    {
        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->lastExecuted = $validator;

            try {
                $validator->validate($value, ...$params);
                $this->succeeded[] = $validator;
            }catch(\Exception $e){
                $this->failed[] = $validator;
                throw $e;
            }
        }

    }

    public function assertFalse($value, ...$params): void
    {
        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->lastExecuted = $validator;

            try {
                $validator->validate($value, ...$params);
                $this->succeeded[] = $validator;
            }catch(\Exception $e){
                $this->failed[] = $validator;
                throw $e;
            }
        }
    }
}