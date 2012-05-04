<?php

global $HEAD;

if (@$Config["module.tracker.ga"]) {
	
	if (@$Config["module.tracker.ga-async"])
	{
		ob_start();
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $Config["module.tracker.ga"]; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
  })();
</script>
<?php
		$HEAD[] = ob_get_contents();
		ob_end_clean();
	}
	else {
		ob_start();
?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("<?php echo $Config["module.tracker.ga"]; ?>");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php
		$HEAD[] = ob_get_contents();
		ob_end_clean();
	}
}
