<?php
	namespace LIS\Controllers;

	use LIS\User\User;
	use LIS\LibraryCard;

	class LibraryCardController extends BaseController {

		/**
		 * @param User $user
		 */
		public function addNewLibraryCard(User $user) {
			$card = $user->getLibraryCard();

			// Make old card or default card inactive
			$card->setStatus(LibraryCard::STATUS_INACTIVE);

			// create new Library Card
			$card = new LibraryCard($this->_pdo);
			$card->create($user);

			$card->setStatus(LibraryCard::STATUS_ACTIVE);

			$_SESSION["profile_update"] = "Card with number: " . $card->getNumber() . " was successfully added to "
				. $user->getNameFull() . "'s account";
			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
		}
	}