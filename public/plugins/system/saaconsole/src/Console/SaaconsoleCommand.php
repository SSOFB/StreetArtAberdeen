<?php
/**
 * @package     Joomla.Console
 * @subpackage  Saaconsole
 * 
 * Get cli options...
 * php cli/joomla.php list
 * 
 * php cli/joomla.php saaconsole:action hello
 * php cli/joomla.php saaconsole:action clear_out
 * php /var/www/html/streetartaberdeen/cli/joomla.php  saaconsole:action hello
 * run every hour with a cron like... 
 * 0 * * * * php /var/www/html/streetartaberdeen/cli/joomla.php  saaconsole:action hello
 *
 * @copyright   Copyright (C) 2005 - 2021 Clifford E Ford. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\System\Saaconsole\Console;

\defined('JPATH_PLATFORM') or die;

# make sure we run it from the right place, eg /var/www/html/StreetArtAberdeen/public or /var/www/html/streetartaberdeen/
chdir("/var/www/html/streetartaberdeen/");

use Joomla\CMS\Factory;
use Joomla\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Joomla\CMS\Date\Date;
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
	 * Application object.
	 *
	 * @var    \Joomla\CMS\Application\CMSApplication
	 * @since  3.8.0
	 */
	protected $app;

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


		if ($action == "geo") {
			self::do_geo($symfonyStyle);

		} elseif ( $action == "geozones" ) {
			self::do_geozones($symfonyStyle);

		} elseif ( $action == "images") {
			self::do_images($symfonyStyle, );

		} elseif ( $action == "images_clear_out") {
			self::do_images($symfonyStyle, $action);

		} elseif ( $action == "social") {
			self::do_social($symfonyStyle);

		} elseif ( $action == "generate_json") {
			self::do_generate_json($symfonyStyle);

		} else {
			$symfonyStyle->text('No recognised action');

		}

		$symfonyStyle->success('StreetArtAberdeen CLI');

		return 0;
	}


	/**
	* do_images
	* 
	* Sets titles based on Geo lookup
	* php /var/www/html/streetartaberdeen/cli/joomla.php  saaconsole:action images
	*
	* @param 	symfonyStyle 	A symfonyStyle object for IO#
	* @param	string			A sting with options from the action value
	*
	* @return 	void			no return value
	*/
	public function do_images($symfonyStyle, $action=""){
		# get all the image filenames
		$db = Factory::getDbo();
		$query = $db
			->getQuery(true)
			->select('value')
			->from($db->quoteName('#__fields_values'))
			->from($db->quoteName('#__content'))
			->where($db->quoteName('item_id') . " = " . $db->quoteName('id'))
			->where($db->quoteName('state') . " = 1")
			->where($db->quoteName('field_id') . " = 6");
		$db->setQuery($query);
		$images = $db->loadColumn();

        JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
        $test = Saa_helper::tester("galopin");
        $symfonyStyle->text('saa_helper test: ' . $test);

		foreach ($images AS $image) {
			$symfonyStyle->text('image: ' . $image);
			# run the helper functions against them

			if ( $action == "images_clear_out") {
				$clear_out_image_out = Saa_helper::clear_out_image($image);
				$symfonyStyle->text('clear_out_image_out: ' . $clear_out_image_out);
			}
			
			$check_image_out = Saa_helper::check_image($image);
			$symfonyStyle->text('check_image_out: ' . $check_image_out);
		}
		return;		
	}


	/**
	* do_geo
	* 
	* Sets titles based on Geo lookup
	* php /var/www/html/streetartaberdeen/cli/joomla.php  saaconsole:action geo
	*
	* @param 	symfonyStyle 	A symfonyStyle object for IO
	*
	* @return 	void			no return value
	*/
	public function do_geo($symfonyStyle){
		# reverse geo lookup
		#$map_api_key = $this->params->get('map_api_key');
		#$map_default_lat_lon = $this->params->get('map_default_lat_lon'); 
		$map_api_key = "AIzaSyDmXMhPB4QnspmKY49FP3YnlhRp7_ao1CA";
		$map_default_lat_lon = "57.145428390778264,-2.0937312622943405"; 

		# get all art
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( array('value', 'id', 'title', 'alias') );
		$query->from($db->quoteName('#__fields_values'));
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('field_id') . " = 2");
		$query->where($db->quoteName('item_id') . " = " . $db->quoteName('id'));
		$query->where($db->quoteName('state') . " = 1");
		$db->setQuery($query);
		$articles = $db->loadAssocList();

		foreach( $articles AS $article ) {
			$symfonyStyle->text('article: ' . $article['title'] . ", lat/lon: " . $article['value'] );

			# check if it has an automated 3 letter title
			if ( $article['value'] == $map_default_lat_lon ) {
				$symfonyStyle->text("lat lon equals default, so it's in the harbour, the location has not been set");
				
			} elseif ( strlen($article['title']) == 3 ) {

				# call the google api with the lat and lon
				$reverse_lookup_json = file_get_contents( "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $article['value'] . "&key=" . $map_api_key);
				$reverse_lookup = json_decode($reverse_lookup_json);
				$symfonyStyle->text("result count: " . count( $reverse_lookup->results ) );

				if ( count( $reverse_lookup->results ) ) {

					#$symfonyStyle->text("reverse_lookup_json: " . $reverse_lookup_json );
					#$symfonyStyle->text("reverse_lookup: " . print_r($reverse_lookup, TRUE) );

					# make up the title from the output
					$address = $reverse_lookup->results[0]->formatted_address;
					$title = "Near " . $address;
					$symfonyStyle->text("title: " .  $title);

					# save it to the database
					$db = Factory::getDbo();
					$query = $db->getQuery(true);
					$fields = array( $db->quoteName('title') . ' = ' . $db->quote($title) );
					$conditions = array( $db->quoteName('id') . ' = ' . $db->quote($article['id']) );
					$query->update($db->quoteName('#__content'))->set($fields)->where($conditions);
					$db->setQuery($query);
					$result = $db->execute();
				}
			} else {
				$symfonyStyle->text('title is good already');

			}
		}

		return;
	}


	/**
	* do_geozones
	* 
	* Tags items based on lat/lon
	* php /var/www/html/streetartaberdeen/cli/joomla.php  saaconsole:action geozones
	*
	* @param 	symfonyStyle 	A symfonyStyle object for IO
	*
	* @return 	void			no return value
	*/
	public function do_geozones($symfonyStyle){

		$symfonyStyle->text("Running do_geozones");

		# get all art
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( array('value', 'id', 'title', 'alias', 'introtext', 'created_by', 'created', 'modified_by', 'modified', 'hits', 'publish_up', 'asset_id', 'metakey', 'catid', 'ordering') );
		$query->from($db->quoteName('#__fields_values'));
		$query->from($db->quoteName('#__content'));
		#$query->from($db->quoteName('#__ucm_base'));
		$query->where($db->quoteName('field_id') . " = 2");
		$query->where($db->quoteName('item_id') . " = " . $db->quoteName('id'));
		#$query->where($db->quoteName('id') . " = " . $db->quoteName('ucm_item_id'));
		$query->where($db->quoteName('state') . " = 1");
		$db->setQuery($query);
		$articles = $db->loadAssocList();

		# geo zone boxes
		$boxes = Array();
		# Sunnybank - https://streetartaberdeen.org/labels/sunnybank
		$boxes[] = Array(
			"alias" => "sunnybank",
			"id" => 38,
			"nw-lat" => 57.162238402474514,
			"nw-lon" => -2.105860783020903,
			"se-lat" => 57.1607824633046,
			"se-lon" => -2.1018278970248505,
		);
		# Old farm, Bridge of Don - https://streetartaberdeen.org/labels/old-farm-bridge-of-don
		$boxes[] = Array(
			"alias" => "old-farm-bridge-of-don",
			"id" => 142,
			"nw-lat" => 57.181638985160184,
			"nw-lon" => -2.1509519008258593,
			"se-lat" => 57.18085688785864,
			"se-lon" => -2.149042168007256,
		);
		# Denburn - https://streetartaberdeen.org/labels/denburn
		$boxes[] = Array(
			"alias" => "denburn",
			"id" => 143,
			"nw-lat" => 57.149386955351865,
			"nw-lon" => -2.108631757374595,
			"se-lat" => 57.14786793998906,
			"se-lon" => -2.1051985298355325,
		);
		# The Green - https://streetartaberdeen.org/labels/the-green
		$boxes[] = Array(
			"alias" => "the-green",
			"id" => 144,
			"nw-lat" => 57.14650705619446,
			"nw-lon" => -2.1009080951601966,
			"se-lat" => 57.14525566713976,
			"se-lon" => -2.0961230342776282,
		);
		# Greyhope Bay - https://streetartaberdeen.org/labels/greyhope-bay
		$boxes[] = Array(
			"alias" => "greyhope-bay",
			"id" => 145,
			"nw-lat" => 57.14324720338828,
			"nw-lon" => -2.062304963597734,
			"se-lat" => 57.1410235800715,
			"se-lon" => -2.0532283682913377,
		);
		# Clifton road shops - https://streetartaberdeen.org/labels/clifton-road-shops
		$boxes[] = Array(
			"alias" => "clifton-road-shops",
			"id" => 146,
			"nw-lat" => 57.16254676159954,
			"nw-lon" => -2.118445791802779,
			"se-lat" => 57.161412274670504,
			"se-lon" => -2.1161766429761797,
		);
		# Fittie - https://streetartaberdeen.org/labels/fittie
		$boxes[] = Array(
			"alias" => "fittie",
			"id" => 147,
			"nw-lat" => 57.14460932715513,
			"nw-lon" => -2.0751346330344655,
			"se-lat" => 57.1418735373049,
			"se-lon" => -2.06803214356303,
		);
		# Mounthooly - https://streetartaberdeen.org/labels/mounthooly
		$boxes[] = Array(
			"alias" => "mounthooly",
			"id" => 46,
			"nw-lat" => 57.15451059610315,
			"nw-lon" => -2.1028246666250694,
			"se-lat" => 57.1534922471564,
			"se-lon" => -2.09905884516816,
		);
		# Westburn park - https://streetartaberdeen.org/labels/westburn-park 
		$boxes[] = Array(
			"alias" => "westburn-park",
			"id" => 148,
			"nw-lat" => 57.15597902760119,
			"nw-lon" => -2.126349941599597,
			"se-lat" => 57.15279413390648,
			"se-lon" => -2.119023860313798,
		);
		# Garthdee  - https://streetartaberdeen.org/labels/garthdee 
		$boxes[] = Array(
			"alias" => "garthdee",
			"id" => 149,
			"nw-lat" => 57.125811699337014,
			"nw-lon" => -2.128185772482789,
			"se-lat" => 57.122666837531206,
			"se-lon" => -2.1181864972752695,
		);
		# Beach tunnel north - https://streetartaberdeen.org/labels/beach-tunnel-north
		$boxes[] = Array(
			"alias" => "beach-tunnel-north",
			"id" => 150,
			"nw-lat" => 57.166122285920885,
			"nw-lon" => -2.0800261473716053,
			"se-lat" => 57.165203152174676,
			"se-lon" => -2.0784007287085804,
		);
		# Former Hydrasun - https://streetartaberdeen.org/labels/former-hydrasun 
		$boxes[] = Array(
			"alias" => "former-hydrasun",
			"id" => 151,
			"nw-lat" => 57.15860591212126,
			"nw-lon" => -2.0948386680909836,
			"se-lat" => 57.1579076960361,
			"se-lon" => -2.092827011329814,
		);
		# Transition Extreme - https://streetartaberdeen.org/labels/transition-extreme
		$boxes[] = Array(
			"alias" => "transition-extreme",
			"id" => 152,
			"nw-lat" => 57.15517374055,
			"nw-lon" => -2.0835623259024594,
			"se-lat" => 57.153707335475175,
			"se-lon" => -2.0804616922812436,
		);
		# Carnegie's Brae - https://streetartaberdeen.org/labels/carnegies-brae
		$boxes[] = Array(
			"alias" => "carnegies-brae",
			"id" => 153,
			"nw-lat" => 57.14753645872849,
			"nw-lon" => -2.0983872089352507,
			"se-lat" => 57.146678487587735,
			"se-lon" => -2.096681324001779,
		);

		/*
		# Name - https://streetartaberdeen.org/labels/
		$boxes[] = Array(
			"alias" => "tbc",
			"id" => 111,
			"nw-lat" => 1111,
			"nw-lon" => 1111,
			"se-lat" => 1111,
			"se-lon" => 1111,
		);
		*/
		$symfonyStyle->text("Processing " . count($articles) . " items");

		# loop through artworks
		foreach( $articles AS $article ) {
			#$symfonyStyle->text(' ');
			$symfonyStyle->text("\n\n Art: " . $article['title'] . " (id: " . $article['id'] . ", alias: " . $article['alias'] . "), lat/lon: " . $article['value'] );
			list($art_lat, $art_lon) = explode(",", $article['value']);

			# loop through boxes
			foreach ( $boxes AS $box ) {
				$symfonyStyle->text("Box: \n" . print_r($box, TRUE) );

				# see if it is in this box
				if ( $art_lat < $box["nw-lat"] AND $art_lat > $box["se-lat"] AND $art_lon > $box["nw-lon"] AND $art_lon < $box["se-lon"] ) {
					$symfonyStyle->text("Item " . $article['id'] . " is in the " . $box["alias"] . " box");

					# check them item doesn't have this tag already
					$query = $db->getQuery(true);
					$query->select(array('COUNT(*)'));
					$query->where($db->quoteName('type_alias') . " = " . $db->quote('com_content.article'));
					$query->where($db->quoteName('content_item_id') . " = " . $db->quote($article['id']));
					$query->where($db->quoteName('tag_id') . " = " . $db->quote($box["id"]));
					$query->from($db->quoteName('#__contentitem_tag_map'));
					$db->setQuery($query);
					$count = $db->loadResult();
					$symfonyStyle->text("matching tag count: " . $count . " from " . $query);

					$dt_obj = new Date('now');
					$sql_datetime = $dt_obj->toSQL();

					if ($count == 0) {
						# insert a tag
						$symfonyStyle->text("Not got this tag, so add it");

						# check if this item has a record in the ucm_base table
						$query = $db->getQuery(true);
						$query->select(array('COUNT(*)'));
						$query->where($db->quoteName('core_content_item_id') . " = " . $db->quote($article['id']));
						$query->from($db->quoteName('#__ucm_content'));
						$db->setQuery($query);
						$count = $db->loadResult();

						# if not, add it
						if ($count == 0) {
							$symfonyStyle->text("Not in ucm_content, so add it");

							# insert row into ucm_content
							$query = $db->getQuery(true);
							$columns = array('core_type_alias', 'core_title', 'core_alias', 'core_body', 'core_state', 'core_access', 'core_params', 'core_featured', 'core_metadata', 'core_created_user_id', 'core_created_by_alias', 'core_created_time', 'core_modified_user_id', 'core_modified_time', 'core_language', 'core_publish_up', 'core_content_item_id', 'asset_id', 'core_images', 'core_urls', 'core_hits', 'core_version', 'core_ordering', 'core_metakey', 'core_metadesc', 'core_catid', 'core_type_id');
							$symfonyStyle->text("columns (" . count($columns) . "): " . print_r($columns, TRUE));
							$values = array(
								$db->quote('com_content.article'),		# `core_type_alias`, eg 'com_content.article'
								$db->quote($article['title']),			# `core_title`, eg 'Near 21 Spa St, Aberdeen AB25 1PU, UK'
								$db->quote($article['alias']),			# `core_alias`, eg '740-2'
								$db->quote($article['introtext']),		# `core_body`, eg '<p>Thanks to....'
								$db->quote(1),							# `core_state`, eg 1
								$db->quote(1),							# `core_access`, eg 1
								$db->quote('{}'),						# `core_params`, eg '{}'
								$db->quote(0),							# `core_featured`, eg 0
								$db->quote('{}'),						# `core_metadata`, eg '{}'
								$db->quote($article['created_by']),		# `core_created_user_id`, eg 873
								$db->quote(''),							# `core_created_by_alias`, eg ''
								$db->quote($article['created']),		# `core_created_time`, eg '2022-03-24 16:32:26'
								$db->quote($article['modified_by']),	# `core_modified_user_id`, eg 873
								$db->quote($article['modified']),		# `core_modified_time`, eg '2022-10-04 10:49:33'
								$db->quote('*'),						# `core_language`, eg '*'
								$db->quote($article['publish_up']),		# `core_publish_up`, eg '2022-03-24 16:32:26'
								$db->quote($article['id']),				# `core_content_item_id`, eg 786
								$db->quote($article['asset_id']),		# `asset_id`, eg 2287
								$db->quote('{}'),						# `core_images`, eg '{}'
								$db->quote('{}'),						# `core_urls`, eg '{}'
								$db->quote($article['hits']),			# `core_hits`, eg 184
								$db->quote(1),							# `core_version`, eg 1
								$db->quote($article['ordering']),		# `core_ordering`, eg 3
								$db->quote($article['metakey']),		# `core_metakey`, eg 667
								$db->quote(''),							# `core_metadesc`, eg ''
								$db->quote($article['catid']),			# `core_catid`, eg 9
								$db->quote(1),							# `core_type_id`, eg 1						
							); 
							$symfonyStyle->text("values (" . count($values) . "): " . print_r($values, TRUE));
							$query->insert($db->quoteName('#__ucm_content'));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$symfonyStyle->text("Insert ucm_content query " . $query);
							$db->setQuery($query);
							$result = $db->execute();
							$symfonyStyle->text("Result for ucm_content query: " . $result);	

							# get inserted id
							$ucm_id = $db->insertid();
							$symfonyStyle->text("ucm_id from insert: " . $ucm_id);

							# insert row into ucm_content
							$query = $db->getQuery(true);
							$columns = array('ucm_id', 'ucm_item_id', 'ucm_type_id', 'ucm_language_id');
							$values = array($db->quote($ucm_id), $db->quote($article['id']), $db->quote(1), $db->quote(0)); 
							$query->insert($db->quoteName('#__ucm_base'));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$db->setQuery($query);
							$symfonyStyle->text("Insert ucm_base query " . $query);
							$result = $db->execute();
							$symfonyStyle->text("Result for ucm_base query: " . $result);						

						} else {
							$symfonyStyle->text("Already got ucm stuff");
						}

						# get the ucm_id
						$query = $db->getQuery(true);
						$query->select(array('ucm_id'));
						$query->where($db->quoteName('ucm_item_id') . " = " . $db->quote($article['id']));
						$query->from($db->quoteName('#__ucm_base'));
						$db->setQuery($query);
						$symfonyStyle->text("Find ucm_id from ucm_base query " . $query);
						$ucm_id = $db->loadResult();
						$symfonyStyle->text("ucm_id from query: " . $ucm_id);

						# Insert into contentitem_tag_map
						$query = $db->getQuery(true);
						$columns = array('type_alias', 'core_content_id', 'content_item_id', 'tag_id', 'tag_date', 'type_id');
						$values = array($db->quote('com_content.article'), $db->quote($ucm_id), $db->quote($article['id']), $db->quote($box['id']), $db->quote($sql_datetime), 1); 
						$query->insert($db->quoteName('#__contentitem_tag_map'));
						$query->columns($db->quoteName($columns));
						$query->values(implode(',', $values));
						$db->setQuery($query);
						$symfonyStyle->text("Insert query " . $query);
						$result = $db->execute();
						$symfonyStyle->text("Result: " . $result);
					} else {
						$symfonyStyle->text("Already got this tag");
					}
				} else {
					$symfonyStyle->text("Not in the box");
				}
			}
		}
		return;
	}



	/**
	* do_generate_json
	* 
	* Generates JSON for cached API output
	* php /var/www/html/streetartaberdeen/cli/joomla.php  saaconsole:action generate_json
	*
	* @return 	void			no return value
	*/
	public function do_generate_json($symfonyStyle){

		$symfonyStyle->text('Running do_generate_json');

		/*

		The de-facto JSON API standard: https://jsonapi.org/
    	Lorna Mitchell's book: http://shop.oreilly.com/product/0636920028291.do
    	Phil Sturgeon's book: https://apisyouwonthate.com/books/build-apis-you-wont-hate
		https://restfulapi.net/introduction-to-json/


		*/
        JLoader::register('Joomla\CMS\Saa_helper\Saa_helper', 'templates/street_art_aberdeen/html/saa_helper.php'); 
        $test = Saa_helper::tester("galopin");
		$data = Array();
		$tag_lookup = Array();

		$base_url = "https://streetartaberdeen.org";

		# get all the tags and make a look-up array
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( array('id', 'title') );
		$query->from($db->quoteName('#__tags'));
		$db->setQuery($query);
		$tags = $db->loadAssocList();
		foreach ($tags as $tag) {
			$tag_lookup[$tag['id']] = $tag['title'];
		}

		# get all the art
		$query = $db->getQuery(true);
		$query->select( array('id', 'title', 'alias', 'introtext', 'created', 'modified' ) );
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('state') . " = 1");
		$query->where($db->quoteName('catid') . " = 9");
		#$query->where($db->quoteName('id') . " = 110");
		$db->setQuery($query);
		$articles = $db->loadAssocList();

		foreach ($articles as $article) {
			$symfonyStyle->text('article: ' . $article['title'] . ", id: " . $article['id']);

			# create the data object
			$this_data = (object)[];

			# base content
			$this_data->id = $article['id'];
			$this_data->title = $article['title'];
			$this_data->description = $article['introtext'];
			$this_data->created = $article['created'];
			$this_data->modified = $article['modified'];

			# link
			$this_data->url = $base_url . "/gallery/" . $article['alias'];

			# photo field, 6
			$query = $db->getQuery(true);
			$query->select('value');
			$query->from($db->quoteName('#__fields_values'));
			$query->where($db->quoteName('item_id') . " = " . $db->quote( $article['id'] ));
			$query->where($db->quoteName('field_id') . " = 6");
			$db->setQuery($query);
			$image = $db->loadResult();
			$this_data->image = $base_url . "/" . $image;		
			$this_data->small_image = $base_url . Saa_helper::small_image($image);
			$this_data->large_image = $base_url . Saa_helper::large_image($image);

			# geo field, 2
			$query = $db->getQuery(true);
			$query->select('value');
			$query->from($db->quoteName('#__fields_values'));
			$query->where($db->quoteName('item_id') . " = " . $db->quote( $article['id'] ));
			$query->where($db->quoteName('field_id') . " = 2");
			$db->setQuery($query);
			$lat_lon = $db->loadResult();
			list($lat, $lon) = explode(",", $lat_lon);
			$this_data->lat_lon = $lat_lon;		
			$this_data->lat = $lat;
			$this_data->lon = $lon;

			# year created field, 3
			$query = $db->getQuery(true);
			$query->select('value');
			$query->from($db->quoteName('#__fields_values'));
			$query->where($db->quoteName('item_id') . " = " . $db->quote( $article['id'] ));
			$query->where($db->quoteName('field_id') . " = 3");
			$db->setQuery($query);
			$year_created = $db->loadResult();
			$this_data->year_created = $year_created;		

			# medium field, 1
			$query = $db->getQuery(true);
			$query->select('value');
			$query->from($db->quoteName('#__fields_values'));
			$query->where($db->quoteName('item_id') . " = " . $db->quote( $article['id'] ));
			$query->where($db->quoteName('field_id') . " = 1");
			$db->setQuery($query);
			$medium = $db->loadResult();
			$this_data->medium = $medium;

			# state field, 9
			$query = $db->getQuery(true);
			$query->select('value');
			$query->from($db->quoteName('#__fields_values'));
			$query->where($db->quoteName('item_id') . " = " . $db->quote( $article['id'] ));
			$query->where($db->quoteName('field_id') . " = 9");
			$db->setQuery($query);
			$state = $db->loadResult();
			$this_data->state = $state;

			# tags
			$query = $db->getQuery(true);
			$query->select( 'tag_id' );
			$query->from($db->quoteName('#__contentitem_tag_map'));
			$query->where($db->quoteName('core_content_id') . " = " . $db->quote( $article['id'] ));
			$query->where($db->quoteName('type_alias') . " = " . $db->quote('com_content.article'));
			$db->setQuery($query);
			$tag_ids = $db->loadColumn();
			#print_r($tag_ids);
			$tags = Array();
			foreach( $tag_ids AS $tag_id ) {
				$tags[] = $tag_lookup[ $tag_id ];
			} 
			$this_data->tags = $tags;

			$data[] = $this_data;
		}

		# build up the json
		$json = "{\n";
		$json .= "  \"success\": true, \n";
		$json .= "  \"message\": \"Thanks for downloading the Street Art Aberdeen data, " . count($data) . " items, data generated at " . date('l jS \of F Y h:i:s A') . "\", \n";
		$json .= "  \"data\":" . json_encode($data, JSON_PRETTY_PRINT) . "\n";
		$json .= "}\n";

		# write it to file
		file_put_contents(JPATH_ROOT . "/art.json", $json);


		return;
	}	


	/**
	* do_social
	* 
	* Posts an item to social media
	*
	* @param 	symfonyStyle 	A symfonyStyle object for IO
	*
	* @return 	void			no return value
	*/
	public function do_social($symfonyStyle){

		$symfonyStyle->text('Running do_social');

		/*
		TODO: all of it really

		Docs and notes
		https://developers.facebook.com/blog/post/2021/01/26/introducing-instagram-content-publishing-api/
		https://developers.facebook.com/docs/instagram-api/guides/content-publishing/#access-tokens
		https://developers.facebook.com/docs/instagram-api/guides/content-publishing/#single-media-posts
		https://developers.facebook.com/docs/instagram-api#instagram-graph-api
		https://developers.facebook.com/docs/graph-api/overview

		*/


		return;
	}
}
