<?php

namespace App\Factory;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Pokemon>
 *
 * @method        Pokemon|Proxy                     create(array|callable $attributes = [])
 * @method static Pokemon|Proxy                     createOne(array $attributes = [])
 * @method static Pokemon|Proxy                     find(object|array|mixed $criteria)
 * @method static Pokemon|Proxy                     findOrCreate(array $attributes)
 * @method static Pokemon|Proxy                     first(string $sortedField = 'id')
 * @method static Pokemon|Proxy                     last(string $sortedField = 'id')
 * @method static Pokemon|Proxy                     random(array $attributes = [])
 * @method static Pokemon|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PokemonRepository|RepositoryProxy repository()
 * @method static Pokemon[]|Proxy[]                 all()
 * @method static Pokemon[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Pokemon[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Pokemon[]|Proxy[]                 findBy(array $attributes)
 * @method static Pokemon[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Pokemon[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PokemonFactory extends ModelFactory
{
	private const POKEMON_NAMES = [
		'bulbasaur', 'ivysaur', 'venusaur', 'charmander', 'charmeleon', 'charizard',
		'squirtle', 'wartortle', 'blastoise', 'caterpie', 'metapod', 'butterfree',
		'weedle', 'kakuna', 'beedrill', 'pidgey', 'pidgeotto', 'pidgeot', 'rattata',
		'raticate', 'spearow', 'fearow', 'ekans', 'arbok', 'pikachu', 'raichu',
		'sandshrew', 'sandslash', 'nidoran♀', 'nidorina', 'nidoqueen', 'nidoran♂',
		'nidorino', 'nidoking', 'clefairy', 'clefable', 'vulpix', 'ninetales',
		'jigglypuff', 'wigglytuff', 'zubat', 'golbat', 'oddish', 'gloom', 'vileplume',
		'paras', 'parasect', 'venonat', 'venomoth', 'diglett', 'dugtrio', 'meowth',
		'persian', 'psyduck', 'golduck', 'mankey', 'primeape', 'growlithe', 'arcanine',
		'poliwag', 'poliwhirl', 'poliwrath', 'abra', 'kadabra', 'alakazam', 'machop',
		'machoke', 'machamp', 'bellsprout', 'weepinbell', 'victreebel', 'tentacool',
		'tentacruel', 'geodude', 'graveler', 'golem', 'ponyta', 'rapidash', 'slowpoke',
		'slowbro', 'magnemite', 'magneton', 'farfetch\'d', 'doduo', 'dodrio', 'seel',
		'dewgong', 'grimer', 'muk', 'shellder', 'cloyster', 'gastly', 'haunter', 'gengar',
		'onix', 'drowzee', 'hypno', 'krabby', 'kingler', 'voltorb', 'electrode', 'exeggcute',
		'exeggutor', 'cubone', 'marowak', 'hitmonlee', 'hitmonchan', 'lickitung', 'koffing',
		'weezing', 'rhyhorn', 'rhydon', 'chansey', 'tangela', 'kangaskhan', 'horsea',
		'seadra', 'goldeen', 'seaking', 'staryu', 'starmie', 'mr. mime', 'scyther', 'jynx',
		'electabuzz', 'magmar', 'pinsir', 'tauros', 'magikarp', 'gyarados', 'lapras', 'ditto',
		'eevee', 'vaporeon', 'jolteon', 'flareon', 'porygon', 'omanyte', 'omastar', 'kabuto',
		'kabutops', 'aerodactyl', 'snorlax', 'articuno', 'zapdos', 'moltres', 'dratini',
		'dragonair', 'dragonite', 'mewtwo', 'mew'
	];


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
		static $pokedexNumber = 1;

		$uniquePokemons = array_slice(array_unique(self::POKEMON_NAMES), 0, count(array_unique(self::POKEMON_NAMES)));

		$pokedexNumberValue = $pokedexNumber++;

		return [
			'baseExperience' => self::faker()->randomNumber(3, false),
			'height' => self::faker()->randomFloat(3, 0, 999.99),
			'name' => self::faker()->randomElement($uniquePokemons),
			'weight' => self::faker()->randomFloat(3, 0, 999.99),
			'pokedexNumber' => $pokedexNumberValue,
		];
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
	 */
	protected function initialize(): self
	{
		return $this
			// ->afterInstantiate(function(Pokemon $pokemon): void {})
		;
	}

	protected static function getClass(): string
	{
		return Pokemon::class;
	}
}
