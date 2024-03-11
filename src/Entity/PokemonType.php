<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PokemonTypeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonTypeRepository::class)]
class PokemonType
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemonType:read'])]
	private ?int $slot = null;

	#[ORM\ManyToOne(inversedBy: 'pokemonTypes')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['pokemonType:read'])]
	private ?Pokemon $pokemon = null;

	#[ORM\ManyToOne(inversedBy: 'pokemonTypes')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['pokemon:read', 'pokemonType:read'])]
	private ?Type $type = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getSlot(): ?int
	{
		return $this->slot;
	}

	public function setSlot(int $slot): static
	{
		$this->slot = $slot;

		return $this;
	}

	public function getPokemon(): ?Pokemon
	{
		return $this->pokemon;
	}

	public function setPokemon(?Pokemon $pokemon): static
	{
		$this->pokemon = $pokemon;

		return $this;
	}

	public function getType(): ?Type
	{
		return $this->type;
	}

	public function setType(?Type $type): static
	{
		$this->type = $type;

		return $this;
	}
}
