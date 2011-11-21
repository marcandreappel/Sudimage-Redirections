<?php
defined('C5_EXECUTE') or die("Access Denied.");

class RedirectHelper {

	public function simpleRedirection()
	{
		$req = Request::get();
		$_cID = $req->getCurrentPage()->cID;
		$co = Collection::getByID($_cID);

		if($cID = $co->getCollectionAttributeValue('redirect_page_to_target_page')) {

            if ( is_object($cID) && !$cID->isError() )
			{
                header('Location: ' . BASE_URL . $cID->cPath);
				exit;
			}
		}
	}
}