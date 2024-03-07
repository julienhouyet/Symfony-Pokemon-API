<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
class ApiToken
{
	private const PERSONAL_ACCESS_TOKEN_PREFIX = 'pcp_';

	public const SCOPE_USER_EDIT = 'ROLE_USER_EDIT';
	// public const SCOPE_POKEMON_CREATE = 'ROLE_POKEMON_CREATE';
	// public const SCOPE_POKEMON_EDIT = 'ROLE_POKEMON_EDIT';
	// public const SCOPES = [
	// 	self::SCOPE_USER_EDIT => 'Edit User',
	// 	self::SCOPE_POKEMON_CREATE => 'Create Pokemon',
	// 	self::SCOPE_POKEMON_EDIT => 'Edit Pokemon',
	// ];

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'apiTokens')]
	#[ORM\JoinColumn(nullable: false)]
	private ?User $ownedBy = null;

	#[ORM\Column(nullable: true)]
	private ?\DateTimeImmutable $expiresAt = null;

	#[ORM\Column(length: 68)]
	private ?string $token = null;

	#[ORM\Column]
	private array $scopes = [];

	public function __construct(string $tokenType = self::PERSONAL_ACCESS_TOKEN_PREFIX)
	{
		$this->token = $tokenType . bin2hex(random_bytes(32));
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getOwnedBy(): ?User
	{
		return $this->ownedBy;
	}

	public function setOwnedBy(?User $ownedBy): static
	{
		$this->ownedBy = $ownedBy;

		return $this;
	}

	public function getExpiresAt(): ?\DateTimeImmutable
	{
		return $this->expiresAt;
	}

	public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
	{
		$this->expiresAt = $expiresAt;

		return $this;
	}

	public function getToken(): ?string
	{
		return $this->token;
	}

	public function setToken(string $token): static
	{
		$this->token = $token;

		return $this;
	}

	public function getScopes(): array
	{
		return $this->scopes;
	}

	public function setScopes(array $scopes): static
	{
		$this->scopes = $scopes;

		return $this;
	}

	public function isValid(): bool
	{
		return $this->expiresAt === null || $this->expiresAt > new \DateTimeImmutable();
	}
}
