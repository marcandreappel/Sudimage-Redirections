<?php   defined('C5_EXECUTE') or die(_("Access Denied."));

class SudimageRedirectionsPackage extends Package {

	protected $pkgHandle = 'sudimage_redirections';
	protected $appVersionRequired = '5.4.2';
	protected $pkgVersion = '1.1';
	
	public function getPackageDescription() {
		return t('Create simple redirections from a page in the sitemap to its child page.');
	}
	
	public function getPackageName() {
		return t('Redirections');
	}
	
	public function on_start() {
		Events::extend('on_start', 'RedirectHelper', 'simpleRedirection', 'packages/' . $this->pkgHandle . '/helpers/redirect.php');
	}
	
	public function install() {
		$pkg = parent::install();
		
		Loader::model('attribute/categories/collection');
		$type = AttributeType::getByHandle('boolean');
		$args = array(
			'akHandle' => 'redirect_page_to_child_page',
			'akName' => t('Redirect this page to its first child page'),
			'akIsSearchable' => 0,
			'akIsSearchableIndexed' => 0,
			'akIsAutoCreated' => 0,
			'akIsEditable' => 1
		);
		$ak = CollectionAttributeKey::add($type, $args, $pkg);
	}
	
	public function uninstall() {
		Loader::model('attribute/categorie/collection');
		$ak = CollectionAttributeKey::getByHandle('redirect_page_to_child_page');
		$ak->delete();
		
		$pkg = parent::uninstall();
	}
}
