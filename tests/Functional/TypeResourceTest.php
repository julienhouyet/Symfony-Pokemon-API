<?php

namespace App\Tests\Functional;

use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use App\Factory\PokemonFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Foundry\Test\ResetDatabase;
use ApiPlatform\GraphQl\Type\TypesFactory;

class TypeResourceTest extends ApiTestCase
{
	use ResetDatabase;

	private $adminUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->adminUser = UserFactory::new()->asAdmin()->create();
	}

	public function testGetCollectionOfTypes(): void
	{
		TypeFactory::createMany(10);

		$this->browser()
			->get('/api/types')
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 10);
	}

	public function testGetType(): void
	{
		$type = TypeFactory::createOne();

		$this->browser()
			->get('/api/types/' . $type->getId())
			->assertJson()
			->assertStatus(200);
	}

	public function testPostToType(): void
	{

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/types', HttpOptions::json(['']))
			->assertStatus(422);

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/types', HttpOptions::json([
				'name' => 'electric'
			]))
			->assertStatus(201)
			->assertJsonMatches('name', 'electric');
	}

	public function testPutType(): void
	{

		$pokemon = TypeFactory::createOne([
			'name' => 'electric'
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->put('/api/types/' . $pokemon->getId(), HttpOptions::json([
				'name' => 'poison'
			]))
			->assertStatus(200)
			->assertJsonMatches('name', 'poison');
	}

	public function testPatchType(): void
	{
		$pokemon = TypeFactory::createOne([
			'name' => 'charmander',
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->patch('/api/types/' . $pokemon->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'charmeleon',
				],
			])
			->assertStatus(200)
			->assertJsonMatches('name', 'charmeleon');
	}

	public function testDeleteType(): void
	{
		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->delete('/api/types/' . $type->getId())
			->assertStatus(204);

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/types/' . $type->getId())
			->assertStatus(404);
	}
}
