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
		$query->select( array('value', 'id', 'title', 'alias', 'ucm_id') );
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

		# Sunnybank 
		$boxes[] = Array(
			"alias" => "sunnybank",
			"id" => 38,
			"nw-lat" => 57.162238402474514,
			"nw-lon" => -2.105860783020903,
			"se-lat" => 57.1607824633046,
			"se-lon" => -2.1018278970248505,
		);
		# Old farm, Bridge of Don 
		$boxes[] = Array(
			"alias" => "old-farm-bridge-of-don",
			"id" => 142,
			"nw-lat" => 57.181638985160184,
			"nw-lon" => -2.1509519008258593,
			"se-lat" => 57.18085688785864,
			"se-lon" => -2.149042168007256,
		);
		# Denburn 
		# need to debug, only 9 items on https://streetartaberdeen.org/labels/denburn
		# https://streetartaberdeen.org/gallery/740-2 Near 21 Spa St, Aberdeen AB25 1PU, UK ID:786
		#
		#
		# INSERT INTO `s3ib7_content` (`id`, `asset_id`, `title`, `alias`, `introtext`, `fulltext`, `state`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `featured`, `language`, `note`) 
		# VALUES(786, 1054, 'Near 21 Spa St, Aberdeen AB25 1PU, UK', '740-2', '<p>Thanks to <a title=\"Danny Christie / wired.wifi\" href=\"https://www.instagram.com/wired.wifi/\" target=\"_blank\" rel=\"noopener\">Danny Christie / wired.wifi</a> for the photo</p>', '', 1, 9, '2022-03-24 16:32:26', 873, '', '2022-10-04 10:49:33', 873, 873, '2022-10-04 10:51:36', '2022-03-24 16:32:26', NULL, '{}', '{}', '{}', 3, 667, '', '', 1, 185, '{}', 0, '*', '');
		#
		#
		# INSERT INTO `s3ib7_ucm_content` (`core_content_id`, `core_type_alias`, `core_title`, `core_alias`, `core_body`, `core_state`, `core_checked_out_time`, `core_checked_out_user_id`, `core_access`, `core_params`, `core_featured`, `core_metadata`, `core_created_user_id`, `core_created_by_alias`, `core_created_time`, `core_modified_user_id`, `core_modified_time`, `core_language`, `core_publish_up`, `core_publish_down`, `core_content_item_id`, `asset_id`, `core_images`, `core_urls`, `core_hits`, `core_version`, `core_ordering`, `core_metakey`, `core_metadesc`, `core_catid`, `core_type_id`) 
		# VALUES(700, 'com_content.article', 'Near 21 Spa St, Aberdeen AB25 1PU, UK', '740-2', '<p>Thanks to <a title=\"Danny Christie / wired.wifi\" href=\"https://www.instagram.com/wired.wifi/\" target=\"_blank\" rel=\"noopener\">Danny Christie / wired.wifi</a> for the photo</p>', 1, NULL, NULL, 1, '{}', 0, '{}', 873, '', '2022-03-24 16:32:26', 873, '2022-10-04 10:49:33', '*', '2022-03-24 16:32:26', NULL, 786, 2287, '{}', '{}', 184, 3, 667, '', '', 9, 1);
		# 
		#
		# INSERT INTO `s3ib7_contentitem_tag_map` (`type_alias`, `core_content_id`, `content_item_id`, `tag_id`, `tag_date`, `type_id`) 
		# VALUES('com_content.article', 700, 786, 143, '2022-10-04 10:49:33', 1);
		#
		# INSERT INTO `s3ib7_ucm_base` (`ucm_id`, `ucm_item_id`, `ucm_type_id`, `ucm_language_id`) 
		# VALUES(700, 786, 1, 0);
		#
		# ucm_base.ucm_item_id = content.id
		#
		# https://joomla.stackexchange.com/questions/32448/unsure-if-all-content-items-need-a-row-in-the-ucm-base-table
		# 
		$boxes[] = Array(
			"alias" => "denburn",
			"id" => 143,
			"nw-lat" => 57.149386955351865,
			"nw-lon" => -2.108631757374595,
			"se-lat" => 57.14786793998906,
			"se-lon" => -2.1051985298355325,
		);
		# The Green 
		$boxes[] = Array(
			"alias" => "the-green",
			"id" => 144,
			"nw-lat" => 57.14650705619446,
			"nw-lon" => -2.1009080951601966,
			"se-lat" => 57.14525566713976,
			"se-lon" => -2.0961230342776282,
		);
		# Greyhope Bay 
		$boxes[] = Array(
			"alias" => "greyhope-bay",
			"id" => 145,
			"nw-lat" => 57.14324720338828,
			"nw-lon" => -2.062304963597734,
			"se-lat" => 57.1410235800715,
			"se-lon" => -2.0532283682913377,
		);
		# Clifton road shops 
		$boxes[] = Array(
			"alias" => "clifton-road-shops",
			"id" => 146,
			"nw-lat" => 57.16254676159954,
			"nw-lon" => -2.118445791802779,
			"se-lat" => 57.161412274670504,
			"se-lon" => -2.1161766429761797,
		);
		# Fittie 
		$boxes[] = Array(
			"alias" => "fittie",
			"id" => 147,
			"nw-lat" => 57.14460932715513,
			"nw-lon" => -2.0751346330344655,
			"se-lat" => 57.1418735373049,
			"se-lon" => -2.06803214356303,
		);



		/*
		# name 
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

					# Test 
					# https://streetartaberdeen.org/gallery/354
					# Article: Near 1 Sunnyside Terrace, Aberdeen AB24 3NB, UK (id: 1193, alias: 354), lat/lon: 57.161112437766306,-2.1045888443866256

					if ($count == 0) {
						# insert a tag
						$symfonyStyle->text("Not got this tag, so add it");

						/*
						# check if this item has a record in the ucm_base table, ucm_base.ucm_item_id = content.id
						$query = $db->getQuery(true);
						$query->select(array('COUNT(*)'));
						$query->where($db->quoteName('ucm_item_id') . " = " . $db->quote($article['id']));
						$query->from($db->quoteName('#__ucm_base'));
						$db->setQuery($query);
						$count = $db->loadResult();
						*/

						# check if this item has a record in the ucm_base table, ucm_base.ucm_item_id = content.id
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
							# INSERT INTO `s3ib7_ucm_content` (`core_content_id`, `core_type_alias`, `core_title`, `core_alias`, `core_body`, `core_state`, `core_checked_out_time`, `core_checked_out_user_id`, `core_access`, `core_params`, `core_featured`, `core_metadata`, `core_created_user_id`, `core_created_by_alias`, `core_created_time`, `core_modified_user_id`, `core_modified_time`, `core_language`, `core_publish_up`, `core_publish_down`, `core_content_item_id`, `asset_id`, `core_images`, `core_urls`, `core_hits`, `core_version`, `core_ordering`, `core_metakey`, `core_metadesc`, `core_catid`, `core_type_id`) 
							# VALUES(700, 'com_content.article', 'Near 21 Spa St, Aberdeen AB25 1PU, UK', '740-2', '<p>Thanks to <a title=\"Danny Christie / wired.wifi\" href=\"https://www.instagram.com/wired.wifi/\" target=\"_blank\" rel=\"noopener\">Danny Christie / wired.wifi</a> for the photo</p>', 1, NULL, NULL, 1, '{}', 0, '{}', 873, '', '2022-03-24 16:32:26', 873, '2022-10-04 10:49:33', '*', '2022-03-24 16:32:26', NULL, 786, 2287, '{}', '{}', 184, 3, 667, '', '', 9, 1);
							$query = $db->getQuery(true);
							$columns = array();
							$values = array(); 
							$query->insert($db->quoteName('#__ucm_content'));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$db->setQuery($query);
							$symfonyStyle->text("Insert ucm_content query " . $query);
							$result = $db->execute();
							$symfonyStyle->text("Result for ucm_content query: " . $result);	

							# TODO: get inserted id
							$ucm_id = 0;

							# insert row into ucm_content
							# INSERT INTO `s3ib7_ucm_base` (`ucm_id`, `ucm_item_id`, `ucm_type_id`, `ucm_language_id`) 
							# VALUES(700, 786, 1, 0);
							$query = $db->getQuery(true);
							$columns = array('type_alias', 'core_content_id', 'content_item_id', 'tag_id', 'tag_date', 'type_id');
							$values = array($db->quote('com_content.article'), $db->quote($ucm_id), $db->quote($article['id']), $db->quote($box['id']), $db->quote($sql_datetime), 1); 
							$query->insert($db->quoteName('#__ucm_base'));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$db->setQuery($query);
							$symfonyStyle->text("Insert ucm_base query " . $query);
							$result = $db->execute();
							$symfonyStyle->text("Result for ucm_base query: " . $result);						


						}

						# get the ucm_id
						$query = $db->getQuery(true);
						$query->select(array('ucm_id'));
						$query->where($db->quoteName('ucm_item_id') . " = " . $db->quote($article['id']));
						$query->from($db->quoteName('#__ucm_base'));
						$db->setQuery($query);
						$ucm_id = $db->loadResult();


						# like INSERT INTO `s3ib7_contentitem_tag_map` (`type_alias`, `core_content_id`, `content_item_id`, `tag_id`, `tag_date`, `type_id`) VALUES('com_content.article', 699, 1398, 39, '2022-10-03 10:44:58', 1);
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
					}
				} else {
					$symfonyStyle->text("Not in the box");
				}
			}
		}
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
		https://developers.facebook.com/blog/post/2021/01/26/introducing-instagram-content-publishing-api/

		https://developers.facebook.com/docs/instagram-api/guides/content-publishing/#access-tokens
		https://developers.facebook.com/docs/instagram-api/guides/content-publishing/#single-media-posts

		https://developers.facebook.com/docs/instagram-api#instagram-graph-api

		https://developers.facebook.com/docs/graph-api/overview

		*/


		return;
	}
}
