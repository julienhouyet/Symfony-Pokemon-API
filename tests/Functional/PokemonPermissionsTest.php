<?php

namespace App\Tests\Functional;

use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use App\Factory\PokemonFactory;
use Zenstruck\Browser\HttpOptions;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

class PokemonPermissionsTest extends ApiTestCase
{
	use ResetDatabase;

	private $publicUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->publicUser = UserFactory::createOne([
			'username' => 'publicuser',
			'password' => 'pass',
		])->object();
	}

	public function testUserCannotPostToPokemon(): void
	{

		$this->browser()
			->actingAs($this->publicUser)
			->post('/api/pokemons', HttpOptions::json([
				'pokedexNumber' => 1,
				'name' => 'pikachu',
				'height' => 1.23,
				'weight' => 2.34,
				'baseExperience' => 64,
			]))
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPutPokemon(): void
	{

		$pokemon = PokemonFactory::createOne([
			'pokedexNumber' => 1,
			'name' => 'bulbasaur',
			'height' => 0.7,
			'weight' => 6.9,
			'baseExperience' => 64,
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->put('/api/pokemons/' . $pokemon->getId(), HttpOptions::json([
				'pokedexNumber' => 2,
				'name' => 'ivysaur',
				'height' => 1.0,
				'weight' => 13.0,
				'baseExperience' => 142,
			]))
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPatchPokemon(): void
	{

		$pokemon = PokemonFactory::createOne([
			'pokedexNumber' => 1,
			'name' => 'charmander',
			'height' => 0.6,
			'weight' => 8.5,
			'baseExperience' => 142,
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->patch('/api/pokemons/' . $pokemon->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'baseExperience' => 62,
				],
			])
			->assertStatus(403)
			->assertJsonMatches('baseExperience', null);
	}

	public function testUserCannotDeletePokemon(): void
	{

		$pokemon = PokemonFactory::createOne();

		$this->browser()
			->actingAs($this->publicUser)
			->delete('/api/pokemons/' . $pokemon->getId())
			->assertStatus(403);
	}
}
