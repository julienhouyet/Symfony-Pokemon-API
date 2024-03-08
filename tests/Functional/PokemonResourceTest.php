<?php

namespace App\Tests\Functional;

use App\Factory\TypeFactory;
use App\Factory\PokemonFactory;
use App\Factory\UserFactory;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PokemonResourceTest extends KernelTestCase
{
	use HasBrowser;
	use ResetDatabase;

	public function testGetCollectionOfPokemons(): void
	{
		TypeFactory::createMany(10);

		$this->browser()
			->get('/api/types')
			->dump()
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 10);

		PokemonFactory::createMany(40, function () {
			return [
				'types' => TypeFactory::randomRange(0, 2),
			];
		});

		$this->browser()
			->get('/api/pokemons')
			->dump()
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 40);
	}
}
