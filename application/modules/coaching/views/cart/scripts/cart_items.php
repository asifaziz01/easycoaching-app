<script type="text/javascript">
	$(document).ready (function () {
		$('#plan_duration').on ('change', function () {
			var months = $(this).val ();
			var price = <?php echo $plan_price; ?>;
			var gst_slab = <?php echo $gst_slab; ?>;
			var amount = (price * months);
			var gst = amount * gst_slab / 100;
			var total_amount = amount + gst;
			
			$('#price_html').html (price);
			$('#price').val (price);

			$('#gst_html').html (gst);
			$('#gst').val (gst);

			$('#amount_html').html (amount);
			$('#amount').val (amount);

			$('#total_amount_html').html (total_amount);
			$('#total_amount').val (total_amount);

			//alert (total_amount);
		});
	});
</script>