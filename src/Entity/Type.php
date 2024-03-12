<?php

namespace App\Entity;

use App\Entity\Pokemon;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource(
	shortName: 'Type',
	description: 'Different types of Pokemon',
	operations: [
		new Get(security: 'is_granted("PUBLIC_ACCESS")'),
		new GetCollection(security: 'is_granted("PUBLIC_ACCESS")'),
		new Post(),
		new Put(),
		new Patch(),
		new Delete()
	],
	normalizationContext: ['groups' => ['type:read']],
	denormalizationContext: ['groups' => ['type:write']],
	security: 'is_granted("ROLE_ADMIN")'
)]
#[ApiFilter(SearchFilter::class, properties: [
	'name' => 'partial'
])]
class Type
{

	/**
	 * The ID of the type
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * The Name of the type
	 */
	#[ORM\Column(length: 255)]
	#[Groups(['type:read', 'type:write', 'pokemon:read'])]
	#[Assert\NotBlank]
	private ?string $name = null;

	#[ORM\OneToMany(mappedBy: 'type', targetEntity: PokemonType::class)]
	private Collection $pokemonTypes;

	#[ORM\OneToMany(mappedBy: 'Type', targetEntity: Move::class)]
	private Collection $moves;

	public function __construct()
	{
		$this->pokemonTypes = new ArrayCollection();
		$this->moves = new ArrayCollection();
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
	 * @return Collection<int, PokemonType>
	 */
	public function getPokemonTypes(): Collection
	{
		return $this->pokemonTypes;
	}

	public function addPokemonType(PokemonType $pokemonType): static
	{
		if (!$this->pokemonTypes->contains($pokemonType)) {
			$this->pokemonTypes->add($pokemonType);
			$pokemonType->setType($this);
		}

		return $this;
	}

	public function removePokemonType(PokemonType $pokemonType): static
	{
		if ($this->pokemonTypes->removeElement($pokemonType)) {
			// set the owning side to null (unless already changed)
			if ($pokemonType->getType() === $this) {
				$pokemonType->setType(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Move>
	 */
	public function getMoves(): Collection
	{
		return $this->moves;
	}

	public function addMove(Move $move): static
	{
		if (!$this->moves->contains($move)) {
			$this->moves->add($move);
			$move->setType($this);
		}

		return $this;
	}

	public function removeMove(Move $move): static
	{
		if ($this->moves->removeElement($move)) {
			// set the owning side to null (unless already changed)
			if ($move->getType() === $this) {
				$move->setType(null);
			}
		}

		return $this;
	}
}
