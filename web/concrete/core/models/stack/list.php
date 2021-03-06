<?

defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Model_StackList extends PageList {

	public function __construct() {
		$c = Page::getByPath(STACKS_PAGE_PATH);
		$this->ignoreAliases = true;
		$this->ignorePermissions = true;
		$this->addToQuery('inner join Stacks on Stacks.cID = p1.cID');
		$this->filterByParentID($c->getCollectionID());
		$this->sortBy('p1.cDisplayOrder', 'asc');
	}
	
	public function filterByGlobalAreas() {
		$this->filter('stType', Stack::ST_TYPE_GLOBAL_AREA);
	}

	public function filterByUserAdded() {
		$this->filter('stType', Stack::ST_TYPE_USER_ADDED);
	}
	
	public static function export(SimpleXMLElement $x) {
		$db = Loader::db();
		$r = $db->Execute('select stName, cID, stType from Stacks order by stName asc');
		if ($r->NumRows()) {
			$gas = $x->addChild('stacks');
			while ($row = $r->FetchRow()) {
				$stack = Stack::getByName($row['stName']);
				$stack->export($gas);
			}
		}
	}

	
}
