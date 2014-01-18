
activity.fb = {

	init: function ()
	{
		/*
		//FB.init({appId: '208222219208475', status: true, cookie: true, xfbml: true});
		 window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '208222219208475', // App ID
	      channelUrl : '//tre.rigorix.com/classes/fb_channel.php', // Channel File
	      status     : true, // check login status
	      cookie     : true, // enable cookies to allow the server to access the session
	      xfbml      : true  // parse XFBML
	    });

	    // Additional initialization code here
	    FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
		    // the user is logged in and has authenticated your
		    // app, and response.authResponse supplies
		    // the user's ID, a valid access token, a signed
		    // request, and the time the access token
		    // and signed request each expire
		    var uid = response.authResponse.userID;
		    var accessToken = response.authResponse.accessToken;
		    FB.api('/me', function(user_response) {
		    	activity.fb.login_from_facebook (user_response);
		    });
		  } else if (response.status === 'not_authorized') {
		    // the user is logged in to Facebook,
		    // but has not authenticated your app
		    alert ("logged but not authorized")
		  } else {
		    // the user isn't logged in to Facebook.
		    alert ("not logged")
		  }
		 });
	  };

	  // Load the SDK Asynchronously
	  (function(d){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document));

	},

	login_from_facebook: function ( response )
	{
		$.get( Game.responder + '?action=get_user_by_fb_id&fbid=' + response.id, function(xhr){
			if ( xhr == "KO" ) {
				Game.loadStaticPageDialog( "FB_CONNECTION_INFO", {
						width: 400,
						height: 300
					}, function() {
						$("[name=register-after-fib]").click ( function() {
							$.get ( Game.responder + '?action=put-in-session&session-var-name=FBID&session-var-value=' + response.id, function( fbxhr ) {
								if ( fbxhr == "OK" )
									window.location.href = "registrazione.php";
							});
						});
						$(".rx-ui-button").button();
				} );
			} else {
				activity.fb.login_process_done ( response.id );
			}
		});

		 /*
     	Object
		birthday: "07/21/1980"
		email: "littlebrown@gmail.com"
		first_name: "Paolo"
		gender: "male"
		hometown: Object
		id: "636103911"
		last_name: "Moretti"
		link: "http://www.facebook.com/pmoretti"
		locale: "en_US"
		location: Object
		name: "Paolo Moretti"
		timezone: 2
		updated_time: "2011-05-16T09:33:50+0000"
		username: "pmoretti"
		verified: true
		work: Array[1]
		__proto__: Object
     	* */
	},

	login_process_done: function ()
	{
		/*$.get( Game.responder + '?action=register_user_session&fbid=' + response.id, function(xhr){
			if ( xhr == "KO" ) {
				alert ("problemi durante l'autologin con FB")
			} else {
				alert ("tutto ok, quindi ricarico la pagina")
				//window.location.reload();
			}
		});*/
	}

}
