<?php

namespace App\Tests\Functional;

use App\Factory\StatFactory;
use App\Factory\UserFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Foundry\Test\ResetDatabase;

class StatPermissionsTest extends ApiTestCase
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

	public function testGuestCannotPostToStat(): void
	{
		$this->browser()
			->post('/api/stats', HttpOptions::json([
				'name' => 'special-attack'
			]))
			->assertStatus(401)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPostToStat(): void
	{
		$this->browser()
			->actingAs($this->publicUser)
			->post('/api/stats', HttpOptions::json([
				'name' => 'special-attack'
			]))
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testGuestCannotPutStat(): void
	{

		$stat = StatFactory::createOne([
			'name' => 'electric'
		]);

		$this->browser()
			->put('/api/stats/' . $stat->getId(), HttpOptions::json([
				'name' => 'poison'
			]))
			->assertStatus(401)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPutStat(): void
	{

		$stat = StatFactory::createOne([
			'name' => 'electric'
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->put('/api/stats/' . $stat->getId(), HttpOptions::json([
				'name' => 'poison'
			]))
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testGuestCannotPatchStat(): void
	{
		$stat = StatFactory::createOne([
			'name' => 'self-attack',
		]);

		$this->browser()
			->patch('/api/stats/' . $stat->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'self-defense',
				],
			])
			->assertStatus(401)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPatchStat(): void
	{
		$stat = StatFactory::createOne([
			'name' => 'self-attack',
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->patch('/api/stats/' . $stat->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'self-defense',
				],
			])
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testGuestCannotDeleteStat(): void
	{
		$stat = StatFactory::createOne();

		$this->browser()
			->delete('/api/stats/' . $stat->getId())
			->assertStatus(401);
	}

	public function testUserCannotDeleteStat(): void
	{
		$stat = StatFactory::createOne();

		$this->browser()
			->actingAs($this->publicUser)
			->delete('/api/stats/' . $stat->getId())
			->assertStatus(403);
	}
}
