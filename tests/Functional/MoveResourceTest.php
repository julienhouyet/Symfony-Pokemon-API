<?php

namespace App\Tests\Functional;

use App\Factory\MoveFactory;
use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Foundry\Test\ResetDatabase;
use ApiPlatform\GraphQl\Type\TypesFactory;

class MoveResourceTest extends ApiTestCase
{
	use ResetDatabase;

	private $adminUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->adminUser = UserFactory::new()->asAdmin()->create();
	}

	public function testGetCollectionOfMoves(): void
	{
		MoveFactory::createMany(10);

		$this->browser()
			->get('/api/moves')
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 10);
	}

	public function testGetMove(): void
	{
		$move = MoveFactory::createOne();

		$this->browser()
			->get('/api/moves/' . $move->getId())
			->assertJson()
			->assertStatus(200);
	}

	public function testPostToMove(): void
	{

		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/moves', HttpOptions::json(['']))
			->assertStatus(422);

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/moves', HttpOptions::json([
				'type' => $type,
				'name' => 'hydrocanon',
				'power' => 10,
				'pp' => 20,
				'accuracy' => 50
			]))
			->assertStatus(201)
			->assertJsonMatches('name', 'hydrocanon');
	}

	public function testPutMove(): void
	{
		$move = MoveFactory::createOne();
		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->put('/api/moves/' . $move->getId(), HttpOptions::json([
				'type' => $type,
				'name' => 'coudboul',
				'power' => 20,
				'pp' => 30,
				'accuracy' => 10
			]))
			->assertStatus(200)
			//->assertJsonMatches('type', $type)
			->assertJsonMatches('name', 'coudboul')
			->assertJsonMatches('power', 20)
			->assertJsonMatches('pp', 30)
			->assertJsonMatches('accuracy', 10);
	}

	public function testPatchMove(): void
	{
		$move = MoveFactory::createOne([
			'name' => 'thunder',
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->patch('/api/moves/' . $move->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'lightning',
				],
			])
			->assertStatus(200)
			->assertJsonMatches('name', 'lightning');
	}

	public function testDeleteMove(): void
	{
		$move = MoveFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->delete('/api/moves/' . $move->getId())
			->assertStatus(204);

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/moves/' . $move->getId())
			->assertStatus(404);
	}
}
