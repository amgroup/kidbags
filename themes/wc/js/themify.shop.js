//////////////////////////////
// Test if touch event exists
//////////////////////////////
function is_touch_device() {
	try {
		document.createEvent("TouchEvent");
		return true;
	} catch(e) {
		return false;
	}
}


jQuery(window).load(function() {
	/////////////////////////////////////////////
	// Product slider
	/////////////////////////////////////////////
	if (jQuery('.product-slides').length > 0) {

		// Parse data from wp_localize_script
		themifyShop.autoplay = parseInt(themifyShop.autoplay);
		themifyShop.speed = parseInt(themifyShop.speed);
		themifyShop.scroll = parseInt(themifyShop.scroll);
		themifyShop.visible = parseInt(themifyShop.visible);
		if (null == themifyShop.wrap) {
			themifyShop.wrap = false;
		} else {
			themifyShop.wrap = true;
		}
		if (0 == themifyShop.autoplay) {
			themifyShop.play = false;
		} else {
			themifyShop.play = true;
		}

		jQuery('.product-slides').carouFredSel({
			responsive : true,
			prev : '#product-slider .carousel-prev',
			next : '#product-slider .carousel-next',
			pagination : "#product-slider .carousel-pager",
			width : '100%',
			circular : themifyShop.wrap,
			infinite : themifyShop.wrap,
			auto : {
				play : themifyShop.play,
				pauseDuration : themifyShop.autoplay * 1000,
				duration : themifyShop.speed
			},
			scroll : {
				items : themifyShop.scroll,
				duration : themifyShop.speed,
				wipe : true
			},
			items : {
				visible : {
					min : 1,
					max : themifyShop.visible
				},
				width : 150
			},
			onCreate : function() {
				jQuery('.product-sliderwrap').css({
					'height' : 'auto',
					'visibility' : 'visible'
				});
			}
		});
		
	}
});

function toggleCartTag(){

	$ct = jQuery('#cart-tag');
	$cw = jQuery('#cart-wrap');
/*	
	if(jQuery('#cart-list .product').length < 1){
		$ct.css('visibility', 'hidden');
		$cw.css('visibility', 'hidden');
	} else {*/
		$ct.css('visibility', 'visible');
		$cw.css('visibility', 'visible');
//	}
}

jQuery(document).ready(function($) {

	/////////////////////////////////////////////
	// Check is_mobile
	/////////////////////////////////////////////
	$('body').addClass(is_touch_device() ? 'is_mobile' : 'is_desktop');

	/////////////////////////////////////////////
	// Enable jScrollPane if is_desktop
	/////////////////////////////////////////////
	$('.is_desktop #cart-list').jScrollPane();
	$('.is_desktop #cart-wrap').hide().css('visibility', 'visible');

	/////////////////////////////////////////////
	// Toggle Cart
	/////////////////////////////////////////////
	$('#cart-tag').live('click', function() {
		$('#cart-wrap').slideToggle().css('visibility', 'visible');
		return false;
	});

	/////////////////////////////////////////////
	// Toggle sorting nav
	/////////////////////////////////////////////
	$(".sort-by").on("click", function(e){
    if($(this).next().is(':visible')) {
      $(this).next().slideUp();
      $(this).removeClass('active');
    }
    else{
      $(this).next().slideDown();
      $(this).addClass('active');
    }
    e.preventDefault();
	});

	$(".orderby-wrap").on("hover", function(e){
    if(e.type == 'mouseenter') {
      if(!$(this).find('.orderby').is(':visible')) {
        $(this).find('.orderby').slideDown();
        $(this).find('.sort-by').addClass('active');
      }
    }
    if(e.type == 'mouseleave') {
      if($(this).find('.orderby').is(':visible') && $(this).find('.sort-by').hasClass('active')) {
        $(this).find('.orderby').slideUp();
        $(this).find('.sort-by').removeClass('active');
      }
    }
    e.preventDefault();
	});
	
	// Toggle Cart Button
	toggleCartTag();

	/////////////////////////////////////////////
	// Add to cart ajax
	/////////////////////////////////////////////
	if (woocommerce_params.option_ajax_add_to_cart == 'yes') {

		// Ajax add to cart
		$(document).on( 'click', '.custom_add_to_cart_button', function() {

			var shopdock = $('#shopdock');
			var cart = $('#cart-wrap');

			// hide cart wrap
			cart.hide();

			// This loading icon
			var $loadingIcon = $('.loading-product', $(this).closest('.product'));

			// AJAX add to cart request
			var $thisbutton = $(this);

			if ($thisbutton.is('.product_type_simple, .product_type_downloadable, .product_type_virtual')) {

				if (!$thisbutton.attr('data-product_id')) return true;

				$thisbutton.removeClass('added');
				$thisbutton.addClass('loading');
				
				$('#cart-loader').addClass('loading');
				// Show loading animation
				$loadingIcon.show();

				var data = {
					action: 		'woocommerce_add_to_cart',
					product_id: 	$thisbutton.attr('data-product_id'),
					quantity:       $thisbutton.attr('data-quantity'),
					security: 		woocommerce_params.add_to_cart_nonce
				};

				// Trigger event
				$('body').trigger( 'adding_to_cart', [ $thisbutton, data ] );

				// Ajax action
				$.post( woocommerce_params.ajax_url, data, function( response ) {

					if ( ! response )
						return;

					var this_page = window.location.toString();

					this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );

					if ( response.error && response.product_url ) {
						window.location = response.product_url;
						return;
					}

					// Redirect to cart option
					if ( woocommerce_params.cart_redirect_after_add == 'yes' ) {

						window.location = woocommerce_params.cart_url;
						return;

					} else {

						$thisbutton.removeClass('loading');

						fragments = response.fragments;
						cart_hash = response.cart_hash;

						// Block fragments class
						if ( fragments ) {
							$.each(fragments, function(key, value) {
								$(key).addClass('updating');
							});
						}

						// Block widgets and fragments
						$('.shop_table.cart, .updating, .cart_totals, .widget_shopping_cart').fadeTo('400', '0.6').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } } );

						// Changes button classes
						if ( $thisbutton.parent().find('.added_to_cart').size() == 0 )
							$thisbutton.addClass('added');

						// Replace fragments
						if ( fragments ) {
							$.each(fragments, function(key, value) {
								$(key).replaceWith(value);
							});
						}

						// Unblock
						$('.widget_shopping_cart').load( this_page + ' .widget_shopping_cart:eq(0) > *', function() {
							$.getScript(woocommerce_params.plugin_url + '/assets/js/frontend/cart-fragments.min.js', function() {
								$('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();
								// Toggle Cart Button
								toggleCartTag();
							});
						});

						// Cart page elements
						$('.shop_table.cart').load( this_page + ' .shop_table.cart:eq(0) > *', function() {

							$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

							$('.shop_table.cart').stop(true).css('opacity', '1').unblock();

							$('body').trigger('cart_page_refreshed');
							// Toggle Cart Button
							toggleCartTag();
						});

						$('.cart_totals').load( this_page + ' .cart_totals:eq(0) > *', function() {
							$('.cart_totals').stop(true).css('opacity', '1').unblock();
						});

						// shopdock load
						if (shopdock.size() > 0) {
							$('#shopdock').load( woocommerce_params.ajax_url + '?action=get_dynamic_shopdock' + ' #shopdock > *', function() {
								$('.is_desktop #cart-list').jScrollPane();
								$('#cart-wrap').hide();
								
								// Hides loading animation
								$loadingIcon.hide(300, function(){
									$(this).addClass('loading-done');
								});
								$loadingIcon
									.fadeIn()
									.delay(500)
									.fadeOut(300, function(){
									$(this).removeClass('loading-done');
								});

								// Toggle Cart Button
								toggleCartTag();
							});
						}

						// Trigger event so themes can refresh other areas
						$('body').trigger( 'added_to_cart', [ fragments, cart_hash ] );
					}
				});

				return false;

			} else {
				return true;
			}

		});

		// remove item ajax
		$(document).on( 'click', '.remove-item-js', function() {
			var href = $(this).attr('href'),
					shopdock = $('#shopdock');

			$('#cart-loader').addClass('loading');

			// AJAX add to cart request
			var $thisbutton = $(this);

			$.get(href, function(response) {

				var this_page = window.location.toString();
				this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );

				// Block widgets and fragments
				$('.shop_table.cart, .updating, .cart_totals, .widget_shopping_cart').fadeTo('400', '0.6').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } } );

				// Unblock
				$('.widget_shopping_cart').load( this_page + ' .widget_shopping_cart:eq(0) > *', function() {
					$.getScript(woocommerce_params.plugin_url + '/assets/js/frontend/cart-fragments.min.js', function() {
						$('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();
						// Toggle Cart Button
						toggleCartTag();
					});
				});

				// Cart page elements
				$('.shop_table.cart').load( this_page + ' .shop_table.cart:eq(0) > *', function() {

					$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

					$('.shop_table.cart').stop(true).css('opacity', '1').unblock();

					$('body').trigger('cart_page_refreshed');
					// Toggle Cart Button
					toggleCartTag();
				});

				$('.cart_totals').load( this_page + ' .cart_totals:eq(0) > *', function() {
					$('.cart_totals').stop(true).css('opacity', '1').unblock();
				});

				// shopdock load
				if (shopdock.size() > 0) {
					$('#shopdock').load( woocommerce_params.ajax_url + '?action=get_dynamic_shopdock' + ' #shopdock > *', function() {
						$('.is_desktop #cart-list').jScrollPane();
						// Toggle Cart Button
						toggleCartTag();
					});
				}

				// Trigger event so themes can refresh other areas
				$('body').trigger('added_to_cart');

				// Toggle Cart Button
				toggleCartTag();

			});

			return false;
		});

		// Ajax add to cart in single page
		var inlightbox = false;
		ajax_add_to_cart_single_page(inlightbox);

	}

	// ajax variation lightbox
	$(".is_desktop a[rel^='prettyPhoto[ajax]']").prettyPhoto({
		social_tools : false,
		deeplinking : false,
		allowresize : true,
		changepicturecallback : function() {
			var inlightbox = true,
      content_h = $('#product_single_wrapper').height() + 70;
      $('.pp_gallery, .pp_nav').hide();
      ajax_variation_callback();
      ajax_add_to_cart_single_page(inlightbox);
      $('.pp_content').height(content_h);
		}
	});

	// reply review
	$('#reviews #respond').hide();
	$('.reply-review').click(function() {
		$('#respond').slideToggle('slow');
		return false;
	});

	// add review
	$('.add-reply-js').click(function() {
		$(this).hide();
		$('#respond').slideDown('slow');
		$('#cancel-comment-reply-link').show();
		return false;
	});
	$('#reviews #cancel-comment-reply-link').click(function() {
		$(this).hide();
		$('#respond').slideUp();
		$('.add-reply-js').show();
		return false;
	});

	/*function ajax add to cart in single page */
	function ajax_add_to_cart_single_page(inlightbox) {
		$('form.cart').submit(function() {
			var data = $(this).serialize();
			var url = $(this).attr('action');
			var shopdock = $('#shopdock');

			// This loading icon
			if ($('#product_single_wrapper .loading-product').length > 0) {
				var $loadingIcon = $('.loading-product', $('#product_single_wrapper'));
			}
			$loadingIcon.show();

			// Ajax action
			$('#cart-loader').addClass('loading');
			// show loader
			$.post(url, data, function(response) {

				var this_page = window.location.toString();
				this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );

				// Block widgets and fragments
				$('.shop_table.cart, .updating, .cart_totals, .widget_shopping_cart').fadeTo('400', '0.6').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } } );

				// Cart widget loaded
				$('.widget_shopping_cart').load( this_page + ' .widget_shopping_cart:eq(0) > *', function() {
					$.getScript(woocommerce_params.plugin_url + '/assets/js/frontend/cart-fragments.min.js', function() {
						$('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();

						// Hides loading animation
						$loadingIcon.hide(300, function(){
							$(this).addClass('loading-done');
						});
						$loadingIcon
							.fadeIn()
							.delay(500)
							.fadeOut(300, function(){
							$(this).removeClass('loading-done');
							// close lightbox
							if(inlightbox) {
								$.prettyPhoto.close();
							}
						});

						// Toggle Cart Button
						toggleCartTag();
					});
				});

				// shopdock load
				if (shopdock.size() > 0) {
					$('#shopdock').load( woocommerce_params.ajax_url + '?action=get_dynamic_shopdock' + ' #shopdock > *', function() {
						$('.is_desktop #cart-list').jScrollPane();
						$('#cart-wrap').hide();
						
						// Hides loading animation
						$loadingIcon.hide(300, function(){
							$(this).addClass('loading-done');
						});
						$loadingIcon
							.fadeIn()
							.delay(500)
							.fadeOut(300, function(){
							$(this).removeClass('loading-done');
							// close lightbox
							if(inlightbox) {
								$.prettyPhoto.close();
							}
						});
						// Toggle Cart Button
						toggleCartTag();
					});
				}

				// Cart page elements
				$('.shop_table.cart').load( this_page + ' .shop_table.cart:eq(0) > *', function() {

					$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

					$('.shop_table.cart').stop(true).css('opacity', '1').unblock();

					$('body').trigger('cart_page_refreshed');
					// Toggle Cart Button
					toggleCartTag();
				});

				$('.cart_totals').load( this_page + ' .cart_totals:eq(0) > *', function() {
					$('.cart_totals').stop(true).css('opacity', '1').unblock();
				});

				// Trigger event so themes can refresh other areas
				$('body').trigger('added_to_cart');

				// Toggle Cart Button
				toggleCartTag();

			});
			return false;
		});
	}

	/* function ajax variation callback */
	function ajax_variation_callback() {
		var themify_product_variations = jQuery.parseJSON($('#themify_product_vars').text());

		//check if two arrays of attributes match
		function variations_match(attrs1, attrs2) {
			var match = true;
			for (name in attrs1) {
				var val1 = attrs1[name];
				var val2 = attrs2[name];

				if (val1.length != 0 && val2.length != 0 && val1 != val2) {
					match = false;
				}
			}

			return match;
		}

		//show single variation details (price, stock, image)
		function show_variation(variation) {
			var img = $('div.images img:eq(0)');
			var link = $('div.images a.zoom:eq(0)');
			var o_src = $(img).attr('original-src');
			var o_link = $(link).attr('original-href');

			var variation_image = variation.image_src;
			var variation_link = variation.image_link;

			$('.variations_button').show();
			$('.single_variation').html(variation.price_html + variation.availability_html);

			if (!o_src) {
				$(img).attr('original-src', $(img).attr('src'));
			}

			if (!o_link) {
				$(link).attr('original-href', $(link).attr('href'));
			}

			if (variation_image && variation_image.length > 1) {
				$(img).attr('src', variation_image);
				$(link).attr('href', variation_link);
			} else {
				$(img).attr('src', o_src);
				$(link).attr('href', o_link);
			}

			if (variation.sku) {
				$('.product_meta').find('.sku').text(variation.sku);
			} else {
				$('.product_meta').find('.sku').text('');
			}

			$('.single_variation_wrap').slideDown('200').trigger('variationWrapShown').trigger('show_variation');
			// depreciated variationWrapShown
		}

		//disable option fields that are unavaiable for current set of attributes
		function update_variation_values(variations) {

			// Loop through selects and disable/enable options based on selections
			$('.variations select').each(function(index, el) {

				current_attr_select = $(el);

				// Disable all
				current_attr_select.find('option:gt(0)').attr('disabled', 'disabled');

				// Get name
				var current_attr_name = current_attr_select.attr('name');

				// Loop through variations
				for (num in variations) {
					var attributes = variations[num].attributes;

					for (attr_name in attributes) {
						var attr_val = attributes[attr_name];

						if (attr_name == current_attr_name) {
							if (attr_val) {

								// Decode entities
								attr_val = $("<div/>").html(attr_val).text();

								// Add slashes
								attr_val = attr_val.replace(/'/g, "\\'");
								attr_val = attr_val.replace(/"/g, "\\\"");

								// Compare the meercat
								current_attr_select.find('option[value="' + attr_val + '"]').removeAttr('disabled');

							} else {
								current_attr_select.find('option').removeAttr('disabled');
							}
						}
					}
				}

			});

		}

		//search for matching variations for given set of attributes
		function find_matching_variations(settings) {
			var matching = [];

			for (var i = 0; i < themify_product_variations.length; i++) {
				var variation = themify_product_variations[i];
				var variation_id = variation.variation_id;

				if (variations_match(variation.attributes, settings)) {
					matching.push(variation);
				}
			}
			return matching;
		}

		//when one of attributes is changed - check everything to show only valid options
		function check_variations(exclude) {
			var all_set = true;
			var current_settings = {};

			$('.variations select').each(function() {

				if (exclude && $(this).attr('name') == exclude) {

					all_set = false;
					current_settings[$(this).attr('name')] = '';

				} else {
					if ($(this).val().length == 0)
						all_set = false;

					// Encode entities
					value = $(this).val().replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

					// Add to settings array
					current_settings[$(this).attr('name')] = value;
				}

			});

			var matching_variations = find_matching_variations(current_settings);

			if (all_set) {
				var variation = matching_variations.pop();
				if (variation) {
					$('form input[name=variation_id]').val(variation.variation_id);
					show_variation(variation);
				} else {
					// Nothing found - reset fields
					$('.variations select').val('');
				}
			} else {
				update_variation_values(matching_variations);
			}
		}


		$('.variations select').change(function() {

			$('form input[name=variation_id]').val('');
			$('.single_variation_wrap').hide();
			$('.single_variation').text('');
			check_variations();
			$(this).blur();
			if ($().uniform && $.isFunction($.uniform.update)) {
				$.uniform.update();
			}

		}).focus(function() {

			check_variations($(this).attr('name'));

		}).change();

		// Quantity buttons
		$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

	}

});
