<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PokemonMoveRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonMoveRepository::class)]
class PokemonMove
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'pokemonMoves')]
	#[Groups(['pokemon:read', 'pokemonMoves:read'])]
	private ?Pokemon $Pokemon = null;

	#[ORM\ManyToOne(inversedBy: 'pokemonMove')]
	#[Groups(['pokemon:read', 'pokemonMove:read'])]
	private ?Move $Move = null;

	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemonMove:read'])]
	private ?int $level = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getPokemon(): ?Pokemon
	{
		return $this->Pokemon;
	}

	public function setPokemon(?Pokemon $Pokemon): static
	{
		$this->Pokemon = $Pokemon;

		return $this;
	}

	public function getMove(): ?Move
	{
		return $this->Move;
	}

	public function setMove(?Move $Move): static
	{
		$this->Move = $Move;

		return $this;
	}

	public function getLevel(): ?int
	{
		return $this->level;
	}

	public function setLevel(int $level): static
	{
		$this->level = $level;

		return $this;
	}
}
