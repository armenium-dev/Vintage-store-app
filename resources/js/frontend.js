(function($){
	'use strict';

	jQuery(document).ready(function($){

		const FJS = {
			options: {},
			vars: {
				ww: 0,
				wh: 0,
			},
			labels: {},
			messages: {
				ajax_error: 'SYSTEM TECHNICAL ERROR'
			},
			routes: {},
			els: {
				body: $("body"),
			},
			Core: {
				Init: function(){
					FJS.Uploader.init();
					this.initEvents();
					this.eventResizeWindow();
				},
				initEvents: function(){
					$(window)
						.on('scroll', FJS.Core.eventScrollWindow)
						.on('resize orientationchange deviceorientation', FJS.Core.eventResizeWindow);

					$(document)
						.on('blur', '[data-trigger="js_action_blur"]', FJS.Core.doAction)
						.on('change', '[data-trigger="js_action_change"]', FJS.Core.doAction)
						.on('click', '[data-trigger="js_action_click"]', FJS.Core.doAction)
						.on('submit', '[data-trigger="js_action_submit"]', FJS.Core.doAction);
				},
				eventResizeWindow: function(){
					FJS.vars.ww = $(window).width();
					FJS.vars.wh = $(window).height();
				},
				doAction: function(e){
					const $this = $(this),
						action = $(this).data('action');

					switch(action){
						case "validate_uploader_form":
							FJS.Uploader.validate($this);
							break;
						case "submit_uploader_form":
							FJS.Uploader.submit($this);
							break;
						default:
							break;
					}

					e.preventDefault();
				},
			},
			Uploader: {
				init: function(){
				},
				validate: function($btn){
					let $form = $btn.parents('form'),
						$file_fields = $form.find('input[type="file"]'),
						flag = true;

					$file_fields.each(function(i, el){
						console.log($(el).get(0).files.length);
						if($(el).get(0).files.length === 0){
							flag = false;
						}
					});

					if(flag){
						$form.submit();
					}

					console.log($form.data('validate'));

					return flag;
				},
				submit: function($form){
				},
			},
		};

		FJS.Core.Init();
	});

})(jQuery);
