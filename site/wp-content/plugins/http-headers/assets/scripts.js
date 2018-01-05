(function ($, undefined) {
	$(function() {
		"use strict";

		$(document).on('change', 'select[name="hh_x_frame_options_value"]', function () {
			var $el = $('input[name="hh_x_frame_options_domain"]'),
				readOnly = $(this).find('option:selected').val() != 'allow-from';
			if ($el.length) {
				$el.prop('readOnly', readOnly).toggle(!readOnly);
			}
		}).on('change', 'select[name="hh_x_xxs_protection_value"]', function (e) {
			var $el = $('input[name="hh_x_xxs_protection_uri"]'),
				readOnly = $(this).find('option:selected').val() != '1; report=';
			if ($el.length) {
				$el.prop('readOnly', readOnly).toggle(!readOnly);
			}
		}).on('change', 'select[name="hh_x_powered_by_option"]', function () {
			var $el = $('input[name="hh_x_powered_by_value"]'),
				readOnly = $(this).find('option:selected').val() != 'set';
			if ($el.length) {
				$el.prop('readOnly', readOnly).toggle(!readOnly);
			}
		}).on('change', 'select[name="hh_access_control_allow_origin_value"]', function () {
			var $el = $('input[name="hh_access_control_allow_origin_url"]'),
				readOnly = $(this).find('option:selected').val() != 'origin';
			if ($el.length) {
				$el.prop('readOnly', readOnly).toggle(!readOnly);
			}
		}).on('change', 'select[name="hh_timing_allow_origin_value"]', function () {
			var $el = $('input[name="hh_timing_allow_origin_url"]'),
				readOnly = $(this).find('option:selected').val() != 'origin';
			if ($el.length) {
				$el.prop('readOnly', readOnly).toggle(!readOnly);
			}
		}).on('change', '.http-header', function () {
			var $this = $(this),
				$el = $this.closest('tr').find('.http-header-value');
			
			if (!$el.length) {
				return;
			}
			
			if (Number($this.val()) === 1) {
				$el.prop('readOnly', false).removeAttr('readonly').removeClass('readonly');
			} else {
				$el.prop('readOnly', true).addClass('readonly');
			}
		}).on('change', 'input[name="hh_x_frame_options"]', function () {
			$('select[name="hh_x_frame_options_value"]').trigger('change');
		}).on('change', 'input[name="hh_x_powered_by"]', function () {
			$('select[name="hh_x_powered_by_option"]').trigger('change');
		}).on('change', 'input[name="hh_access_control_allow_origin"]', function () {
			$('select[name="hh_access_control_allow_origin_value"]').trigger('change');
		}).on('change', 'input[name="hh_timing_allow_origin"]', function () {
			$('select[name="hh_timing_allow_origin_value"]').trigger('change');
		}).on('submit', '#frmIspect', function (e) {
			e.preventDefault();
			var $this = $(this);
			$.post($this.attr('action'), $this.serialize()).done(function (data) {
				$('#hh-result').html(data);
			});
			return false;
		}).on('change', '#authentication', function () {
			var $a = $('#box-authentication');
			if (this.checked) {
				$a.show();
			} else {
				$a.hide();
			}
		}).on('click', '#hh-btn-add-header', function () {
			$(this).closest('tr').before('<tr> \
					<td><input type="text" name="hh_custom_headers_value[name][]" class="http-header-value" placeholder="X-Custom-Name"></td> \
					<td><input type="text" name="hh_custom_headers_value[value][]" class="http-header-value" placeholder="some value"></td> \
					<td><button type="button" class="button button-small hh-btn-delete-header" title="Delete">x</button></td> \
				</tr>');
		}).on('click', '.hh-btn-delete-header', function () {
			$(this).closest('tr').remove();
		});
		
		$('.hh-tabs').on('click', 'ul a', function (e) {
			e.preventDefault();
			
			var $this = $(this);
			$($this.attr('href'))
				.removeClass('hh-hidden').addClass('hh-tab-active').attr('aria-hidden', 'false').attr('aria-expanded', 'true')
				.siblings('div').addClass('hh-hidden').removeClass('hh-tab-active').attr('aria-hidden', 'true').attr('aria-expanded', 'false');
			$this.closest('li')
				.addClass('hh-active').attr('aria-selected', 'true').attr('tabindex', 0)
				.siblings('li').removeClass('hh-active').attr('aria-selected', 'false').attr('tabindex', -1);
		}).each(function () {
			var $this = $(this),
				$ul = $this.children('ul').attr('role', 'tablist'),
				$li = $ul.children('li').attr('role', 'tab')
					.not(':first').attr('aria-selected', 'false').attr('tabindex', -1)
					.end().eq(0).attr('aria-selected', 'true').attr('tabindex', 0)
					.end(),
				$a = $li.find('a').attr('role', 'presentation').attr('tabindex', -1),
				$div = $this.children('div').attr('role', 'tabpanel')
					.not(':first').attr('aria-hidden', 'true').attr('aria-expanded', 'false')
					.end().eq(0).attr('aria-hidden', 'false').attr('aria-expanded', 'true')
					.end();
			
			$li.each(function (i) {
				var $this = $(this),
					id = 'hh-tabs-' + Math.ceil(Math.random() * 999999) + '-' + i,
					$a = $this.attr('aria-labelledby', id).find('a').attr('id', id),
					href = $a.attr('href');
				$this.attr('aria-controls', href.substring(1)).attr('aria-labelledby', id);
				$(href).attr('aria-labelledby', id);
			});
			
		});
	});
})(jQuery);