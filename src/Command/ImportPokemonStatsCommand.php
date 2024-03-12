<?php

namespace App\Command;

use App\Entity\Pokemon;
use App\Entity\PokemonStat;
use App\Entity\Stat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'import:pokemon-stats',
	description: 'Add a short description for your command',
)]
class ImportPokemonStatsCommand extends Command
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct();
		$this->entityManager = $entityManager;
	}

	protected function configure(): void
	{
		$this
			->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
			->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title('Starting pokemon stats import from CSV');

		$csvFilePath = 'assets/csv/pokemon_stats.csv';
		$fileHandle = fopen($csvFilePath, 'r');
		if (!$fileHandle) {
			$io->error('The file could not be opened.');
			return Command::FAILURE;
		}

		$io->note('Importing...');

		fgetcsv($fileHandle);

		while (($data = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
			$pokemonType = new PokemonStat();

			$pokemon = $this->entityManager->getRepository(Pokemon::class)->find($data[0]);
			if (!$pokemon) {
				$io->warning('Pokemon not found for id: ' . $data[0]);
				continue;
			}

			$stat = $this->entityManager->getRepository(Stat::class)->find($data[1]);
			if (!$stat) {
				$io->warning('Type not found for id: ' . $data[1]);
				continue;
			}

			$pokemonType->setPokemon($pokemon);
			$pokemonType->setStat($stat);
			$pokemonType->setBaseStat($data[2]);
			$pokemonType->setEffort($data[3]);

			$this->entityManager->persist($pokemonType);
		}

		$this->entityManager->flush();

		$io->success('Import complete!');

		fclose($fileHandle);

		return Command::SUCCESS;
	}
}
