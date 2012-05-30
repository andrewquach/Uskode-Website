<?php
/*
* @name FAQ Slider Plugin 0.9RC5.1
* @type Joomla 1.5 Plugin
* @author Matt Faulds
* @website http://www.trafalgardesign.com
* @email webmaster@trafalgardesign.com
* @copyright Copyright (C) 2009-2010 Trafalgar Design (Trafalgar Press IOM Ltd.). All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*
* FAQ Slider Plugin is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.plugin.plugin');

class plgContentFaqSlider extends JPlugin
{
	private $fsArticleIds = array();
		
	private $tabCount = 1;
	
	private $sliderCount = 1;
	
	public function plgContentFaqSlider( &$subject, $config )
	{
		parent::__construct( $subject, $config ); 									//Call the parent constructor
		$this->_plugin = &JPluginHelper::getPlugin( 'content', 'faqslider' ); 		//Get a reference to this plugin
		$this->params = new JParameter( $this->_plugin->params ); 					//Get the plugin parameters
	}
	
	public function onPrepareContent( &$article, &$params, $limitstart = 0)
	{
		JPlugin::loadLanguage( 'plg_content_faqslider', JPATH_ADMINISTRATOR );		//Load the plugin language file - not in contructor in case plugin called by third party components
		$app = &JFactory::getApplication();
				
		if( $app->isAdmin() ) {
			return true;
		} else {
			if(isset($article->id)) {
				$this->fsArticleIds[] = $article->id;
			}
			
			$regex = '#\{(?:faq|faqslider)\b\s?([^/^}]+)?/?([^/^}]+)?/?(.*?)?\}(.*?)\{/(?:faq|faqslider)\}#s';
			if( preg_match_all( $regex, $article->text,$matches, PREG_SET_ORDER ) > 0 ) {
				//print_r($matches);
				foreach ( $matches as $match ) {
					$article->text = str_replace( $match['0'], $this->faqslider_replacer($match), $article->text );
				}

				if($this->params->get('css',1)) {
					$doc = &JFactory::getDocument();
					$doc->addStyleSheet(JURI::base().'plugins/content/faqslider/faqslider.css');
				}
			}
		}
	}
	
	private function faqslider_replacer ( &$match )
	{
        require_once('plugins'.DS.'content'.DS.'faqslider'.DS.'tdpane.php');
		
		if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
			jimport( 'joomla.error.profiler' );
			$this->p = JProfiler::getInstance('FAQ Slider');
			$this->p->mark('Start');
		}
		
		$app = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = $user->get('aid', 0);
		$database = &JFactory::getDBO();
		
		// allow in-situ editing
		$contentParams	= &$app->getParams('com_content');
		// Create a user access object for the current user
		$access = new stdClass();
		$access->canEdit	= $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn	= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish	= $user->authorize('com_content', 'publish', 'content', 'all');
		
		// set up default values
		$html = '<div class="faqslider">';
		$htmlArray = array();
		$exp = 'N/A';
		$nested = 'N/A';
		$category = 'N/A';
		$section = false;
		$query = 'N/A';
		
		$type = $this->params->get('defaulttype','sliders');
		$source = $this->params->get('defaultsource','cat');
		$option = 'N/A';

		$profileHtml = '';
		$fsErrorMsg = '';
		
		if($match[1]) {
			$source = addslashes(strip_tags(JString::trim(plgContentFaqSlider::cleanNBSP($match[1]))));
		}
		if($match[2]) {
			$type = addslashes(strip_tags(JString::trim(plgContentFaqSlider::cleanNBSP($match[2]))));
		}
		if($match[3]) {
			$option = addslashes(strip_tags(JString::trim(plgContentFaqSlider::cleanNBSP($match[3]))));
		}
		if(strpos($match[4],',') !== false) {
			$matchArray = explode(',',$match[4]);
			foreach($matchArray as &$value) {
				$value = plgContentFaqSlider::getMatchContainer(plgContentFaqSlider::cleanNBSP($value));
			}
			// set for debugging output
			$fs_match_container = $match[4];
		} else {
			$fs_match_container = plgContentFaqSlider::getMatchContainer(plgContentFaqSlider::cleanNBSP($match[4]));
		}
		
		// need these switches to cover for incorrectly set variables - need to clean up
		switch($source)
		{
			case 'tabs':
			case 'sliders':
				$type = $source;
				$source = $this->params->get('defaultsource','cat');
			break;
			
			case 'exp':
			case 'expand':
				$exp = 1;
			break;
			
			case 'nested':
				$nested = 1;
			break;
			
			case 'insert':
				$type = 'insert';
			break;
			
			default:
			break;
		}
		
		switch($type)
		{
			case 'art':
			case 'cat':
			case 'sec':
			case 'section':
			case 'mod':
				$source = $type;
				$type = $this->params->get('defaulttype','sliders');
			break;
			
			case 'exp':
			case 'expand':
				$exp = 1;
				$type = $this->params->get('defaulttype','sliders');
			break;
			
			case 'nested':
				$nested = 1;
				$type = $this->params->get('defaulttype','sliders');
			break;
			
			case 'insert':
				$html = '';
			break;
			
			default:
			break;
		}
		
		switch($option)
		{
			case 'exp':
			case 'expand':
				$exp = 1;
			break;
			
			case 'nested':
				$nested = 1;
			break;
		}
//echo $source;
//echo $type;
//echo $option;
		
		switch($source)
		{
			case 'sec':
			case 'section':
			
				$section = true;
				$section_search = addslashes(strip_tags(JString::trim($match[4])));				
				
				if($tdarticles = plgContentFaqSlider::getSection($fs_match_container,$aid)) {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$this->p->mark('secLoadSuccess');
					}
		    	
			    	foreach($tdarticles as $key => &$tdarticle) {
						if(!in_array($tdarticle->id, $this->fsArticleIds)) {
							$tdarticle->art_count > 1 ? $orphan = 0 : $orphan = 1;
							$this->parseText($tdarticle,$htmlArray,$access,$contentParams,$section,$orphan);
						}
					}
				     
				    //instantiate tabs and sliders
			        ($tabOffset = JRequest::getInt('faqtab')) ? $tabOptions = array('startOffset' => $tabOffset) : $tabOptions = array();
					$tabPane = &TDPane::getInstance('tabs', $tabOptions);
					if($this->params->get('native',1)) {
						$sliderOffset = JRequest::getInt('faqslider',-1);
						if($sliderOffset === -1) $sliderOffset = $this->params->get('startclosed',-1);
						!($this->params->get('startclosed',-1)) ? $startTransition = 1 : $startTransition = 0;
						$options =  array('native'=>$this->params->get('native',1),'allowAllClose'=>'true','startOffset'=>$sliderOffset,'startTransition'=>$startTransition,'opacityTransition'=>$this->params->get('opacity',1),'heightTransition'=>$this->params->get('height',1));
						$slidersPane = &TDPane::getInstance('sliders', $options);
					} else {
						$expandCollapse = 0;
						if($exp == 1 AND $this->params->get('expandcollapse',1)) {
							$expandCollapse = 1;
							//$expandCollapseLinks = JText::sprintf('FS_EXPANDCOLLAPSE_LINKS','|');
							$html .= JText::sprintf('FS_EXPANDCOLLAPSE_LINKS',$this->params->get('ecsep','|'))."<br />";
						}
						$options =  array('native'=>$this->params->get('native',1),'refocus'=>$this->params->get('refocus',1),'expandCollapse'=>$expandCollapse);
						$slidersPane = &TDPane::getInstance('sliders', $options);
					}
					
					$html = plgContentFaqSlider::buildTabsandSliders($html,$htmlArray,$tabPane,$slidersPane);
					
					if($this->params->get('debug',0)) {
						$category = 'N/A';
						$sectionTitleArray = array();
						$query = 'SELECT sec.title'
					 	. ' FROM #__sections as sec'
					 	. ' WHERE sec.'. $fs_match_container
			            . ' AND sec.access <= '. (int)$aid
			            . ' AND sec.published = 1';
			            $database->setQuery($query);
			            $sectionsArray = $database->loadAssocList();
			            foreach($sectionsArray as &$sectionArray) {
			            	foreach($sectionArray as &$sectionTitle) {
						    	$sectionTitleArray[] = $sectionTitle;
				           }
					    }
				    	$sections = implode($sectionTitleArray, ", ");
			   			if($this->params->get('developer',0)) {
							$profileHtml = plgContentFaqSlider::getProfileHtml($p,'End');
			   			}
						$fsErrorMsg = JText::sprintf('FS_DEBUGGING_SEC_SUCCESS',$section_search,$sections);
						$html .= JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					}
				} else {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$profileHtml = plgContentFaqSlider::getProfileHtml($p,'secFail');
					}
					$fsErrorMsg = JText::sprintf('FS_DEBUGGING_SEC_FAIL',$section_search);
					$html = JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					return $html;
				}
			
			break;
			
			case 'cat':
			
				$category = addslashes(strip_tags(JString::trim($match[4])));
				
				if($tdarticles = plgContentFaqSlider::getCategoryArticles($fs_match_container,$category,$aid)) {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$this->p->mark('catLoadSuccess');
					}
					foreach($tdarticles as $tdarticle) {
						if(is_object($tdarticle) AND !in_array($tdarticle->id, $this->fsArticleIds)) {
							$this->parseText($tdarticle,$htmlArray,$access,$contentParams,$section);
						}
					}
				} else {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$profileHtml = plgContentFaqSlider::getProfileHtml($p,'catFail');
					}
					$fsErrorMsg = JText::sprintf('FS_DEBUGGING_CAT_FAIL',$category);
					$html = JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					return $html;					
				}
				
			break;
			
			case 'art':
			
				$article = addslashes(strip_tags(JString::trim($match[4])));
				
				$tdarticles = array();
				if(isset($matchArray)) {
					foreach($matchArray as $key => $temp_fs_match_container) {					
						if($tdarticles[$key] = plgContentFaqSlider::getArticle($temp_fs_match_container,$aid,false)) {
							$art_set = true;
						} else {
							$tdarticles[$key] = $temp_fs_match_container;
						}
					}
				} else {
					if($tdarticles = plgContentFaqSlider::getArticle($fs_match_container,$aid)) {
						$art_set = true;
					}
				}
			   
				if(!isset($art_set)) {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$profileHtml = plgContentFaqSlider::getProfileHtml($p,'artFail');
					}
					$fsErrorMsg = JText::sprintf('FS_DEBUGGING_ART_FAIL',$category);
					$html = JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					return $html;
				} else {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$this->p->mark('artLoadSuccess');
					}
					foreach($tdarticles as $tdarticle) {
						if(is_object($tdarticle) AND !in_array($tdarticle->id, $this->fsArticleIds)) {
							$this->parseText($tdarticle,$htmlArray,$access,$contentParams,$section);
						}
					}
				}
			
			break;
			
			case 'mod':
			
				//$category only set for debugging
				$category = addslashes(strip_tags(JString::trim($match[4])));
	
				$modules = array();
				if(isset($matchArray)) {
					foreach($matchArray as $key => $temp_fs_match_container) {					
						if($modules[$key] = plgContentFaqSlider::getModule($temp_fs_match_container,$type,$aid,false)) {
							$mod_set = true;
						} else {
							$modules[$key] = $temp_fs_match_container;
						}
					}
				} else {
					if($modules = plgContentFaqSlider::getModule($fs_match_container,$type,$aid)) {
						$mod_set = true;
					}
				}
				if(!isset($mod_set)) {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$profileHtml = plgContentFaqSlider::getProfileHtml($p,'modFail');
					}
					$fsErrorMsg = JText::sprintf('FS_DEBUGGING_MOD_FAIL',$fs_match_container);
					$html = JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					return $html;
				} else {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$this->p->mark('modLoadSuccess');
					}
					$htmlArray = plgContentFaqSlider::buildModule($modules,$htmlArray,$option,$type);
				}
				
			break;
			
			case 'inline':
	
				$text = JString::trim($match[4]);
				
				if(JString::strpos($text,"[[") !== false) {
					$text = str_replace(array('[[',']]'),array('{','}'),$text);
				}
			//uncomment below 1 & 2 for possible non-English character fix...	
			//1	$text = htmlentities ($text,ENT_COMPAT,'UTF-8',false);
				
				// no tabs/sliders mode
				if($type == 'html') {
					$html = $text;
					if($this->params->get('debug',0)) {
						if($this->params->get('developer',0)) {
							$profileHtml = plgContentFaqSlider::getProfileHtml($p,'inlineHtml');
						}
						$html .= JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					}
					return $html;
				}
				
				if(class_exists('DOMDOcument')) {
					$matchAll = array(1=>array(),2=>array());
					$dom = new DOMDocument();
					@$dom->loadHTML($text);
					$table = $dom->getElementsByTagName('table')->item(0);
					$trs = $table->getElementsByTagName('tr');			
					for($i=0;$i<$trs->length;$i++) {
						if($trs->item($i)->hasAttribute('class') AND $trs->item($i)->getAttribute('class') == 'faqslider') {
							$dom_match = 1;
							$tds = $trs->item($i)->getElementsByTagName('td');
							for($j=0;$j<$tds->length;$j++) {
								if($tds->item($j)->hasAttribute('class') AND $tds->item($j)->getAttribute('class') == 'title') {
									$matchAll[1][] = $this->getInnerHTML($tds->item(0));
								}
								if($tds->item($j)->hasAttribute('class') AND $tds->item($j)->getAttribute('class') == 'content') {
									$matchAll[2][] = $this->getInnerHTML($tds->item(1));
								}
							}
						}
					}
					unset($dom);
				}
				
				if(!isset($dom_match)) {
					$regex = "#<tr>.*?<td>(.*?)<\/td>.*?<td>([\s\S]*?)<\/td>.*?<\/tr>#su";
					preg_match_all($regex,$text,$matchAll);
				}
				
				if(!empty($matchAll[1]) AND !empty($matchAll[2])) {
					if($option == 'plugins') {
						foreach($matchAll[2] as &$value) {
							$value = JHTML::_('content.prepare', $value);
						}
					}
					$htmlArray = array_combine($matchAll[1],$matchAll[2]);
				} else {
					if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
						$profileHtml = plgContentFaqSlider::getProfileHtml($p,'inlineFail');
					}
					$fsErrorMsg = JText::sprintf('FS_DEBUGGING_INLINE_FAIL');
					$html = JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					return $html;
				}
				//if nested sliders
				//$html = plgContentFaqSlider::buildTabsandSliders($html,$htmlArray,$tabPane,$slidersPane);
			
			break;
			
			default:
			
				if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
					$profileHtml = plgContentFaqSlider::getProfileHtml($p,'sourceFail');
				}
				$fsErrorMsg = JText::sprintf('FS_DEBUGGING_ERROR');
				$html = JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
				return $html;
			
			break;
			
		}
		
		if(!$section AND !empty($htmlArray)) {
			require_once('plugins'.DS.'content'.DS.'faqslider'.DS.'tdpane.php');
			
			if($type == 'insert' AND ($option == 'sliders' OR $option == 'tabs')) {
				$type = $option;
				$option = 'switch fix for insert';
			}
			
			switch($type)
			{
				case 'tabs':
					($tabOffset = JRequest::getInt('faqtab')) ? $tabOptions = array('startOffset' => $tabOffset) : $tabOptions = array();
					$pane = &TDPane::getInstance('tabs', $tabOptions);
					$html .= $pane->startPane( 'faqTabs' );
				break;
				
				case 'sliders':
					$sliderOffset = JRequest::getInt('faqslider',-1);
					if($sliderOffset === -1) $sliderOffset = $this->params->get('startclosed',-1);
					if($this->params->get('native',1)) {
						!($this->params->get('startclosed',-1)) ? $startTransition = 1 : $startTransition = 0;
						$options =  array('native'=>$this->params->get('native',1),'allowAllClose'=>'true','startOffset'=>$sliderOffset,'startTransition'=>$startTransition,'opacityTransition'=>$this->params->get('opacity',1),'heightTransition'=>$this->params->get('height',1));
						$pane = &TDPane::getInstance('sliders', $options);
					} else {
						$expandCollapse = 0;
						if($exp == 1 AND $this->params->get('expandcollapse',1)) {
							$expandCollapse = 1;
							$html .= JText::sprintf('FS_EXPANDCOLLAPSE_LINKS',$this->params->get('ecsep','|'));
						}
						$options =  array('native'=>$this->params->get('native',1),'refocus'=>$this->params->get('refocus',1),'expandCollapse'=>$expandCollapse);
						$pane = &TDPane::getInstance('sliders', $options);
					}
					$html .= $pane->startPane( 'faqSliders' );
				break;
				
				case 'insert':
					foreach($htmlArray as $key => &$value) {
						JString::strpos($option,'title') !== false ? $html .= "<h3>$key</h3>" : $html .= "";
						$html .= "<div>$value</div>";
						$html .= "<br />";
					}
					if($this->params->get('debug',0)) {
						if($this->params->get('developer',0)) {
							$profileHtml = plgContentFaqSlider::getProfileHtml($p,'End');
						}
						isset($this->_vars['categories']) ? $fsErrorMsg = JText::sprintf('FS_DEBUGGING_ART_SUCCESS',$category,$this->_vars['categories']) : $fsErrorMsg = '';
						$html .= JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
					}
					return $html;
				break;
			}
							
			$i = 1;
			foreach($htmlArray as $key => &$value) {
				$html .= $pane->startPanel( $key, "panel$i" );
				$html .= $value;
				$html .= $pane->endPanel();
				$i++;
			}
			
			$html .= $pane->endPane();
		}
				
		if($this->params->get('debug',0)) {
			$fsErrorMsg = '';
			
			if($this->params->get('developer',0)) {
				$profileHtml = plgContentFaqSlider::getProfileHtml($p,'End');
			}
			if(isset($this->_vars['articles'])) {
				$fsErrorMsg .= JText::sprintf('FS_DEBUGGING_ART_SUCCESS',$category,$this->_vars['articles']);
			}
			if(isset($this->_vars['categories'])) {
				$fsErrorMsg .= JText::sprintf('FS_DEBUGGING_CAT_SUCCESS',$category,$this->_vars['categories']);
			}
			if(isset($this->_vars['section'])) {
				$fsErrorMsg .= JText::sprintf('FS_DEBUGGING_SEC_SUCCESS',$category,$this->_vars['section']);
			}
			if(isset($this->_vars['moduleTitles'])) {
				$fsErrorMsg .= JText::sprintf('FS_DEBUGGING_MOD_SUCCESS',$category,$this->_vars['moduleTitles']);
			}
			if(isset($this->_vars['moduleFailTitles'])) {
				$fsErrorMsg .= JText::sprintf('FS_DEBUGGING_MOD_FAIL',$category,$this->_vars['moduleFailTitles']);
			}
			$html .= JText::sprintf('FS_DEBUGGING_TEXT',$fsErrorMsg,$aid,$source,$type,$option,$category,$exp,$nested,$profileHtml);
		}
		$html .= '</div>';
		
		return $html;
	}
	
	private function getProfileHtml(&$p, $mark)
	{
		$this->p->mark($mark);
		$profiles = $this->p->getBuffer();
		unset($this->p->_buffer);
		$profileHtml = '';
		foreach($profiles as $profile) {
			$profileHtml .= $profile."<br />";
		}
		return $profileHtml;
	}
	
	private function getModule($fs_match_container,$type,$aid,$object_list = true)
	{		
		$app = &JFactory::getApplication();
		$database = &JFactory::getDBO();
		
		if($type != 'insert' AND strpos($fs_match_container,'id =') === false) {
			$fs_match_container = 'position'.substr($fs_match_container,5);
		}
		
		$query = 'SELECT id, title, module, position, content, showtitle, control, params'
		. ' FROM #__modules AS m'
		. ' WHERE m.published = 1'
		. ' AND m.access <= '. (int)$aid
		. ' AND m.client_id = '. (int)$app->getClientId()
		. ' AND m.'.$fs_match_container
		. ' ORDER BY m.'. $this->params->get('modordering','ordering');
		
		$database->setQuery($query);
				
		if($object_list ) {
			if(!$return = $database->loadObjectList()) {
				return false;
			}
		} else {
			if(!$return = $database->loadObject()) {
				return false;
			}
		}
		return $return;
	}
	
	private function buildModule(&$modules,&$htmlArray,$option,$type)
	{
		$moduleTitleArray = array();
		$moduleFailArray = array();

		// do some stuff that is found in libraries/joomla/application/module/helper.php
		$total = count($modules);
		for($i = 0; $i < $total; $i++)
		{
			if(isset($modules[$i]->module)) {
				//determineif this is a custom module
				$file					= $modules[$i]->module;
				$custom 				= JString::substr( $file, 0, 4 ) == 'mod_' ?  0 : 1;
				$modules[$i]->user  	= $custom;
				// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
				$modules[$i]->name		= $custom ? $modules[$i]->title : JString::substr( $file, 4 );
				$modules[$i]->style		= null;
				$modules[$i]->position	= strtolower($modules[$i]->position);
			} else {
				$moduleFailArray[] = $modules[$i];
				unset($modules[$i]);
			}
		}

		$document	= &JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$option ? $style = JString::trim($option) : $style = JString::trim($this->params->get('modstyle','table'));
		
		foreach ($modules as $mod)  {
			if($this->params->get('debug',0)) {
				$type == 'insert' ? $moduleTitleArray[] = $mod->title : $moduleTitleArray[] = $mod->position;
			}
			$attribs = array();
			$attribs['style'] = $style;
			$htmlArray[$mod->title] = $renderer->render($mod, $attribs);
		}
		if($this->params->get('debug',0)) {
			$moduleTitleArray = array_unique($moduleTitleArray);
			$this->_vars['moduleTitles'] = implode($moduleTitleArray, ", ");
			$this->_vars['moduleFailTitles'] = implode($moduleFailArray, ", ");
			if($this->params->get('developer',0)) {
				$this->p->mark('modSuccess');
			}
		}
		
		return $htmlArray;
	}
	
	private function getArticle($fs_match_container,$aid,$object_list = true)
	{		
		$app = &JFactory::getApplication();
		$database = &JFactory::getDBO();
		
		$query = 'SELECT art.*, u.name AS author, u.usertype, cat.title AS category, s.title AS section,'
		. ' CASE WHEN CHAR_LENGTH(art.alias) THEN CONCAT_WS(":", art.id, art.alias) ELSE art.id END as slug,'
		. ' CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(":", cat.id, cat.alias) ELSE cat.id END as catslug,'
		. ' g.name AS groups, s.published AS sec_pub, cat.published AS cat_pub, s.access AS sec_access, cat.access AS cat_access '
	 	. ' FROM #__content as art'
		. ' LEFT JOIN #__categories AS cat ON cat.id = art.catid AND cat.access <= '. (int)$aid.' AND cat.published = 1'
		. ' LEFT JOIN #__sections AS s ON s.id = cat.section AND s.scope = "content" AND s.access <= '. (int)$aid.' AND s.published = 1'
		. ' LEFT JOIN #__users AS u ON u.id = art.created_by'
		. ' LEFT JOIN #__groups AS g ON art.access = g.id'
        . ' WHERE art.'. $fs_match_container
        . ' AND art.access <= '. (int)$aid
        . ' AND art.state = 1'
        . ' ORDER BY art.'. $this->params->get('ordering','ordering');
           
	    $database->setQuery( $query );
	//    echo '<pre>'.$database->_sql.'</pre>';
	    if($object_list) {
			if(!$return = $database->loadObjectList()) {
				return false;
			}
		} else {
			if(!$return = $database->loadObject()) {
				return false;
			}
		}
		if($this->params->get('debug',0)) {
	    	if(is_array($return)) {
	    		foreach($return as $article) {
			    	isset($this->_vars['articles']) ? $this->_vars['articles'] .= ', '.$article->title : $this->_vars['articles'] = $article->title;
	    		}
           	} else {
           		isset($this->_vars['articles']) ? $this->_vars['articles'] .= ', '.$return->title : $this->_vars['articles'] = $return->title;
           	}	    	
		}
		return $return;
	}
	
	private function getCategoryArticles($fs_match_container,$category,$aid)
	{		
		$app = &JFactory::getApplication();
		$database = &JFactory::getDBO();
							
		if(JString::strpos('uncategorised',strtolower($category)) !== false) {
			// need to check on uncategorised method
			$query = 'SELECT art.*, u.name AS author, u.usertype, "uncategorised" AS category, "uncategorised" AS section,'
			. ' CASE WHEN CHAR_LENGTH(art.alias) THEN CONCAT_WS(":", art.id, art.alias) ELSE art.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(":", cat.id, cat.alias) ELSE cat.id END as catslug,'
			. ' g.name AS groups, s.published AS sec_pub, cat.published AS cat_pub, s.access AS sec_access, cat.access AS cat_access'
		 	. ' FROM #__content as art'
			. ' LEFT JOIN #__users AS u ON u.id = art.created_by'
			. ' LEFT JOIN #__groups AS g ON art.access = g.id'
		 	. ' WHERE art.catid = 0'
            . ' AND art.access <= '. (int)$aid
            . ' AND art.state = 1'
            . ' ORDER BY art.'. $this->params->get('ordering','ordering');
		} else {
			$query = 'SELECT art.*, u.name AS author, u.usertype, cat.title AS category, s.title AS section,'
			. ' CASE WHEN CHAR_LENGTH(art.alias) THEN CONCAT_WS(":", art.id, art.alias) ELSE art.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(":", cat.id, cat.alias) ELSE cat.id END as catslug,'
			. ' g.name AS groups, s.published AS sec_pub, cat.published AS cat_pub, s.access AS sec_access, cat.access AS cat_access '
		 	. ' FROM #__content as art'
			. ' LEFT JOIN #__categories AS cat ON cat.id = art.catid AND cat.access <= '. (int)$aid.' AND cat.published = 1'
			. ' LEFT JOIN #__sections AS s ON s.id = cat.section AND s.scope = "content" AND s.access <= '. (int)$aid.' AND s.published = 1'
			. ' LEFT JOIN #__users AS u ON u.id = art.created_by'
			. ' LEFT JOIN #__groups AS g ON art.access = g.id'
            . ' WHERE cat.'. $fs_match_container
            . ' AND art.access <= '. (int)$aid
            . ' AND art.state = 1'
            . ' ORDER BY art.'. $this->params->get('ordering','ordering');
		}
           
	    $database->setQuery( $query );
	//    echo '<pre>'.$database->_sql.'</pre>';
		if(!$return = $database->loadObjectList()) {
			return false;
		}
		
		if($this->params->get('debug',0)) {
	    	$this->_vars['categories'] = $return[0]->category;	    	
		}
		
		return $return;
	}
	
	private function getSection($fs_match_container,$aid)
	{		
		$app = &JFactory::getApplication();
		$database = &JFactory::getDBO();
		
		$query = 'SELECT art.*, COUNT(aa.id) AS art_count, u.name AS author, u.usertype,'
				. ' cat.title AS category, cat.description AS cat_desc, cat.ordering AS cat_ordering, s.title AS section,'
				. ' CASE WHEN CHAR_LENGTH(art.alias) THEN CONCAT_WS(":", art.id, art.alias) ELSE art.id END as slug,'
				. ' CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(":", cat.id, cat.alias) ELSE cat.id END as catslug,'
				. ' g.name AS groups, s.published AS sec_pub, cat.published AS cat_pub, s.access AS sec_access, cat.access AS cat_access '
			 	. ' FROM #__content as art'
				. ' LEFT JOIN #__categories AS cat ON cat.id = art.catid AND cat.access <= '. (int)$aid.' AND cat.published = 1'
			 	. ' LEFT JOIN #__content AS aa ON aa.catid = cat.id AND aa.access <= '. (int)$aid.' AND aa.state = 1'
				. ' LEFT JOIN #__sections AS s ON s.id = cat.section AND s.scope = "content" AND s.access <= '. (int)$aid.' AND s.published = 1'
				. ' LEFT JOIN #__users AS u ON u.id = art.created_by'
				. ' LEFT JOIN #__groups AS g ON art.access = g.id'
	            . ' WHERE s.'. $fs_match_container
	            . ' AND art.access <= '. (int)$aid
	            . ' AND art.state = 1'
	            . ' GROUP BY art.id'
	            . ' ORDER BY cat.'.$this->params->get('catordering','ordering').', art.'. $this->params->get('ordering','ordering');
	            
	    $database->setQuery( $query );
	 /* echo "<pre>";
	    print_r( $database->_sql);
	    echo "</pre>";*/
		if(!$return = $database->loadObjectList()) {
			return false;
		}
		
		if($this->params->get('debug',0)) {
	    	$this->_vars['section'] = $return[0]->section;	    	
		}
		
		return $return;
	}
					
	private function buildTabsandSliders($html,$htmlArray,$tabPane,$slidersPane)
	{
		/*echo "<pre>";
		print_r($htmlArray);
		echo "</pre>";*/
				
		//build tabs and sliders
		$html .= $tabPane->startPane( 'faqTabs' );
		$i = $this->tabCount;
		$j = $this->sliderCount;
		foreach($htmlArray as $catTitle => &$catData) {
			$html .= $tabPane->startPanel( $catTitle, "tabPanel$i" );
			foreach($catData as $catDesc => &$artData) {
				$this->params->get('catdesc',1) ? $html .= $catDesc.'<br /><br />' : $html .= '<br />';
				//$this->params->get('expandcollapse',1) ? $html .= $expandCollapseLinks : $html .= '';
				$html .= $slidersPane->startPane( "faqSliders$i" );
				if($this->params->get('orphan_art',1) AND count($artData) == 1) {
					foreach($artData as $artTitle => $artText) {
						$html .= $artText;
					}
				} else {
					foreach($artData as $artTitle => $artText) {
						$html .= $slidersPane->startPanel( $artTitle, "sliderPanel$j" );
						$html .= $artText;
						$html .= $slidersPane->endPanel();
						$j++;
					}
				}
			}
			$html .= $slidersPane->endPane();
			$html .= "<br />";
			$html .= $tabPane->endPanel();
			$i++;
		}
		$html .= $tabPane->endPane();
		$this->tabCount = $i;
		$this->sliderCount = $j;
		
		return $html;
	}
	
	private function buildReadmore(&$tdarticle)
	{
		// just in case plugin not called in com_content
		require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

		if($this->params->get('show_readmore',0)) {
			// need to make this not split words in the middle.
			$intro_len = JString::strlen(strip_tags($tdarticle->introtext));
			if($intro_len > $this->params->get('readmore_limit',500)) {
				$text = substr($tdarticle->introtext, 0, $this->params->get('readmore_limit',500));
			} else {
				$text = $tdarticle->introtext;
			}
			$tdarticle->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($tdarticle->slug, $tdarticle->catslug, $tdarticle->sectionid));
			if($tdarticle->fulltext != '' OR $intro_len > $this->params->get('readmore_limit',500) OR $this->params->get('force_readmore',0)) {
				$text .= '<a href="'.$tdarticle->readmore_link.'" class="fsreadon">'.$this->params->get('readmore_link','Read More...').'</a>';
			}
		} else {
			$tdarticle->readmore_link = '';
			$text = $tdarticle->introtext.' '.$tdarticle->fulltext;
		}
		return $text;
	}
	
	/**
     * Escapes a value for output
     *
     *if escaping mechanism is one of htmlspecialchars or htmlentities, uses
     * {@link $_encoding} setting.
     *
     * @param  mixed $var The output to escape.
     * @return mixed The escaped value.
     */
    private function escape($var)
    {
    	return call_user_func('htmlspecialchars', $var, ENT_COMPAT, 'UTF-8');
   }
	
	private function parseText(&$tdarticle,&$htmlArray,&$access,&$contentParams,$section,$orphan=0)
	{
		// Get the page/component configuration
		$tdarticle->parameters = clone($contentParams);

		// Merge article parameters into the page configuration
		$aparams = new JParameter($tdarticle->attribs);
		$tdarticle->parameters->merge($aparams);
	  	
	  	$tdarticle->parameters->set('show_title', ($this->params->get('show_title', '0') != '2' ? $this->params->get('show_title', '0') : $tdarticle->parameters->get('show_title')));
		$tdarticle->parameters->set('link_titles', ($this->params->get('link_titles', '0') != '2' ? $this->params->get('link_titles', '0') : $tdarticle->parameters->get('link_titles')));
	  	$tdarticle->parameters->set('show_intro', ($this->params->get('show_intro', '0') != '2' ? $this->params->get('show_intro', '0') : $tdarticle->parameters->get('show_intro')));
	  	$tdarticle->parameters->set('show_section', ($this->params->get('show_section', '0') != '2' ? $this->params->get('show_section', '0') : $tdarticle->parameters->get('show_section')));
	  	$tdarticle->parameters->set('link_section', ($this->params->get('link_section', '0') != '2' ? $this->params->get('link_section', '0') : $tdarticle->parameters->get('link_section')));
	  	$tdarticle->parameters->set('show_category', ($this->params->get('show_category', '0') != '2' ? $this->params->get('show_category', '0') : $tdarticle->parameters->get('show_category')));
	  	$tdarticle->parameters->set('link_category', ($this->params->get('link_category', '0') != '2' ? $this->params->get('link_category', '0') : $tdarticle->parameters->get('link_category')));
	  	$tdarticle->parameters->set('show_author', ($this->params->get('show_author', '0') != '2' ? $this->params->get('show_author', '0') : $tdarticle->parameters->get('show_author')));
	  	$tdarticle->parameters->set('show_create_date', ($this->params->get('show_create_date', '0') != '2' ? $this->params->get('show_create_date', '0') : $tdarticle->parameters->get('show_create_date')));
	  	$tdarticle->parameters->set('show_modify_date', ($this->params->get('show_modify_date', '1') != '2' ? $this->params->get('show_modify_date', '1') : $tdarticle->parameters->get('show_modify_date')));
	  	//$tdarticle->parameters->set('show_item_navigation', ($this->params->get('show_item_navigation', '0') != '2' ? $this->params->get('show_item_navigation', '0') : $tdarticle->parameters->get('show_item_navigation')));
	  	$tdarticle->parameters->set('show_vote', ($this->params->get('show_vote', '0') != '2' ? $this->params->get('show_vote', '0') : $tdarticle->parameters->get('show_vote')));
	  	//$tdarticle->parameters->set('show_icons', ($this->params->get('show_icons', '0') != '2' ? $this->params->get('show_icons', '0') : $tdarticle->parameters->get('show_icons')));
	  	$tdarticle->parameters->set('show_pdf_icon', ($this->params->get('show_pdf_icon', '0') != '2' ? $this->params->get('show_pdf_icon', '0') : $tdarticle->parameters->get('show_pdf_icon')));
	  	$tdarticle->parameters->set('show_print_icon', ($this->params->get('show_print_icon', '0') != '2' ? $this->params->get('show_print_icon', '0') : $tdarticle->parameters->get('show_print_icon')));
	  	$tdarticle->parameters->set('show_email_icon', ($this->params->get('show_email_icon', '0') != '2' ? $this->params->get('show_email_icon', '0') : $tdarticle->parameters->get('show_email_icon')));
		
		$tdarticle->text = $this->buildReadmore($tdarticle);
		
		//orphan article over-rides
		if($this->params->get('orphan_art',1) AND $orphan) {
			if($this->params->get('orphan_art_title',1)) { $tdarticle->parameters->set('show_title',1); }
			if($this->params->get('orphan_art_link_titles',1)) { $tdarticle->parameters->set('link_titles',1); }
		}
		
		$tdarticle->text = $this->buildArticle($tdarticle,$access);

		if($this->params->get('processart',0)) {
			$section ? $htmlArray[$tdarticle->category][$tdarticle->cat_desc][$tdarticle->title] = JHTML::_('content.prepare', $tdarticle->text) : $htmlArray[$tdarticle->title] = JHTML::_('content.prepare', $tdarticle->text);
		} else {
			$section ? $htmlArray[$tdarticle->category][$tdarticle->cat_desc][$tdarticle->title] = $tdarticle->text : $htmlArray[$tdarticle->title] = $tdarticle->text;
		}
		if($this->params->get('debug',0) AND $this->params->get('developer',0)) {
			$this->p->mark( $this->params->get('processart',0) ? 'artPluginTextSuccess' : 'artTextSuccess');
		}
	}
	
	private function buildArticle(&$tdarticle,&$access)
	{
		// in case used in component not com_content
		JHTML::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers');
		
		$html = '';

		if($access->canEdit OR $tdarticle->parameters->get('show_title') OR $tdarticle->parameters->get('show_pdf_icon') OR $tdarticle->parameters->get('show_print_icon') OR $tdarticle->parameters->get('show_email_icon')) {
			$html .= '<table class="contentpaneopen '.$this->escape($tdarticle->parameters->get('pageclass_sfx')).'">';
			$html .= '<tr>';
			if ($tdarticle->parameters->get('show_title')) { 
				$html .= '<td class="contentheading '.$this->escape($tdarticle->parameters->get('pageclass_sfx')).'" width="100%">';
				if ($tdarticle->parameters->get('link_titles') AND $tdarticle->readmore_link != '') {
					$html .= '<a href="'.$tdarticle->readmore_link.'" class="contentpagetitle '.$this->escape($tdarticle->parameters->get('pageclass_sfx')).'">'.$this->escape($tdarticle->title).'</a>';
				} else {
					$html .= $this->escape($tdarticle->title); 
				}
				$html .= '</td>';
			} 
			if ($tdarticle->parameters->get('show_pdf_icon')) {
				$html .= '<td align="right" width="100%" class="buttonheading">'.JHTML::_('icon.pdf',  $tdarticle, $tdarticle->parameters, $access).'</td>';
			}
	
			if ( $tdarticle->parameters->get( 'show_print_icon' )) {
				$html .= '<td align="right" width="100%" class="buttonheading">'.JHTML::_('icon.print_popup',  $tdarticle, $tdarticle->parameters, $access).'</td>';
			}
	
			if ($tdarticle->parameters->get('show_email_icon')) {
				$html .= '<td align="right" width="100%" class="buttonheading">'.JHTML::_('icon.email',  $tdarticle, $tdarticle->parameters, $access).'</td>';
			}
			 
			if ($access->canEdit AND $this->params->get('can_edit', '0')) {
				$html .= '<td align="right" width="100%" class="buttonheading">'.JHTML::_('icon.edit', $tdarticle, $tdarticle->parameters, $access).'</td>';
			}
			$html .= '</tr>';
			$html .= '</table>';
		}
		
		if (!$tdarticle->parameters->get('show_intro')) {
			//echo $tdarticle->event->afterDisplayTitle;
		}
		 //echo $tdarticle->event->beforeDisplayContent;
		$html .= '<table class="contentpaneopen '.$this->escape($tdarticle->parameters->get('pageclass_sfx')).'">';
		if (($tdarticle->parameters->get('show_section') AND $tdarticle->sectionid) OR ($tdarticle->parameters->get('show_category') AND $tdarticle->catid)) {
			$html .= '<tr>';
			$html .= '<td>';
				if ($tdarticle->parameters->get('show_section') AND $tdarticle->sectionid AND isset($tdarticle->section)) {
					$html .= '<span>';
					if ($tdarticle->parameters->get('link_section')) { 
						$html .= '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($tdarticle->sectionid)).'">'; 
					} 
					$html .= $this->escape($tdarticle->section); 
					if ($tdarticle->parameters->get('link_section')) { 
						$html .= '</a>'; 
					} 
					if ($tdarticle->parameters->get('show_category')) { 
						$html .= ' - '; 
					} 
					$html .= '</span>';
				}
				if ($tdarticle->parameters->get('show_category') AND $tdarticle->catid) { 
					$html .= '<span>';
					if ($tdarticle->parameters->get('link_category')) { 
						$html .= '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($tdarticle->catslug, $tdarticle->sectionid)).'">'; 
					} 
					$html .= $this->escape($tdarticle->category); 
					if ($tdarticle->parameters->get('link_category')) { 
						$html .= '</a>'; 
					} 
					$html .= '</span>';
				} 
			$html .= '</td>';
			$html .= '</tr>';
		}
		if (($tdarticle->parameters->get('show_author')) AND ($tdarticle->author != "")) { 
			$html .= '<tr>';
			$html .= '<td valign="top">';
			$html .= '<span class="small">'.JText::printf( 'Written by', ($this->escape($tdarticle->created_by_alias) ? $this->escape($tdarticle->created_by_alias) : $this->escape($tdarticle->author)) ).'</span>&nbsp;&nbsp;</td>';
			$html .= '</tr>';
		} 
		
		if ($tdarticle->parameters->get('show_create_date')) { 
			$html .= '<tr>';
			$html .= '<td valign="top" class="createdate">'.JHTML::_('date', $tdarticle->created, JText::_('DATE_FORMAT_LC2')).'</td>';
			$html .= '</tr>';
		} 
		
		if ($tdarticle->parameters->get('show_url') AND $tdarticle->urls) { 
			$html .= '<tr>';
			$html .= '<td valign="top"><a href="http:// echo $tdarticle->urls ; " target="_blank">'.$this->escape($tdarticle->urls).'</a></td>';
			$html .= '</tr>';
		} 
		
		$html .= '<tr>';
		$html .= '<td valign="top">';
		if (isset ($tdarticle->toc)) { 
			 $html .= $tdarticle->toc; 
		} 
		$html .= $tdarticle->text; 
		$html .= '</td>';
		$html .= '</tr>';
		
		if ($tdarticle->parameters->get('show_modify_date')) { 
			$html .= '<tr>';
			$html .= '<td class="modifydate">'.JText::sprintf('LAST_UPDATED2', JHTML::_('date', intval($tdarticle->modified) != 0 ? $tdarticle->modified : $tdarticle->created, JText::_('DATE_FORMAT_LC2'))).'</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		$html .= '<span class="article_separator">&nbsp;</span>';
		 //echo $tdarticle->event->afterDisplayContent;
	 
		return $html;
	}
	
	private function getMatchContainer($value)
	{
		if(preg_match('/[\D]/',trim($value)) === 0) {
			$fs_match_container = 'id = \''.(int)$value.'\'';
		} elseif(strpos($value,'==')) {
			$value = JString::str_ireplace('==','',$value);
			$fs_match_container = "title = '$value'";
		} else {
			$fs_match_container = "title LIKE '%$value%'";
		}
		
		return $fs_match_container;
	}
	
	private function cleanNBSP($string)
	{
		$string = str_replace('&nbsp;',' ',$string);
		
		return $string;
	}
	
	private function getInnerHTML($e)
	{
		$inner = '';
		foreach ($e->childNodes as $child) {
			$inner .= $e->ownerDocument->saveXML($child);
		}
		return $inner;
	}
}