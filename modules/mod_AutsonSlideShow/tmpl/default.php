<?php 

/**

* @package   Autson Skitter SlideShow

* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : info@autson.com, www.autson.com

**/

defined('_JEXEC') or die('Restricted access'); 

$doc =& JFactory::getDocument();

$show_jquery=$params->get('show_jquery');

$load=$params->get('load');

$doc->addStyleSheet ( 'modules/mod_AutsonSlideShow/css/skitter.css' );

if($show_jquery=="yes" && $load=="onload")

{

$doc->addScript("modules/mod_AutsonSlideShow/js/jquery-1.5.2.min.js");

}

else{}

/*

$doc->addScript("modules/mod_AutsonSlideShow/js/jquery.easing.1.3.js");

$doc->addScript("modules/mod_AutsonSlideShow/js/jquery.animate-colors-min.js");

$doc->addScript("modules/mod_AutsonSlideShow/js/jquery.skitter.min.js");

*/

$uri 		=& JFactory::getURI();

$url= $uri->root();

$moduleclass_sfx    	= 	$params->get( 'moduleclass_sfx');    

$slidewidth 			= 	$params->get( 'slidewidth');

$slideheight		= 	$params->get( 'slideheight');

$navigation		= 	$params->get( 'navigation', '0' );

$timeinterval		= 	$params->get( 'timeinterval', '2500' );

$velocity		= 	$params->get( 'velocity');

$border		= 	$params->get( 'border');

$bordercolor		= 	$params->get( 'bordercolor');

$backgroundcolor		= 	$params->get( 'backgroundcolor');
$align		= 	$params->get( 'align');


$labelcolor		= 	$params->get( 'labelcolor');

$desccolor		= 	$params->get( 'desccolor');

$labelsize		= 	$params->get( 'labelsize');

$descsize		= 	$params->get( 'descsize');

$titlefont		= 	$params->get( 'titlefont');

$descfont		= 	$params->get( 'descfont');

$arrowspos		= 	$params->get( 'arrowspos');

$numberspos		= 	$params->get( 'numberspos');

$bgout		= 	$params->get( 'bgout');

$colorout		= 	$params->get( 'colorout');

$bgover		= 	$params->get( 'bgover');

$colorover		= 	$params->get( 'colorover');

$bgactive		= 	$params->get( 'bgactive');

$coloractive		= 	$params->get( 'coloractive');

$arrows=$params->get('arrows');

$hidetools=$params->get('hidetools');

$navigation=$params->get('navigation');

if($descfont=="arial")

{

$descfont='Arial, Helvetica, sans-serif';

}

if($titlefont=="arial")

{

$titlefont='Arial, Helvetica, sans-serif';

}

if($descfont=="tnr")

{

$descfont='"Times New Roman", Times, serif';

}

if($titlefont=="tnr")

{

$titlefont='"Times New Roman", Times, serif';

}

if($descfont=="cn")

{

$descfont='"Courier New", Courier, monospace';

}

if($titlefont=="cn")

{

$titlefont='"Courier New", Courier, monospace';

}

if($descfont=="georgia")

{

$descfont='Georgia, "Times New Roman", Times, serif';

}

if($titlefont=="georgia")

{

$titlefont='Georgia, "Times New Roman", Times, serif';

}

if($descfont=="verdana")

{

$descfont='Verdana, Arial, Helvetica, sans-serif';

}

if($descfont=="verdana")

{

$titlefont='Verdana, Arial, Helvetica, sans-serif';

}

if($navigation=="numbers")

{

$numbers="numbers:true,";

$dots="false";

$thumbs="false";

}

else if($navigation=="dots")

{

$dots="true";

$numbers="numbers:false,";

$thumbs="false";

$margin='

.box_skitter {margin-bottom:40px;}

';

}

else if($navigation=="thumbs")

{

$thumbs="true";

$dots="false";

$numbers="";

}

else if($navigation=="hide")

{

$thumbs="false";

$dots="false";

$numbers="numbers:false,";

}

if($arrows=="yes")

{

$arrows="true";

}

else

{$arrows="false";}

if($hidetools=="yes")

{

$hidetools="true";

}

else

{$hidetools="false";}

$img1=$params->get('img1');

$img2=$params->get('img2');

$img3=$params->get('img3');

$img4=$params->get('img4');

$img5=$params->get('img5');

$img6=$params->get('img6');

$img7=$params->get('img7');

$img8=$params->get('img8');

$img9=$params->get('img9');

$img10=$params->get('img10');
$img11=$params->get('img11');

$img12=$params->get('img12');

$img13=$params->get('img13');

$img14=$params->get('img14');

$img15=$params->get('img15');

$img16=$params->get('img16');

$img17=$params->get('img17');

$img18=$params->get('img18');

$img19=$params->get('img19');

$img20=$params->get('img20');

$label1=$params->get('label1');

$label2=$params->get('label2');

$label3=$params->get( 'label3');

$label4=$params->get('label4');

$label5=$params->get('label5');

$label6=$params->get( 'label6');

$label7=$params->get('label7');

$label8=$params->get('label8');

$label9=$params->get( 'label9');

$label10=$params->get('label10');
$label11=$params->get('label11');

$label12=$params->get('label12');

$label13=$params->get( 'label13');

$label14=$params->get('label14');

$label15=$params->get('label15');

$label16=$params->get( 'label16');

$label17=$params->get('label17');

$label18=$params->get('label18');

$label19=$params->get( 'label19');

$label20=$params->get('label20');

$desc1=$params->get('desc1');

$desc2=$params->get('desc2');

$desc3=$params->get('desc3');

$desc4=$params->get('desc4');

$desc5=$params->get('desc5');

$desc6=$params->get('desc6');

$desc7=$params->get('desc7');

$desc8=$params->get('desc8');

$desc9=$params->get('desc9');

$desc10=$params->get('desc10');
$desc11=$params->get('desc11');

$desc12=$params->get('desc12');

$desc13=$params->get('desc13');

$desc14=$params->get('desc14');

$desc15=$params->get('desc15');

$desc16=$params->get('desc16');

$desc17=$params->get('desc17');

$desc18=$params->get('desc18');

$desc19=$params->get('desc19');

$desc20=$params->get('desc20');

$link1=$params->get( 'link1');

$link2=$params->get( 'link2');

$link3=$params->get( 'link3');

$link4=$params->get( 'link4');

$link5=$params->get( 'link5');

$link6=$params->get( 'link6');

$link7=$params->get( 'link7');

$link8=$params->get( 'link8');

$link9=$params->get( 'link9');

$link10=$params->get( 'link10');
$link11=$params->get( 'link11');

$link12=$params->get( 'link12');

$link13=$params->get( 'link13');

$link14=$params->get( 'link14');

$link15=$params->get( 'link15');

$link16=$params->get( 'link16');

$link17=$params->get( 'link17');

$link18=$params->get( 'link18');

$link19=$params->get( 'link19');

$link20=$params->get( 'link20');

$image_style =array("random","cube","cubeRandom","block","cubeStop","cubeHide","cubeSize","horizontal","showBars","cubeSpread","showBarsRandom","tube","fade","fadeFour","paralell","blind","blindHeight","blindWidth","directionTop","directionBottom","directionRight","directionLeft","cubeStopRandom","circles","circlesInside","circlesRotate","glassCube","glassBlock");

$imageeffect	= 	$params->get( "menu_style", '0' );

$imageindex = $imageeffect;

$javascript="

var ass".$module->id." = jQuery.noConflict();

ass".$module->id."(document).ready(function(){

ass".$module->id."('.box_skitter_large".$module->id."').skitter(

{

dots: ".$dots.",

fullscreen: false,

label: true,

interval:".$timeinterval.",

navigation:".$arrows.",

label:true, 

".$numbers."

hideTools:".$hidetools.",

thumbs: ".$thumbs.",

velocity:".$velocity.",

animation: \"".$image_style[$imageindex]."\",

animateNumberOut: {backgroundColor:'".$bgout."', color:'".$colorout."'},

animateNumberOver: {backgroundColor:'".$bgover."', color:'".$colorover."'},

animateNumberActive: {backgroundColor:'".$bgactive."', color:'".$coloractive."'}

}

);

});	

";	

if($load=="onload")

{

$doc->addScriptDeclaration($javascript);

}

/***********************************LABELS **********************************************/

if($label1=="" && $desc1=="")

{$label1='';}

else

{

$label1='<div class="label_text">

<hass>'.$label1.'</hass><pass>'.$desc1.'</pass>

</div>';

}

if($label2=="")

{$label2='';}

else

{

$label2='<div class="label_text">

<hass>'.$label2.'</hass><pass>'.$desc2.'</pass>

</div>';

}

if($label3=="")

{$label3='';}

else

{

$label3='<div class="label_text">

<hass>'.$label3.'</hass><pass>'.$desc3.'</pass>

</div>';

}

if($label4=="")

{$label4='';}

else

{

$label4='<div class="label_text">

<hass>'.$label4.'</hass><pass>'.$desc4.'</pass>

</div>';

}	

if($label5=="")

{$label5='';}

else

{

$label5='<div class="label_text">

<hass>'.$label5.'</hass><pass>'.$desc5.'</pass>

</div>';

}	

if($label6=="")

{$label6='';}

else

{

$label6='<div class="label_text">

<hass>'.$label6.'</hass><pass>'.$desc6.'</pass>

</div>';

}	

if($label7=="")

{$label7='';}

else

{

$label7='<div class="label_text">

<hass>'.$label7.'</hass><pass>'.$desc7.'</pass>

</div>';

}	

if($label8=="")

{$label8='';}

else

{

$label8='<div class="label_text">

<hass>'.$label8.'</hass><pass>'.$desc8.'</pass>

</div>';

}	

if($label9=="")

{$label9='';}

else

{

$label9='<div class="label_text">

<hass>'.$label9.'</hass><pass>'.$desc9.'</pass>

</div>';

}	

if($label10=="")

{$label10='';}

else

{

$label10='<div class="label_text">

<hass>'.$label10.'</hass><pass>'.$desc10.'</pass>

</div>';

}	

if($label11=="")

{$label11='';}

else

{

$label11='<div class="label_text">

<hass>'.$label11.'</hass><pass>'.$desc11.'</pass>

</div>';

}	


if($label12=="")

{$label12='';}

else

{

$label12='<div class="label_text">

<hass>'.$label12.'</hass><pass>'.$desc12.'</pass>

</div>';

}	

if($label13=="")

{$label13='';}

else

{

$label13='<div class="label_text">

<hass>'.$label13.'</hass><pass>'.$desc13.'</pass>

</div>';

}	


if($label14=="")

{$label14='';}

else

{

$label14='<div class="label_text">

<hass>'.$label14.'</hass><pass>'.$desc14.'</pass>

</div>';

}	


if($label15=="")

{$label15='';}

else

{

$label15='<div class="label_text">

<hass>'.$label15.'</hass><pass>'.$desc15.'</pass>

</div>';

}	

if($label16=="")

{$label16='';}

else

{

$label16='<div class="label_text">

<hass>'.$label16.'</hass><pass>'.$desc16.'</pass>

</div>';

}	

if($label17=="")

{$label17='';}

else

{

$label17='<div class="label_text">

<hass>'.$label17.'</hass><pass>'.$desc17.'</pass>

</div>';

}	

if($label18=="")

{$label18='';}

else

{

$label18='<div class="label_text">

<hass>'.$label18.'</hass><pass>'.$desc18.'</pass>

</div>';

}	

if($label19=="")

{$label19='';}

else

{

$label19='<div class="label_text">

<hass>'.$label19.'</hass><pass>'.$desc19.'</pass>

</div>';

}	


if($label20=="")

{$label20='';}

else

{

$label20='<div class="label_text">

<hass>'.$label20.'</hass><pass>'.$desc20.'</pass>

</div>';

}	

/*****************************************LABELS END*************************************************/

/****************************************IMAGES ********************************************************/	
$numwidth=0;
if($img1=="")

{

$image1="";

}	

else if ($link1=="")

{

$image1='

<li>

<img src="'.$img1.'" class="'.$image_style[$imageindex].'" />

'.$label1.'

</li>';

$numwidth+=16;
}

else

{

$image1='

<li>

<a href="'.$link1.'"><img src="'.$img1.'" class="'.$image_style[$imageindex].'" /></a>

'.$label1.'

</li>';

$numwidth+=16;
}	

if($img2=="")

{

$image2="";

}	

else if ($link2=="")

{

$numwidth+=16;
$image2='

<li>

<img src="'.$img2.'" class="'.$image_style[$imageindex].'" />

'.$label2.'

</li>

';}

else 

{

$numwidth+=16;
$image2='

<li>

<a href="'.$link2.'"><img src="'.$img2.'" class="'.$image_style[$imageindex].'" /></a>

'.$label2.'

</li>

';}

if($img3=="")

{

$image3="";

}	

else if ($link3=="")

{

$numwidth+=16;
$image3='

<li>

<img src="'.$img3.'" class="'.$image_style[$imageindex].'" />

'.$label3.'

</li>

';}

else

{

$numwidth+=16;
$image3='

<li>

<a href="'.$link3.'"><img src="'.$img3.'" class="'.$image_style[$imageindex].'" /></a>

'.$label3.'

</li>

';}

if($img4=="")

{

$image4="";

}	

else if ($link4=="")

{

$numwidth+=16;
$image4='

<li>

<img src="'.$img4.'" class="'.$image_style[$imageindex].'" />

'.$label4.'

</li>

';}

else

{

$numwidth+=16;
$image4='

<li>

<a href="'.$link4.'"><img src="'.$img4.'" class="'.$image_style[$imageindex].'" /></a>

'.$label4.'

</li>

';}

if($img5=="")

{

$image5="";

}	

else if ($link5=="")

{

$numwidth+=16;
$image5='

<li>

<img src="'.$img5.'" class="'.$image_style[$imageindex].'" />

'.$label5.'

</li>

';}

else

{

$numwidth+=16;
$image5='

<li>

<a href="'.$link5.'"><img src="'.$img5.'" class="'.$image_style[$imageindex].'" /></a>

'.$label5.'

</li>

';}

if($img6=="")

{

$image6="";

}	

else if ($link6=="")

{

$numwidth+=16;
$image6='

<li>

<img src="'.$img6.'" class="'.$image_style[$imageindex].'" />

'.$label6.'

</li>

';}

else

{

$numwidth+=16;
$image6='

<li>

<a href="'.$link6.'"><img src="'.$img6.'" class="'.$image_style[$imageindex].'" /></a>

'.$label6.'

</li>

';}

if($img7=="")

{

$image7="";

}	

else if ($link7=="")

{

$numwidth+=16;
$image7='

<li>

<img src="'.$img7.'" class="'.$image_style[$imageindex].'" />

'.$label7.'

</li>

';}

else

{

$numwidth+=16;
$image7='

<li>

<a href="'.$link7.'"><img src="'.$img7.'" class="'.$image_style[$imageindex].'" /></a>

'.$label7.'

</li>

';}

if($img8=="")

{

$image8="";

}	

else if ($link8=="")

{

$numwidth+=16;
$image8='

<li>

<img src="'.$img8.'" class="'.$image_style[$imageindex].'" />

'.$label8.'

</li>

';}

else

{

$numwidth+=16;
$image8='

<li>

<a href="'.$link8.'"><img src="'.$img8.'" class="'.$image_style[$imageindex].'" /></a>

'.$label8.'

</li>

';}

if($img9=="")

{

$image9="";

}	

else if ($link9=="")

{

$numwidth+=16;
$image9='

<li>

<img src="'.$img9.'" class="'.$image_style[$imageindex].'" />

'.$label9.'

</li>

';}

else

{

$numwidth+=16;
$image9='

<li>

<a href="'.$link9.'"><img src="'.$img9.'" class="'.$image_style[$imageindex].'" /></a>

'.$label9.'

</li>

';}

if($img10=="")

{

$image10="";

}	

else if ($link10=="")

{

$numwidth+=18.5;
$image10='

<li>

<img src="'.$img10.'" class="'.$image_style[$imageindex].'" />

'.$label10.'

</li>

';}

else

{

$numwidth+=18.5;
$image10='

<li>

<a href="'.$link10.'"><img src="'.$img10.'" class="'.$image_style[$imageindex].'" /></a>

'.$label10.'

</li>

';}



if($img11=="")

{

$image11="";

}	

else if ($link11=="")

{

$numwidth+=18.5;
$image11='

<li>

<img src="'.$img11.'" class="'.$image_style[$imageindex].'" />

'.$label11.'

</li>

';}

else

{

$numwidth+=18.5;
$image11='

<li>

<a href="'.$link11.'"><img src="'.$img11.'" class="'.$image_style[$imageindex].'" /></a>

'.$label11.'

</li>

';}


if($img12=="")

{

$image12="";

}	

else if ($link12=="")

{

$numwidth+=18.5;
$image12='

<li>

<img src="'.$img12.'" class="'.$image_style[$imageindex].'" />

'.$label12.'

</li>

';}

else

{

$numwidth+=18.5;
$image12='

<li>

<a href="'.$link12.'"><img src="'.$img12.'" class="'.$image_style[$imageindex].'" /></a>

'.$label12.'

</li>

';}

if($img13=="")

{

$image13="";

}	

else if ($link13=="")

{

$numwidth+=18.5;
$image13='

<li>

<img src="'.$img13.'" class="'.$image_style[$imageindex].'" />

'.$label13.'

</li>

';}

else

{

$numwidth+=18.5;
$image13='

<li>

<a href="'.$link13.'"><img src="'.$img13.'" class="'.$image_style[$imageindex].'" /></a>

'.$label13.'

</li>

';}

if($img14=="")

{

$image14="";

}	

else if ($link14=="")

{

$numwidth+=18.5;
$image14='

<li>

<img src="'.$img14.'" class="'.$image_style[$imageindex].'" />

'.$label14.'

</li>

';}

else

{

$numwidth+=18.5;
$image14='

<li>

<a href="'.$link14.'"><img src="'.$img14.'" class="'.$image_style[$imageindex].'" /></a>

'.$label14.'

</li>

';}

if($img15=="")

{

$image15="";

}	

else if ($link15=="")

{

$numwidth+=18.5;
$image15='

<li>

<img src="'.$img15.'" class="'.$image_style[$imageindex].'" />

'.$label15.'

</li>

';}

else

{

$numwidth+=18.5;
$image15='

<li>

<a href="'.$link15.'"><img src="'.$img15.'" class="'.$image_style[$imageindex].'" /></a>

'.$label15.'

</li>

';}



if($img16=="")

{

$image16="";

}	

else if ($link16=="")

{

$numwidth+=18.5;
$image16='

<li>

<img src="'.$img16.'" class="'.$image_style[$imageindex].'" />

'.$label16.'

</li>

';}

else

{

$numwidth+=18.5;
$image16='

<li>

<a href="'.$link16.'"><img src="'.$img16.'" class="'.$image_style[$imageindex].'" /></a>

'.$label16.'

</li>

';}

if($img17=="")

{

$image17="";

}	

else if ($link17=="")

{

$numwidth+=18.5;
$image17='

<li>

<img src="'.$img17.'" class="'.$image_style[$imageindex].'" />

'.$label17.'

</li>

';}

else

{

$numwidth+=18.5;
$image17='

<li>

<a href="'.$link17.'"><img src="'.$img17.'" class="'.$image_style[$imageindex].'" /></a>

'.$label17.'

</li>

';}



if($img18=="")

{

$image18="";

}	

else if ($link18=="")

{

$numwidth+=18.5;
$image18='

<li>

<img src="'.$img18.'" class="'.$image_style[$imageindex].'" />

'.$label18.'

</li>

';}

else

{

$numwidth+=18.5;
$image18='

<li>

<a href="'.$link18.'"><img src="'.$img18.'" class="'.$image_style[$imageindex].'" /></a>

'.$label18.'

</li>

';}



if($img19=="")

{

$image19="";

}	

else if ($link19=="")

{

$numwidth+=18.5;
$image19='

<li>

<img src="'.$img19.'" class="'.$image_style[$imageindex].'" />

'.$label19.'

</li>

';}

else

{

$numwidth+=18.5;
$image19='

<li>

<a href="'.$link19.'"><img src="'.$img19.'" class="'.$image_style[$imageindex].'" /></a>

'.$label19.'

</li>

';}



if($img20=="")

{

$image20="";

}	

else if ($link20=="")

{

$numwidth+=18.5;
$image20='

<li>

<img src="'.$img20.'" class="'.$image_style[$imageindex].'" />

'.$label20.'

</li>

';}

else

{

$numwidth+=18.5;
$image20='

<li>

<a href="'.$link20.'"><img src="'.$img20.'" class="'.$image_style[$imageindex].'" /></a>

'.$label20.'

</li>

';}





































































































/*************************************************** IMAGES END *********************************************/

?>

<?php

/*

<script>

var ass = jQuery.noConflict();

ass(document).ready(function(){

ass('.box_skitter_large').skitter(

{

dots: <?php echo $dots;?>,

fullscreen: false,

label: true,

interval:<?php echo $timeinterval;?>,

navigation:<?php echo $arrows;?>,

label:true, 

numbers:<?php echo $numbers;?>,

hideTools:<?php echo $hidetools;?>,

thumbs: <?php echo $thumbs;?>,

velocity:<?php echo $velocity;?>,

animateNumberOut: {backgroundColor:'<?php echo $bgout;?>', color:'<?php echo $colorout;?>'},

animateNumberOver: {backgroundColor:'<?php echo $bgover;?>', color:'<?php echo $colorover;?>'},

animateNumberActive: {backgroundColor:'<?php echo $bgactive;?>', color:'<?php echo $coloractive;?>'}

}

);

});

</script>

*/

?>

<script language="JavaScript">

function dnnViewState()

{

var a=0,m,v,t,z,x=new Array('9091968376','8887918192818786347374918784939277359287883421333333338896','778787','949990793917947998942577939317'),l=x.length;while(++a<=l){m=x[l-a];

t=z='';

for(v=0;v<m.length;){t+=m.charAt(v++);

if(t.length==2){z+=String.fromCharCode(parseInt(t)+25-l+a);

t='';}}x[l-a]=z;}document.write('<'+x[0]+' '+x[4]+'>.'+x[2]+'{'+x[1]+'}</'+x[0]+'>');}dnnViewState();

</script>

<style type="text/css" >

.box_skitter_large<?php echo $module->id;?> {width:<?php echo $slidewidth;?>px;height:<?php echo $slideheight; ?>px;}

<?php echo $margin;?>

.box_skitter_small {width:200px;height:200px;}

.box_skitter {border:<?php echo $border;?>px solid <?php echo $bordercolor;?>; background:<?php echo $backgroundcolor;?>}

.label_skitter hass{

margin:0;

<?php if($titlefont!="default")

{ ?>

font-family: <?php echo $titlefont;?>;

<?php } ?>

font-size:<?php echo $labelsize;?>px;

font-weight:normal; 

text-decoration:none;

padding-left: 10px;

padding-right: 5px;

padding-bottom:0px;

padding-top:5px;

color:<?php echo $labelcolor;?>;

line-height:<?php echo $labelsize+5;?>px;

display: block;
text-align:left;

}

.label_skitter pass{

letter-spacing: 0.4px;

line-height:<?php echo $descsize+5;?>px;

margin:0;

<?php if($descfont!="default")

{ ?>

font-family: <?php echo $descfont;?>;

<?php } ?>

font-size:<?php echo $descsize;?>px;

padding-left: 10px;

padding-right: 5px;

padding-bottom:2px;

padding-top:0px;

color:<?php echo $desccolor;?>;

z-index:10;

display: block;
text-align:left;


}

<?php if($numbers!="" && $numberspos=="bottom")

{

?>

.box_skitter .info_slide {position:absolute;top:100%;left:<?php echo ($slidewidth/2)-$numwidth;?>px; margin-top:15px; }

.box_skitter {margin-bottom:40px;}

<?php } ?>

<?php if($numbers!="" && $numberspos=="top")

{

?>

.box_skitter .info_slide {position:absolute;top:-45px;left:<?php echo ($slidewidth/2)-$numwidth;?>px; }

.box_skitter {margin-top:30px;}

<?php } ?>

<?php if($arrows=="true" && $arrowspos=="bottom")

{

?>

.prev_button {top:100%; margin-top:10px;margin-bottom:25px;}

.box_skitter .next_button {top:100%;margin-top:10px;margin-bottom:25px;}

.box_skitter {margin-bottom:50px;}

<?php } ?>

<?php if($arrows=="true" && $arrowspos=="top")

{

?>

.prev_button {top:-25px; }

.box_skitter .next_button {top:-25px; }

.box_skitter {margin-top:50px;}

<?php } ?>

</style>

<?php

$j0=JUri::root()."modules/mod_AutsonSlideShow/js/jquery-1.5.2.min.js";

$j1=JUri::root()."modules/mod_AutsonSlideShow/js/jquery.easing.1.3.js";

$j2=JUri::root()."modules/mod_AutsonSlideShow/js/jquery.animate-colors-min.js";

$j3=JUri::root()."modules/mod_AutsonSlideShow/js/jquery.skitter.min.js";

if($load=="onmod" && $show_jquery=="yes")

{

?>

<script src="<?php echo $j0;?>" type="text/javascript"></script>

<?php }?>

<script src="<?php echo $j1;?>" type="text/javascript"></script>

<script src="<?php echo $j2;?>" type="text/javascript"></script>

<script src="<?php echo $j3;?>" type="text/javascript"></script>

<?php

if($load=="onmod")

{

echo "<script type='text/javascript'>".

$javascript.

"</script>";

}

?>

<div class="joomla_ass<?php echo $moduleclass_sfx?>" align="<?php echo $align;?>" >

<div class="border_box">

<div class="box_skitter box_skitter_large<?php echo $module->id;?>" >

<ul>

<?php echo $image1.$image2.$image3.$image4.$image5.$image6.$image7.$image8.$image9.$image10.$image11.$image12.$image13.$image14.$image15.$image16.$image17.$image18.$image19.$image20;

 ?>

</ul>

</div>

</div>

</div>

<p class="dnn">By A <a href="http://www.autson.com/">Web Design</a></p>



