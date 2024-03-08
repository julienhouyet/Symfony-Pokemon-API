<?php

namespace App\Tests\Functional;

use Zenstruck\Browser\Json;
use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use App\Factory\PokemonFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PokemonResourceTest extends ApiTestCase
{
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

	public function testPostToPokemon(): void
	{
		$user = UserFactory::createOne([
			'roles' => ['ROLE_ADMIN'],
			'password' => 'pass',
		]);

		$this->browser()
			->actingAs($user)
			->post('/api/pokemons', HttpOptions::json(['']))
			->dump()
			->assertStatus(500);

		$this->browser()
			->actingAs($user)
			->post('/api/pokemons', HttpOptions::json([
				'name' => 'Pikachu',
				'height' => 1.23,
				'weight' => 2.34,
				'baseExperience' => 64,
			]))
			->dump()
			->assertStatus(201)
			->assertJsonMatches('name', 'Pikachu');

		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($user)
			->post('/api/pokemons', HttpOptions::json([
				'name' => 'Charmander',
				'height' => 0.6,
				'weight' => 8.5,
				'baseExperience' => 62,
				'types' => ['/api/types/' . $type->getId()],
			]))
			->dump()
			->assertStatus(201)
			->assertJsonMatches('name', 'Charmander');
	}
}
