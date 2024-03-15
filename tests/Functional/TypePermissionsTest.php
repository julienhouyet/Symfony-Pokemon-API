<?php

namespace App\Tests\Functional;

use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Foundry\Test\ResetDatabase;

class TypePermissionsTest extends ApiTestCase
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

	public function testGuestCannotPostToType(): void
	{

		$this->browser()
			->post('/api/types', HttpOptions::json([
				'name' => 'electric'
			]))
			->assertStatus(401)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPostToType(): void
	{

		$this->browser()
			->actingAs($this->publicUser)
			->post('/api/types', HttpOptions::json([
				'name' => 'electric'
			]))
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testGuestCannotPutType(): void
	{

		$type = TypeFactory::createOne([
			'name' => 'electric'
		]);

		$this->browser()
			->put('/api/types/' . $type->getId(), HttpOptions::json([
				'name' => 'poison'
			]))
			->assertStatus(401)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPutType(): void
	{

		$type = TypeFactory::createOne([
			'name' => 'electric'
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->put('/api/types/' . $type->getId(), HttpOptions::json([
				'name' => 'poison'
			]))
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testGuestCannotPatchType(): void
	{
		$type = TypeFactory::createOne([
			'name' => 'charmander',
		]);

		$this->browser()
			->patch('/api/types/' . $type->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'charmeleon',
				],
			])
			->assertStatus(401)
			->assertJsonMatches('name', null);
	}

	public function testUserCannotPatchType(): void
	{
		$type = TypeFactory::createOne([
			'name' => 'charmander',
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->patch('/api/types/' . $type->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'name' => 'charmeleon',
				],
			])
			->assertStatus(403)
			->assertJsonMatches('name', null);
	}

	public function testGuestCannotDeleteType(): void
	{
		$type = TypeFactory::createOne();

		$this->browser()
			->delete('/api/types/' . $type->getId())
			->assertStatus(401);
	}

	public function testUserCannotDeleteType(): void
	{
		$type = TypeFactory::createOne();

		$this->browser()
			->actingAs($this->publicUser)
			->delete('/api/types/' . $type->getId())
			->assertStatus(403);
	}
}
