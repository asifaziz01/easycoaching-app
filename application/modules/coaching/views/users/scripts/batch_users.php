<script>
$("#check-all").click(function(){
    $('.check').not(this).prop('checked', this.checked);
});
</script>