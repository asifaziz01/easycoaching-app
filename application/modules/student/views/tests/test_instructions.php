<div class="card card-primary paper-shadow" data-z="0.5">
	<div class="card-body">
		<h4>
			Please read the instructions carefully <br> 
			कृपया निर्देशों को ध्यान से पढ़ें
		</h4>
		<ol class="pl-4">
			<li>
				<p>
					Test Name: <?php echo ($test['title']); ?><br>
					टेस्ट का नाम: <?php echo ($test['title']); ?><br>
				</p>
				<p>
					Test Duration: <strong><span class="completeDuration"><?php echo $test['time_min']; ?></span> </strong>minutes.<br>
					टेस्ट अवधि: <?php echo $test['time_min']; ?> मिनट।
				</p>
				<p>
					<div>Questions: <span class="completeDuration"><?php echo $num_test_questions; ?></span> </div>
					<div>प्रश्न: <?php echo $num_test_questions; ?></div>					
				</p>
				<p>
					<div>Marks: <span class="completeDuration"><?php echo $test_marks; ?></span> </div>
					<div>अंक: <?php echo $test_marks; ?></div>				
				</p>
				<p>
					<div>Pass Percentage: <span class="completeDuration"><?php echo $test['pass_marks']; ?>%</span> </div>
					<div>पास प्रतिशत: <span class="completeDuration"><?php echo $test['pass_marks']; ?>%</span> </div>					
				</p>
			</li>
			<li>
				Timer and Test Submission: The timer clock will show you the remaining time to complete the examination. You must submit your test within time. Once time is up your test will be automatically submitted <br>
				टाइमर और टेस्ट सबमिशन: टाइमर घड़ी आपको परीक्षा को पूरा करने के लिए शेष समय दिखाएगी। आपको अपनी परीक्षा समय के भीतर समाप्त करनी होगी। एक बार समय समाप्त होने के बाद आपका टेस्ट अपने आप सबमिट हो जाएगा
			</li>
			<li>
				The Question Palette displayed below the question section on screen will show the status of each question using one of the following symbols: <br>
				स्क्रीन पर प्रश्न अनुभाग के नीचे प्रदर्शित प्रश्न पैलेट निम्नलिखित प्रतीकों में से किसी एक का उपयोग करके प्रत्येक प्रश्न की स्थिति दिखाएगा
				<table class="table">
					<tbody>
						<tr>
							<td width="5%"><span class="btn btn-sm btn-secondary text-white" title="Not Visited">1</span></td>
							<td>
								You have not visited the question yet. <br>
								आपने अभी तक प्रश्न नहीं देखा है।
							</td>
						</tr>
						<tr>
							<td><span class="btn btn-sm btn-danger" title="Not Answered">3</span></td>
							<td>
								You have not answered the question. <br>
								आपने अभी तक प्रश्न का उत्तर नहीं दिया है।								
							</td>
						</tr>    
						<tr>
							<td><span class="btn btn-sm btn-success" title="Answered">5</span></td>
							<td>
								You have answered the question. <br>
								आपने प्रश्न का उत्तर दे दिया है।
							</td>
						</tr>
						<tr>
							<td><span class="btn btn-sm btn-warning" title="Mark for Review">7</span></td>
							<td>
								You have marked the question for review. <br>
								आपने प्रश्न को समीक्षा के लिए चिह्नित किया है।
							</td>
						</tr>
					</tbody>
				</table>
			<li>
				Bookmark Questions: The Marked for Review status for a question simply indicates that you would like to look at that question again. Selected answer will be considered for evaluation. <br>
				बुकमार्क प्रश्न: किसी प्रश्न के लिए समीक्षा की स्थिति के लिए चिह्नित किया गया संकेत केवल यह दर्शाता है कि आप उस प्रश्न को फिर से देखना चाहेंगे। चयनित उत्तर मूल्यांकन के लिए मान्य होगा
			</li>

			<li>
				Procedure for answering a multiple choice type question: <br>
				बहुविकल्पी प्रकार के प्रश्न का उत्तर देने की प्रक्रिया:
				<ol class="pl-4" type="a">
					<li>
						To select your answer, click on the button of one of the options <br>
						अपने उत्तर का चयन करने के लिए, किसी एक विकल्प के बटन पर क्लिक करें
					</li>
					<li>
						To deselect your chosen answer, click on the option <strong><em>Leave Blank</em></strong> button <br>
						अपने चुने हुए उत्तर को रद्द करने के लिए <strong><em>Leave Blank</em></strong> विकल्प पर क्लिक करें
					</li>
					<li>
						To change your chosen answer, click on another option from the answer choices <br>
						अपने चुने हुए उत्तर को बदलने के लिए, उत्तर विकल्पों में से अन्य विकल्प पर क्लिक करें
					</li>
				</ol>
			</li>
			<li>
				You can navigate between tests and questions anytime during the examination using <strong>Next</strong> and <strong>Previous</strong> buttons as per your convenience only during the time stipulated. <br>
				आप परीक्षा के दौरान <strong>Next</strong> और <strong>Previous</strong> बटन के माध्यम से कभी भी प्रश्नों के बीच नेविगेट कर सकते हैं 
			</li>
		</ol>
	</div>
	<div class="card-footer">
		<p class="btn-toolbar btn-toolbar-demo">
			<?php if ($start_test == true) { ?>
				<a href="<?php echo site_url ('student/tests/start_test/'.$coaching_id.'/'.$member_id.'/'.$test_id); ?>" class="btn btn-success  ">Start Test <i class="fa fa-play-circle"></i> </a>
			<?php } ?>
		</p>
	</div>
</div>