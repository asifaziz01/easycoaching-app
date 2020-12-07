<script>
function start_test () {
	var params = [
		'channelmode=1',
		'scrollbars=1',
		'status=0',
		'titlebar=0',
		'toolbar=0',
		'resizable=1',
		'fullscreen=yes' /*only works in IE, but here for completeness*/
	].join(',');
	var testWindow = window.open ('<?php echo site_url ('student/tests/start_test/'.$coaching_id.'/'.$member_id.'/'.$category_id.'/'.$test_id); ?>', "_blank", params);
	if (testWindow.outerWidth < screen.availWidth || testWindow.outerHeight < screen.availHeight){
	    testWindow.moveTo(0,0);
	    testWindow.resizeTo(screen.availWidth, screen.availHeight);
	}
	testWindow.addEventListener("unload", function(event) {
		document.location.href= "<?php echo site_url ('student/tests/my_tests/'.$coaching_id.'/'.$member_id); ?>";
	});
}
</script>