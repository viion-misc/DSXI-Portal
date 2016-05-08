<?php

namespace DSXI\Storage;

//
// Character Storage
//
class CharacterStorage extends \DSXI\Handle
{
	private $dbs;

	public $joblist = [
		1 => 'Warrior',
		2 => 'Monk',
		3 => 'White Mage',
		4 => 'Black Mage',
		5 => 'Red Mage',
		6 => 'Thief',
		7 => 'Paladin',
		8 => 'Dark Knight',
		9 => 'Beastmaster',
		10 => 'Bard',
		11 => 'Ranger',
		12 => 'Samurai',
		13 => 'Ninja',
		14 => 'Dragoon',
		15 => 'Summoner',
		16 => 'Blue Mage',
		17 => 'Corsair',
		18 => 'Puppetmaster',
		19 => 'Dancer',
		20 => 'Scholar',
		21 => 'Geomancer',
		22 => 'Rune Fencer',
	];

	public $columns = [
		// character
		"chars.charid",
		"chars.accid",
		"chars.charname",
		"chars.nation",
		"chars.pos_zone",
		"chars.pos_prevzone",
		"chars.pos_rot",
		"chars.pos_x",
		"chars.pos_y",
		"chars.pos_z",
		"chars.boundary",
		"chars.home_zone",
		"chars.home_rot",
		"chars.home_x",
		"chars.home_y",
		"chars.home_z",
		"chars.missions",
		"chars.assault",
		"chars.campaign",
		"chars.quests",
		"chars.keyitems",
		"chars.set_blue_spells",
		"chars.abilities",
		"chars.titles",
		"chars.zones",
		"chars.playtime",
		"chars.unlocked_weapons",
		"chars.gmlevel",
		"chars.isnewplayer",
		"chars.mentor",
		"chars.campaign_allegiance",
		"chars.isstylelocked",

		// char stats
		"char_stats.hp as stats_hp",
		"char_stats.mp as stats_mp",
		"char_stats.nameflags as stats_nameflags",
		"char_stats.mhflag as stats_mhflag",
		"char_stats.mjob as stats_mjob",
		"char_stats.sjob as stats_sjob",
		"char_stats.death as stats_death",
		"char_stats.2h as stats_2h",
		"char_stats.title as stats_title",
		"char_stats.bazaar_message as stats_bazaar_message",
		"char_stats.zoning as stats_zoning",
		"char_stats.mlvl as stats_mlvl",
		"char_stats.slvl as stats_slvl",
		"char_stats.pet_id as stats_pet_id",
		"char_stats.pet_type as stats_pet_type",
		"char_stats.pet_hp as stats_pet_hp",
		"char_stats.pet_mp as stats_pet_mp",
	];

	function __construct()
	{
		$this->dbs = $this->get('database');
	}

	//
	// Get characters for user
	//
	public function getCharactersByUserId($accId)
	{
		$sql = sprintf('SELECT %s FROM chars
			LEFT JOIN char_stats ON char_stats.charid = chars.charid
			WHERE chars.accid = :accid', implode(',', $this->columns));

		return $this->dbs->sql($sql, [
			':accid' => $accId,
		]);
	}

	//
	// Get all characters that are not tied to the user
	//
	public function getCharactersNotUsers($accId)
	{
		$sql = sprintf('SELECT %s FROM chars
			LEFT JOIN char_stats ON char_stats.charid = chars.charid
			WHERE chars.accid != :accid', implode(',', $this->columns));

		return $this->dbs->sql($sql, [
			':accid' => $accId,
		]);
	}
}
