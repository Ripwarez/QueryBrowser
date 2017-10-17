$(function () {
    QBR = function () {
 // QueryBrowserResult namespace

        /* public */
        return {
            getForm: function (formId) {
                var form = $('form#' + formId);
                if (form.length == 0) {
                    var div = $('#' + formId);
                    form = $('<form id="' + div.data('id') + '" action="' + div.data('form-action') + '" method="' + div.data('form-method') + '"></form>');
                    form.append('<input type="hidden" name="qbId" value="' + div.data('id') + '">');
                    form.append('<input type="hidden" name="qbPage" value="' + div.data('page') + '">');
                    form.append('<input type="hidden" name="qbPageSize" value="' + div.data('pagesize') + '">');
                    form.append('<input type="hidden" name="qbGlobalSearch" value="' + div.data('globalsearch') + '">');
                    form.append('<input type="hidden" name="qbOrderBy" value="' + div.data('orderby') + '">');
                    form.append('<input type="hidden" name="qbOrderDirection" value="' + div.data('orderdirection') + '">');
                    $('body').append(form);
                }

                return form;
            },

            gotoPage: function (formId, pageNumber) {
                var form = QBR.getForm(formId);
                form.find('input[name=qbPage]').val(pageNumber);
                form.attr('action', '');
                form.submit();
            },

            doSort: function (formId, column) {
                var form = QBR.getForm(formId);
                if (form.find('input[name=qbOrderBy]').val() == column) {
                    if (form.find('input[name=qbOrderDirection]').val() == 'desc') {
                        form.find('input[name=qbOrderDirection]').val('asc');
                    } else {
                        form.find('input[name=qbOrderDirection]').val('desc');
                    }
                } else {
                    form.find('input[name=qbOrderBy]').val(column);
                    form.find('input[name=qbOrderDirection]').val('asc');
                }

                QBR.gotoPage(formId, 1);
            },

            setPageSize(formId, pageSize) {
                var form = QBR.getForm(formId);
                form.find('input[name=qbPageSize]').val(pageSize);
                QBR.gotoPage(formId, 1);
            },

            doSearch: function (formId, searchString) {
                var form = QBR.getForm(formId);
                form.find('input[name=qbGlobalSearch]').val(searchString);
                QBR.gotoPage(formId, 1);
            },

            init: function () {
                $('input[name=qbr_q]').keypress(function (e) {
                    if (e.which == 13) {
                        var formId = $(this).parents('.qbr').data('id');
                        QBR.doSearch(formId, $(this).val());
                        return false;
                    }
                });
            }
        };
    }();

    QBR.init();
});