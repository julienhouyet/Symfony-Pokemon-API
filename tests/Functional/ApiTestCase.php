<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;

abstract class ApiTestCase extends KernelTestCase
{
	use HasBrowser {
		browser as baseKernelBrowser;
	}

	protected function browser(array $options = [], array $server = [])
	{
		return $this->baseKernelBrowser($options, $server)
			->setDefaultHttpOptions(
				HttpOptions::create()
					->withHeader('Accept', 'application/ld+json')
					->withHeader('Content-Type', 'application/ld+json')

			);
	}
}
