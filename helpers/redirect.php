<?php
defined('C5_EXECUTE') or die("Access Denied.");

class RedirectHelper {

	public function simpleRedirection() {
		$req = Request::get();
		$_cID = $req->getCurrentPage()->cID;
		$co = Collection::getByID($_cID);
		if($co->getCollectionAttributeValue('redirect_page_to_child_page')) {
			$c = Page::getByID($_cID);
			if(is_object($c) && !$c->isError()) {
				$redirectpath = null;
				$ca = array($c);
				$i = 0;
				while($redirectpath == null) {
					if(!is_object($ca[$i])) {
						header('Location: ' . BASE_URL . '/page_not_found');
						exit;
					}
					$ca[($i+1)] = $ca[$i]->getFirstChild();
					if(is_object($ca[($i+1)]) && !$ca[($i+1)]->isError) {
						$tco = Collection::getByID($ca[($i+1)]->cID);
						if(!$tco->getCollectionAttributeValue('redirect_page_to_child_page')) {
							$redirectpath = $ca[($i+1)]->cPath;
						}
					}
					$i++;
				}
				header('Location: ' . BASE_URL . $redirectpath);
				exit;
			}
		}
	}
}