<?php

namespace App\Tests\Functional;

use App\Factory\StatFactory;
use App\Factory\UserFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Foundry\Test\ResetDatabase;
use ApiPlatform\GraphQl\Type\TypesFactory;

class StatResourceTest extends ApiTestCase
{
	use ResetDatabase;

	private $adminUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->adminUser = UserFactory::new()->asAdmin()->create();
	}

	public function testGetCollectionOfStats(): void
	{
		StatFactory::createMany(8);

		$this->browser()
			->get('/api/stats')
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 8);
	}

	public function testGetStat(): void
	{
		$stat = StatFactory::createOne();

		$this->browser()
			->get('/api/stats/' . $stat->getId())
			->assertJson()
			->assertStatus(200);
	}

	public function testPostToStat(): void
	{

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/stats', HttpOptions::json(['']))
			->assertStatus(422);

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/stats', HttpOptions::json([
				'name' => 'special-attack'
			]))
			->assertStatus(201)
			->assertJsonMatches('name', 'special-attack');
	}

	public function testPutStat(): void
	{

		$stat = StatFactory::createOne([
			'name' => 'electric'
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->put('/api/stats/' . $stat->getId(), HttpOptions::json([
				'name' => 'poison'
			]))
			->assertStatus(200)
			->assertJsonMatches('name', 'poison');
	}

	public function testPatchStat(): void
	{
		$stat = StatFactory::createOne([
			'name' => 'self-attack',
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->patch('/api/stats/' . $stat->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'self-defense',
				],
			])
			->assertStatus(200)
			->assertJsonMatches('name', 'self-defense');
	}

	public function testDeleteStat(): void
	{
		$stat = StatFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->delete('/api/stats/' . $stat->getId())
			->assertStatus(204);

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/stats/' . $stat->getId())
			->assertStatus(404);
	}
}
