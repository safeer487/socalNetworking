(function(){
	


//validation object
var validation = {
	init : function(config){
	this.config = config;
	this.restEmail();
	this.passMatch();
	this.onSignUp();
	this.isAvailableUser()
	},
	// check for the signUp 
	onSignUp : function(){
		this.config.signUpForm.on('submit',this.validation)

	},
	// check for all the fields 
	validation:function(e){
		console.log('hola')
		var self = validation;
		e.preventDefault();		
		var userName = self.config.userName.val(),
			email = self.config.email.val(),
			pass = self.config.pass.val(),
			rPass = self.config.rPass.val(),
			month = self.config.month.val(),
			day = self.config.day.val(),
			year = self.config.year.val(),
			gender = self.config.gender.val(),
			error = self.config.finalError;
			
		if (userName.length == 0 || email.length == 0 || pass.length == 0 || rPass.length == 0 || month == 0 || day == 0 || year == 0 || gender == 0) {
			error.html('');
			error.append('<strong>please fill all the fields</strong>')
		}else{
			error.html('')
			// console.log(self.config.signUpForm.serialize());
			$.ajax({
				url : 'signUp/signUp.php',
				method:'POST',
				data : self.config.signUpForm.serialize(),
				// dataType : 'json',
				success : function(results){
					if (results == 'signup_success') {
						self.config.signUpForm.html('');
						self.config.signUpForm.append('Congrats you have been registered successfully.A verification email has been send to your email please confirm to continue.');
						
					}else{
						self.config.newUser.html('')
						self.config.newUser.append(results);
					}
				}
			})
		}
	},

	// validating the email address
	restEmail : function(){
		this.config.email.on('blur',function(){
			var self = validation;
			self.config.errorE.html('')
			if ($(this).val().search('@') == -1 || $(this).val().search('.') == -1 ) {
				self.config.errorE.append('<strong>Invalid email address</strong>');
				
			}else{
				self.config.errorU.html('');
			}
		})
	},
	//  will check the passwords
	passMatch : function(){
		this.config.rPass.on('blur',function(){
			var self = validation;
			self.config.errorP.html('');
			if (self.config.pass.val() != $(this).val()) {
				self.config.errorP.append('<strong>pass does not matched</strong>')

			}else{
				self.config.errorP.html('');
			}
		})
	},
	// will check if the username exists or not
	isAvailableUser : function(){
		this.config.userName.on('keyup',function(){
			var self = validation,
				error = self.config.errorU;

			$.ajax({
				url:'signUp/checkUser.php',
				method : 'POST',
				data : $(this).serialize(),
				success : function(results){
					error.html('');
					error.addClass('s')
					error.append(results)
				}
			})
		})
	}
}
validation.init({
	signUpForm : $('#signUpForm'),
	userName : $('#userN'),
	email : $('#email'),
	pass : $('#pass'),
	rPass : $('#rPass'),
	month : $('#month'),
	day : $('#day'),
	year : $('#year'),
	gender :  $('#gender'),
	errorU : $('span.errorU'),
	errorE : $('span.errorE'),
	errorP : $('span.errorP'),
	newUser : $('span.newUser'),
	finalError : $('span.errorAll')
});


// log in object
var logIn = {
	init:function(config){
		this.config = config;
		this.FormLogIn();
	},
	FormLogIn : function(){
		this.config.form.on('submit',function(e){
			e.preventDefault();
			var self = logIn
			if(self.config.email.val() == '' || self.config.pass.val() == '') {
				self.config.lError.html('');
				self.config.lError.addClass('e');
				self.config.lError.append('<strong>please fill all the fields</strong>');
			}else{
				self.config.lError.html('');
				$.ajax({
					url:'logIn/logIn.php',
					type : 'POST',
					data : $(this).serialize(),
					success : function(results){
						if (results == 'login_fail') {
							self.config.lError.html('')						
							self.config.lError.addClass('e')			
							self.config.lError.append('<strong>log in failed,please try again later</strong>')
						}else{												
							self.config.lError.html('');
							
							// if no error then just redirect to use
							//becoz we have prevented the default action of form so we 
							//set the header here
						 	// console.log(results);
							  $(location).attr('href', 'user.php?u='+results);
						}
					}
				})
			}
		})
	}
}

logIn.init({
	email : $('#eLog'),
	pass : $('#pLog'),
	form : $('#fLog'),
	lError: $('.lError')
});



//forgot password object
var forgotPass = {
	init:function(config){
		this.config = config;
		this.emailValid()
	},

	emailValid : function(){	
		this.config.form.on('submit',function(e){
			var self = forgotPass,
				status = self.config.status;
				self.config.status.html('');

			e.preventDefault();
			
			if (self.config.email.val() == '') {
				self.config.status.append('please enter your email address');
				self.config.status.addClass('e');
			}else{
				self.config.status.html('');
				$.ajax({
					url : '#',
					method : 'POST',
					data : $(this).serialize(),
					success : function(results){
						status.html('');
						if (results == 'success') {
							status.append('<h2>Please check your email.A temporary password has been send.</h2>');
						}else if(results == 'noExists'){
							status.html('');
							status.append('Sorry that email address does not exists in our system');
						}else if(results == 'emailSentFail'){
							status.html('');
							status.append('There is an error,please try again laer');
						}else if(results == 'emailNotValid'){
							status.html('');
							status.append('Please enter a valid email address');
						}else{
							status.html('');
							// status.append('unknown error occurs');
							console.log(results);
							
						}
					}
				})
			}
		})
	}
}


forgotPass.init({
	form : $('#forgotP'),
	email : $('#emailR'),
	status : $('p.status'),
});


//friend system object
var friendSystem = {
	init : function(config){
		this.config = config;
		this.friendEvent();
		this.blockEvent();
	},

	friendEvent : function(){
		this.config.fForm.on('submit',function(e){
			e.preventDefault();
			var self = friendSystem;
			//empty
			self.config.pStatus.html('');
			$.ajax({
				url:'phpParsers/friend_system.php',
				method:'POST',
				data: $(this).serialize(),
				success : function(results){
					
					if (results == 'friend_request_send') {
						self.config.fButton.attr('disabled','disabled');
						self.config.pStatus.append('Friend request successfully send!');
					}else if(results == 'unfriend successfully'){
						self.config.pStatus.append('unfriend successfull');	
						alert('The page will be refresh in 2 seconds');		
						setTimeout(function(){
							window.location.reload();			
						},2000);
					}else{
						self.config.pStatus.append(results);
					}
				}
			})
		})
	},
	blockEvent : function(){
	this.config.bForm.on('submit',function(e){
			e.preventDefault();
			var self = friendSystem;
			self.config.pStatus.html('');
			$.ajax({
				url:'phpParsers/block_system.php',
				method:'POST',
				data: $(this).serialize(),
				success : function(results){					
					if (results == 'blocked_ok') {	
						self.config.pStatus.append('You have successfully blocked');
						alert('The page will be refresh in 2 seconds');			
						setTimeout(function(){
							window.location.reload();			
						},2000);
						
					}else if(results == 'unblocked_ok'){
						self.config.pStatus.append('You have successfully unblocked');
						alert('The page will be refresh in 2 seconds');
						setTimeout(function(){
							window.location.reload();			
						},2000);			
					}else{
						self.config.pStatus.append('results')
					}
				}
			})
		})	
	}
}

friendSystem.init({
	fForm : $('#fForm'),
	bForm : $('#bForm'),
	pStatus : $('p.status'),
	fButton : $('#fButton'),
});



var notification = {
	init:function(config){
		this.config = config
		this.bindEvents();
		
	},
	bindEvents : function(){
		var self = notification;
		this.config.boxNotification.hide();
		this.config.showNotification.on('click',function(){
			self.config.boxNotification.fadeToggle(1000);
		})
	},
	
}

notification.init({
	boxNotification : $('#notification'),
	showNotification : $('#showNot'),
	
});


var photoUpload = {
	init:function(config){
		this.config = config;
		this.bindPhoto();
	},
	bindPhoto : function(){
		this.config.button.hide();
		this.config.uploadF.hide();
		
		//when the mouse enter show the button
		this.config.pChange.on('mouseenter',function(){
			var self = photoUpload;
			$(this).fadeTo('slow',0.5);
			self.config.button.slideToggle();
			
		})

		//when the user click the change picture button
		this.config.button.on('click',function(){
				var self = photoUpload;
				self.config.uploadF.slideToggle();			
		})	



		//when the user leave the image or field
		this.config.pChange.on('mouseleave',function(){
			$(this).fadeTo('slow',1);
			
		})


		this.config.uploadF.on('submit',function(e){
			var self = photoUpload;
			e.preventDefault();
			var formData = new FormData($(this)[0])
			

			$.ajax({
//important				//for sending the multipart file data we use
				url:'phpParsers/photoSystem.php',
				method:'POST',
				data : formData,
				success:function(results){
					self.config.pStatus.html('');
					self.config.pStatus.append(results);
					setTimeout(function(){
						window.location.reload();
					},500)
					alert('page will be refresh after 2 seconds');	
				},
				cache: false,
				processData: false,
    			contentType: false,

			})
		})
	},
}

photoUpload.init({
	pChange : $('#pChange'),
	button: $('#pButton'),
	uploadF : $('#uploadF'),
	pStatus : $('.imgError')
});

var chatWithFriend = {
	init:function(config){
		this.config = config;
		this.chat();

	},
	chat : function(){
		this.config.chat.on('click',function(e){
			e.preventDefault();
			console.log('working');	
		})
	}

} 

chatWithFriend.init({
	chat : $('.chat')
});



var galImage = {
	init : function(config){
		this.config = config;
		this.showGal();
		this.setupTemplate();
		this.uploadG();
		
	},
	uploadG : function(){
		//this is for sending form file input and other at the same time
		this.config.fotoUp.on('submit',function(e){
			var self = galImage;
			e.preventDefault();
			var formdata = new FormData($(this)[0]) ;
			$.ajax({
				url:'phpParsers/uploadImg.php',
				type : 'POST',
				data : formdata,
				async : false,
				success:function(results){
					self.config.status.html('');
					self.config.status.append(results);
					if(results == "your image uploaded successfully"){
						alert('The page will be refresh in 2 seconds');
						setTimeout(function(){
							window.location.reload();			
						},2000);
					}
				},
				cache : false, //will not cache the data
				contentType : false,	//will not set the contenttype
				processData : false,    //will not process the data
			})  	
		})
	},
	setupTemplate : function(){
		this.config.imgTemplate = Handlebars.compile(this.config.imgTemplate);
	},


	showGal : function(){
		this.config.images.on('click',function(e){
			e.preventDefault();
			var cat = $(this).find('span.bold').html(),
				usr = $(this).find('input').val(),
				self = galImage;
			$.ajax({
				method :'POST',
				url : 'phpParsers/showImgs.php',
				//another way of sending form data
				data : {catagory: cat,user:usr },
				dataType : 'json',
				success : function(results){
					self.config.empty.html('');
					self.config.imgList.append(self.config.imgTemplate(results));
				}
			})
		})
	},
}

galImage.init({
	fotoUp     : $('#fotoUp'),
	status     : $('#statusUp'),
	images 	   : $('li.imagess'),
	imgTemplate	   : $('#img_list_template').html(),
	imgList : $('ul#showImg'),
	empty : $('ul#showImgs'),
	
	
});




})();

