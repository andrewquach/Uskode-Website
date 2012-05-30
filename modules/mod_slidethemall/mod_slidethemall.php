<?php
/**
* @module		Slide Them All! - Image slider for Jooma!
* @copyright	Copyright (C) 2010 artetics.com
* @license		GPL
*/

defined('_JEXEC') or die('Restricted access');
error_reporting(E_ERROR);
$document = &JFactory::getDocument();

$slider = $params->get('slider', 1);
$path = $params->get('path', '');
$thumbWidth = $params->get('thumbWidth', 75);
$thumbHeight = $params->get('thumbHeight', 75);
$containerWidth = $params->get('containerWidth', 300);
$containerHeight = $params->get('containerHeight', 300);
$lightbox = $params->get('lightbox', 0);
$speed = $params->get('speed', 3000);
$moduleId = $module->id;
$sortBy = $params->get('sortBy', 'name');
$sortDir = $params->get('sortDir', 'asc');

if (!function_exists('artSTAFileAscSort')) {
  function artSTAFileAscSort($a, $b) {
    list ($anum, $aalph) = explode ('.', $a);
    list ($bnum, $balph) = explode ('.', $b);
    
    if ($anum == $bnum) return strcmp($aalph, $balph);
    return $anum < $bnum ? -1 : 1;
  }
}

if (!function_exists('artSTAFileDescSort')) {
  function artSTAFileDescSort($a, $b) {
    list ($anum, $aalph) = explode ('.', $a);
    list ($bnum, $balph) = explode ('.', $b);
    
    if ($anum == $bnum) return !strcmp($aalph, $balph);
    return $anum > $bnum ? -1 : 1;
  }
}

if (!function_exists('isImage')) {
	function isImage($fileName) {
		$extensions = array('.jpeg', '.jpg', '.gif', '.png', '.bmp', '.tiff', '.tif', '.ico', '.rle', '.dib', '.pct', '.pict');
		$extension = substr($fileName, strrpos($fileName,"."));
		if (in_array(strtolower($extension), $extensions)) return true;
		return false;
	}
}

if ($path) {
	$directory_stream = @ opendir (JPATH_SITE.DS . $path . DS) or die ("Could not open a directory stream for <i>" . JPATH_SITE . DS . $path . DS . "</i>");
	$filelist = array();
  if ($sortBy == 'date') {
    while($entry = readdir($directory_stream)){
        if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
            $ctime = filectime(JPATH_SITE.DS . $path . DS . $entry) . ',' . $entry;
            $filelist[$ctime] = $entry;
        }
    }
    if ($sortDir == 'desc') {
      krsort($filelist);
    } else {
      ksort($filelist);
    }
  } else {
    while ($entry = readdir ($directory_stream)) {
      if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
        $filelist[] = $entry;
      }
    }
  }
    if ($sortBy == 'name') {
      if ($sortDir == 'asc') {
        usort ($filelist, 'artSTAFileAscSort');
      } else {
        usort ($filelist, 'artSTAFileDescSort');
      }
    }
    reset ($filelist);
    $file_handle = @fopen(JPATH_SITE . DS . $path . DS . "slidethemall.txt", "rb");
    $descriptionArray = array();
    if ($file_handle) {
      while (!feof($file_handle) ) {
        $line_of_text = fgets($file_handle);
					$parts = explode('=', htmlspecialchars($line_of_text, ENT_QUOTES));
					$str = '';
					$partsNumber = count($parts);
					for ($i = 1; $i < $partsNumber; $i++) {
						$str .= $parts[$i];
						if ($i != $partsNumber - 1) {
							$str .= '=';
						}
					}
					$str = str_replace('"', "'", $str);
					$descriptionArray[$parts[0]] = html_entity_decode($str);

      }
      fclose($file_handle);
    }

  
  if ($slider == 1) {
    $document->addScript(JURI::root() . 'modules/mod_slidethemall/js/jquery.1.4.js');
    $document->addScript(JURI::root() . 'modules/mod_slidethemall/js/script.js');
    $document->addStylesheet(JURI::root() . 'modules/mod_slidethemall/css/style.css');

?>
<style type="text/css">
.msg_slideshow {
	width:<?php echo $containerWidth; ?>px;
	height:<?php echo $containerHeight; ?>px;
}
.msg_wrapper {
	width:<?php echo $containerWidth; ?>px;
	height:<?php echo $containerHeight; ?>px;
}
</style>
<div id="msg_slideshow" class="msg_slideshow">
	<div id="msg_wrapper" class="msg_wrapper"></div>
  <div id="msg_text" class="msg_text"></div>
	<div id="msg_controls" class="msg_controls">
		<a href="#" id="msg_grid" class="msg_grid"></a>
		<a href="#" id="msg_prev" class="msg_prev"></a>
		<a href="#" id="msg_pause_play" class="msg_pause"></a>
		<a href="#" id="msg_next" class="msg_next"></a>
    <?php
    if ($lightbox) {
    $document->addCustomTag('<script type="text/javascript">window.slideThemAllLightbox = true;</script>');
    ?>
    <style type="text/css">
      .msg_controls {
        right:-130px;
      }
    </style>
    <a href="#" id="msg_lightbox" class="msg_lightbox" rel="minimal-slider"></a>
    <?php
      switch ($lightbox) {
        case 'artsexylightbox':
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/jquery.easing.1.3.js');
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/sexylightbox.v2.3.4.jquery.min.js');
          $document->addStyleSheet( JURI::root() . 'plugins/content/artsexylightbox/css/sexylightbox.css' );
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/jquery.nc.js');
          ?>
          <script type="text/javascript" charset="utf-8">asljQuery(function(){asljQuery(document).ready(function(){SexyLightbox.initialize({"imagesdir": "<?php echo JURI::BASE(); ?>plugins/content/artsexylightbox/images","find": "minimal-slider"});})});</script>
          <?php
        break;
        case 'artcolorbox':
          $document->addScript( JURI::root() . 'plugins/content/artcolorbox/js/jquery.colorbox-min.js' );
          $document->addScript( JURI::root() . 'plugins/content/artcolorbox/js/jquery.nc.js' );
          $document->addStyleSheet( JURI::root() . 'plugins/content/artcolorbox/css/themes/1/colorbox.css' );
          ?>
          <script type="text/javascript" charset="utf-8">acbjQuery(document).ready(function(){acbjQuery("a[rel^='minimal-slider']").colorbox({});});</script>
          <?php
        break;
        case 'artprettyphoto':
          $document->addScript(JURI::root() . 'plugins/content/artprettyphoto/js/jquery.prettyPhoto.js');
          $document->addStyleSheet( JURI::root() . 'plugins/content/artprettyphoto/css/prettyPhoto.css');
          ?>
          <script type="text/javascript" charset="utf-8">jQuery(document).ready(function(){jQuery("a[rel^='minimal-slider']").prettyPhoto({theme:"facebook"});});</script>
          <?php
        break;
        case 'artnicebox':
          $document->addScript( JURI::root() . 'plugins/content/artnicebox/js/jquery.nc.js' );
          $document->addScript( JURI::root() . 'plugins/content/artnicebox/js/nflightbox.js' );
          $document->addStyleSheet( JURI::root() . 'plugins/content/artnicebox/css/nf.lightbox.css' ); 
          ?>
          <script type="text/javascript" charset="utf-8">anbjQuery(document).ready(function(){anbjQuery("a[rel^='minimal-slider']").lightBox({})});</script>
          <?php
        break;
        default:
        break;
      }
    }
    ?>
	</div>
	<div id="msg_thumbs" class="msg_thumbs">
		<div class="msg_thumb_wrapper">
<?php
	while ((list ($key, $entry) = each ($filelist))) {
		if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
			echo '<a href="#"><img width="' . $thumbWidth . '" height="' . $thumbHeight . '" src="' . JURI::root() . $path . '/' . $entry . '" alt="' . JURI::root() . $path . '/' . $entry . '"';
      if ($descriptionArray[$entry]) {
        $descriptionArray[$entry] = str_replace("'", "\'", $descriptionArray[$entry]);
        echo ' rel="' . $descriptionArray[$entry] . '" ';
      }
      echo '/></a>';
		}
	}
?>
		</div>
		<a href="#" id="msg_thumb_next" class="msg_thumb_next"></a>
		<a href="#" id="msg_thumb_prev" class="msg_thumb_prev"></a>
		<a href="#" id="msg_thumb_close" class="msg_thumb_close"></a>
		<span class="msg_loading"></span>
	</div>
</div>
<?php
  } elseif ($slider == 2) {
    $document->addScript(JURI::root() . 'modules/mod_slidethemall/js/jquery.1.4.js');
    $document->addScript(JURI::root() . 'modules/mod_slidethemall/js/coin-slider.min.js');
    $document->addStylesheet(JURI::root() . 'modules/mod_slidethemall/css/coin-slider-styles.css');
    
    if ($lightbox) {
      switch ($lightbox) {
        case 'artsexylightbox':
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/jquery.easing.1.3.js');
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/sexylightbox.v2.3.4.jquery.min.js');
          $document->addStyleSheet( JURI::root() . 'plugins/content/artsexylightbox/css/sexylightbox.css' );
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/jquery.nc.js');
          ?>
          <script type="text/javascript" charset="utf-8">asljQuery(function(){asljQuery(document).ready(function(){SexyLightbox.initialize({"imagesdir": "<?php echo JURI::BASE(); ?>plugins/content/artsexylightbox/images","find": "coin-slider"});})});</script>
          <?php
        break;
        case 'artcolorbox':
          $document->addScript( JURI::root() . 'plugins/content/artcolorbox/js/jquery.colorbox-min.js' );
          $document->addScript( JURI::root() . 'plugins/content/artcolorbox/js/jquery.nc.js' );
          $document->addStyleSheet( JURI::root() . 'plugins/content/artcolorbox/css/themes/1/colorbox.css' );
          ?>
          <script type="text/javascript" charset="utf-8">acbjQuery(document).ready(function(){acbjQuery("a[rel^='coin-slider']").colorbox({});});</script>
          <?php
        break;
        case 'artprettyphoto':
          $document->addScript(JURI::root() . 'plugins/content/artprettyphoto/js/jquery.prettyPhoto.js');
          $document->addStyleSheet( JURI::root() . 'plugins/content/artprettyphoto/css/prettyPhoto.css');
          ?>
          <script type="text/javascript" charset="utf-8">jQuery(document).ready(function(){jQuery("a[rel^='coin-slider']").prettyPhoto({theme:"facebook"});});</script>
          <?php
        break;
        case 'artnicebox':
          $document->addScript( JURI::root() . 'plugins/content/artnicebox/js/jquery.nc.js' );
          $document->addScript( JURI::root() . 'plugins/content/artnicebox/js/nflightbox.js' );
          $document->addStyleSheet( JURI::root() . 'plugins/content/artnicebox/css/nf.lightbox.css' ); 
          ?>
          <script type="text/javascript" charset="utf-8">anbjQuery(document).ready(function(){anbjQuery("a[rel^='coin-slider']").lightBox({})});</script>
          <?php
        break;
        default:
        break;
      }
    }
    ?>
    
    <div id="coin_slider<?php echo $moduleId;?>" class="coin_slider">
      <?php
        while ((list ($key, $entry) = each ($filelist))) {
          if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
            echo '<a rel="coin-slider" href="' . JURI::root() . $path . '/' . $entry . '"><img src="' . JURI::root() . $path . '/' . $entry . '" alt="' . JURI::root() . $path . '/' . $entry . '"/>';
            if ($descriptionArray[$entry]) {
              echo '<span>' . $descriptionArray[$entry] . '</span>';
            }
            echo '</a>';
          }
        }
        $document->addCustomTag('<script type="text/javascript">jQuery(document).ready(function() {jQuery("#coin_slider' . $moduleId . '").coinslider({width:' . $containerWidth . ', height: ' . $containerHeight . ', delay: ' . $speed . '});});</script>');
      ?>
    </div>
<?php
  } else if ($slider == 3) {
    $document->addScript(JURI::root() . 'modules/mod_slidethemall/js/jquery.1.4.js');
    $document->addScript(JURI::root() . 'modules/mod_slidethemall/js/jqFancyTransitions.min.js');
    ?>
    
    <div id="jqfancy<?php echo $moduleId;?>" class="jqfancy">
      <?php
        while ((list ($key, $entry) = each ($filelist))) {
          if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
            if (array_key_exists($entry, $descriptionArray)) {
              echo '<img src="' . JURI::root() . $path . '/' . $entry . '" alt="' . $descriptionArray[$entry] . '"/>';
            } else {
              echo '<img src="' . JURI::root() . $path . '/' . $entry . '" />';
            }
          }
        }
        $document->addCustomTag('<script type="text/javascript">jQuery(document).ready(function() {jQuery("#jqfancy' . $moduleId . '").jqFancyTransitions({width:' . $containerWidth . ', height:' . $containerHeight . '});});</script>');
      ?>
    </div>
    <?php
  }
  }
?>