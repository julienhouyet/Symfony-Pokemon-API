<?php

namespace App\Factory;

use App\Entity\Move;
use App\Repository\MoveRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Move>
 *
 * @method        Move|Proxy                     create(array|callable $attributes = [])
 * @method static Move|Proxy                     createOne(array $attributes = [])
 * @method static Move|Proxy                     find(object|array|mixed $criteria)
 * @method static Move|Proxy                     findOrCreate(array $attributes)
 * @method static Move|Proxy                     first(string $sortedField = 'id')
 * @method static Move|Proxy                     last(string $sortedField = 'id')
 * @method static Move|Proxy                     random(array $attributes = [])
 * @method static Move|Proxy                     randomOrCreate(array $attributes = [])
 * @method static MoveRepository|RepositoryProxy repository()
 * @method static Move[]|Proxy[]                 all()
 * @method static Move[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Move[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Move[]|Proxy[]                 findBy(array $attributes)
 * @method static Move[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Move[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class MoveFactory extends ModelFactory
{
	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
	 *
	 * @todo inject services if required
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
	 *
	 * @todo add your default values here
	 */
	protected function getDefaults(): array
	{
		return [
			'type' => TypeFactory::randomOrCreate(),
			'name' => self::faker()->word(),
			'power' => self::faker()->numberBetween(10, 100),
			'pp' => self::faker()->numberBetween(5, 40),
			'accuracy' => self::faker()->numberBetween(50, 100),
		];
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
	 */
	protected function initialize(): self
	{
		return $this
			// ->afterInstantiate(function(Move $move): void {})
		;
	}

	protected static function getClass(): string
	{
		return Move::class;
	}
}
