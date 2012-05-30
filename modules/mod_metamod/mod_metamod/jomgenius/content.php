<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * JomGenius classes
 * 
 * @package		JomGenius
 * @version		5
 * @license		GNU/GPL
 * @copyright	Copyright (C) 2010-2011 Brandon IT Consulting. All rights reserved.
 */

class JomGeniusClassContent extends JomGeniusParent {
	
	var $Itemid;
	var $view;
	var $option;
	var $category_id;
	var $section_id;
	var $layout;
	var $task;
	var $id;
	
	function __construct() {
		$this->Itemid		= JRequest::getVar('Itemid');
		$this->view			= JRequest::getWord('view');
		$this->option		= JRequest::getVar('option');
		$this->category_id	= JRequest::getVar('category_id');
		$this->section_id	= JRequest::getVar('section_id');
		$this->layout		= JRequest::getVar('layout');
		$this->task			= JRequest::getVar('task');
		$this->id			= JRequest::getVar('id');
	}
	
	function shouldInstantiate() {
		return true;
		// we allow instantiation even if we are not viewing a com_content page,
		// as there are some things we can query without being on that page.
		//( $this->option == 'com_content' );
	}

	
	/* particular methods for this component */
	
	/**
	 * A generic function that knows how to get lots of different info about the current article, category or section.
	 */
	function info( $type ) {
		
		// some special handling, that need not hit the database
		switch( $type ) {
			case 'category_id': return $this->categoryId();
			case 'section_id' : return $this->sectionId();
			case 'pagetype':
			case 'page_type'  : return $this->pageType();
			case 'page_number':
			case 'pagenumber' : return $this->pageNumber(); // pagination control, 0 is 1st page
			default:
		}
		
		if ( $this->option != 'com_content' ) return null;
		// from here on, only serves info relating to com_content pages that we are on now.

		// everything else requires the database
		if ( $this->view == 'article' ) {
			$row = $this->_infoForArticleId( $this->id );
		} else if ( $this->view == 'category' ) {
			$row = $this->_infoForCategoryId( $this->id );
		} else if ( $this->view == 'section' ) {
			$row = $this->_infoForSectionId( $this->id );
		}
		switch( $type ) {
			case 'article_id':
			case 'article_title':
			case 'article_alias':
			case 'article_hits':
			case 'article_version':
			case 'article_created_by':
			case 'article_modified_by':
			case 'article_introtext':
			case 'article_fulltext':

			case 'article_created':
			case 'article_modified':
			case 'article_publishup':
			case 'article_publishdown':
			case 'article_metakeywords':
			case 'article_metadescription':
			case 'article_created_age':
			case 'article_modified_age':
			case 'article_frontpage':

			case 'category_id':
			case 'category_title':
			case 'category_alias':
			case 'category_count':
			
			case 'section_id':
			case 'section_title':
			case 'section_alias':
			case 'section_count':
				return @$row->$type;
			default:
		}
		// are there some more things that we might need to calculate?
		return null;
	}
	
	/**
	 * pageNumber() gives pagination information. 0 = 1st page of pagination; index increases from there.
	 */
	function pageNumber() {
		if ( $this->option != 'com_content' ) return null;
		if ( $this->view == 'article' ) {
			return JRequest::getInt( 'limitstart', 0 );
		}
		$limitstart = JRequest::getInt( 'limitstart', 0 );
		$limit = JRequest::getInt( 'limit', 0 ); // provided by Joomla, not in the URL itself. # items per page.
		if ( $limit == 0 ) return 0; // we're not on a paginated page?
		return (int)( $limit / $limitstart );
	}
	
	
	function pageType() {
		if ( $this->option != 'com_content' ) return null;
		switch ( $this->view ) {
			case 'frontpage':
				return 'frontpage';
			case 'article':
				if ($this->task == 'edit') return 'articleedit';
				if ($this->layout == 'form') return 'articlesubmit';
				return 'article';
			case 'section':
				if ($this->layout == 'blog' ) return 'sectionblog';
				return 'sectionlist';
			case 'category':
				if ($this->layout == 'blog' ) return 'categoryblog';
				return 'categorylist';
			case 'archive':
				return 'archive';
		}
		return '';
	}
		
	/* returns the category id of the list, or the item being displayed.
	 * If the list: this is taken from the URL
	 * If the item: if a category id was in the URL then this is used. Otherwise,
	 *  the category of the item is used.
	 */
	function categoryId() {
		if ( $this->option != 'com_content' ) return null;
		
		$category_id = null;
		
		if ( $this->view == "category" ) {
			/* category list pages (blog or list style) */
			$category_id = (int)$this->id;
		} else if (array_key_exists("catid",$_REQUEST)) {
			/* if the category id is in the URL */
			$category_id = (int)JRequest::getInt("catid",0);
		}
		if ( $category_id === null && $this->view == "article" ) {
			/* if it's an article page without the catid mentioned in the url */
			$row = $this->_infoForArticleId( $this->id );
			$category_id = (int)@$row->category_id;
		}
		return $category_id;
	}
	
	function sectionId() {
		if ( $this->option != 'com_content' ) return null;

		$section_id = null;
		if ( $this->view == "section" ) {
			/* section list pages (blog or list style) */
			$section_id = (int)$this->id;			
		} else if (array_key_exists("sectionid",$_REQUEST)) {
			/* if the section id is in the URL */
			$section_id = (int)JRequest::getInt("sectionid",0);
		}
		if ( $section_id === null && $this->view == "article" ) {
			/* if it's an article page without the sectionid mentioned in the url */
			$row = $this->_infoForArticleId( $this->id );
			$section_id = (int)@$row->section_id;
		}
		if ( $section_id === null && $this->view == "category" ) {
			/* if it's an category page without the sectionid mentioned in the url */
			$row = $this->_infoForCategoryId( (int)$this->id );
			$section_id = (int)@$row->section_id;
		}
		return $section_id;	
	}
	
	function _infoForArticleId( $id ) {
		static $rows = array();
		
		if ( !array_key_exists( $id, $rows ) ) {
			$db			=& JFactory::getDBO();
			$nullDate	= $db->Quote( $db->getNullDate() );
			$my_id		= $db->Quote( $db->getEscaped( (int)$id ) );
			$jnow		=& JFactory::getDate();
			$now		= $db->Quote( $db->getEscaped( $jnow->toMySQL() ) );
			$query		= "SELECT 
				a.id as article_id,
				a.title as article_title,
				a.alias as article_alias,
				a.hits as article_hits,
				a.version as article_version,
				a.created_by as article_created_by,
				a.modified_by as article_modified_by,
				a.introtext as article_introtext,
				a.fulltext as article_fulltext,
				a.created as article_created,
				a.modified as article_modified,
				a.publish_up as article_publishup,
				a.publish_down as article_publishdown,
				a.metakey as article_metakeywords,
				a.metadesc as article_metadescription,
				floor(time_to_sec(timediff(now(),a.created))/60) as article_created_age,
				floor(time_to_sec(timediff(now(),a.modified))/60) as article_modified_age,
				
				a.catid as category_id,
				c.title as category_title,
				c.alias as category_alias,
				c.count as category_count,

				s.id as section_id,
				s.title as section_title,
				s.alias as section_alias,
				s.count as section_count,
				(f.content_id is not null) as article_frontpage
			    FROM #__content a
				LEFT JOIN #__categories c ON a.catid = c.id
				LEFT JOIN #__sections s ON a.sectionid = s.id
				LEFT JOIN #__content_frontpage f ON a.id = f.content_id
				WHERE a.id = $my_id AND a.state = 1
			    AND ( a.publish_up = $nullDate OR a.publish_up <= $now )
		    	AND ( a.publish_down = $nullDate OR a.publish_down >= $now )
			";
			$db->setQuery( $query, 0, 1 );
			$row		= $db->loadObject();
			$rows[$id]	= $row;
		}
		return @$rows[$id];
	}

	function _infoForCategoryId( $id ) {
		static $rows = array();
		
		if ( !array_key_exists( $id, $rows ) ) {
			$db			=& JFactory::getDBO();
			$nullDate	= $db->Quote( $db->getNullDate() );
			$my_id		= $db->Quote( $db->getEscaped( (int)$id ) );
			$query		= "SELECT 
				c.id as category_id,
				c.title as category_title,
				c.alias as category_alias,
				c.count as category_count,
				c.description as category_description,
				
				s.id as section_id,
				s.title as section_title,
				s.alias as section_alias,
				s.count as section_count,
				s.description as section_description

			    FROM #__categories c
				LEFT JOIN #__sections s ON c.section = s.id

				WHERE c.id = $my_id AND c.published = 1
			";
			
			$db->setQuery( $query, 0, 1 );
			$row		= $db->loadObject();
			$rows[$id]	= $row;
		}
		return @$rows[$id];
	}
	
	function _infoForSectionId( $id ) {
		static $rows = array();
		
		if ( !array_key_exists( $id, $rows ) ) {
			$db			=& JFactory::getDBO();
			$nullDate	= $db->Quote( $db->getNullDate() );
			$my_id		= $db->Quote( $db->getEscaped( (int)$id ) );
			$query		= "SELECT 
				s.id as section_id,
				s.title as section_title,
				s.alias as section_alias,
				s.count as section_count,
				s.description as section_description

			    FROM #__sections s

				WHERE s.id = $my_id AND s.published = 1
			";
			$db->setQuery( $query, 0, 1 );
			$row		= $db->loadObject();
			$rows[$id]	= $row;
		}
		return @$rows[$id];
	}

}