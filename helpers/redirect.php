<?php
defined('C5_EXECUTE') or die("Access Denied.");

class RedirectHelper {

	public function simpleRedirection()
	{
		$req = Request::get();
		$_cID = $req->getCurrentPage()->cID;
		$co = Collection::getByID($_cID);
		if($co->getCollectionAttributeValue('redirect_page_to_child_page')) {
			$c = Page::getByID($_cID);
			if ( is_object($c) && !$c->isError() )
			{
				$db = Loader::db();
				$redirectpath = null;
				$ca = array($c);
				$i = 0;
				while ( $redirectpath == null )
				{
					if ( !is_object($ca[$i]) )
					{
						header('Location: ' . BASE_URL . '/page_not_found');
						exit;
					}
					$r = $db->query("select Pages.cID from Pages as Pages, Collections as Collections
					    where Collections.cID = Pages.cID and Pages.cParentID = ?
					    and Collections.cHandle != 'Login'
					    and Collections.cHandle != 'register'
					    and Collections.cHandle != 'profile'
					    and Collections.cHandle != 'edit'
					    and Collections.cHandle != 'members'
					    and Collections.cHandle != 'page_not_found'
					    and Collections.cHandle != 'page_forbidden'
					    and Collections.cHandle != 'dashboard'
					    and Collections.cHandle != 'members'
					    order by Pages.cDisplayOrder asc", array($ca[$i]->cID));
					$row = $r->fetchRow();
					$ca[($i+1)] = Page::getByID($row['cID']);
					if ( is_object($ca[($i+1)]) && !$ca[($i+1)]->isError )
					{

						$tco = Collection::getByID($ca[($i+1)]->cID);
						if ( !$tco->getCollectionAttributeValue('redirect_page_to_child_page') )
						{
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