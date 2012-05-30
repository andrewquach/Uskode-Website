<div class="widget">
<h3>Latest Tweets</h3>
<?php
$number_of_tweets = get_option('swt_num_of_tweets');
$twitter_username = get_option('swt_tuser');
?>
<ul id="twitter_update_list">
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js">
</script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $twitter_username; ?>.json?callback=twitterCallback2&amp;count=<?php echo $number_of_tweets; ?>">
</script>
</ul>
<a title="Follow us on Twitter!" href="http://twitter.com/<?php echo $twitter_username; ?>" id="twitterb"></a>
</div>