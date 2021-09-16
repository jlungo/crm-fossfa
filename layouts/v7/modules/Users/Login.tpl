{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Users/views/Login.php *}

{strip}
       <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<style>
		body {
			background: url(layouts/v7/resources/Images/login-background.jpg);
			background-position: center;
			background-size: cover;
			width: 100%;
			height: 96%;
			background-repeat: no-repeat;
		}
		hr {
			margin-top: 15px;
			background-color: #7C7C7C;
			height: 2px;
			border-width: 0;
		}
		h3, h4 {
			margin-top: 0px;
		}
		hgroup {
			text-align:center;
			margin-top: 4em;
		}
		input {
			font-size: 16px;
			padding: 10px 10px 10px 0px;
			-webkit-appearance: none;
			display: block;
			color: #636363;
			width: 100%;
			border: none;
			border-radius: 0;
			border-bottom: 1px solid #757575;
		}
		input:focus {
			outline: none;
		}
		label {
			font-size: 16px;
			font-weight: normal;
			position: absolute;
			pointer-events: none;
			left: 0px;
			top: 10px;
			transition: all 0.2s ease;
		}
		input:focus ~ label, input.used ~ label {
			top: -20px;
			transform: scale(.75);
			left: -12px;
			font-size: 18px;
		}
		input:focus ~ .bar:before, input:focus ~ .bar:after {
			width: 50%;
		}
		#page {
			padding-top: 6%;
		}
		.widgetHeight {
			height: 444px;
			margin-top: 20px !important;
			width: 425px !important;
		}
		.loginDiv {
			width: 380px;
			margin: 0 auto;
			border-radius: 4px;
			box-shadow: 0 0 10px gray;
			background-color: #FFFFFF;
		}
		.marketingDiv {
			color: #303030;
		}
		.separatorDiv {
			background-color: #7C7C7C;
			width: 2px;
			height: 460px;
			margin-left: 20px;
		}
		.user-logo {
			width: 110px;
			margin: 0 auto;
			padding-top: 10px;
			padding-bottom: 5px;
		}
		.blockLink {
			border: 1px solid #303030;
			padding: 3px 5px;
		}
		.group {
			position: relative;
			margin: 20px 20px 40px;
		}
		.failureMessage {
			color: red;
			display: block;
			text-align: center;
			padding: 0px 0px 10px;
		}
		.successMessage {
			color: green;
			display: block;
			text-align: center;
			padding: 0px 0px 10px;
		}
		.inActiveImgDiv {
			padding: 5px;
			text-align: center;
			margin: 30px 0px;
		}
		.app-footer p {
			margin-top: 0px;
		}
		.footer {
			background-color: #fbfbfb;
			height:26px;
		}
		.bar {
			position: relative;
			display: block;
			width: 100%;
		}
		.bar:before, .bar:after {
			content: '';
			width: 0;
			bottom: 1px;
			position: absolute;
			height: 1px;
			background: #35aa47;
			transition: all 0.2s ease;
		}
		.bar:before {
			left: 50%;
		}
		.bar:after {
			right: 50%;
		}
		.button {
			position: relative;
			display: inline-block;
			padding: 9px;
			margin: .3em 0 1em 0;
			width: 100%;
			vertical-align: middle;
			color: #fff;
			font-size: 16px;
			line-height: 20px;
			-webkit-font-smoothing: antialiased;
			text-align: center;
			letter-spacing: 1px;
			background: transparent;
			border: 0;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.button:focus {
			outline: 0;
		}
		.buttonBlue {
			background-image: linear-gradient(to bottom, #35aa47 0px, #35aa47 100%)
		}
		.ripples {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			overflow: hidden;
			background: transparent;
		}

		//Animations
		@keyframes inputHighlighter {
			from {
				background: #4a89dc;
			}
			to 	{
				width: 0;
				background: transparent;
			}
		}
		@keyframes ripples {
			0% {
				opacity: 0;
			}
			25% {
				opacity: 1;
			}
			100% {
				width: 200%;
				padding-bottom: 200%;
				opacity: 0;
			}
		}
		.regwidgetHeight{
	        height: 847px !important;
	        width: 425px !important;
		}
		.col-lg-offset-4 {
		    margin-left: 32.333%;
		}
                .pointer {
		    cursor: pointer;
		}
	</style>

	<span class="app-nav"></span>
	<div class="col-lg-12">
		<div class="col-lg-3 col-lg-offset-4">
			<div class="loginDiv widgetHeight" id="loginDiv">
				<img class="img-responsive user-logo" src="layouts/v7/resources/Images/logo.jpg">
				<div>
					<span class="{if !$ERROR}hide{/if} failureMessage" id="validationMessage">{$MESSAGE}</span>
					<span class="{if !$MAIL_STATUS}hide{/if} successMessage">{$MESSAGE}</span>
				</div>

				<div id="loginFormDiv">
					<form class="form-horizontal" method="POST" action="index.php">
						<input type="hidden" name="module" value="Users"/>
						<input type="hidden" name="action" value="Login"/>
						<div class="group">
							<input id="username" type="text" name="username" placeholder="Username">
							<span class="bar"></span>
							<label>Username</label>
						</div>
						<div class="group">
							<input id="password" type="password" name="password" placeholder="Password">
							<span class="bar"></span>
							<label>Password</label>
						</div>
						<div class="group">
							<button type="submit" class="button buttonBlue">Sign in</button><br>
							<a class="forgotPasswordLink pointer" style="color: #15c;">forgot password?</a>
							<a class="loginFormDiv pointer" href="{$REGISTER}" style="color: #15c;margin-left: 55%;">Register</a>
						</div>
					</form>
				</div>


				<div class="registerdiv hide" id="registrationFormDiv">
					<form class="form-horizontal" method="POST" action="index.php">
						<input type="hidden" name="module" value="Users"/>
						<input type="hidden" name="action" value="Register"/>
						<div class="group">
							<input id="user_name" type="text" name="user_name" placeholder="Username" required>
							<span class="bar"></span>
							<label>Username</label>
						</div>
						<div class="group">
							<input id="email1" type="email" name="email1" placeholder="Email" required>
							<span class="bar"></span>
							<label>Email</label>
						</div>
						
						<div class="group">
							<input id="first_name" type="text" name="first_name" placeholder="Firstname" required>
							<span class="bar"></span>
							<label>Firstname</label>
						</div>
						<div class="group">
							<input id="last_name" type="text" name="last_name" placeholder="Lastname" required>
							<span class="bar"></span>
							<label>Lastname</label>
						</div>
						<div class="group">
							<input id="user_password" type="password" name="user_password" placeholder="Password" required>
							<span class="bar"></span>
							<label>Password</label>
						</div>
						<div class="group">
							<input id="confirm_password" type="password" name="confirm_password" class='checkpasswordmatch'  placeholder="ConfirmPassword" required>
							<span class="" id='message'></span>
							<label>ConfirmPassword</label>
						</div>
                                                 <div class="g-recaptcha" data-sitekey="6Ld0Hd0ZAAAAAFps2dtYK5QwYKOgn8msEIpFwxS2" style="margin-left: 14%;"></div>

						<div class="group">
							<button type="submit" id="submit" class="button buttonBlue">Sign Up</button><br>
							<center><a class="loginFormDiv pointer" style="color: #15c;">Login</a></center>
							
						</div>
					</form>
				</div>

				<div id="forgotPasswordDiv" class="hide">
					<form class="form-horizontal" action="forgotPassword.php" method="POST">
						<div class="group">
							<input id="fusername" type="text" name="username" placeholder="Username" >
							<span class="bar"></span>
							<label>Username</label>
						</div>
						<div class="group">
							<input id="email" type="email" name="emailId" placeholder="Email" >
							<span class="bar"></span>
							<label>Email</label>
						</div>
						<div class="group">
							<button type="submit" class="button buttonBlue forgot-submit-btn">Submit</button><br>
							<span>Please enter details and submit<a class="forgotPasswordLink pull-right" style="color: #15c;">Back</a></span>
						</div>
					</form>
				</div> 
				
			</div>
		</div>
	</div>

		<script>
			jQuery(document).ready(function () {
				$( "#submit" ).click(function() {
                    var v = grecaptcha.getResponse();
                    if(v.length == 0){
                        document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
                        return false;
                    }else{
                        document.getElementById('captcha').innerHTML="Captcha completed";
                        return true; 
                    }
                });

                $('.checkpasswordmatch').keyup(function(event){
                    if (document.getElementById('user_password').value ==
                        document.getElementById('confirm_password').value) {
                        document.getElementById('message').style.color = 'green';
                        document.getElementById('message').innerHTML = 'matching';
                        document.getElementById('submit').disabled = false;
                       
                    } else {
                        document.getElementById('message').style.color = 'red';
                        document.getElementById('message').innerHTML = 'not matching';
                        document.getElementById('submit').disabled = true;
                    }
                });
                                var validationMessage = jQuery('#validationMessage');
				var forgotPasswordDiv = jQuery('#forgotPasswordDiv');
				var registrationFormDiv = jQuery('#registrationFormDiv');

				var loginFormDiv = jQuery('#loginFormDiv');
				loginFormDiv.find('#password').focus();

				loginFormDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					forgotPasswordDiv.toggleClass('hide');
					validationMessage.addClass('hide');
				});

				forgotPasswordDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					forgotPasswordDiv.toggleClass('hide');
					validationMessage.addClass('hide');
				});

				registrationFormDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					registrationFormDiv.toggleClass('hide');
					validationMessage.addClass('hide');
					jQuery('#loginDiv').removeClass('regwidgetHeight');
				});
				
				$(".registerlink").on('click', function(){ 
				    forgotPasswordDiv.addClass('hide');
					registrationFormDiv.toggleClass('hide');
					loginFormDiv.addClass('hide');
					jQuery('#loginDiv').addClass('regwidgetHeight');
				});

				loginFormDiv.find('button').on('click', function () {
					var username = loginFormDiv.find('#username').val();
					var password = jQuery('#password').val();
					var result = true;
					var errorMessage = '';
					if (username === '') {
						errorMessage = 'Please enter valid username';
						result = false;
					} else if (password === '') {
						errorMessage = 'Please enter valid password';
						result = false;
					}
					if (errorMessage) {
						validationMessage.removeClass('hide').text(errorMessage);
					}
					return result;
				});
				registrationFormDiv.find('button').on('click', function () { 
					
				});

				forgotPasswordDiv.find('button').on('click', function () {
					var username = jQuery('#forgotPasswordDiv #fusername').val();
					var email = jQuery('#email').val();

					var email1 = email.replace(/^\s+/, '').replace(/\s+$/, '');
					var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
					var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;

					var result = true;
					var errorMessage = '';
					if (username === '') {
						errorMessage = 'Please enter valid username';
						result = false;
					} else if (!emailFilter.test(email1) || email == '') {
						errorMessage = 'Please enter valid email address';
						result = false;
					} else if (email.match(illegalChars)) {
						errorMessage = 'The email address contains illegal characters.';
						result = false;
					}
					if (errorMessage) {
						validationMessage.removeClass('hide').text(errorMessage);
					}
					return result;
				});
				jQuery('input').blur(function (e) {
					var currentElement = jQuery(e.currentTarget);
					if (currentElement.val()) {
						currentElement.addClass('used');
					} else {
						currentElement.removeClass('used');
					}
				});

				var ripples = jQuery('.ripples');
				ripples.on('click.Ripples', function (e) {
					jQuery(e.currentTarget).addClass('is-active');
				});

				ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function (e) {
					jQuery(e.currentTarget).removeClass('is-active');
				});
				loginFormDiv.find('#username').focus();

				var slider = jQuery('.bxslider').bxSlider({
					auto: true,
					pause: 4000,
					nextText: "",
					prevText: "",
					autoHover: true
				});
				jQuery('.bx-prev, .bx-next, .bx-pager-item').live('click',function(){ slider.startAuto(); });
				jQuery('.bx-wrapper .bx-viewport').css('background-color', 'transparent');
				jQuery('.bx-wrapper .bxslider li').css('text-align', 'left');
				jQuery('.bx-wrapper .bx-pager').css('bottom', '-15px');

				var params = {
					theme		: 'dark-thick',
					setHeight	: '100%',
					advanced	:	{
										autoExpandHorizontalScroll:true,
										setTop: 0
									}
				};
				jQuery('.scrollContainer').mCustomScrollbar(params);
			});
		</script>
		</div>
	{/strip}
