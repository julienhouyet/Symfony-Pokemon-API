<?php

namespace App\Entity;

use App\Entity\PokemonStat;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StatRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StatRepository::class)]
#[ApiResource(
	shortName: 'Stat',
	description: 'Different stats of Pokemon',
	operations: [
		new Get(security: 'is_granted("PUBLIC_ACCESS")'),
		new GetCollection(security: 'is_granted("PUBLIC_ACCESS")'),
		new Post(),
		new Put(),
		new Patch(),
		new Delete()
	],
	normalizationContext: ['groups' => ['stat:read']],
	denormalizationContext: ['groups' => ['stat:write']],
	security: 'is_granted("ROLE_ADMIN")'
)]
#[ApiFilter(SearchFilter::class, properties: [
	'name' => 'partial'
])]
class Stat
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	#[Groups(['stat:read', 'stat:write', 'pokemon:read'])]
	private ?string $name = null;

	#[ORM\OneToMany(mappedBy: 'stat', targetEntity: PokemonStat::class)]
	private Collection $pokemonStats;

	public function __construct()
	{
		$this->pokemonStats = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): static
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Collection<int, PokemonStat>
	 */
	public function getPokemonStats(): Collection
	{
		return $this->pokemonStats;
	}

	public function addPokemonStat(PokemonStat $pokemonStat): static
	{
		if (!$this->pokemonStats->contains($pokemonStat)) {
			$this->pokemonStats->add($pokemonStat);
			$pokemonStat->setStat($this);
		}

		return $this;
	}

	public function removePokemonStat(PokemonStat $pokemonStat): static
	{
		if ($this->pokemonStats->removeElement($pokemonStat)) {
			// set the owning side to null (unless already changed)
			if ($pokemonStat->getStat() === $this) {
				$pokemonStat->setStat(null);
			}
		}

		return $this;
	}
}
