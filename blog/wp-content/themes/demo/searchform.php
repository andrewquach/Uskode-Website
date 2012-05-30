<?php $search_text = empty($_GET['s']) ? "Search" : get_search_query(); ?>
<div id="search">
    <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/"> 
        <input type="text" value="<?php echo $search_text; ?>" 
            name="s" id="s"  onblur="if (this.value == '')  {this.value = '<?php echo $search_text; ?>';}"  
            onfocus="if (this.value == '<?php echo $search_text; ?>') {this.value = '';}" />
        <input class="sim" type="image" src="<?php bloginfo('template_url'); ?>/images/go.png" style="border:0; vertical-align: top;" /> 
    </form>
</div>