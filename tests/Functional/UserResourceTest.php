<?php

namespace App\Tests\Functional;

use Zenstruck\Browser\Json;
use App\Factory\UserFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserResourceTest extends ApiTestCase
{
	use ResetDatabase;

	private $adminUser;

	protected function setUp(): void
	{
		parent::setUp();

		$this->adminUser = UserFactory::createOne([
			'roles' => ['ROLE_ADMIN'],
			'password' => 'pass',
		])->object();
	}

	public function testGetCollectionOfUsers(): void
	{
		UserFactory::createMany(9);

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/users')
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', 10);
	}

	public function testGetUser(): void
	{
		$userTest = UserFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/users/' . $userTest->getId())
			->assertJson()
			->assertStatus(200);
	}

	public function testPostToUser(): void
	{
		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/users', HttpOptions::json(['']))
			->assertStatus(422);

		$this->browser()
			->actingAs($this->adminUser)
			->post('/api/users', HttpOptions::json([
				'email' => 'test@test.com',
				'password' => 'test123',
				'username' => 'userrandom'
			]))
			->assertStatus(201)
			->assertJsonMatches('email', 'test@test.com');
	}

	public function testPutUser(): void
	{
		$userTest = UserFactory::createOne([
			'email' => 'test@test.com',
			'password' => 'test123',
			'username' => 'userrandom'
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->put('/api/users/' . $userTest->getId(), HttpOptions::json([
				'email' => 'test2@test2.com',
				'password' => '321test',
				'username' => 'randomuser'
			]))
			->assertStatus(200)
			->assertJsonMatches('email', 'test2@test2.com');
	}

	public function testPatchUser(): void
	{
		$userTest = UserFactory::createOne([
			'email' => 'test@test.com',
			'password' => 'test123',
			'username' => 'userrandom'
		]);

		$this->browser()
			->actingAs($this->adminUser)
			->patch('/api/users/' . $userTest->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'username' => 'randomuser'
				],
			])
			->assertStatus(200)
			->assertJsonMatches('username', 'randomuser');
	}

	public function testDeleteUser(): void
	{
		$userTest = UserFactory::createOne();

		$this->browser()
			->actingAs($this->adminUser)
			->delete('/api/users/' . $userTest->getId())
			->assertStatus(204);

		$this->browser()
			->actingAs($this->adminUser)
			->get('/api/users/' . $userTest->getId())
			->assertStatus(404);
	}
}
