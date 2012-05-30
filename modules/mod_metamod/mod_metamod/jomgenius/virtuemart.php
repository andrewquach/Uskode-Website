<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * JomGenius classes
 * 
 * @package		JomGenius
 * @version		9
 * @license		GNU/GPL
 * @copyright	Copyright (C) 2010-2011 Brandon IT Consulting. All rights reserved.
 */

class JomGeniusClassVirtuemart extends JomGeniusParent {
	
	/* for VirtueMart, we allow it to be instantiated even if we are not currently on the VM component.
	 * This is because we might want to check the contents of the cart on any page.
	 */
	
	var $Itemid;
	var $view;
	var $option;
	var $product_id;
	var $page;
	var $category_id;
	
	function __construct() {
		$this->Itemid		= JRequest::getVar('Itemid');
		$this->product_id	= JRequest::getVar('product_id');
		$this->view			= JRequest::getWord('view');
		$this->option		= JRequest::getVar('option');
		$this->page			= JRequest::getVar('page');
		$this->category_id	= JRequest::getVar('category_id');
		
			
		// we check to see if the page URL is simply "option=com_virtuemart&Itemid=XXX", and a GET request. If so
		// then we get the page type from the menu item rather than the URL.
		// double check the URL... should work with SEF or non SEF
		if ( $this->option == 'com_virtuemart' and $this->Itemid != '' and $this->page == null and $this->view == null and $this->category_id == null 
			and !empty($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'GET' and count( $_GET ) == 2 ) {

			$this->page = JomGeniusParent::menuItemParam( 'page' );
			$this->category_id = JomGeniusParent::menuItemParam( 'category_id' );
			$this->product_id = JomGeniusParent::menuItemParam( 'product_id' );
			// in the menu params, if a category id was given, page defaults to shop.browse.
			// likewise with a product id - defaults to a shop.product_details page.
			if ( $this->page == null ) {
				if ( $this->category_id ) $this->page = 'shop.browse';
				else if ( $this->product_id ) $this->page = 'shop.product_details';
			}
		}
	}
	
	function shouldInstantiate() {
		if ( $this->componentExists( 'com_virtuemart' ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * A generic function that knows how to get lots of different info about the current page or product.
	 */
	function info( $type ) {
		$type = strtolower( str_replace( array(' ','_'), '', $type ) );
		switch( $type ) {
			case 'productid':
				return $this->productId();
				
			case 'manufacturerid':
			case 'manufacturername':
			case 'manufacturercategoryid':
			case 'manufacturercategoryname':
			case 'vendorid':
			case 'productparentid':
			case 'productsku':
			case 'productshortdesc':
			case 'productdesc':
			case 'isproductpublished':
			case 'productweight':
			case 'productweightunit':
			case 'productwidth':
			case 'productheight':
			case 'productlength':
			case 'productmeasurementunit':
			case 'productinstock':
			case 'isproductspecial':
			case 'productdiscountid':
			case 'productshipcodeid':
			case 'productname':
			case 'productsales':
			case 'productattributes':
			case 'producttaxid':
			case 'productunit':
			case 'unitsinbox':
			case 'unitsinpackage':
			case 'childoptions':
			case 'quantityoptions':
				return $this->productInfo( $type );
				
			case 'pagetype':
				return $this->pageType();
			
			case 'flypage':
				return $this->flypage();

			case 'categoryname':
				return $this->categoryName();
			
			case 'categorynames':
				return $this->categoryNames();
			
			case 'categoryid':
				return $this->categoryId();
				
			case 'categoryids':
				return $this->categoryIds();

			case 'ancestorcategoryids':
				return $this->categoryIds( 'all' );

			case 'numbercartproducts':
				return $this->numberCartProducts();

			case 'numbercartitems':
				return $this->numberCartItems();

			case 'iscartpopulated':
				return ( $this->numberCartItems() > 0 );

			case 'iscartempty':
				return ( $this->numberCartItems() == 0 );
				
			case 'cartproductids':
				return $this->cartProductIds();

			case 'cartcategoryids':
				return $this->cartCategoryIds();
				
			case 'shoppergroup':
				return $this->shopperGroup();
				
			case 'couponcode':		// MY_COUPON
			case 'couponredeemed':	// 1
			case 'iscouponredeemed':	// true
			case 'coupondiscount':	// 6.00
			case 'couponid':		// 1
			case 'coupontype':		// gift
				return $this->coupons( $type );
			
			// these ones there can be several of per user.
			case 'shiptocountry3s':
			case 'shiptocountry2s':
			case 'shiptocountrynames':
			case 'shiptostates':
			case 'shiptocitys':
			case 'shiptozips':
			case 'shiptoemails':
			
			// these ones there can only 1 of per user.
			case 'billtocountry3':
			case 'billtocountry2':
			case 'billtocountryname':
			case 'billtostate':
			case 'billtocity':
			case 'billtozip':
			case 'billtoemail':
				return $this->userInfo( $type );
			
			case 'issearchresults':
				return $this->isSearchResults();
				
			default:
		}
	}
	
	function isSearchResults() {
		if ( $this->option != 'com_virtuemart' ) return false;
		if ( array_key_exists( 'keyword1', $_REQUEST ) ) return true;
		return false;
	}
	
	function productInfo( $type ) {
		if ( $this->option != 'com_virtuemart' ) return null;
		
		switch ( $type ) {
			case 'manufacturerid':
				$type = 'manufacturer_id';		break;
			case 'manufacturername':
				$type = 'manufacturer_name';	break;
			case 'manufacturercategoryid':
				$type = 'manufacturer_category_id';	break;
			case 'manufacturercategoryname':
				$type = 'manufacturer_category_name';	break;
			case 'vendorid':
				$type = 'vendor_id';			break;
			case 'productparentid':
				$type = 'product_parent_id';	break;
			case 'productsku':
				$type = 'product_sku';			break;
			case 'productshortdesc':
				$type = 'product_s_desc';		break;
			case 'productdesc':
				$type = 'product_desc';			break;
			case 'isproductpublished':
				$type = 'product_publish';		break;
			case 'productweight':
				$type = 'product_weight';		break;
			case 'productweightunit':
				$type = 'product_weight_uom';	break;
			case 'productwidth':
				$type = 'product_width';		break;
			case 'productheight':
				$type = 'product_height';		break;
			case 'productlength':
				$type = 'product_length';		break;
			case 'productmeasurementunit':
				$type = 'product_lwh_uom';		break;
			case 'productinstock':
				$type = 'product_in_stock';		break;
			case 'isproductspecial':
				$type = 'product_special';		break;
			case 'productdiscountid':
				$type = 'product_discount_id';	break;
			case 'productshipcodeid':
				$type = 'ship_code_id';			break;
			case 'productname':
				$type = 'product_name';			break;
			case 'productsales':
				$type = 'product_sales';		break;
			case 'productattributes':
				$type = 'attribute';			break;
			case 'producttaxid':
				$type = 'product_tax_id';		break;
			case 'productunit':
				$type = 'product_unit';			break; // e.g. "download";
			case 'unitsinbox':
				$type = 'units_in_box';			break;
			case 'unitsinpackage':
				$type = 'units_in_package';		break;
			case 'childoptions':
				$type = 'child_options';		break;
			case 'quantityoptions':
				$type = 'quantity_options';		break;
			return '';
		}
		
		$man_id = JRequest::getInt('manufacturer_id');
		
		// browse pages can be indexed by manufacturer - shortcut!
		if ( $type == 'manufacturer_id'
			and ( $this->page == 'shop.browse' or $this->page == 'shop.manufacturer_page' )
			and $man_id > 0
		  ) {
			return $man_id;
		}
		
		// look up product by id.
		$prod_id = (int) $this->product_id;
		if ( $prod_id > 0 ) {
			static $prods = array();
			if ( !array_key_exists( $prod_id, $prods ) ) {
				$db		=& JFactory::getDBO();
				$query = 'SELECT p.*, '
					. 'mfx.manufacturer_id, '
					. 'mf.mf_name as manufacturer_name, '
					. 'mf.mf_category_id as manufacturer_category_id, '
					. 'mfc.mf_category_name as manufacturer_category_name '
					. 'FROM #__vm_product p '
					. 'LEFT JOIN #__vm_product_mf_xref mfx ON mfx.product_id = p.product_id '
					. 'LEFT JOIN #__vm_manufacturer mf ON mfx.manufacturer_id = mf.manufacturer_id '
					. 'LEFT JOIN #__vm_manufacturer_category mfc ON mf.mf_category_id = mfc.mf_category_id '
					. 'WHERE p.product_id = ' . (int)$prod_id;
				$db->setQuery( $query );
				$row = $db->loadAssoc();
				$row['product_publish'] = ( $row['product_publish'] == 'Y');
				$row['product_special'] = ( $row['product_special'] == 'Y');
				$row['units_in_box'] = (int)($row['product_packaging'] / 65536 );
				$row['units_in_package'] = (int)($row['product_packaging'] % 65536 );
				
				
				$prods[ $prod_id ] = $row;
			}
			return @$prods[ $prod_id ][ $type ];
		}
		
		// or maybe we have a manufacturer id. We'll look up the info by this.
		if ( $man_id > 0 and 
			( $type == 'manufacturer_id' or $type == 'manufacturer_name' or $type == 'manufacturer_category_id' or $type == 'manufacturer_category_name' )
		  ) {
			static $mans = array();
			if ( !array_key_exists( $this->product_id, $mans ) ) {
				$db		=& JFactory::getDBO();
				$query = 'SELECT '
					. 'mf.mf_name as manufacturer_name, '
					. 'mf.mf_category_id as manufacturer_category_id, '
					. 'mfc.mf_category_name as manufacturer_category_name '
					. 'FROM #__vm_manufacturer mf '
					. 'LEFT JOIN #__vm_manufacturer_category mfc ON mf.mf_category_id = mfc.mf_category_id '
					. 'WHERE mf.manufacturer_id = ' . (int)$man_id;
				$db->setQuery( $query );
				$row = $db->loadAssoc();
				$mans[ $man_id ] = $row;
			}
			return @$mans[ $man_id ][ $type ];
		}
	}
	
	function pageType() {
		if ( $this->option != 'com_virtuemart' ) return null;
		
		if ( $this->page == null or $this->page == "shop.index" ) return "frontpage";
		if ( in_array( $this->page, 
			array(
			'account.billing',
			'account.index',
			'account.order_details',
			'account.orders',
			'account.shipping',
			'account.shipto',
			'checkout.2Checkout_result',
			'checkout.epay_result',
			'checkout.ipayment_result',
			'checkout.paysbuy',
			'checkout.result',
			'checkout.thankyou',
			'shop.browse',
			'shop.ask',
			'shop.cart',
			'shop.registration',
			'shop.infopage',
			'shop.manufacturer_page', // normally appears on menuless popup window
			'shop.product_details',
			'shop.savedcart',
			'shop.search', // advanced search form
			'shop.waiting_list',
			'shop.waiting_thanks'
			) ) ) {
				return $this->page;
		}
		if ( $this->page == "checkout.index") {
			$last_step = JRequest::getVar("checkout_last_step");
			$stage = JRequest::getVar("checkout_stage");
			if ( $last_step == "3" or $stage == 4 ) return "checkout.index#4";
			if ( $last_step == "2" or $stage == 3 ) return "checkout.index#3";
			if ( $last_step == "1" or $stage == 2 ) return "checkout.index#2";
			if ( $last_step == ""  or $stage == 1 ) return "checkout.index#1";
		}
		
		return '';
	}
	
	function flypage() {
		if ( $this->option != 'com_virtuemart' ) {
			return '';
		}
		return JRequest::getVar( 'flypage' );
	}
	
	/**
	 * product_id
	 */
	function productId() {
		if ( $this->option != 'com_virtuemart' ) {
			return '';
		}
		$p =  (int)$this->product_id;
		return $p ? $p : '';
	}
	
	/**
	 * shopper group
	 */
	
	function shopperGroup() {
		$user 	=& JFactory::getUser();
		if ( $user->id > 0 ) {
			$id = (int)$user->id;
			$db		=& JFactory::getDBO();
			$query = "SELECT shopper_group_id FROM #__vm_shopper_vendor_xref " .
			   " WHERE user_id = '$id'";
			$db->setQuery( $query );
			$row = $db->loadResult();
			return $row;
		}
		return '';
	}
	
	/**
	 * shopperInfo
	 * type can be "bt" (billto) or "st" (shipto)
	 */
	function shopperInfo( $type='all' ) {
		$user 	=& JFactory::getUser();
		$id = (int)$user->id;
		if ( $id > 0 ) {
			static $users = array();
			$key = $type . $id;
			if ( !array_key_exists( $key, $users ) ) {
				$db		=& JFactory::getDBO();
				$query = "SELECT ui.*, c.country_2_code, c.country_3_code, c.country_name FROM #__vm_user_info ui " .
					" LEFT JOIN #__vm_country c on ui.country = c.country_3_code " .
					" WHERE user_id = '$id'";
				if ( $type == 'bt') $query .= " and address_type = 'BT'";
				if ( $type == 'st') $query .= " and address_type = 'ST'";
				$db->setQuery( $query );
				$row = $db->loadAssocList();
				$users[ $key ] = $row;
			}
			return $users[ $key ];
		}
		return array();
	}
	
	/**
	 * retrieves 1-dimensional array according to the index given
	 */
	function _extractArrayIndex( $array, $index ) {
		$res = array();
		if ( $array == null) return $res;
		$c = count( $array );
		if ( $c == 0 ) return $res;
		for ( $i = 0; $i < $c; $i++ ) {
			$res[] = @$array[ $i ][ $index ];
		}
		return $res;
	}
	
	// these are all well and good, but have some problems:
	// 1 - shiptos all return lists, not single items. check() can process them, but which one is which?
	//
	// 2 - in practicality, these probably should refer to the current order being processed
	//     rather than to all addresses in the database. Perhaps we need another set of shiptos
	//     that look up the current order and associate the shipto associated with the current
	//     order?
	
	function userInfo( $type ) {
		switch( $type ) {
			// these ones there can be several of per user.
			case 'shiptocountry3s':
			case 'shiptocountry2s':
			case 'shiptocountrynames':
			case 'shiptostates':
			case 'shiptocitys':
			case 'shiptozips':
			case 'shiptoemails':
				$rows = $this->shopperInfo('st'); //shipto
				if ( !is_array($rows) or count($rows) == 0 ) return null;
				if ($type == 'shiptocountry3s') return $this->_extractArrayIndex( $rows, 'country_3_code');
				if ($type == 'shiptocountry2s') return $this->_extractArrayIndex( $rows, 'country_2_code');
				if ($type == 'shiptocountrynames') return $this->_extractArrayIndex( $rows, 'country_name');
				if ($type == 'shiptostates') return $this->_extractArrayIndex( $rows, 'state');
				if ($type == 'shiptocitys') return $this->_extractArrayIndex( $rows, 'city');
				if ($type == 'shiptozips') return $this->_extractArrayIndex( $rows, 'zip');
				if ($type == 'shiptoemails') return $this->_extractArrayIndex( $rows, 'user_email');
			default:
		}
			// these ones there can only 1 of per user.
		switch( $type ) {
			case 'billtocountry3':
			case 'billtocountry2':
			case 'billtocountryname':
			case 'billtostate':
			case 'billtocity':
			case 'billtozip':
			case 'billtoemail':
				$rows = $this->shopperInfo('bt'); // billto
				if ( count( $rows ) != 1 ) return '';
				$row = $rows[0];
				if ($type == 'billtocountry3') return $row['country_3_code'];
				if ($type == 'billtocountry2') return $row['country_2_code'];
				if ($type == 'billtocountryname') return $row['country_name'];
				if ($type == 'billtostate') return $row['state'];
				if ($type == 'billtocity') return $row['city'];
				if ($type == 'billtozip') return $row['zip'];
				if ($type == 'billtoemail') return $row['user_email'];
		}
	}
	/**
	 * coupons
	 */
	function coupons( $type ) {
		
		switch( $type ) {
			case 'couponcode':		// MY_COUPON
				return @$_SESSION['coupon_code']; 
			
			case 'couponredeemed':	// 1
				return @$_SESSION['coupon_redeemed'];
				
			case 'iscouponredeemed':
				return ( @$_SESSION['coupon_redeemed'] == true );

			case 'coupondiscount':	// 6.00
			return @$_SESSION['coupon_discount'];

			case 'couponid':		// 1
				return @$_SESSION['coupon_id'];

			case 'coupontype':		// gift
				return @$_SESSION['coupon_type'];
				
			default: return;
		}
	}
	
	/* generic searcher for category ids.
	 * type:
	 * "all" (default) searches the entire category hierarchy, for every category the item or browse page is in.
	 * "top" searches only the top-level categorys.
	 * "bottom" searches only the actual category that the item is in, or the category of the browse page.
	 * If the item is in more than one category, then obviously these searches will search all categories.
	 * returns: boolean
	 */
	function inCategories( $ids, $type='all') {

		$cats = $this->categoryIds( $type );
		if ( $cats == null ) return false;
		$ids = $this->convertToArray( $ids );

		foreach ( $ids as $id ) {
			if ( in_array( $id, $cats ) ) return true;
		}
		return false;
	}

	/* generic searcher for category names.
	 * type:
	 * "all" (default) searches the entire category hierarchy, for every category the item or browse page is in.
	 * "top" searches only the top-level categorys.
	 * "bottom" searches only the actual category that the item is in, or the category of the browse page.
	 * If the item is in more than one category, then obviously these searches will search all categories.
	 * returns: boolean
	 */
	function inCategoryNames( $names, $type='all') {

		$cats = $this->categoryNames( $type );
		if ( $cats == null ) return false;
		$names = $this->convertToArray( $names );

		foreach ( $names as $name ) {
			if ( in_array( $name, $cats ) ) return true;
		}
		return false;
	}
	
	/* returns an array of immediate categories that the item is in; not their parents.
	 * If the category id is in the URL, then this one will be first on the list.
	 */
	function categoryNames( $type="bottom" ) {
		switch( $type ) {
			case "top":
				return $this->_topLevelCategoryNames();
				
			case "all":
				return $this->_allCategoryNames();
			
			case "bottom":
			default:
				$names = array();
				if ($this->option == "com_virtuemart" ) {
					if ( $this->page == "shop.product_details" ) {
						$allCatInfo = $this->_categoryInfoForProductId( $this->product_id );
						if ( is_array( $allCatInfo ) ) {
							// step through array, and return array of items containing the c1 item in each
							foreach ( $allCatInfo as $cat ) {
								$names[] = $cat['n1'];
							}
							return $names;
						}
					} else {
						// run a query to get category name for $this->category_id in the URL
						$row = $this->_infoForCategoryId( $this->category_id );
						if ( is_array( $row )  and array_key_exists("category_name", $row ) ) {
							return array( $row['category_name'] );
						} else {
							return null;
						}
					}
				}
				//was not in virtuemart
				return null;
		}
	}
	
	/* returns the category name of the list, or the item being displayed.
	 * If the list: this is taken from the URL
	 * If the item: if a category id was in the URL then the name corresponds to that ID. Otherwise,
	 *  if the item is in only 1 category, then that is used. Otherwise, if it's
	 *  in more than one, then one will be selected in no particular order.
	 */
	function categoryName( $type="bottom" ) {
		switch( $type ) {
			case "top":
				return $this->_topLevelCategoryName();
			case "bottom":
			default:
				$names = $this->categoryNames();
				if (is_array($names) and count($names) > 0) return $names[0];
				return null;
		}		
	}

		
	/* returns the category id of the list, or the item being displayed.
	 * * top / bottom (default bottom)
	 * If the list: this is taken from the URL
	 * If the item: if a category id was in the URL then this is used. Otherwise,
	 *  if the item is in only 1 category, then that is used. Otherwise, if it's
	 *  in more than one, then one will be selected in no particular order.
	 */
	function categoryId( $type="bottom" ) {
		switch( $type ) {
			case "top":
				return $this->_topLevelCategoryId();
			case "bottom":
			default:
				$ids = $this->categoryIds();
				if (is_array($ids) and count($ids) > 0) return $ids[0];
				return null;
		}
	}
	

	/* returns an array of immediate category ids that the item is in; not their parents.
	 * If the category id is in the URL, then this one will be first on the list.
	 */
	function categoryIds( $type="bottom" ) {
		switch ( $type ) {
			case "top":
				return $this->_topLevelCategoryIds();

			case "all":
				return $this->_allCategoryIds();
			
			case "bottom":
			default:
				$ids = array();
				if ($this->option == "com_virtuemart" ) {
					if ( $this->page == "shop.product_details" ) {
						$allCatInfo = $this->_categoryInfoForProductId( $this->product_id );
						if ( is_array( $allCatInfo ) ) {
							// step through array, and return array of items containing the c1 item in each
							foreach ( $allCatInfo as $cat ) {
								$ids[] = $cat['c1'];
							}
							return $ids;
						}
					} else {
						if ( $this->category_id ) return array( $this->category_id );
						return array();
					}
				}
				//was not in virtuemart
				return null;
		}
	}
	
	/* returns an array of top level category names that the item (or list) is in.
	 * If the category id is in the URL, then the top-level category will correspond to that one.
	 */
	function _topLevelCategoryNames() {
		return $this->_topLevelStuff("names");
	}

	function _topLevelCategoryName() {
		$names = $this->_topLevelCategoryNames();
		if ( is_array( $names ) and count( $names ) > 0 ) {
			return $names[0];
		}
	}
	
	/* returns an array of top level category ids that the item (or list) is in.
	 * If the category id is in the URL, then the top-level category will correspond to that one.
	 */
	function _topLevelCategoryIds() {
		return $this->_topLevelStuff("ids");
	}
	
	function _topLevelCategoryId() {
		$ids = $this->_topLevelCategoryIds();
		if ( is_array( $ids ) and count( $ids ) > 0 ) {
			return $ids[0];
		}
	}
	
	function _allCategoryIds() {
		return $this->_allCategoryStuff( "ids" );
	}

	function _allCategoryNames() {
		return $this->_allCategoryStuff( "names" );
	}
		
	function _allCategoryStuff( $type ) {
		$ids = array();
		if ($this->option == "com_virtuemart" ) {
			if ( $this->page == "shop.product_details" ) {
				$allCatInfo = $this->_categoryInfoForProductId( $this->product_id );
			} else {
				$allCatInfo = $this->_categoryInfoForCategoryId( $this->category_id );				
			}
			if ( is_array( $allCatInfo ) ) {
				// step through array, and return array of items containing the c1 item in each
				foreach ( $allCatInfo as $cat ) {
					for ( $i = 1; $i <= 8; $i++ ) {
						if ($type == "names") {
							$thing = $cat['n' . $i];
						} else {
							$thing = $cat['c' . $i];
						}
						if ($thing != null and !in_array( $thing, $ids ) ) {
							$ids[] = $thing;
						}
					}
				}
				return $ids;
			}
		}
		//was not in virtuemart
		return null;
		
	}
	
	
	function _topLevelStuff( $type ) {
		$ids = array();
		$allCatInfo = array();
		if ($this->option == "com_virtuemart" ) {
			if ( $this->page == "shop.product_details" ) {
				$allCatInfo = $this->_categoryInfoForProductId( $this->product_id );
			} else {
				$allCatInfo = $this->_categoryInfoForCategoryId( $this->category_id );
			}
			if ( is_array( $allCatInfo ) ) {
				// step through array, and return array of items containing the c1 item in each
				foreach ( $allCatInfo as $cat ) {
					for ($i = 8; $i >= 1; $i-- ) {
						if ($cat['c' . $i] != null) {
							if ($type == "names") {
								$ids[] = $cat['n' . $i];
							} else {
								$ids[] = $cat['c' . $i];
							}
							break;
						}
					}
				}
				return $ids;
			}
		}
		//was not in virtuemart
		return null;
	}
	
	function _infoForCategoryId( $id ) {
		$id = (int)$id;
		$db		=& JFactory::getDBO();
		$query = "select c.*			
			from 
			#__vm_category c
			where c.category_id = $id";

		$db->setQuery( $query, 0, 1 );
		$res = $db->loadAssoc();
		return $res;
	}
	
	/* This method returns an array of information, cached for each product_id, about the categories
	 * that the product is in.
	 * If the current URL is in Virtuemart and contains the category id, then this category is always put
	 * to the top of the list.
	 * Otherwise, each row contains:
	 * c1 - c5: the immediate category id of the product (c1), and all the parents, up to 5 levels including the child
	 * n1 - n5: the names of each of the categories above.
	 * To get the top-level name or id, you need to go backward through c5 to c1 to find the first one that's not blank.
	 */
	function _categoryInfoForProductId( $ids ) {
		$ids = $this->convertToArray( $ids );
		if ( $ids == null or !is_array( $ids ) or count( $ids ) == 0 ) {
			return null;
		}
		array_map( 'intval', $ids );
		$id = implode( ",", $ids);
		
		static $infoForProduct = array();
		if ( array_key_exists( $id, $infoForProduct ) ) {
			return $infoForProduct[ $id ];
		}
		$db		=& JFactory::getDBO();

		$product_id = (int)$id;
		$query = "select distinct
			cx1.category_child_id as c1,
			vmc1.category_name as n1,
			cx2.category_child_id as c2,
			vmc2.category_name as n2,
			cx3.category_child_id as c3,
			vmc3.category_name as n3,
			cx4.category_child_id as c4,
			vmc4.category_name as n4,
			cx5.category_child_id as c5,
			vmc5.category_name as n5,
			cx6.category_child_id as c6,
			vmc6.category_name as n6,
			cx7.category_child_id as c7,
			vmc7.category_name as n7,
			cx8.category_child_id as c8,
			vmc8.category_name as n8
			
			from #__vm_product_category_xref pcx
			left outer join #__vm_category_xref cx1 on pcx.category_id = cx1.category_child_id
			left outer join #__vm_category_xref cx2 on cx1.category_parent_id = cx2.category_child_id
			left outer join #__vm_category_xref cx3 on cx2.category_parent_id = cx3.category_child_id
			left outer join #__vm_category_xref cx4 on cx3.category_parent_id = cx4.category_child_id
			left outer join #__vm_category_xref cx5 on cx4.category_parent_id = cx5.category_child_id
			left outer join #__vm_category_xref cx6 on cx5.category_parent_id = cx6.category_child_id
			left outer join #__vm_category_xref cx7 on cx6.category_parent_id = cx7.category_child_id
			left outer join #__vm_category_xref cx8 on cx7.category_parent_id = cx8.category_child_id
			
			left outer join #__vm_category vmc1 on vmc1.category_id = cx1.category_child_id
			left outer join #__vm_category vmc2 on vmc2.category_id = cx2.category_child_id
			left outer join #__vm_category vmc3 on vmc3.category_id = cx3.category_child_id
			left outer join #__vm_category vmc4 on vmc4.category_id = cx4.category_child_id
			left outer join #__vm_category vmc5 on vmc5.category_id = cx5.category_child_id
			left outer join #__vm_category vmc6 on vmc6.category_id = cx6.category_child_id
			left outer join #__vm_category vmc7 on vmc7.category_id = cx7.category_child_id
			left outer join #__vm_category vmc8 on vmc8.category_id = cx8.category_child_id

			where pcx.product_id in ( $id )";

		$db->setQuery( $query );
		$res = $db->loadAssocList();

		// now make sure that the category id mentioned in the URL, if any, is at the top
		$cat = $this->category_id;
		$found_cat = null;
		// only put the categories into order if we are in virtuemart, and the category id is in the URL
		if ($ret != null and $cat != null and $this->option == 'com_virtuemart') {
			foreach ( $res as $key=>$val ) {
				if ( $val['c1'] == $cat ) {
					$found_cat = $val;
					unset( $res[$key] );
					break;
				}
			}
			if ( $found_cat != null ) {
				$res = array_merge( array( $found_cat ), $res );
				$res = array_values( $res );
			}
		}
		// cache it
		$infoForProduct[$id] = $res;

		return $res;
	}
	
	
	function _categoryInfoForCategoryId( $id ) {
		static $infoForCategory = array();
		if ( array_key_exists( $id, $infoForCategory ) ) {
			return $infoForCategory[ $id ];
		}
		$db		=& JFactory::getDBO();

		$category_id = (int)$id;
		$query = "select distinct
			cx1.category_child_id as c1,
			vmc1.category_name as n1,
			cx2.category_child_id as c2,
			vmc2.category_name as n2,
			cx3.category_child_id as c3,
			vmc3.category_name as n3,
			cx4.category_child_id as c4,
			vmc4.category_name as n4,
			cx5.category_child_id as c5,
			vmc5.category_name as n5,
			cx6.category_child_id as c6,
			vmc6.category_name as n6,
			cx7.category_child_id as c7,
			vmc7.category_name as n7,
			cx8.category_child_id as c8,
			vmc8.category_name as n8
			
			from
			#__vm_category_xref cx1
			left outer join #__vm_category_xref cx2 on cx1.category_parent_id = cx2.category_child_id
			left outer join #__vm_category_xref cx3 on cx2.category_parent_id = cx3.category_child_id
			left outer join #__vm_category_xref cx4 on cx3.category_parent_id = cx4.category_child_id
			left outer join #__vm_category_xref cx5 on cx4.category_parent_id = cx5.category_child_id
			left outer join #__vm_category_xref cx6 on cx5.category_parent_id = cx6.category_child_id
			left outer join #__vm_category_xref cx7 on cx6.category_parent_id = cx7.category_child_id
			left outer join #__vm_category_xref cx8 on cx7.category_parent_id = cx8.category_child_id

			left outer join #__vm_category vmc1 on vmc1.category_id = cx1.category_child_id
			left outer join #__vm_category vmc2 on vmc2.category_id = cx2.category_child_id
			left outer join #__vm_category vmc3 on vmc3.category_id = cx3.category_child_id
			left outer join #__vm_category vmc4 on vmc4.category_id = cx4.category_child_id
			left outer join #__vm_category vmc5 on vmc5.category_id = cx5.category_child_id
			left outer join #__vm_category vmc6 on vmc6.category_id = cx6.category_child_id
			left outer join #__vm_category vmc7 on vmc7.category_id = cx7.category_child_id
			left outer join #__vm_category vmc8 on vmc8.category_id = cx8.category_child_id

			where cx1.category_child_id = $category_id";

		$db->setQuery( $query );
		$res = $db->loadAssocList();
		
		// now make sure that the category id mentioned in the URL, if any, is at the top
		$cat = $this->category_id;
		$found_cat = null;
		// only put the categories into order if we are in virtuemart, and the category id is in the URL
		if ( $ret != null and $cat != null and $this->option == 'com_virtuemart' ) {
			foreach ( $res as $key=>$val ) {
				if ( $val['c1'] == $cat ) {
					$found_cat = $val;
					unset( $res[$key] );
					break;
				}
			}
			if ( $found_cat != null ) {
				$res = array_merge( array( $found_cat ), $res );
				$res = array_values( $res );
			}
		}
		// cache it
		$infoForCategory[$id] = $res;

		return $res;
	}
	
	function numberCartProducts() {
		$cart = @$_SESSION['cart'];
		if ($cart == null or @$cart["idx"] == 0) {
			return 0;
		}
		return $cart['idx'];
	}
	
	/* bear in mind that if this method is used in a system plugin, then the number
	 * of items in the cart is detected BEFORE the current page is processed. If
	 * there were any adjustments made due to cart updating, these will not be reflected
	 * in the numbers given here.
	 */
	function numberCartItems() {
		$cart = @$_SESSION['cart'];
		if ($cart == null or @$cart["idx"] == 0) {
			return 0;
		}
		$products = $cart['idx'];
		$count = 0;
		for ($i = 0; $i < $products; $i++ ) {
			$count += $cart[$i]['quantity'];
		}
		return $count;
	}

	/* bear in mind that if this method is used in a system plugin, then the number
	 * of items in the cart is detected BEFORE the current page is processed. If
	 * there were any adjustments made due to cart updating, these will not be reflected
	 * in the numbers given here.
	 */
	function cartProductIds() {
		$cart = @$_SESSION['cart'];
		if ($cart == null or @$cart["idx"] == 0) {
			return array();
		}
		$ret = array();
		$products = $cart['idx'];
		for ($i = 0; $i < $products; $i++ ) {
			$ret[] = $cart[$i]['product_id'];
		}
		return $ret;
	}

	/* bear in mind that if this method is used in a system plugin, then the number
	 * of items in the cart is detected BEFORE the current page is processed. If
	 * there were any adjustments made due to cart updating, these will not be reflected
	 * in the numbers given here.
	 */
	function cartCategoryIds() {
		$cart = @$_SESSION['cart'];
		if ($cart == null or @$cart["idx"] == 0) {
			return array();
		}
		$ret = array();
		$products = $cart['idx'];
		for ($i = 0; $i < $products; $i++ ) {
			$ret[] = $cart[$i]['category_id'];
		}
		return $ret;
	}	
}