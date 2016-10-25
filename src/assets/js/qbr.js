$(function(){
	QBR = function() { // QueryBrowserResult namespace

		/* public */
		return {
			getForm: function(formId) {
				var form = $('form#' + formId);
				if ( form.size() == 0 ) {
					var div = $('#' + formId);
					form = $('<form id="' + div.data('id') + '" action="' + div.data('form-action') + '" method="' + div.data('form-method') + '"></form>');
					form.append('<input type="hidden" name="qbId" value="' + div.data('id') + '">');
					form.append('<input type="hidden" name="qbPage" value="' + div.data('page') + '">');
					form.append('<input type="hidden" name="qbSearchString" value="">');
					form.append('<input type="hidden" name="qbOrderColumn" value="' + div.data('order-column') + '">');
					form.append('<input type="hidden" name="qbOrderDirection" value="' + div.data('order-direction') + '">');
					$('body').append(form);
				}

				return form;
			},

			gotoPage: function(formId, pageNumber) {
				var form = QBR.getForm(formId);
				form.find('input[name=qbPage]').val(pageNumber);
				form.attr('action', '');
				form.submit();
			},

			doSort: function(formId, column) {
				var form = QBR.getForm(formId);
				if ( form.find('input[name=qbOrderColumn]').val() == column ) {
					if ( form.find('input[name=qbOrderDirection]').val() == 'desc' ) {
						form.find('input[name=qbOrderDirection]').val('asc');
					}
					else {
						form.find('input[name=qbOrderDirection]').val('desc');
					}
				}
				else {
					form.find('input[name=qbOrderColumn]').val(column);
					form.find('input[name=qbOrderDirection]').val('asc');
				}

				QBR.gotoPage(formId, 1);
			},

			doSearch: function(formId) {
				var form = QBR.getForm(formId);
				form.find('input[name=qbSearchString]').val($('.' + formId + '_q').val());
				QBR.gotoPage(formId, 1);
			},

			cbToggleAll: function(formId, checkBox) {
				var form = $('#' + formId);
				if ( checkBox.checked ) {
					form.find('.removeId').prop('checked', true);
					form.find('tbody tr').addClass('warning');
					if ( form.find('.removeId:checked').size() > 0 ) {
						form.find('.btn-disabled').removeAttr('disabled');
					}
				}
				else {
					form.find('.removeId').prop('checked', false);
					form.find('tbody tr').removeClass('warning');
					form.find('.btn-disabled').attr('disabled', true);
				}

			},

			cbToggle: function(formId, checkBox) {
				var form = $('#' + formId);
				$(checkBox).parents('tr').toggleClass('warning');
				var totalCheckBoxes = form.find('.removeId').size();
				var totalChecked = form.find('.removeId:checked').size();
				form.find('.checkAll').prop('checked', ( totalChecked == totalCheckBoxes ));
				if ( totalChecked > 0 ) {
					form.find('.btn-disabled').removeAttr('disabled');
				}
				else {
					form.find('.btn-disabled').attr('disabled', true);
				}
			},

			confirmDelete: function(formId,  modalTitle, modalBody) {
				var form = $('#' + formId);
				var c = form.find('.removeId:checked').length;
				if ( c > 0 ) {
					var confirmModal = $('#' + formId + '-confirm-modal');
					$(confirmModal).find('.modal-title').html(modalTitle);
					$(confirmModal).find('.modal-body').html(modalBody);
					$(confirmModal).find('.nr-selected-rows').html(c);
					$(confirmModal).modal('show');
				}
			},

			doDelete: function(formId, uri) {
				var form = QBR.getForm(formId);
				form.attr('action', uri);
				$('#' + formId + ' .removeId:checked').clone().hide().appendTo(form);
				form.submit();
			},

			init: function() {
				$('input[name=qbr_q]').keypress(function(e) {
					if ( e.which == 13 ) {
						var formId = $(this).parents('.qbr').find('div[id^="qb_"]').prop('id');
						QBR.doSearch(formId);
						return false;
					}
				});
			}
		};
	}();

	QBR.init();
});