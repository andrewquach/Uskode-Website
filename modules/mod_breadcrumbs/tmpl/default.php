<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<span class="breadcrumbs pathway" style="background:#F0F0F0; line-height:24px; display:block; padding:0px 30px; font-size:12px; margin-bottom:20px;">
<?php for ($i = 0; $i < $count; $i ++) :

	// If not the last item in the breadcrumbs add the separator
	if ($i < $count -1) {
		if(!empty($list[$i]->link)) {
			echo '<a href="'.$list[$i]->link.'" class="pathway" style="color:#1C426F;" >'.$list[$i]->name.'</a>';
		} else {
			echo $list[$i]->name;
		}
		echo ' : ';
	}  else if ($params->get('showLast', 1)) { // when $i == $count -1 and 'showLast' is true
	    echo '<strong>' .$list[$i]->name.'</strong>';
	}
endfor; ?>
</span>
