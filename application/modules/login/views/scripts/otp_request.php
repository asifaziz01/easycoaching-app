<script>
(function($){
	$('#otp-imput').pincodeInput({
		hidedigits:true,
		inputs:6,
		complete:function(value, e, errorElement){
			$("#otp-callback").html("Complete callback from 6-digit test: Current value: " + value);
			$(errorElement).html("I'm sorry, but the code not correct");
		}
	});
})(jQuery);
</script>