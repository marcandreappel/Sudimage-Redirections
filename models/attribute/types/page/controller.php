<?
defined('C5_EXECUTE') or die("Access Denied.");

class PageAttributeTypeController extends AttributeTypeController  {

	protected $searchIndexFieldDefinition = 'I DEFAULT 0 NULL';

	public function getValue() {
		$db = Loader::db();
		$value = $db->GetOne("select cID from atPage where avID = ?", array($this->getAttributeValueID()));
		if ($value > 0) {
			$c = Page::getByID($value);
			return $c;
		}
	}
	
	public function searchForm($list) {
		$cID = $this->request('value');
		$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $cID);
		return $list;
	}
	
	public function getSearchIndexValue() {
		$db = Loader::db();
		$value = $db->GetOne("select cID from atPage where avID = ?", array($this->getAttributeValueID()));
		return $value;	
	}
	
	public function search() {
		// search by file causes too many problems
		//$al = Loader::helper('concrete/asset_library');
		//print $al->file('ccm-file-akID-' . $this->attributeKey->getAttributeKeyID(), $this->field('value'), t('Choose File'), $bf);
	}
	
	public function form() {
		$bf = false;
		if ($this->getAttributeValueID() > 0) {
			$gv = $this->getValue();
            $bf = $gv->getCollectionID();
		}
		$al = Loader::helper('form/page_selector');
		print $al->selectPage('internal_link', $bf);
	}

	// run when we call setAttribute(), instead of saving through the UI
	public function saveValue($obj) {
		$db = Loader::db();
		$db->Replace('atPage', array('avID' => $this->getAttributeValueID(), 'cID' => $obj->getCollectionID()), 'avID', true);
	}
	
	public function deleteKey() {
		$db = Loader::db();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atPage where avID = ?', array($id));
		}
	}
	
	public function saveForm($data) {
		if ($_POST['internal_link'] > 0) {
			$f = Collection::getByID($_POST['internal_link']);
			$this->saveValue($f);
		} else {
			$db = Loader::db();
			$db->Replace('atPage', array('avID' => $this->getAttributeValueID(), 'cID' => 0), 'avID', true);
		}
	}
	
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atPage where avID = ?', array($this->getAttributeValueID()));
	}
	
}