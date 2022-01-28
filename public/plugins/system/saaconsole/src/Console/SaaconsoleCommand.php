<?php
/**
 * @package     Joomla.Console
 * @subpackage  Saaconsole
 * 
 * Get cli options...
 * php cli/joomla.php list
 * 
 * 
 * php cli/joomla.php saaconsole:action hello
 *
 * @copyright   Copyright (C) 2005 - 2021 Clifford E Ford. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\System\Saaconsole\Console;

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use JLoader;
#use Saa_helper\Saa_helper;
use Joomla\CMS\Saa_helper\Saa_helper;
class SaaconsoleCommand extends AbstractCommand
{
	
	
	/**
	 * The default command name
	 *
	 * @var    string
	 *
	 * @since  4.0.0
	 */
	protected static $defaultName = 'saaconsole:action';

	/**
	 * @var InputInterface
	 * @since version
	 */
	private $cliInput;

	/**
	 * SymfonyStyle Object
	 * @var SymfonyStyle
	 * @since 4.0.0
	 */
	private $ioStyle;

	/**
	 * Instantiate the command.
	 *
	 * @since   4.0.0
	 */
	public function __construct()
	{
		parent::__construct();
		
	}

	/**
	 * Configures the IO
	 *
	 * @param   InputInterface   $input   Console Input
	 * @param   OutputInterface  $output  Console Output
	 *
	 * @return void
	 *
	 * @since 4.0.0
	 *
	 */
	private function configureIO(InputInterface $input, OutputInterface $output)
	{
		$this->cliInput = $input;
		$this->ioStyle = new SymfonyStyle($input, $output);
	}

	/**
	 * Initialise the command.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	protected function configure(): void
	{
		$this->addArgument('action',
				InputArgument::REQUIRED,
				'name of action');

		$help = "<info>%command.name%</info> Does cli tasks related to StreetArtAberdeen
			\nUsage: <info>php %command.full_name% action
			\nwhere action is what you want to do, like makeimages</info>";

		$this->setDescription('Called by cron to do cli tasks related to StreetArtAberdeen.');
		$this->setHelp($help);

	}

	/**
	 * Internal function to execute the command.
	 *
	 * @param   InputInterface   $input   The input to inject into the command.
	 * @param   OutputInterface  $output  The output to inject into the command.
	 *
	 * @return  integer  The command exit code
	 *
	 * @since   4.0.0
	 */
	protected function doExecute(InputInterface $input, OutputInterface $output): int
	{
		$this->configureIO($input, $output);

		$action = $this->cliInput->getArgument('action');

		$symfonyStyle = new SymfonyStyle($input, $output);

		$symfonyStyle->title('StreetArtAberdeen CLI');

		$symfonyStyle->text('action: ' . $action);

		# get all the image filenames
		$db = Factory::getDbo();
		$query = $db
			->getQuery(true)
			->select('value')
			->from($db->quoteName('#__fields_values'))
			->where($db->quoteName('field_id') . " = 6");
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$images = $db->loadColumn();


        JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
        $test = Saa_helper::tester("galopin");
        $symfonyStyle->text('saa_helper test: ' . $test);


		foreach ($images AS $image) {
			$symfonyStyle->text('image: ' . $image);
			# run the helper functions against them
			#$test = Saa_helper::tester("galopin");
			#$symfonyStyle->text('test: ' . $test);



			$clear_out_image_out = Saa_helper::clear_out_image($image);
			$symfonyStyle->text('clear_out_image_out: ' . $clear_out_image_out);
			
			$check_image_out = Saa_helper::check_image($image);
			$symfonyStyle->text('check_image_out: ' . $check_image_out);


			
		}


		
		


		$symfonyStyle->success('StreetArtAberdeen CLI');

		return 0;
	}

}
