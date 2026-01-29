<?php

declare(strict_types=1);

namespace App\Model\Parser;

use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

readonly class ProductParserRegistry
{
    public function __construct(
        #[AutowireLocator('app.product_parser', defaultIndexMethod: 'type')]
        private ServiceLocator $parsers,
    ) {}

    public function getParser(string $type): ProductParserInterface
    {
        if (!$this->parsers->has($type)) {
            throw new \LogicException(sprintf('No parser found for type "%s"', $type));
        }

        /** @var ProductParserInterface $parser */
        $parser = $this->parsers->get($type);
        return $parser;
    }
}
