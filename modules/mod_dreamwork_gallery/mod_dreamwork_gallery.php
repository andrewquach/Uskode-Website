<?php
/**
* @Copyright Copyright (C) 2011- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/ 
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once (dirname(__FILE__).DS.'noimage_functions.php');


$bannerWidth                   = intval($params->get( 'bannerWidth', 710 ));
$bannerHeight                  = intval($params->get( 'bannerHeight', 630 ));

$curr_h_sym = '#';
$curr_h_val = 'ffffff';
$curr_pinput = $params->get( 'backgroundColor', $curr_h_sym . $curr_h_val);
if (strtolower(substr($curr_pinput, 0, 2)) == '0x') {
	$curr_hex = substr($curr_pinput, 2);
} elseif (substr($curr_pinput, 0, 1) == '#') {
	$curr_hex = substr($curr_pinput, 1);
} else {
	$curr_hex = $curr_pinput;
}
if (strspn($curr_hex, "0123456789abcdefABCDEF") == 6) {
	$curr_pinput = $curr_h_sym . $curr_hex;
} else {
	$curr_pinput = $curr_h_sym . $curr_h_val;
}
$backgroundColor = $curr_pinput;

$wmode                 = trim($params->get( 'wmode', 'transparent' ));
$xml_fname = $params->get( 'xml_fname');
$catppv_id = 'xml/' . $xml_fname;

$module_path = dirname(__FILE__).DS;
if (!is_dir($module_path . 'xml/')) {
	@mkdir($module_path . 'xml/', 0777);
}

if (!function_exists('create_jmdrmw_files')) {
function create_jmdrmw_files($params, &$catppv_id)
{
$xml_fname = $params->get( 'xml_fname');
$xml_data = '<?xml version="1.0" encoding="iso-8859-1"?>
<data>
	<settings>';
	
$xml_data .= '<background>
	<color code="'.$params->get( 'backgroundColor', '#FFFFFF').'" alpha="'.$params->get( 'bgcolor_alpha', '1').'" />	</background><viewer><base>
			<color code="'.$params->get( 'base_color', '#cccccc').'" alpha="'.$params->get( 'basecolor_alpha', '1').'" />
		</base><effect>
			<type>'.$params->get( 'effect_type', '1').'</type>
			<time>'.$params->get( 'effect_time', '1').'</time>
			<closingType>'.$params->get( 'closing_type', 'default').'</closingType>
				<preloader>
					<color code="'.$params->get( 'preloader_color', '#202020').'" alpha="'.$params->get( 'preloader_alpha', '1').'" />
				</preloader>
		</effect>

		<description position="'.$params->get( 'desc_position', 'top').'">
				
				<effect>'.$params->get( 'desc_effect', '3').'</effect>
				
				<base>
					<color code="'.$params->get( 'desc_basecolor', '#020202').'" alpha="'.$params->get( 'desc_alpha', '0.7').'" />
				</base>
				
				<text>
					<size>'.$params->get( 'desctext_size', '12').'</size>
					<color code="'.$params->get( 'desctext_color', '#FFFFFF').'" alpha="'.$params->get( 'desctext_alpha', '1').'" />
				</text>
		</description>

		<priceTag enabled="no">
				
				<tag width="120" height="120">assets/tags/flower_green.png</tag>
				
				<position>TL</position>
				
				<symbol>$</symbol>
				
				<label>
					<size>20</size>
					<color code="#FFFFFF" alpha="1" />
				</label>
				
			</priceTag>

			<controls position="'.$params->get( 'control_position', 'bottom').'">
				
				<base>
					<color code="'.$params->get( 'control_basecolor', '#524B46').'" alpha="'.$params->get( 'control_basecoloralpha', '0.75').'" />
				</base>
				
				<arrow>
					
					<base>
						<up>
							<color code="'.$params->get( 'arrow_baseup_color', '#FBFBFB').'" alpha="'.$params->get( 'arrow_baseup_alpha', '0').'" />
						</up>
						<over>
							<color code="'.$params->get( 'arrow_baseover_color', '#FBFBFB').'" alpha="'.$params->get( 'arrow_baseover_alpha', '0.6').'" />
						</over>
						<down>
							<color code="'.$params->get( 'arrow_baseover_color', '#FBFBFB').'" alpha="'.$params->get( 'arrow_baseover_alpha', '0.6').'" />
						</down>
					</base>
					
					<arrow>
						<up>
							<color code="'.$params->get( 'arrow_up_color', '#FBFBFB').'" alpha="'.$params->get( 'arrow_up_alpha', '1').'" />
						</up>
						<over>
							<color code="'.$params->get( 'arrow_over_color', '#020202').'" alpha="'.$params->get( 'arrow_over_alpha', '1').'" />
						</over>
						<down>
							<color code="'.$params->get( 'arrow_over_color', '#020202').'" alpha="'.$params->get( 'arrow_over_alpha', '1').'" />
						</down>
					</arrow>
					
				</arrow>
				
				<display>
					
					<base>
						<up>
							<color code="'.$params->get( 'display_baseup_color', '#FBFBFB').'" alpha="'.$params->get( 'display_baseup_alpha', '0').'" />
						</up>
						<over>
							<color code="'.$params->get( 'display_baseover_color', '#FBFBFB').'" alpha="'.$params->get( 'display_baseover_alpha', '0.6').'" />
						</over>
						<down>
							<color code="'.$params->get( 'display_baseover_color', '#FBFBFB').'" alpha="'.$params->get( 'circle_up_alpha', '1').'" />
						</down>
					</base>
					
					<circle>
						<up>
							<color code="'.$params->get( 'circle_up_color', '#FBFBFB').'" alpha="'.$params->get( 'circle_up_alpha', '1').'" />
						</up>
						<over>
							<color code="'.$params->get( 'circle_over_color', '#020202').'" alpha="'.$params->get( 'circle_over_alpha', '1').'" />
						</over>
						<down>
							<color code="'.$params->get( 'circle_over_color', '#020202').'" alpha="'.$params->get( 'circle_over_alpha', '1').'" />
						</down>
					</circle>
					
					<symbol>
						<up>
							<color code="'.$params->get( 'symbal_up_color', '#020202').'" alpha="'.$params->get( 'symbal_up_alpha', '1').'" />
						</up>
						<over>
							<color code="'.$params->get( 'symbal_over_color', '#FBFBFB').'" alpha="'.$params->get( 'symbal_over_alpha', '1').'" />
						</over>
						<down>
							<color code="'.$params->get( 'symbal_over_color', '#FBFBFB').'" alpha="'.$params->get( 'symbal_over_alpha', '1').'" />
						</down>
					</symbol>
					
				</display>
				
				<autoplay displayTime="'.$params->get( 'autoplay_display_time', '4').'" default="'.$params->get( 'autoplay', 'on').'">
					
					<base>
						<up>
							<color code="'.$params->get( 'autoplay_baseup_color', '#FBFBFB').'" alpha="'.$params->get( 'autoplay_baseup_alpha', '0').'" />
						</up>
						<over>
							<color code="'.$params->get( 'autoplay_baseover_color', '#FBFBFB').'" alpha="'.$params->get( 'autoplay_baseover_alpha', '0.6').'" />
						</over>
						<down>
							<color code="'.$params->get( 'autoplay_baseover_color', '#FBFBFB').'" alpha="'.$params->get( 'autoplay_baseover_alpha', '0.6').'" />
						</down>
					</base>
					
					<animation>
						<up>
							<color code="'.$params->get( 'animation_up_color', '#FBFBFB').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
						</up>
						<over>
							<color code="'.$params->get( 'animation_over_color', '#020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
						</over>
						<down>
							<color code="'.$params->get( 'animation_over_color', '#020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
						</down>
					</animation>
					
					<symbol>
						<up>
							<color code="'.$params->get( 'autoplay_sym_up_color', '#FBFBFB').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
						</up>
						<over>
							<color code="'.$params->get( 'autoplay_sym_over_color', '#020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
						</over>
						<down>
							<color code="'.$params->get( 'autoplay_sym_over_color', '#020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
						</down>
					</symbol>
					
				</autoplay>
				
			</controls>
			
		</viewer>

		<thumbsPanel position="'.$params->get( 'thumbpanel_position', 'bottom').'" height="'.$params->get( 'thumbpanel_height', '76').'">
			
			<base>
				<color code="'.$params->get( 'thumbpanel_base_color', '#2C2C2C').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
			</base>
			
			<thumb width="'.$params->get( 'thumb_width', '86').'" height="'.$params->get( 'thumb_height', '64').'">
				
				<base>
					<up>
						<color code="'.$params->get( 'thumb_baseup_color', '#FEFEFE').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
					</up>
					<over>
						<color code="'.$params->get( 'thumb_mouseover_color', '#0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
					</over>
					<down>
						<color code="'.$params->get( 'thumb_mouseover_color', '#0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
					</down>
					<selected>
						<color code="'.$params->get( 'thumb_mouseover_color', '#0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
					</selected>
				</base>
				
				<preloader>
					<color code="'.$params->get( 'thumb_preloader_color', '#202020').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
				</preloader>
				
			</thumb>
			
			<tooltip>
				
				<base>
					<color code="'.$params->get( 'tooltip_base_color', '#FBFBFB').'" alpha="'.$params->get( 'tooltip_base_alpha', '0.8').'" />
				</base>
				
				<label>
					<color code="'.$params->get( 'tooltip_label_color', '#202020').'" alpha="'.$params->get( 'tooltip_label_alpha', '1').'" />
				</label>
				
			</tooltip>
			
			<arrow>
				<up>
					<color code="'.$params->get( 'thumb_mouseover_color', '#0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
				</up>
				<over>
					<color code="'.$params->get( 'thumb_mouseover_color', '#0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
				</over>
				<down>
					<color code="'.$params->get( 'thumb_mouseover_color', '#0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
				</down>
			</arrow>
			
		</thumbsPanel>
		</settings>
		';

$caption        = trim($params->get('caption', '' )); 
$caption_arr    = explode("\n",$caption);
$thumbs        = trim($params->get('thumbs', '' )); 
$thumbs_arr    = explode("\n",$thumbs);
$pic        = trim($params->get('pic', '' )); 
$pic_arr    = explode("\n",$pic);
$imgdsc        = trim($params->get('imgdsc', '' ));
$imgdsc_arr    = explode("\n",$imgdsc);

$imgurl        = trim($params->get('imgurl', '' ));
$imgurl_arr    = explode("\n",$imgurl);

$before_dsc = '';
$after_dsc = '';
$before_name = '';
$after_name = '';

////////// start : noimage code //////////////

$exist_url = JURI::root();
$server_path = getCurUrl($exist_url);

//////////////////////////////////////////
$xml_data .= ' <content>';

foreach ($pic_arr as $curr_k=>$curr_pic) {
//$xml_category_data .= '<picture>';
$xml_data .= ' <item interactive="'.$params->get( 'item_interactive', 'no').'" zoom="'.$params->get( 'item_zoom', 'enabled').'">';

$xml_data .= ' <main scale="'.$params->get( 'mainImg_scale', 'yes').'">'.trim($server_path.$pic_arr[$curr_k]).'</main>';

$xml_data .= ' <thumb scale="'.$params->get( 'thumbImg_scale', 'yes').'">'.trim($server_path.$thumbs_arr[$curr_k]).'</thumb>';

if ($params->get('show_tooltip', 'on') == 'on') {
	$xml_data .= '<label><![CDATA['.trim($caption_arr[$curr_k]).']]></label>';
}else{
	$xml_data .= '<label><![CDATA[]]></label>';
}

if ($params->get('show_desc', 'on') == 'on') {
$xml_data .= '<description><![CDATA['.trim($imgdsc_arr[$curr_k]).']]></description>';
}else{
	$xml_data .= '<description><![CDATA[]]></description>';
}

$xml_data .= '<link window="'.$params->get( 'target', 'new').'">'.trim($imgurl_arr[$curr_k]).'</link>';

$xml_data .= '<price>
				<regular></regular>
				<updated></updated>
			</price>';

$xml_data .= '</item>';
}
$curr_h_sym = '0x';
$curr_h_val = '003366';
$curr_pinput = $params->get( 'slideshow_bg_color', $curr_h_sym . $curr_h_val);
if (strtolower(substr($curr_pinput, 0, 2)) == '0x') {
	$curr_hex = substr($curr_pinput, 2);
} elseif (substr($curr_pinput, 0, 1) == '#') {
	$curr_hex = substr($curr_pinput, 1);
} else {
	$curr_hex = $curr_pinput;
}
if (strspn($curr_hex, "0123456789abcdefABCDEF") == 6) {
	$curr_pinput = $curr_h_sym . $curr_hex;
} else {
	$curr_pinput = $curr_h_sym . $curr_h_val;
}
$slideshow_bg_color = $curr_pinput;

$curr_h_sym = '0x';
$curr_h_val = '003366';
$curr_pinput = $params->get( 'titlebar_bg_color', $curr_h_sym . $curr_h_val);
if (strtolower(substr($curr_pinput, 0, 2)) == '0x') {
	$curr_hex = substr($curr_pinput, 2);
} elseif (substr($curr_pinput, 0, 1) == '#') {
	$curr_hex = substr($curr_pinput, 1);
} else {
	$curr_hex = $curr_pinput;
}
if (strspn($curr_hex, "0123456789abcdefABCDEF") == 6) {
	$curr_pinput = $curr_h_sym . $curr_hex;
} else {
	$curr_pinput = $curr_h_sym . $curr_h_val;
}
$titlebar_bg_color = $curr_pinput;

$curr_h_sym = '0x';
$curr_h_val = '003366';
$curr_pinput = $params->get( 'thumbnail_bg_color', $curr_h_sym . $curr_h_val);
if (strtolower(substr($curr_pinput, 0, 2)) == '0x') {
	$curr_hex = substr($curr_pinput, 2);
} elseif (substr($curr_pinput, 0, 1) == '#') {
	$curr_hex = substr($curr_pinput, 1);
} else {
	$curr_hex = $curr_pinput;
}
if (strspn($curr_hex, "0123456789abcdefABCDEF") == 6) {
	$curr_pinput = $curr_h_sym . $curr_hex;
} else {
	$curr_pinput = $curr_h_sym . $curr_h_val;
}
$thumbnail_bg_color = $curr_pinput;

$curr_h_sym = '0x';
$curr_h_val = '3399FF';
$curr_pinput = $params->get( 'tooltip_bg_color', $curr_h_sym . $curr_h_val);
if (strtolower(substr($curr_pinput, 0, 2)) == '0x') {
	$curr_hex = substr($curr_pinput, 2);
} elseif (substr($curr_pinput, 0, 1) == '#') {
	$curr_hex = substr($curr_pinput, 1);
} else {
	$curr_hex = $curr_pinput;
}
if (strspn($curr_hex, "0123456789abcdefABCDEF") == 6) {
	$curr_pinput = $curr_h_sym . $curr_hex;
} else {
	$curr_pinput = $curr_h_sym . $curr_h_val;
}
$tooltip_bg_color = $curr_pinput;

$xml_data .= '</content></data>';

$module_path = dirname(__FILE__).DS;

if($xml_fname==''){
	$catppv_id .= md5($xml_data);
}

if (!file_exists($module_path . $catppv_id . '.swf')) {
	copy($module_path . 'mod_dreamwork_gallery.swf', $module_path . $catppv_id . '.swf');

	///////// set chmod 0644 for creating .swf file  if server is not windows
	$os_string = php_uname('s');
	$cnt = substr_count($os_string, 'Windows');
	if($cnt ==0){
		@chmod($module_path . $catppv_id . '.swf', 0644);
	}

}

$xml_categories_filename = $module_path . $catppv_id . '.xml';
//if (!file_exists($xml_categories_filename)) {
	$xml_categories_file = fopen($xml_categories_filename,'w');
	fwrite($xml_categories_file, $xml_data);

///////// set chmod 0777 for creating .xml file  if server is not windows
	$os_string = php_uname('s');
	$cnt = substr_count($os_string, 'Windows');
	if($cnt ==0){
		@chmod($xml_categories_filename, 0777);
	}

	fclose($xml_categories_file);
//}
}
}

create_jmdrmw_files($params, $catppv_id);

$exist_url = JURI::root();
$server_path = getCurUrl($exist_url);

?>

<div style="text-align:center;">
<script type="text/javascript">AC_FL_RunContent = 0;</script>
<script src="<?php echo $server_path?>modules/mod_dreamwork_gallery/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript">

	if (AC_FL_RunContent == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
			'width', '<?php echo $bannerWidth;?>',
			'height', '<?php echo $bannerHeight; ?>',
			'src', '<?php echo $server_path?>modules/mod_dreamwork_gallery/<?php echo $catppv_id; ?>',
			'quality', 'high',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', '<?php echo $wmode;?>',
			'devicefont', 'false',
			'id', 'gallery',
			'bgcolor', '<?php echo $backgroundColor; ?>',
			'name', 'gallery',
			'menu', 'true',
			'allowFullScreen', 'true',
			'flashVars', 'data=<?php echo $server_path?>modules/mod_dreamwork_gallery/<?php echo $catppv_id; ?>.xml',
			'allowScriptAccess','sameDomain',
			'movie', '<?php echo $server_path?>modules/mod_dreamwork_gallery/<?php echo $catppv_id; ?>',
			'salign', ''
			); //end AC code
	}
</script>
<noscript>

<object data="<?php echo $server_path.'modules/mod_dreamwork_gallery/'.$catppv_id.'.swf';?>"  type="application/x-shockwave-flash" width="<?php echo $bannerWidth;?>" height="<?php echo $bannerHeight; ?>" align="middle" id="gallery">
<param name="allowScriptAccess" value="sameDomain" />
<param name="wmode" value="<?php echo $wmode;?>" />
<param name="flashVars" value="data=<?php echo $server_path?>modules/mod_dreamwork_gallery/<?php echo $catppv_id; ?>.xml"/>
<param name="allowFullScreen" value="false" />	
<param name="movie" value="<?php echo $server_path.'modules/mod_dreamwork_gallery/'.$catppv_id.'.swf';?>" />
<param name="quality" value="high" />
<param name="bgcolor" value="<?php echo $backgroundColor; ?>" />
	</object>
</noscript>

</div>