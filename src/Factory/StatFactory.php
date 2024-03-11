<?php

namespace App\Factory;

use App\Entity\Stat;
use App\Repository\StatRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Stat>
 *
 * @method        Stat|Proxy                     create(array|callable $attributes = [])
 * @method static Stat|Proxy                     createOne(array $attributes = [])
 * @method static Stat|Proxy                     find(object|array|mixed $criteria)
 * @method static Stat|Proxy                     findOrCreate(array $attributes)
 * @method static Stat|Proxy                     first(string $sortedField = 'id')
 * @method static Stat|Proxy                     last(string $sortedField = 'id')
 * @method static Stat|Proxy                     random(array $attributes = [])
 * @method static Stat|Proxy                     randomOrCreate(array $attributes = [])
 * @method static StatRepository|RepositoryProxy repository()
 * @method static Stat[]|Proxy[]                 all()
 * @method static Stat[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Stat[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Stat[]|Proxy[]                 findBy(array $attributes)
 * @method static Stat[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Stat[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class StatFactory extends ModelFactory
{

	private const STAT_NAMES = ['hp', 'attack', 'defense', 'special-attack', 'special-defense', 'speed', 'accuracy', 'evasion'];

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
		$uniqueStats = array_slice(array_unique(self::STAT_NAMES), 0, count(array_unique(self::STAT_NAMES)));

		return [
			'name' => self::faker()->randomElement($uniqueStats),
		];
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
	 */
	protected function initialize(): self
	{
		return $this
			// ->afterInstantiate(function(Stat $stat): void {})
		;
	}

	protected static function getClass(): string
	{
		return Stat::class;
	}
}
