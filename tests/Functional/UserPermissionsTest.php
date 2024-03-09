<?php

namespace App\Tests\Functional;

use Zenstruck\Browser\Json;
use App\Factory\UserFactory;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserPermissionsTest extends ApiTestCase
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

	public function testUserCannotGetCollectionOfUsers(): void
	{
		UserFactory::createMany(9);

		$this->browser()
			->actingAs($this->publicUser)
			->get('/api/users')
			->assertJson()
			->assertJsonMatches('"hydra:totalItems"', null);
	}

	public function testUserCannotGetAnotherUser(): void
	{
		$userTest = UserFactory::createOne();

		$this->browser()
			->actingAs($this->publicUser)
			->get('/api/users/' . $userTest->getId())
			->assertJson()
			->assertStatus(403);
	}

	public function testUserCanGetOwnAccount(): void
	{

		$this->browser()
			->actingAs($this->publicUser)
			->get('/api/users/' . $this->publicUser->getId())
			->assertJson()
			->assertStatus(200);
	}

	public function testUserCannotPutAnotherUser(): void
	{
		$userTest = UserFactory::createOne([
			'email' => 'test123@test123.com',
			'password' => 'test123',
			'username' => 'userrandom'
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->put('/api/users/' . $userTest->getId(), HttpOptions::json([
				'email' => 'test2@test2.com',
				'password' => '321test',
				'username' => 'randomuser'
			]))
			->assertStatus(403)
			->assertJsonMatches('email', null);
	}

	public function testUserCanPutOwnAccount(): void
	{
		$this->browser()
			->actingAs($this->publicUser)
			->put('/api/users/' . $this->publicUser->getId(), HttpOptions::json([
				'email' => 'canedit@test.com',
				'password' => 'pass',
				'username' => 'test'
			]))
			->assertStatus(200)
			->assertJsonMatches('email', 'canedit@test.com');
	}

	public function testUserCannotPatchAnotherUser(): void
	{
		$userTest = UserFactory::createOne([
			'email' => 'test123@test123.com',
			'password' => 'test123',
			'username' => 'userrandom'
		]);

		$this->browser()
			->actingAs($this->publicUser)
			->patch('/api/users/' . $userTest->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'username' => 'sacha'
				],
			])
			->assertStatus(403)
			->assertJsonMatches('username', null);
	}

	public function testUserCanPatchOwnAccount(): void
	{
		$this->browser()
			->actingAs($this->publicUser)
			->patch('/api/users/' . $this->publicUser->getId(), [
				'headers' => [
					'Content-Type' => 'application/merge-patch+json',
				],
				'json' => [
					'username' => 'sacha'
				],
			])
			->assertStatus(200)
			->assertJsonMatches('username', 'sacha');
	}

	public function testUserCannotDeleteAnotherUser(): void
	{
		$userTest = UserFactory::createOne();

		$this->browser()
			->actingAs($this->publicUser)
			->delete('/api/users/' . $userTest->getId())
			->assertStatus(403);
	}

	public function testUserCannotDeleteOwnAccount(): void
	{
		$userTest = UserFactory::createOne();

		$this->browser()
			->actingAs($this->publicUser)
			->delete('/api/users/' . $this->publicUser->getId())
			->assertStatus(403);
	}
}
