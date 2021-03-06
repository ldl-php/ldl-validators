<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use LDL\Validators\RegexValidator;
use LDL\Validators\StringValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\NumericComparisonValidator;
use LDL\Validators\Chain\Exception\CombinedException;

echo "Create Validator Chain\n";

$chain = new OrValidatorChain([
    new AndValidatorChain([
        new StringValidator(),
        new RegexValidator('#[a-z]+#')
    ]),
    new AndValidatorChain([
        new IntegerValidator(),
        new OrValidatorChain([
            new IntegerValidator(),
            new AndValidatorChain([
                new NumericComparisonValidator(500, '>'),
                new NumericComparisonValidator(10, '<='),
            ])
        ])
    ]),
    new OrValidatorChain([
        new RegexValidator('#[a-z]+#')
    ])
]);

echo "Validate: 'abc'\n";
$chain->validate('abc');
echo "OK!\n";

echo "Validate: 123\n";
$chain->validate(123);
echo "OK!\n";

echo "Validate: '@@@'\n";
try {
    $chain->validate('@@@');
} catch (CombinedException $e) {
    dump("EXCEPTION: {$e->getCombinedMessage()}");
}

echo "\nGet Validator collection:\n";

foreach ($chain->getChainItems()->getValidators() as $validator) {
    echo get_class($validator) . "\n";
}