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

	public function testGetPokemon(): void
	{
		$pokemon = PokemonFactory::createOne();

		$this->browser()
			->get('/api/pokemons/' . $pokemon->getId())
			->dump()
			->assertJson()
			->assertStatus(200);
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
				'name' => 'pikachu',
				'height' => 1.23,
				'weight' => 2.34,
				'baseExperience' => 64,
			]))
			->dump()
			->assertStatus(201)
			->assertJsonMatches('name', 'pikachu');

		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($user)
			->post('/api/pokemons', HttpOptions::json([
				'name' => 'charmander',
				'height' => 0.6,
				'weight' => 8.5,
				'baseExperience' => 62,
				'types' => ['/api/types/' . $type->getId()],
			]))
			->dump()
			->assertStatus(201)
			->assertJsonMatches('name', 'charmander');
	}

	public function testPutPokemon(): void
	{
		$user = UserFactory::createOne([
			'roles' => ['ROLE_ADMIN'],
			'password' => 'pass',
		]);

		$pokemon = PokemonFactory::createOne([
			'name' => 'bulbasaur',
			'height' => 0.7,
			'weight' => 6.9,
			'baseExperience' => 64,
		]);

		$this->browser()
			->actingAs($user)
			->put('/api/pokemons/' . $pokemon->getId(), HttpOptions::json([
				'name' => 'ivysaur',
				'height' => 1.0,
				'weight' => 13.0,
				'baseExperience' => 142,
			]))
			->assertStatus(200)
			->assertJsonMatches('name', 'ivysaur');
	}

	public function testPatchPokemon(): void
	{
		$user = UserFactory::createOne([
			'roles' => ['ROLE_ADMIN'],
			'password' => 'pass',
		]);

		$pokemon = PokemonFactory::createOne([
			'name' => 'charmander',
			'height' => 0.6,
			'weight' => 8.5,
			'baseExperience' => 142,
		]);

		$this->browser()
			->actingAs($user)
			->patch('/api/pokemons/' . $pokemon->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'baseExperience' => 62,
				],
			])
			->dump()
			->assertStatus(200)
			->assertJsonMatches('baseExperience', 62);
	}

	public function testDeletePokemon(): void
	{
		$user = UserFactory::createOne([
			'roles' => ['ROLE_ADMIN'],
			'password' => 'pass',
		]);

		$pokemon = PokemonFactory::createOne();

		$this->browser()
			->actingAs($user)
			->delete('/api/pokemons/' . $pokemon->getId())
			->assertStatus(204);

		$this->browser()
			->actingAs($user)
			->get('/api/pokemons/' . $pokemon->getId())
			->assertStatus(404);
	}
}
