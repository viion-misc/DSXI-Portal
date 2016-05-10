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
		// Manage character inventory
		//
		$this->route('/characters/update/{id}/{name}/inventory', 'GET|POST', function(Request $request, $id, $name)
		{
			$this->mustBeOnline();

			$storage = new CharacterStorage();
			$character = $storage->getCharacterById($id);

			// get inventory for character
			$itemStorage = new InventoryStorage();
			$inventory = $itemStorage->getInventoryByCharId($id);

			return $this->respond('Characters/inventory.html.twig', [
				'character' => $character,
				'inventory' => $inventory,
			]);
		});

		//
        // Cap the storage for a character
        //
        $this->route('/characters/update/{id}/{name}/storage', 'GET', function(Request $request, $id, $name)
        {
			$this->mustBeOnline();

            $storage = new InventoryStorage();
            $storage->setStorageSize($id);

            $this->get('session')->add('success', 'All inventory storages have been set to the cap.');
            return $this->redirect(sprintf('/characters/update/%s/%s', $id, $name));
        });

		//
        // Cap the storage for a character
        //
        $this->route('/characters/update/{id}/{name}/profile', 'GET|POST', function(Request $request, $id, $name)
        {
            $dbs = $this->get('database');
			$this->mustBeOnline();

			// get character
			$storage = new CharacterStorage();
			$character = $storage->getCharacterById($id);

			if ($request->isMethod('POST')) {
				$storage->updateTableValues($id, 'char_profile', $request);
				$this->get('session')->add('success', 'Character profile has been updated!');
			}

			// get profile
			$fields = $dbs->sql('SELECT * FROM char_profile WHERE charid = :id', [ ':id' => $id ])[0];

			return $this->respond('Characters/profile.html.twig', [
				'character' => $character,
				'fields' => $fields,
			]);
        });

		//
        // Cap the storage for a character
        //
        $this->route('/characters/update/{id}/{name}/points', 'GET|POST', function(Request $request, $id, $name)
        {
            $dbs = $this->get('database');
			$this->mustBeOnline();

			// get character
			$storage = new CharacterStorage();
			$character = $storage->getCharacterById($id);

			if ($request->isMethod('POST')) {
				$storage->updateTableValues($id, 'char_points', $request);
				$this->get('session')->add('success', 'Character points has been updated!');
			}

			// get profile
			$fields = $dbs->sql('SELECT * FROM char_points WHERE charid = :id', [ ':id' => $id ])[0];

			return $this->respond('Characters/points.html.twig', [
				'character' => $character,
				'fields' => $fields,
			]);
        });

		//
        // Unlock all jobs on a character
        //
        $this->route('/characters/update/{id}/{name}/jobs/unlock', 'GET', function(Request $request, $id, $name)
        {
            $dbs = $this->get('database');
			$this->mustBeOnline();

			$dbs->sql('UPDATE char_jobs SET
				unlocked = 2097150, genkai = 75
				pld = 1, drk = 1, bst = 1, rng = 1, sam = 1, nin = 1, drg = 1, smn = 1,
				blu = 1, cor = 1, pup = 1, dnc = 1, sch = 1, geo = 1, run = 1
				WHERE charid = :charid', [
				':charid' => $id,
			]);

			$this->get('session')->add('success', 'Character jobs have been unlocked, zone to see the effects.');
			return $this->redirect(sprintf('/characters/update/%s/%s', $id, $name));
        });
    }
}
