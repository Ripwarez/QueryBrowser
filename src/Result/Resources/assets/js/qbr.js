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
                    form.append('<input type="hidden" name="qb[id]" value="' + div.data('id') + '">');
                    form.append('<input type="hidden" name="qb[page]" value="' + div.data('page') + '">');
                    form.append('<input type="hidden" name="qb[pageSize]" value="' + div.data('pagesize') + '">');
                    //form.append('<input type="hidden" name="qbGlobalSearch" value="' + div.data('globalsearch') + '">');
                    form.append('<input type="hidden" name="qb[orderBy][field]" value="' + div.data('orderby') + '">');
                    form.append('<input type="hidden" name="qb[orderBy][direction]" value="' + div.data('orderdirection') + '">');
                    $('body').append(form);
                }

                return form;
            },

            gotoPage: function (formId, pageNumber) {
                var form = QBR.getForm(formId);
                form.find('input[name="qb[page]"]').val(pageNumber);
                form.attr('action', '');
                form.submit();
            },

            doSort: function (formId, column) {
                var form = QBR.getForm(formId);
                if (form.find('input[name="qb[orderBy][field]"]').val() == column) {
                    if (form.find('input[name="qb[orderBy][direction]"]').val() == 'desc') {
                        form.find('input[name="qb[orderBy][direction]"]').val('asc');
                    } else {
                        form.find('input[name="qb[orderBy][direction]"]').val('desc');
                    }
                } else {
                    form.find('input[name="qb[orderBy][field]"]').val(column);
                    form.find('input[name="qb[orderBy][direction]"]').val('asc');
                }

                QBR.gotoPage(formId, 1);
            },

            setPageSize(formId, pageSize) {
                var form = QBR.getForm(formId);
                form.find('input[name="qb[pageSize]"]').val(pageSize);
                QBR.gotoPage(formId, 1);
            },

            doSearch: function (formId, searchString) {
                var form = QBR.getForm(formId);
                form.find('input[name=qbGlobalSearch]').val(searchString);
                QBR.gotoPage(formId, 1);
            },

            init: function () {
                /*
                $('input[name=qbr_q]').keypress(function (e) {
                    if (e.which == 13) {
                        var formId = $(this).parents('.qbr').data('id');
                        QBR.doSearch(formId, $(this).val());
                        return false;
                    }
                });
                */
            }
        };
    }();

    QBR.init();
});