<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;

use DSXI\Storage\CharacterStorage,
	DSXI\Storage\InventoryStorage;

trait Characters
{
    protected function _characters()
    {
		//
		// Characters
		//
		$this->route('/characters', 'GET', function(Request $request)
		{
			$this->mustBeOnline();

			$storage = new CharacterStorage();
			$characters = $storage->getCharactersNotUsers($this->getUser()->id);

			return $this->respond('Characters/index.html.twig', [
				'characters' => $characters,
			]);
		});

		//
		// Update a character
		//
		$this->route('/characters/update/{id}/{name}', 'GET|POST', function(Request $request, $id, $name)
		{
			$this->mustBeOnline();

			$storage = new CharacterStorage();
			$character = $storage->getCharacterById($id);

			// if updating
			if ($request->isMethod('POST')) {
				$storage->updateCharacterProfile($id, [
					'charname' => trim($request->get('charname')),
					'gmlevel' => trim($request->get('gmlevel')),
				]);

				$this->get('session')->add('success', 'Character profile has been saved!');

				// get character again
				$character = $storage->getCharacterById($id);
			}

			return $this->respond('Characters/update.html.twig', [
				'character' => $character,
			]);
		});

		//
		// Update a character
		//
		$this->route('/characters/update/{id}/{name}/inventory', 'GET|POST', function(Request $request, $id, $name)
		{
			$this->mustBeOnline();

			$storage = new CharacterStorage();
			$character = $storage->getCharacterById($id);
			if (!$character) {
				die('Could not find a character with this ID, go back and do it properly :D');
			}

			// get inventory for character
			$itemStorage = new InventoryStorage();
			$inventory = $itemStorage->getInventoryByCharId($id);

			return $this->respond('Characters/inventory.html.twig', [
				'character' => $character,
			]);
		});
    }
}
