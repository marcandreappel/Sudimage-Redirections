<?php   defined('C5_EXECUTE') or die(_("Access Denied."));

class SudimageRedirectionsPackage extends Package {

	protected $pkgHandle = 'sudimage_redirections';
	protected $appVersionRequired = '5.4.2';
	protected $pkgVersion = '1.3.1';
	
	public function getPackageDescription() {
		return t('Create simple redirections from a page in the sitemap to another page.');
	}
	
	public function getPackageName() {
		return t('Redirections');
	}
	
	public function on_start() {
		Events::extend('on_start', 'RedirectHelper', 'simpleRedirection', 'packages/' . $this->pkgHandle . '/helpers/redirect.php');
	}
	
	public function install() {
		$pkg = parent::install();

        Loader::model('attribute/type');
        $at = AttributeType::add('page', 'Pages', $pkg);

        Loader::model('attribute/category');
        $list = AttributeKeyCategory::getList();
		foreach($list as $cat) {
            $cat->associateAttributeKeyType($at);
        }

        Loader::model('attribute/categories/collection');
		$args = array(
			'akHandle' => 'redirect_page_to_target_page',
			'akName' => t('Redirect this page to another page'),
			'akIsSearchable' => 0,
			'akIsSearchableIndexed' => 0,
			'akIsAutoCreated' => 0,
			'akIsEditable' => 1
		);
		$ak = CollectionAttributeKey::add($at, $args, $pkg);
	}
	
	public function uninstall() {
        Loader::model('attribute/categorie/collection');
		$ak = CollectionAttributeKey::getByHandle('redirect_page_to_target_page');
		$ak->delete();

		Loader::model('attribute/type');
		$at = AttributeType::getByHandle('page');
		$at->delete();
		
		$pkg = parent::uninstall();
	}
}
