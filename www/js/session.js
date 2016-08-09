// Session timeout popup
jQuery(function($){

	function ajaxUpdateError(XHR){
		if(XHR.statusText == 'Unauthorized' || (XHR.responseText && XHR.responseText.indexOf('Login Required') !== -1)){
			showTimeoutDialog();
		} else {
			hideTimeoutDialog();
		}
	}

	function showTimeoutDialog(){
		var dialog = $('#session-timeout');
		if(!dialog.length){
			dialog = $('<div id="session-timeout"><h4>Your session has timed out. Please login again.</h4></div>').appendTo(document.body);
		}

		dialog.dialog({
			autoOpen: true,
			modal: true,
			resizable: false,
			modal: true,
			width: 300,
			buttons: [{
				showText: false,
				html: '<i class="fa fa-sign-out"></i> Ok',
				'class': 'btn btn-primary',
				click: function(){
					window.location.href = window.sessionTimeoutUrl + (window.sessionTimeoutUrl.indexOf('?') == -1 ? '?' : '&') + 'redirect=' + encodeURIComponent(window.location.href);
				}
			}]
		});
	}

	function hideTimeoutDialog(){
		$('#session-timeout').dialog('close');
	}


	if($.fn.yiiGridView){

		$.fn.yiiGridViewOriginal = $.fn.yiiGridView;
		$.fn.yiiGridView = function(options){
			if(typeof options == 'object'){
				if(options.ajaxUpdateError){
					options.ajaxUpdateErrorOriginal = options.ajaxUpdateError;
				}

				options.ajaxUpdateError = function(XHR, textStatus, errorThrown, err){
					ajaxUpdateError(XHR);

					if(options.ajaxUpdateErrorOriginal){
						options.ajaxUpdateErrorOriginal(XHR, textStatus, errorThrown, err);
					}
				}
			}

			return $.fn.yiiGridViewOriginal.apply(this, arguments);
		}

		//extend all setting and methods
		$.extend($.fn.yiiGridView, $.fn.yiiGridViewOriginal);
	}


	if(window.sessionTimeoutUrl){
		var sid = null;

		$(document).ajaxSend(function(){
			clearTimeout(sid);
			sid = setTimeout(function(){
				$.get(window.location.href);
			}, parseInt(window.sessionTimeout) * 1000 + 30000);

			if(window.localStorage){
				window.localStorage.setItem('session-timeout', new Date().getTime());
			}

		}).ajaxError(function(e, ajax){
			ajaxUpdateError(ajax);
		});

		$(window).on('storage', function(e){
			if(e.originalEvent.key == 'session-timeout'){
				clearTimeout(sid);
				sid = setTimeout(function(){
					$.get(window.location.href);
				}, parseInt(window.sessionTimeout) * 1000 + 30000);
			}
		});
	}

});