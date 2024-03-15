<?php

namespace App\Command;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'import:pokemons',
	description: 'Add a short description for your command',
)]
class ImportPokemonsCommand extends Command
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
		$io->title('Starting pokemon import from CSV');

		$csvFilePath = 'assets/csv/pokemons.csv';
		$fileHandle = fopen($csvFilePath, 'r');
		if (!$fileHandle) {
			$io->error('The file could not be opened.');
			return Command::FAILURE;
		}

		$io->note('Importing...');

		fgetcsv($fileHandle);

		while (($data = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
			$pokemon = new Pokemon();
			$pokemon->setPokedexNumber($data[1]);
			$pokemon->setName($data[2]);
			$pokemon->setHeight($data[3]);
			$pokemon->setWeight($data[4]);
			$pokemon->setBaseExperience($data[5]);

			$this->entityManager->persist($pokemon);
		}

		$this->entityManager->flush();

		$io->success('Import complete!');

		fclose($fileHandle);

		return Command::SUCCESS;
	}
}
