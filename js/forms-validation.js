// JavaScript Document


	function removeChecks(){
	
	var app_fname = document.getElementById("app_fname");
	var app_lname = document.getElementById("app_lname");

	var app_email_address = document.getElementById("app_email_address");
	var datepicker = document.getElementById("datepicker");
	var news_name = document.getElementById("news_name");
	var news_email_address = document.getElementById("news_email_address");

	if(app_fname.value != ""){
	app_fname.style.borderColor = "#C9C9C9";
	
	}
	if(app_lname.value != ""){
	app_lname.style.borderColor = "#C9C9C9";
	
	}

	if(app_email_address.value != ""){
	app_email_address.style.borderColor = "#C9C9C9";
	}
	if(checkcontact(app_email_address.value) == true){
	app_email_address.style.borderColor = "#C9C9C9";
	}
    if(datepicker.value != ""){
	datepicker.style.borderColor = "#C9C9C9";
	}
	if(news_name.value != "" ){
	news_name.style.borderColor = "#C9C9C9";
	}
	if(news_email_address.value != ""){
	news_email_address.style.borderColor = "#C9C9C9";
	}
	
	
	
	}


	
	function checkcontact(input)
	{
		var pattern1=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		if(pattern1.test(input))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
					
					
	function validateAppoint(){
		var app_fname = document.getElementById("app_fname");
		var app_lname = document.getElementById("app_lname");
		var app_email_address = document.getElementById("app_email_address");
		var app_date = document.getElementById("datepicker");
	
		var flag = 0;
		
		if(app_fname.value == "" )
		{
			app_fname.style.borderColor = "#FE847B";
			flag = 1;
		}
		else if(app_lname.value == "" )
		{
			app_lname.style.borderColor = "#FE847B";
			flag = 1;
		}
	
	
		else if(app_email_address.value=="")
		{
			app_email_address.style.borderColor = "#FE847B";
			flag = 1;
		}
		else if(checkcontact(app_email_address.value)==false)
		{
			app_email_address.style.borderColor = "#FE847B";
			flag = 1;
		}	
		else if(app_date.value == "" )
		{
			app_date.style.borderColor = "#FE847B";
			flag = 1;
		}
	
		if(flag == 0)
		{	
			
			$.ajax({
							type: "POST",
							url: "submit.php",
							data: $("#appoint_form").serialize(),
							success: function(msg)
							{
								//alert(msg);
								if(msg == 'success'){
								
								$('#message-app').fadeIn(2000);
								document.getElementById("message-app").innerHTML = "Thank You! We'll contact you shortly";
								return true;
								}else{
								
								
								$('#message-app').fadeIn(2000);
								document.getElementById("message-app").innerHTML = "Thanks for contacting us.";
								return true;
								}
							}
						});
			
		}
	}

	function sendWhatsAppMessage() {
		const fname = document.getElementById('app_fname').value;
		const lname = document.getElementById('app_lname').value;
		const email = document.getElementById('app_email_address').value;
		const phone = document.getElementById('app_phone').value;
		const date = document.getElementById('datepicker').value;
		const gender = document.getElementById('gender').value;
		const msg = document.getElementById('app_msg').value;
	
		const message = `First Name: ${fname}\nLast Name: ${lname}\nEmail: ${email}\nPhone: ${phone}\nAppointment Date: ${date}\nGender: ${gender}\nMessage: ${msg}`;
		
		const whatsappUrl = `https://api.whatsapp.com/send?phone=+917010497996&text=${encodeURIComponent(message)}`;
	
		window.open(whatsappUrl, '_blank');
	}
	
	
	
	function validateNewsletter(){
		var news_name = document.getElementById("news_name");
		var news_email_address = document.getElementById("news_email_address");
	
		var flag = 0;
		
		if(news_name.value == "" )
		{
			news_name.style.borderColor = "#FE847B";
			flag = 1;
		}
		else if(news_email_address.value=="")
		{
			news_email_address.style.borderColor = "#FE847B";
			flag = 1;
		}
		else if(checkcontact(news_email_address.value)==false)
		{
			news_email_address.style.borderColor = "#FE847B";
			flag = 1;
		}	
		
		if(flag == 0)
		{	
			
			
			
			$.ajax({
							type: "POST",
							url: "submit.php",
							data: $("#newsletter_form").serialize(),
							success: function(msg)
							{
								//alert(msg);
								if(msg == 'success'){
								$('#newsletter_form').fadeOut(500);
								$('#message-news').fadeIn(2000);
								document.getElementById("message-news").innerHTML = "Thank You! You've subscribed successfully.";
								return true;
								}else{
								
								$('#newsletter_form').fadeOut(500);
								$('#message-news').fadeIn(2000);
								document.getElementById("message-news").innerHTML = "Thank You! You've subscribed successfully.";
								return true;
								}
							}
						});
 
						
		}
}


	///////////////////////////////////////// Contact us form //////////////////////////////////////////


	function checkcontact(input)
	{
		var pattern1=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		if(pattern1.test(input))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		
		function removeChecks(){
		
		var fname = document.getElementById("fname");
		var email_address = document.getElementById("email_address");
		var news_name = document.getElementById("news_name");
		var news_email_address = document.getElementById("news_email_address");
	
		if(fname.value != ""){
		fname.style.borderColor = "#C9C9C9";
		
		}
	
	
		if(email_address.value != ""){
		email_address.style.borderColor = "#C9C9C9";
		}
		if(checkcontact(email_address.value) == true){
		email_address.style.borderColor = "#C9C9C9";
		}
		
		if(news_name.value != "" ){
		news_name.style.borderColor = "#C9C9C9";
		}
		if(news_email_address.value != ""){
		news_email_address.style.borderColor = "#C9C9C9";
		}
		
		
		
		}			
					
	function validateContact(){
		var fname = document.getElementById("fname");
		var email_address = document.getElementById("email_address");
	
		var flag = 0;
		
		if(fname.value == "" )
		{
			fname.style.borderColor = "#FE847B";
			flag = 1;
		}
		else if(email_address.value=="")
		{
			email_address.style.borderColor = "#FE847B";
			flag = 1;
		}
		else if(checkcontact(email_address.value)==false)
		{
			email_address.style.borderColor = "#FE847B";
			flag = 1;
		}	
		
		if(flag == 0)
		
		{
			$.ajax({
							type: "POST",
							url: "submit.php",
							data: $("#contact_form").serialize(),
							success: function(msg)
							{
								//alert(msg);
								if(msg == 'success'){
								
								$('#message-contact').fadeIn(2000);
								document.getElementById("message-contact").innerHTML = "Thank You! We'll contact you shortly";
								return true;
								}else{
								
								$('#message-contact').fadeIn(2000);
								document.getElementById("message-contact").innerHTML = "Thank You! We'll contact you shortly";
								return true;
								}
							}
						});
	
		}
	}
	
	
	
	
		