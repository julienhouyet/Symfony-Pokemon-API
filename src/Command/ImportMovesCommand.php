<?php

namespace App\Command;

use App\Entity\Move;
use App\Entity\Stat;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'import:moves',
	description: 'Add a short description for your command',
)]
class ImportMovesCommand extends Command
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
		$io->title('Starting moves import from CSV');

		$csvFilePath = 'assets/csv/moves.csv';
		$fileHandle = fopen($csvFilePath, 'r');
		if (!$fileHandle) {
			$io->error('The file could not be opened.');
			return Command::FAILURE;
		}

		$io->note('Importing...');

		fgetcsv($fileHandle);

		while (($data = fgetcsv($fileHandle, 1000, ";")) !== FALSE) {
			$move = new Move();

			$type = $this->entityManager->getRepository(Type::class)->find($data[2]);
			if (!$type) {
				$io->warning('Type not found for id: ' . $data[2]);
				continue;
			}
			$move->setName($data[1]);
			$move->setType($type);

			$power = $data[3] === '' ? null : (int)$data[3];
			$pp = $data[4] === '' ? null : (int)$data[4];
			$accuracy = $data[5] === '' ? null : (int)$data[5];

			$move->setPower($power);
			$move->setPp($pp);
			$move->setAccuracy($accuracy);

			$this->entityManager->persist($move);
		}

		$this->entityManager->flush();

		$io->success('Import complete!');

		fclose($fileHandle);

		return Command::SUCCESS;
	}
}
