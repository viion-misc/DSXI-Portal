<?php

namespace DSXI\Apps\Account;

//
// Account wrapper
//
class Character extends \DSXI\Handle
{
	public $id;
	public $name;
	public $nation;
	public $zone;
	public $zone_prev;
	public $zone_pos;
	public $home;
	public $home_pos;
	public $gmlevel;
	public $new;
	public $mentor;
	public $campaign;
	public $playtime;

	function __construct($character)
	{
		$this->id = $character['charid'];
		$this->name = $character['charname'];
		$this->nation = $character['nation'];
		$this->zone = $character['pos_zone'];
		$this->zone_prev = $character['pos_prevzone'];
		$this->zone_pos = [
			'x' => $character['pos_x'],
			'y' => $character['pos_y'],
			'z' => $character['pos_z'],
		];
		$this->home = $character['home_zone'];
		$this->home_pos = [
			'x' => $character['home_x'],
			'y' => $character['home_y'],
			'z' => $character['home_z'],
		];
		$this->gmlevel = $character['gmlevel'];
		$this->new = $character['isnewplayer'];
		$this->mentor = $character['mentor'];
		$this->campaign = $character['campaign_allegiance'];

		$playtime = new \DateTime('@' . $character['playtime'], new \DateTimeZone('UTC'));
		$this->playtime = [
			'days' => $playtime->format('z'),
			'hours' => $playtime->format('G'),
			'minutes' => $playtime->format('i'),
			'seconds' => $playtime->format('s'),
		];
	}
}
