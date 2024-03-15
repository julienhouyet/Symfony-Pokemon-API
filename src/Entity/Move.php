<?php

namespace App\Entity;

use App\Entity\PokemonMove;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MoveRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MoveRepository::class)]
#[ApiResource(
	shortName: 'Move',
	description: 'Different moves of Pokemon',
	operations: [
		new Get(security: 'is_granted("PUBLIC_ACCESS")'),
		new GetCollection(security: 'is_granted("PUBLIC_ACCESS")'),
		new Post(),
		new Put(),
		new Patch(),
		new Delete()
	],
	normalizationContext: ['groups' => ['move:read']],
	denormalizationContext: ['groups' => ['move:write']],
	security: 'is_granted("ROLE_ADMIN")'
)]
#[ApiFilter(SearchFilter::class, properties: [
	'name' => 'partial'
])]
class Move
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Groups(['move:read', 'move:write', 'pokemon:read'])]
	private ?string $name = null;

	#[ORM\Column(nullable: true)]
	#[Groups(['move:read', 'move:write', 'pokemon:read'])]
	private ?int $power = null;

	#[ORM\Column(nullable: true)]
	#[Groups(['move:read', 'move:write', 'pokemon:read'])]
	private ?int $pp = null;

	#[ORM\Column(nullable: true)]
	#[Groups(['move:read', 'move:write', 'pokemon:read'])]
	private ?int $accuracy = null;

	#[ORM\ManyToOne(inversedBy: 'moves')]
	#[Groups(['move:read', 'move:write', 'pokemon:read'])]
	private ?Type $Type = null;

	#[ORM\OneToMany(mappedBy: 'Move', targetEntity: PokemonMove::class)]
	private Collection $pokemonMoves;

	public function __construct()
	{
		$this->pokemonMoves = new ArrayCollection();
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

	public function getPower(): ?int
	{
		return $this->power;
	}

	public function setPower(?int $power): static
	{
		$this->power = $power;

		return $this;
	}

	public function getPp(): ?int
	{
		return $this->pp;
	}

	public function setPp(?int $pp): static
	{
		$this->pp = $pp;

		return $this;
	}

	public function getAccuracy(): ?int
	{
		return $this->accuracy;
	}

	public function setAccuracy(?int $accuracy): static
	{
		$this->accuracy = $accuracy;

		return $this;
	}

	public function getType(): ?Type
	{
		return $this->Type;
	}

	public function setType(?Type $Type): static
	{
		$this->Type = $Type;

		return $this;
	}

	/**
	 * @return Collection<int, PokemonMove>
	 */
	public function getPokemonMoves(): Collection
	{
		return $this->pokemonMoves;
	}

	public function addPokemonMove(PokemonMove $pokemonMove): static
	{
		if (!$this->pokemonMoves->contains($pokemonMove)) {
			$this->pokemonMoves->add($pokemonMove);
			$pokemonMove->setMove($this);
		}

		return $this;
	}

	public function removePokemonMove(PokemonMove $pokemonMove): static
	{
		if ($this->pokemonMoves->removeElement($pokemonMove)) {
			// set the owning side to null (unless already changed)
			if ($pokemonMove->getMove() === $this) {
				$pokemonMove->setMove(null);
			}
		}

		return $this;
	}
}
