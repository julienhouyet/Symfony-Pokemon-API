<?php

namespace App\DataFixtures;

use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use App\Factory\PokemonFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$this->createAdminUser();

		TypeFactory::createMany(10);
		PokemonFactory::createMany(40, function () {
			return [
				'types' => TypeFactory::randomRange(0, 2),
			];
		});

		UserFactory::createMany(10);

		$manager->flush();
	}

	private function createAdminUser(): void
	{
		$existingAdmin = UserFactory::repository()->findOneBy(['email' => 'admin@pokemonmail.com']);

		if (!$existingAdmin) {
			UserFactory::new()->create([
				'email' => 'admin@pokemonmail.com',
				'roles' => ['ROLE_ADMIN'],
				'password' => 'root',
				'username' => 'admin',
			]);
		}
	}
}
