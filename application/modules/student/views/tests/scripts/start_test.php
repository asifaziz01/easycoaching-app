<script>
$(document).ready (function () {
	/*Hide All Questions on First Load and show only section 1*/
	$(".pages,nav").hide ();
	$("#page1").show ();
	const finishTime = Date.now() + (<?php echo $test_duration; ?> * 1000);
	const fiveMinutesBefore = ((<?php echo $test_duration; ?> - 300) * 1000);
	const oneMinutesBefore =  ((<?php echo $test_duration; ?> - 60) * 1000);
	var x = setInterval(()=>{
		// Get current unix timestamp time
		const now = Date.now();
		// Find the timeRemain between now and the time finishTime
		const timeRemain = finishTime - now;

		if (timeRemain < 0) {
		    clearInterval(x);
			$("#submit-test").trigger('click').prop('disabled', true);
			toastr.success('Time is up. Submitting test now.');
		    return false;
		}
		const hoursRemain = Math.floor((timeRemain % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		const minutesRemain = Math.floor((timeRemain % (1000 * 60 * 60)) / (1000 * 60));
		const secondsRemain = Math.floor((timeRemain % (1000 * 60)) / 1000);
		$("#hours").text((hoursRemain<10)?`0${hoursRemain}`:hoursRemain);
		$("#minutes").text((minutesRemain<10)?`0${minutesRemain}`:minutesRemain);
		$("#seconds").text((secondsRemain<10)?`0${secondsRemain}`:secondsRemain);
	}, 1000);

	if(fiveMinutesBefore > 0){
		setTimeout(() => {
		  toastr.warning('Only 5 minutes remaining');
		}, fiveMinutesBefore);
	}
	setTimeout(() => {
	  toastr.error('Hurry up, 1 minute remaining');
	}, oneMinutesBefore);
	$(".next").click(function() {
		
		/*Hide/Show question blocks*/
		/*$(".pages:visible").hide().next(".pages").andSelf().last().show();*/
		var id = $('#num_question').val();
		var next = parseInt (id) + 1;
		var last = <?php echo $total_questions; ?>;
		var confirm_div = <?php echo $confirm_div; ?>;

		$(".pages").hide();
		$("#page"+next).show();
		$('#num_question').val (next);

		/*Change color of progress buttons and increment values for - Answered, Not-answered and For-review*/
		if (document.getElementById("leaveblank_"+id).checked == true ) {
			/*Change color*/
			document.getElementById("btn_"+id).className="btn btn-sm btn-warning";
		} else {
			/*Change color*/
			document.getElementById("btn_"+id).className="btn btn-sm btn-success";
		}

		if (document.getElementById("visitlater_"+id).checked == true ) {
			/*Change color*/
			document.getElementById("btn_"+id).className="btn btn-sm btn-danger";
		}	
	});

	$(".previous").click(function() {
		
		/*Hide/Show question blocks*/
		/*$(".pages:visible").hide().next(".pages").andSelf().last().show();*/
		var id = $('#num_question').val();
		var prev = parseInt (id) - 1 ;
		var first = 1;
		var confirm_div = <?php echo $confirm_div; ?>;

		if (prev < first) {
			$(".pages").hide();
			$("#page"+first).show();
			$('#num_question').val (first);
		} else {
			$(".pages").hide();
			$("#page"+prev).show();
			$('#num_question').val (prev);
		}

		/*Change color of progress buttons and increment values for - Answered, Not-answered and For-review*/
		if (document.getElementById("leaveblank_"+id).checked == true ) {
			/*Change color*/
			document.getElementById("btn_"+id).className="btn btn-sm btn-warning";
		} else {
			/*Change color*/
			document.getElementById("btn_"+id).className="btn btn-sm btn-success";
		}

		if (document.getElementById("visitlater_"+id).checked == true ) {
			/*Change color*/
			document.getElementById("btn_"+id).className="btn btn-sm btn-danger";
		}	
	});	

	$('.visitlater').click (function() { 
		var id = $(this).attr ('data-id');
		if ($(this).is(':checked')) {
			$("#btn_"+id).addClass ("btn btn-sm btn-danger");		
		} else {
			$("#btn_"+id).removeClass ("btn-danger");		
		}		
	});

	$('.answer_choices').click (function() {
		var id = $(this).attr ('data-id');
		if ($(this).is(':checked')) {
			$("#btn_"+id).addClass ("btn btn-sm btn-success");		
		} else {
			$("#btn_"+id).removeClass ("btn-success");		
		}
	});

	$('.leaveblank').click (function() {
		var id = $(this).attr ('data-id');
		if ($(this).is(':checked')) {
			$("#btn_"+id).removeClass ("btn-success");
			$("#btn_"+id).addClass ("btn-warning");
		} else {
			$("#btn_"+id).removeClass ("btn-warning");		
		}		
	});

	/*Enable/Disable Timer*/
	$('#disable-timer-d, #disable-timer-m').on ('click', function (e) {		
		var x = confirm ('This will disable automatic test submission on time complete. Though the timer will keep running.');
		if (x) {
			/*$(this).checked = true;*/
		} else {
			e.preventDefault ();
		}
	});

	$('#submit-test').on ('click', function (e) {
		e.preventDefault ();
		$(this).val('Submitting...').prop ('disabled', true).parents("#test_form").trigger('submit');
	});
});
/*Multi Select questions checkboxes toggle*/
function mcmc_deselect (blankid, qid) {
	if ( blankid.checked == true) {
		/*leave blank selected*/
		for ( var i=1; i <= 6; i++) {
			itemid = document.getElementById ('mc_'+qid+'_'+i);
			if ( itemid.checked == true ) {
				itemid.checked = false;
			}
		}
	}
}

/*Multi Select questions checkboxes toggle*/
function mcmc_select (qid, blankid) {
	itemid = document.getElementById (blankid);
	if ( itemid.checked == true) {
		/*leave blank selected*/
		itemid.checked = false;
	}
}
function match_deselect (blankid, qid) {
	if ( blankid.checked == true ) {
		for ( var i=1; i <=6; i++) {
			document.getElementById('ans_'+qid+'_'+i).value = "0";
		}
	}

}
function match_select (blankid) {
	item = document.getElementById(blankid);
	if ( item.checked == true ) {
		item.checked = false;
	}
}
function display_question(id) {	
	$('.pages').hide();
	$('#page'+id).show();
	$('#num_question').val(id);
    if($("#page"+id).height() > $('.test-page .st-content-inner').height()){
        $("#page"+id).find('.card-footer').addClass('sticky');
	}
    else{
        $("#page"+id).find('.card-footer').css('width',$("#test_form").width()+'px');
    }
}
function show_last () {	
	var last = <?php echo $total_questions; ?>;
	$('.pages').hide();
	$('#page'+last).show();
}
function show_first () {	
	var first = 1;
	$('.pages').hide();
	$('#page'+first).show();
	$('#num_question').val (first);	
}
$(document).ready(function() {
    $('input:radio').change(function(){
		var total_questions = <?php echo $total_questions; ?>;
        var num_answered = $('.answer:checked').length;
		var num_not_answered = total_questions - num_answered;
        $('#review-answered').text(num_answered);
        $('#review-not-answered').text(num_not_answered);
    });
	$('input:checkbox').change(function(){
        var num_visitlater = $('.visitlater:checked').length;
        $('#review-for-review').text(num_visitlater);
    });
});
</script>