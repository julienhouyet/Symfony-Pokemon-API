<?php

namespace App\Tests\Functional;

use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use App\Factory\PokemonFactory;
use Zenstruck\Browser\HttpOptions;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

class PokemonResourceTest extends ApiTestCase
{
	use ResetDatabase;

	private $adminUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->adminUser = UserFactory::new()->asAdmin()->create();
	}

	public function testGetCollectionOfPokemons(): void
	{
		PokemonFactory::createMany(40);

		$this->browser()
			->get('/api/pokemons')
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 40);
	}

	public function testGetPokemon(): void
	{
		$pokemon = PokemonFactory::createOne();

		$this->browser()
			->get('/api/pokemons/' . $pokemon->getId())
			->assertJson()
			->assertStatus(200);
	}

	public function testPostToPokemon(): void
	{

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/pokemons', HttpOptions::json(['']))
			->assertStatus(422);

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/pokemons', HttpOptions::json([
				'pokedexNumber' => 1,
				'name' => 'pikachu',
				'height' => 1.23,
				'weight' => 2.34,
				'baseExperience' => 64,
			]))
			->assertStatus(201)
			->assertJsonMatches('name', 'pikachu');

		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/pokemons', HttpOptions::json([
				'pokedexNumber' => 2,
				'name' => 'charmander',
				'height' => 0.6,
				'weight' => 8.5,
				'baseExperience' => 62,
				'types' => ['/api/types/' . $type->getId()],
			]))
			->assertStatus(201)
			->assertJsonMatches('name', 'charmander');
	}

	public function testPutPokemon(): void
	{

		$pokemon = PokemonFactory::createOne([
			'name' => 'bulbasaur',
			'height' => 0.7,
			'weight' => 6.9,
			'baseExperience' => 64,
		]);

		$this->browser()
			->actingAs($this->adminUser)
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

		$pokemon = PokemonFactory::createOne([
			'name' => 'charmander',
			'height' => 0.6,
			'weight' => 8.5,
			'baseExperience' => 142,
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->patch('/api/pokemons/' . $pokemon->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'baseExperience' => 62,
				],
			])
			->assertStatus(200)
			->assertJsonMatches('baseExperience', 62);
	}

	public function testDeletePokemon(): void
	{

		$pokemon = PokemonFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->delete('/api/pokemons/' . $pokemon->getId())
			->assertStatus(204);

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/pokemons/' . $pokemon->getId())
			->assertStatus(404);
	}
}
