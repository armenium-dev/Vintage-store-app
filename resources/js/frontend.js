(function($){
	'use strict';

	jQuery(document).ready(function($){

		const FJS = {
			options: {},
			vars: {ww: 0, wh: 0},
			labels: {},
			messages: {ajax_error: 'SYSTEM TECHNICAL ERROR'},
			routes: {
				remove_sale: "sales-remove",
				pick_product: "warehouse-pick-product"
			},
			els: {},
			Main: {
				Init: function(){
					FJS.Uploader.init();
					FJS.MysteryBox.init();
					this.initEvents();
					this.eventResizeWindow();
				},
				initEvents: function(){
					$(window)
						.on('scroll', FJS.Main.eventScrollWindow)
						.on('resize orientationchange deviceorientation', FJS.Main.eventResizeWindow);

					$(document)
						.on('blur', '[data-trigger="js_action_blur"]', FJS.Main.doAction)
						.on('change', '[data-trigger="js_action_change"]', FJS.Main.doAction)
						.on('click', '[data-trigger="js_action_click"]', FJS.Main.doAction)
						.on('submit', '[data-trigger="js_action_submit"]', FJS.Main.doAction);
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
						case "delete_sale":
							FJS.Sales.remove($this);
							break;
						case "copy_to_clipboard":
							FJS.Common.copyToClipboard($this);
							break;
						case "load_order_more_info":
							FJS.Orders.loadMoreInfo($this);
							break;
						case "choice_mb_product":
							FJS.MysteryBox.choiceProduct($this);
							break;
						case "pick_product":
							FJS.MysteryBox.pickProduct($this);
							break;
						default:
							break;
					}

					e.preventDefault();
				},
			},
			Common: {
				createAjaxUrl: function(endpoint){
					let baseurl = $('meta[name="baseurl"]').attr('content');

					return baseurl + '/' + endpoint;
				},
				copyToClipboard: function($btn){
					let $source = $($btn.data('source')),
						text = $source.text();

					if(navigator && navigator.clipboard && navigator.clipboard.writeText){
						return navigator.clipboard.writeText(text);
					}else{
						const el = document.createElement('textarea');
						el.value = text;
						el.setAttribute('readonly', '');
						el.style.position = 'absolute';
						el.style.left = '-9999px';
						document.body.appendChild(el);
						el.select();
						document.execCommand('copy');
						document.body.removeChild(el);
					}

					$source.addClass('color-red');
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
			Sales: {
				remove: function($btn){
					let entry_id = $btn.data('id'),
						$parent = $($btn.data('parent'));

					$.ajax({
						type: "POST",
						url: FJS.Common.createAjaxUrl(FJS.routes.remove_sale),
						headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
						data: {id: entry_id},
						dataType: "json",
						beforeSend: function(xhr){
							$btn.attr('disabled', true).find('span').text('Removing...');
						}
					}).done(function(response){
						if(response.error == 0){
							$parent.find('ol').addClass('line-through color-gray');
							setTimeout(function(){
								$btn.addClass('hidden');
							}, 1000);
						}
					}).fail(function(){
						$btn.attr('disabled', false).find('span').text('Remove');
						console.log(FJS.messages.ajax_error);
					});

				},
			},
			Orders: {
				init: function(){
					// set the dropdown menu element
					const targetEl = document.getElementById('dropdownMenu');

					// set the element that trigger the dropdown menu on click
					const triggerEl = document.getElementById('dropdownButton');

					// options with default values
					const options = {
						placement: 'bottom',
						onHide: () => {
							console.log('dropdown has been hidden');
						},
						onShow: () => {
							console.log('dropdown has been shown');
						}
					};

					const dropdown = new Dropdown(targetEl, triggerEl, options);
				},
				loadMoreInfo: function($obj){
					let $dropdown_toggle = $($obj.data('dropdown-toggle'));

					if($dropdown_toggle.is('empty')){
						console.log($obj);
					}
				},
			},
			MysteryBox: {
				init: function(){
					FJS.MysteryBox._changeSubmitStatus();
					FJS.MysteryBox._changeCheckboxesStatus();
				},
				choiceProduct: function($obj){
					let $parent = $($obj.data('parent')),
						$container = $parent.parent('ul'),
						max_count = $container.data('choice-max-count'),
						checked_count = $container.find('[type="checkbox"]:checked').length;

					if(checked_count === max_count){
						$container.find('[type="checkbox"]:not(:checked)').prop('disabled', true);
					}else{
						$container.find('[type="checkbox"]').prop('disabled', false);
					}

					if($obj.is(':checked')){
						$parent.addClass('dark');
					}else{
						$parent.removeClass('dark');
					}

					FJS.MysteryBox._changeSubmitStatus();
					//console.log(max_count, checked_count);
				},
				_changeCheckboxesStatus: function(){
					const $form = $('#js_mbox_form');
					let $containers = $form.find('.js_container'),
						max_choices = $form.find('[type="checkbox"]:checked').length,
						max_items = 0;

					$containers.each(function(){
						let $container = $(this);
						let max_count = ~~$container.data('choice-max-count');
						let checked_count = $container.find('[type="checkbox"]:checked').length;

						if(checked_count === max_count){
							$container.find('[type="checkbox"]:not(:checked)').prop('disabled', true);
						}
					});
				},
				_changeSubmitStatus: function(){
					const $form = $('#js_mbox_form');
					let $containers = $form.find('.js_container'),
						max_choices = $form.find('[type="checkbox"]:checked').length,
						max_items = 0;

					$containers.each(function(){
						max_items += ~~$(this).data('choice-max-count');
					});

					$form.find('[type="submit"]').prop('disabled', !(max_choices === max_items));
					//console.log($form.find('[type="submit"]').prop('disabled'));
				},
				pickProduct: function($btn){
					let entry_id = $btn.data('id'),
						$parent = $('#item_'+entry_id);

					$.ajax({
						type: "POST",
						url: FJS.Common.createAjaxUrl(FJS.routes.pick_product),
						headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
						data: {id: entry_id},
						dataType: "json",
						beforeSend: function(xhr){
							$btn.attr('disabled', true).find('span').text('............');
						}
					}).done(function(response){
						if(response.error === 0){
							let btn_text = '';

							if(response.selected === 1){
								btn_text = 'Cancel';
								$parent.addClass('dark');
							}else{
								btn_text = 'Found id';
								$parent.removeClass('dark');
							}

							$btn.attr('disabled', false).find('span').text(btn_text);
						}
					}).fail(function(){
						$btn.attr('disabled', false).find('span').text('Found id');
						console.log(FJS.messages.ajax_error);
					});

				},
			}
		};

		FJS.Main.Init();
	});

})(jQuery);
