<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';


use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Chain\Exception\CombinedException;

echo "Create Validator Chain\n";

$chain = new OrValidatorChain([
    new RegexValidator('#[a-z]+#'),
    new RegexValidator('#[0-9]+#')
], true);

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($chain));

try{
    $chain->validate('a');
}catch(CombinedException $e){
    echo "EXCEPTION: {$e->getCombinedMessage()}\n";
}