<?php

/**
 * @subpackage  system - HOT Login
 * @version     1.5.7
 * @author      Alessandro Argentiero
 * @license     GNU/GPL v.2
 * @see         /plugins/system/hotlogin/LICENSE.php
 */


defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin');


global $LoginModule;

class plgSystemHotlogin extends JPlugin {
    
    var $buffer = '';
	
    function plgSystemHotlogin(&$subject, $config)  {
        parent::__construct($subject, $config);
    }
    function onAfterDispatch()
    {
        global $LoginModule;
        $app =& JFactory::getApplication();
        if ( $app->getName() != 'site' ) return true;        
        if ( $app->getCfg('offline') ) return true;
        $document =& JFactory::getDocument();
        if ( $document->getType() != 'html' ) return true;         
        jimport( 'joomla.application.module.helper' );
        
        $override=$this->params->get( 'override', 'n' );
        $ov_module=$this->params->get( 'ov_module', '1' );
        $ov_module=str_replace('mod_','',$ov_module);
        
        if ($override=='n') {
        $LoginModule = JModuleHelper::getModule( 'login' );
        } else {
        $LoginModule = JModuleHelper::getModule( $ov_module );
        }
        
		if ( !$LoginModule ) return true;
		if ( !property_exists( $LoginModule , 'title' ) ) return true;  
		if ($LoginModule->title !='') {

        $LoginModule->position = 'hotlogin';

        if ($this->params->get('nomootools','n')=='n') { JHTML::_('behavior.mootools'); }  
            
        JHTML::_( 'stylesheet', 'hotlogin.css', 'plugins/system/hotlogin/extra/' );
        JHTML::_( 'script', 'hotlogin.js', 'plugins/system/hotlogin/extra/' );

		$openonguest=$this->params->get( 'openonguest', '0' );
		$user = & JFactory::getUser();
		$guest=$user->get('guest');
		if ( ($guest) && ($openonguest) ) {
			$document->addScriptDeclaration("      var startopen  = true; ");
		} else {
			$document->addScriptDeclaration("      var startopen  = false; ");
		}
        $opacity=$this->params->get( 'opacity', '9' )/10;
        $document->addScriptDeclaration("      var HLopacity  = ".$opacity."; ");
        //$document->addStyleDeclaration('#HLwrapper {height: 1px;}');  
        $this->buffer = plgSystemHotlogin::_renderModule();
		}
 
}

    function _renderModule()
    {
        global $LoginModule, $mainframe;

        $bPath = JPATH_BASE.DS.'plugins'.DS.'system'.DS.'hotlogin'.DS.'tmpl'.DS.'default.php';
        ob_start();
        require_once( $bPath );
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    function onAfterRender()    {
		global $LoginModule;
		
        $app =& JFactory::getApplication();
        if ( $app->getName() != 'site' ) return true;
		if ( !$LoginModule ) return true;		
		if ( !property_exists( $LoginModule , 'title' ) ) return true;      
        if ($LoginModule->title !='') {		
			$pattern = "/<body[^>]{0,}>/";
			preg_match( $pattern, JResponse::getBody(), $matches );
			$bodyOpen = $matches[0];
			JResponse::setBody(str_replace($bodyOpen, $bodyOpen.$this->buffer, JResponse::getBody()));
		}
        return true;
    }
}

