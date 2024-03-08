<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class PokemonResourceTest extends KernelTestCase
{
	use HasBrowser;
	use ResetDatabase;

	public function testGetCollectionOfPokemon(): void
	{
		$this->browser()
			->get('/api/pokemon')
			->dump()
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 0);
	}
}
