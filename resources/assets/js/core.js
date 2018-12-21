/******************************************************
 * Core class is used throughout the site
 ******************************************************/
class Core {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        // declare our class properties
        this.dataTables= {};
        this.noty = null;
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */
    init() {
        // various custom utilities
        this.utilities();
        // setup custom floating labels
        this.floatingLabels();
        // initiate vendor plugins
        this.vendors();
        // setup form validation
        this.validation();
        // setup sidebar slider
        this.sidebar();
        // setup datatables
        this.datatables();
        // init some default ajax settings
        this.ajaxSettings();
    }

    /**
     * Setup some global utilities
     */
    utilities(fromSidebar) {
        const self = this;

        // setup our Noty defaults
        Noty.overrideDefaults({
            layout   : 'topRight',
            theme    : 'nest',
            progressBar: true,
            closeWith: ['click', 'button']
        });

        let setupSwal = function($this) {
            swal({
                title: $this.attr('data-title') !== undefined ? $this.attr('data-title') : 'Are you sure?',
                html: $this.attr('data-text') !== undefined ? $this.attr('data-text') : 'This action cannot be undone.',
                type: $this.attr('data-type') !== undefined ? $this.attr('data-type') : 'question',
                showCancelButton: true,
                confirmButtonClass: $this.attr('data-button-class') !== undefined ? 'btn ' + $this.attr('data-button-class') : 'btn btn-danger',
                confirmButtonText: $this.attr('data-button-text') !== undefined ? $this.attr('data-button-text') : 'Yes, I\'m sure!',
                cancelButtonClass: 'btn btn-outline-secondary ml-2',
                buttonsStyling: false
            }).then(function(result) {
                if ( result.value ) {
                    if ($this.is('button') || $this.hasClass('submit-form')) {
                        $this.closest('form').submit();
                    } else if ($this.attr('href') !== undefined && $this.attr('href') !== '#' && $this.attr('href') !== '') {
                        window.location = $this.attr('href');
                    }
                }
            });
        };

        // confirm a click before following link
        $('body').on('click', '.confirm-click', function(e) {
            e.preventDefault();
            setupSwal($(this));
        });

        // nav tabs
        if ( $('.nav-tabs').length ) {
            if ( $.url().fparam('tab') ) {
                $('.nav-tabs a[href="#' + $.url().fparam('tab') + '"]').tab('show');
            } else {
                $('.nav-tabs a:first').tab('show');
                if ( $('.nav-tabs.hash-tabs').length ) {
                    window.location.hash = '#tab=' + $('.nav-tabs.hash-tabs a:first').attr('href').substr(1);
                }
            }
            $('.hash-tabs a[data-toggle="tab"]').off('shown.bs.tab').on('shown.bs.tab', function(e) {
                window.location.hash = '#tab=' + e.target.hash.substr(1);
            });
        }

        // allow link to submit a form
        $('a.submit-form').not('.confirm-click').on('click', function(e) {
            e.preventDefault();
            $(this).closest('form').submit();
        });

        // mask password field characters
        $('.hide-password').on('change', function(e) {
            let target = $(this).closest('form').find('input[name="' + $(this).attr('data-target') + '"]');
            if ( $(this).prop('checked') ) {
                target.attr('type', 'password');
            } else {
                target.attr('type', 'text');
            }
        });

        // show/hide elements
        let toggleContent = function($this) {
            if ( $this.attr('data-toggle') !== undefined ) {
                $contentToggle = $($this.attr('data-toggle'));
                $contentToggle.toggle();
            }
            if ( $this.attr('data-hide') !== undefined ) {
                $contentHide = $($this.attr('data-hide'));
                if ( $contentHide.attr('data-ignore-validation') === 'true' ) {
                    $contentHide.addClass('ignore-validation');
                    $contentHide.find('[data-fv-field]').each(function(index, el) {
                        $this.closest('form').data('formValidation').resetField($(el));
                    });
                }
                if ( $this.closest('.show-after-clone').length > 0 ) {
                    $this.closest('.show-after-clone').find($this.attr('data-hide')).hide();
                } else {
                    $contentHide.hide();
                }
            }
            if ( $this.attr('data-show') !== undefined ) {
                $contentShow = $($this.attr('data-show'));
                if ( $this.closest('.show-after-clone').length > 0 ) {
                    $this.closest('.show-after-clone').find($this.attr('data-show')).show();
                } else {
                    $contentShow.show();
                }
                if ( $contentShow.hasClass('ignore-validation') ) {
                    $contentShow.removeClass('ignore-validation');
                    $contentShow.attr('data-ignore-validation', 'true');
                }
                $contentShow.find('input.toggle-content:checked').trigger('click');
            }
        };
        $('.toggle-content').on('click', function(e) {
            let $this = $(this);
            if ( $this.is('a') ) {
                e.preventDefault();
            }
            toggleContent($this);
        });
        $('select.toggle-content').on('change', function(e) {
            let $this = $(this).find('option:selected');
            toggleContent($this);
        });

        // clone content
        $('.clone-content').on('click', function(e) {
            if ( $(this).is('a') ) {
                e.preventDefault();
            }

            let $content = $($(this).attr('data-content')).clone(true);
            $content.find('input').val('');
            $content.find('textarea').val('');
            $content.find('.show-after-clone').css('display', 'block');
            $content.find('.hide-after-clone').css('display', 'none');
            if ( $content.hasClass('show-after-clone') ) {
                $content.css('display', 'block');
            }
            $content.find('.display-none').each(function(index, el) {
                if ( !$(el).hasClass('show-after-clone') ) {
                    $(el).css('display', 'none');
                }
            });

            $content.find('input:radio, input:checkbox').each(function(index, el) {
                let name = $(el).attr('name');
                let id = $(el).attr('id');
                let label = $(el).next('label').attr('for');
                let matches = name.match(/[\d+]/);
                if ( matches ) {
                    let oldIndex = matches[0];
                    let newIndex = parseFloat(oldIndex) + 1;
                    $(el).attr('name', name.replace(oldIndex, newIndex));
                    $(el).attr('id', id.replace(oldIndex, newIndex));
                    $(el).next('label').attr('for', label.replace(oldIndex, newIndex));
                }
            });

            $content.find('input[data-default-checked="true"]').prop('checked', true);
            $content.find('option[data-default-selected="true"]').prop('selected', true);

            if ( $(this).attr('data-insert-after') ) {
                $($(this).attr('data-insert-after')).after($content);
            } else if ( $(this).attr('data-insert-before') ) {
                $($(this).attr('data-insert-before')).before($content);
            }

        });

        // delete closest
        $('.delete-closest').on('click', function(e) {
            if ( $(this).is('a') ) {
                e.preventDefault();
            }
            $(this).closest($(this).attr('data-closest')).remove();
        });

        // hide alerts instead of removing them
        $('.alert [data-hide]').off('click').on('click', function(e) {
            let alertClass = $(this).attr('data-hide');
            $('.' + alertClass).hide();
        });

        // show notification if present
        if ( fromSidebar !== true ) {
            if (notification !== undefined && notification !== null && notification.status !== '' && notification.message !== '') {
                if (window.location.pathname !== '/auth/login') {
                    self.notify(notification.status, notification.message, 7000);
                }
            }
        }
    }

    /**
     * Custom floating labels
     */
    floatingLabels() {
        // create the floating label for input/textarea elements
        function createInputLabel(text, $input, animate) {
            var animate = animate === undefined ? true : animate;
            $input.attr('data-placeholder', text).attr('placeholder', '');
            $input.after('<label class="floating-label">' + text + '</label>');
            var $label = $input.parent().find('.floating-label:first');
            var marginLeft = 0;
            // position our label if it's within an input group
            if ( $label.parent().hasClass('input-group') ) {
                marginLeft = (parseInt($input.position().left) + 1) + 'px';
                $label.css({
                    marginLeft: marginLeft
                });
            }
            var marginTop = $label.css('margin-top');
            var fontSize = $label.css('font-size');
            if ( $input.parent().find('.input-group-prepend .btn').length ) {
                var negMargin = $input.parent().find('.input-group-prepend .btn-sm').length ? 25 : 28;
            } else {
                var negMargin = $input.is('textarea') ? 18 : 17;
            }
            $label.attr('data-margin-top', marginTop).attr('data-margin-left', marginLeft).attr('data-font-size', fontSize);
            var css = {
                fontSize: (parseFloat(fontSize) - 3) + 'px',
                marginTop: (parseFloat(marginTop) - negMargin) + 'px',
                marginLeft: 0
            };
            if ( animate ) {
                $label.animate(css, 200);
            } else {
                $label.css(css);
            }
        }

        // create the floating label for select fields
        function createSelectLabel(text, $select) {
            $select.after('<label class="floating-label">' + text + '</label>');
            var $label = $select.parent().find('.floating-label:first');
            var marginTop = $label.css('margin-top');
            var marginLeft = $label.css('margin-left');
            var fontSize = $label.css('font-size');
            $label.attr('data-margin-top', marginTop).attr('data-margin-left', marginLeft).attr('data-font-size', fontSize);
            if ( $select.val() !== '' ) {
                $label.css({
                    marginLeft: 0,
                    marginTop: '-56px',
                    fontSize: '13px'
                });
                $select.attr('data-label-animated', 1);
            }
        }

        // animate the placeholder label for input/textarea upon focus
        $('body').on('focus', '.floating input[placeholder], .floating textarea[placeholder]', function(e) {
            var $this = $(this);
            if ( $this.hasClass('file-input') ) {
                $this.prev('input[type="file"]').trigger('click');
            } else {
                if ( !$this.next().is('label') && $.trim($this.attr('placeholder')) !== '' ) {
                    var text = $this.attr('placeholder');
                    createInputLabel(text, $this);
                }
            }
        });
        // restore the placeholder for input/textarea upon blur
        $('body').on('blur', '.floating input[placeholder], .floating textarea[placeholder]', function(e) {
            var $this = $(this);
            if ( $.trim($this.val()) === '' && !$this.hasClass('file-input') ) {
                var text = $this.attr('data-placeholder');
                var $label = $this.parent().find('.floating-label:first');
                $label.animate({
                    fontSize: $label.attr('data-font-size'),
                    marginTop: $label.attr('data-margin-top'),
                    marginLeft: $label.attr('data-margin-left')
                }, 200, function() {
                    $label.remove();
                    $this.attr('data-placeholder', '').attr('placeholder', text);
                });
            }
        });
        // for each select, add our placeholder label
        $('.floating select[placeholder]').each(function() {
            var $this = $(this);
            var text = $this.attr('placeholder');
            if ( $this.attr('multiple') === undefined ) {
                createSelectLabel(text, $this);
            } else {
                $this.before('<label class="floating-label select-multiple">' + text + '</label>');
            }
        });
        // animate our label for select upon focus
        $('.floating select[placeholder]:not("[multiple]")').on('focus', function(e) {
            var $this = $(this);
            var $label = $this.parent().find('.floating-label:first');
            // prevent label from being animated up twice
            if ( $this.attr('data-label-animated') ) {
                return;
            }
            $label.animate({
                fontSize: (parseFloat($label.attr('data-font-size')) - 3) + 'px',
                marginTop: (parseFloat($label.attr('data-margin-top')) - 25) + 'px',
                marginLeft: 0
            }, 200, function() {
                $this.attr('data-label-animated', 1);
            });
        });
        // restore the placeholder label for select on blur
        $('.floating select[placeholder]:not("[multiple]")').on('blur', function(e) {
            var $this = $(this);
            var value = $.trim($this.val());
            var $label = $this.parent().find('.floating-label:first');
            if ( value === '' ) {
                $label.animate({
                    fontSize: $label.attr('data-font-size'),
                    marginTop: $label.attr('data-margin-top'),
                    marginLeft: $label.attr('data-margin-left')
                }, 200, function() {
                    $this.attr('data-label-animated', '');
                });
            }
        });
        // add dummy text input for each file input so it looks good
        $('.floating input[type="file"][placeholder]').each(function() {
            var $this = $(this);
            var text = $this.attr('placeholder');
            $this.after('<input type="text" class="form-control file-input" placeholder="Select Your File..." readonly>');
        });
        // handle our file input type change event
        $('.floating input[type="file"][placeholder]').on('change', function(e) {
            var $this = $(this);
            var file = $this[0].files[0];
            var text = $this.next('.file-input').attr('placeholder');
            var $input = $this.next('.file-input');
            var $label = $this.parent().find('.floating-label:first');
            if ( file !== undefined ) {
                $input.val(file.name);
                $input.after('<label class="floating-label file">' + text + '</label>');
            } else {
                $input.val('').trigger('blur');
                $label.remove();
            }
        });

        // trigger our autfocus input event
        setTimeout(function() {
            $('[autofocus][placeholder]:focus').trigger('focus');
        }, 10);

        // look for input/textarea fields that already have a value set
        $('.floating input[placeholder], .floating textarea[placeholder]').filter(function() {
            return $(this).val();
        }).each(function() {
            var $this = $(this);
            createInputLabel($this.attr('placeholder'), $this, false);
        });
    }


    /**
     * initiate vendor plugin functionality
     */
    vendors() {
        // bootstrap
        $('[data-toggle="popover"]').popover({
            html: true
        });
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'mm/dd/yyyy',
            todayHighlight: true,
        });
        /*$('.color-picker').colorpicker({
            align: 'left'
        });*/
        $('.color-picker input').on('focus', function() {
            $(this).closest('.color-picker').colorpicker('show');
        });
    }


    /**
     * Form validation
     */
    validation(form) {
        const self = this;
        let selector = form ? form : 'form.validate';
        $(selector).formValidation({
            icon: {
                valid: 'fa fa-check text-success',
                invalid: 'fa fa-times text-danger',
                validating: 'fa fa-spinner text-muted'
            },
            framework: 'bootstrap4',
            trigger: 'blur',
            threshold: 1,
            row: {
                valid: ''
            },
            excluded: [
                ':disabled',
                function($field, validator) {
                    if ( $field.closest('form').hasClass('tabs') && $field.closest('.ignore-validation').length === 0 ) {
                        let field = validator.getInvalidFields().eq(0);
                        let id = field.closest('.tab-pane').attr('id');
                        let tab = field.closest('form').find('.nav-tabs a[href="#' + id + '"]');
                        if ( id !== undefined && !tab.hasClass('active') ) {
                            tab.tab('show');
                        }
                        return false;
                    } else if ( $field.closest('form').hasClass('steps') && $field.closest('.ignore-validation').length === 0 ) {
                        if ( validator.getInvalidFields().length ) {
                            $.each(validator.getInvalidFields(), function (index, field) {
                                if ( $(field).closest('.ignore-validation').length === 0 ) {
                                    let step = $(field).closest('.form-step-content').attr('data-step');
                                    let $stepWithError = $('.form-steps ul li[data-step="' + step + '"]');
                                    if (!$stepWithError.hasClass('active')) {
                                        $stepWithError.addClass('error');
                                    }
                                }
                            });
                        }
                        return false;
                    } else {
                        return $field.is(':hidden');
                    }
                },
                function($field, validator) {
                    if ( $field.closest('form').hasClass('tabs') && $field.closest('.ignore-validation').length === 0 ) {
                        let field = validator.getInvalidFields().eq(0);
                        let id = field.closest('.tab-pane').attr('id');
                        let tab = field.closest('form').find('.nav-tabs a[href="#' + id + '"]');
                        if ( id !== undefined && !tab.hasClass('active') ) {
                            tab.tab('show');
                        }
                        return false;
                    } else if ( $field.closest('form').hasClass('steps') && $field.closest('.ignore-validation').length === 0 ) {
                        if ( validator.getInvalidFields().length ) {
                            $.each(validator.getInvalidFields(), function (index, field) {
                                if ( $(field).closest('.ignore-validation').length === 0 ) {
                                    let step = $(field).closest('.form-step-content').attr('data-step');
                                    let $stepWithError = $('.form-steps ul li[data-step="' + step + '"]');
                                    if (!$stepWithError.hasClass('active')) {
                                        $stepWithError.addClass('error');
                                    }
                                }
                            });
                        }
                        return false;
                    } else {
                        return !$field.is(':visible');
                    }
                }
            ]
        }).on('success.field.fv', function(e, data) {
            let $field = $(e.target);
            let $form = $field.closest('form');
            let $button = $form.find('.btn[data-loading-text]:last');
            let obj = {field: $field, form: $form, button: $button};
            $(window).trigger($form.attr('id') + '.fieldSuccess', obj);
        }).on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target);
            let fv = $form.data('formValidation');
            if ( $form.hasClass('invalid') ) {
                return false;
            } else {
                self.submitForm($form, fv);
            }
        });

    }


    /**
     * handle a form submission
     */
    submitForm($form, fv) {
        const self = this;
        let id = $form.attr('id');
        let $button = $form.find('.btn[data-loading-text]:visible:last');

        // declare trigger object
        let obj = {halt: false, form: $form, button: $button};
        // trigger validation success event
        $(window).trigger(id + '.validationSuccess', obj);
        if ( obj.halt ) return false;

        // set our button to loading state
        $button.button('loading');

        function sendUnchecked() {
            $form.find('input.send-unchecked[value="1"]').each(function() {
                if ( !$(this).prop('checked') ) {
                    $form.append('<input type="hidden" name="' + $(this).attr('name') + '" value="0">');
                }
            });
        }

        if ( $form.find('input:hidden[name="_ajax"]').length && $form.find('input:hidden[name="_ajax"]').val() == 'true' ) {

            if ( id === undefined || id === '' ) {
                alert('ajax forms must have an ID assigned');
                return false;
            }

            sendUnchecked();

            $form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    // trigger event and halt if necessary
                    $(window).trigger(id + '.beforeSubmit', obj);
                    if ( obj.halt ) return false;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // trigger event and halt if necessary
                    obj.data = jqXHR;
                    obj.message = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : ( jqXHR.responseText ? jqXHR.responseText : 'Oops, something went wrong...');
                    $(window).trigger(id + '.error', obj);
                    if ( obj.halt ) return false;
                    // send the notification
                    self.notify('error', obj.message, 0);
                    // reset the button state now
                    $button.button('reset');

                },
                success: function(data) {
                    // trigger event and halt if necessary
                    obj.data = data;
                    $(window).trigger(id + '.success', obj);
                    if ( obj.halt ) return false;
                    // send the notification
                    if ( data.message !== undefined && data.message !== '' ) {
                        self.notify(data.status, data.message);
                    }
                    // reload datatables
                    self.reloadDataTables();
                    // hide sidebar
                    $('div[data-simplersidebar="mask"]').trigger('click');
                    // reset the button state now
                    $button.button('reset');
                }

            });

        } else {
            // trigger event and halt if necessary
            obj.fv = fv;
            $(window).trigger(id + '.defaultSubmit', obj);
            if ( !obj.halt ) {
                sendUnchecked();
                // submit form normally
                fv.defaultSubmit();
            }
        }
        return false;
    }

    /**
     * Sidebar slider
     */
    sidebar() {
        const self = this;

        $("#sidebar-right").simplerSidebar({
            selectors: {
                trigger: '#open-sidebar',
                quitter: '.close-sidebar'
            },
            sidebar: {
                width: 800
            },
            animation: {
                duration: 700,
                easing: 'easeOutQuint'
            }
        });

        $('body').on('click', '.open-sidebar', function(e) {
            e.preventDefault();
            let url = $(this).attr('href') !== '' && $(this).attr('href') !== undefined ? $(this).attr('href') : $(this).attr('data-url');
            // load ajax source
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'html',
                error: function(jqXHR, textStatus, errorThrown) {
                    let response = JSON.parse(jqXHR.responseText);
                    if ( jqXHR.status == 401 ) {
                        $('#sidebar-right .sidebar-wrapper').html('<div class="error-401"><i class="fa fa-exclamation-triangle text-danger"></i><br>' + response.message + '</div>');
                        $('#sidebar-right .cssload-container').fadeOut('fast', function() {
                            $('#sidebar-right .sidebar-wrapper').fadeIn('fast');
                        });
                    } else {
                        self.notify('error', response.message, 5000);
                    }
                },
                beforeSend: function() {
                    $('#sidebar-right .sidebar-wrapper').empty().hide();
                    $('#sidebar-right .cssload-container').show();
                },
                success: function(data) {
                    $('#sidebar-right .sidebar-wrapper').html(data);
                    self.utilities(true);
                    self.vendors();
                    self.vendors();
                    // add form validation to results
                    $('#sidebar-right .sidebar-wrapper form').each(function() {
                        self.validation(this);
                    });
                    $('#sidebar-right .cssload-container').fadeOut('fast', function() {
                        $('#sidebar-right .sidebar-wrapper').fadeIn('fast');
                    });
                }
            });
            // open sidebar now
            $('#open-sidebar').trigger('click');
        });

        $(document).on('keyup', function(e) {
            if ( e.keyCode === 27 && $('div[data-simplersidebar="mask"]').is(':visible') ) {
                $('div[data-simplersidebar="mask"]').trigger('click');
            }
        });

    }

    /**
     * datatables
     */
    datatables() {
        const self = this;

        if ( $('.dataTable').length ) {
            $.fn.dataTableExt.sErrMode = 'throw';
            $('.dataTable').each(function() {
                let $this = $(this);

                // build our order array
                let orderArr = [];
                let $primarySort = $this.find('[data-order^="primary-"]');
                let $secondarySort = $this.find('[data-order^="secondary-"]');
                if ( $secondarySort.length ) {
                    let primaryDirection = $primarySort.attr('data-order').split('-')[1];
                    let secondaryDirection = $secondarySort.attr('data-order').split('-')[1];
                    orderArr = [
                        [$primarySort.index(), primaryDirection],
                        [$secondarySort.index(), secondaryDirection]
                    ];
                } else if ( $primarySort.length ) {
                    let primaryDirection = $primarySort.attr('data-order').split('-')[1];
                    orderArr = [
                        [$primarySort.index(), primaryDirection]
                    ];
                } else {
                    orderArr = [];
                }

                // build our columns array
                let columnsArr = [];
                $this.find('thead tr th').each(function(index, element) {
                    let column = {};
                    column.name = $(this).attr('data-name');
                    if ( $(this).attr('data-o-filter') === 'true' || $(this).attr('data-o-sort') === 'true' ) {
                        column.data = {
                            _: $(this).attr('data-name') + '.display'
                        };
                        if ( $(this).attr('data-o-filter') === 'true' ) {
                            column.data.filter = $(this).attr('data-name') + '.filter';
                        }
                        if ( $(this).attr('data-o-sort') === 'true' ) {
                            column.data.sort = $(this).attr('data-name') + '.sort';
                            column.type = 'string';
                        }
                    } else {
                        column.data = $(this).attr('data-name');
                    }
                    if ( $(this).attr('data-order') === 'false' ) {
                        column.orderable = false;
                    }
                    if ( $(this).attr('data-search') === 'false' ) {
                        column.searchable = false;
                    }
                    if ( $(this).attr('data-class') !== undefined ) {
                        $(this).addClass($(this).attr('data-class'));
                        column.class = $(this).attr('data-class');
                    }
                    columnsArr.push(column);
                });

                // call the datatable plugin now
                $this.on('processing.dt', function(e, settings, processing) {
                    // update our refresh indicator when processing
                    let $icon = $this.closest('.dataTables_wrapper').find('.dataTables_header .dataTables_refresh i.fa-sync');
                    if ( processing ) {
                        $this.addClass('processing');
                        $icon.addClass('fa-spin-fast');
                    } else {
                        $this.removeClass('processing');
                        $icon.removeClass('fa-spin-fast');
                    }
                }).on('preInit.dt', function(e, settings) {
                    if ( $this.attr('data-params') !== undefined && $this.attr('data-params') !== '' && localStorage.getItem('datatable_filters') !== null ) {
                        let params = $.parseJSON($this.attr('data-params'));
                        let filters = $.parseJSON(localStorage.getItem('datatable_filters'));
                        let $filters = $this.closest('.dataTables_wrapper').prev('.dataTable-filters');
                        $.each(filters, function(id, val) {
                            if ( $filters.find('#' + id).is('select') ) {
                                $filters.find('#' + id).val(val);
                            } else {
                                $filters.find('#' + id).prop('checked', val ? true : false);
                            }
                            params[id] = val;
                        });
                        $this.attr('data-params', JSON.stringify(params));
                    }
                });
                let table = $this.dataTable({
                    dom: '<"dataTables_header"<"row"<"col-xs-1 col-sm-5"<"dataTables_with_selected hidden-xs">><"col-xs-11 col-sm-7"f>>r>t<"dataTables_footer"<"row"<"col-xs-3 col-sm-5"l><"col-xs-9 col-sm-7"pi>>>',
                    pagingType: 'full_numbers',
                    stateSave: true,
                    processing: false,
                    autoWidth: false,
                    order: orderArr,
                    columns: columnsArr,
                    displayLength: 25,
                    deferRender: true,
                    lengthMenu: [
                        [10, 25, 50, 100, 250, -1],
                        [10, 25, 50, 100, 250, 'All']
                    ],
                    ajax: {
                        url: $this.attr('data-url'),
                        dataSrc: '',
                        data: function(data) {
                            let params = $this.attr('data-params');
                            if ( params !== '' && params !== undefined ) {
                                let newData = {};
                                let paramsObj = $.parseJSON($this.attr('data-params'));
                                $.each(paramsObj, function(name, value) {
                                    if ( name !== '' && value !== '' ) {
                                        newData[name] = value;
                                    }
                                });
                                return $.extend({}, data, newData);
                            }
                        }
                    },
                    language: {
                        lengthMenu: '_MENU_ <span class="hidden-xs">Per Page</span>',
                        info: '<span class="hidden-xs"><strong>_START_</strong> to <strong>_END_</strong> of _TOTAL_ items</span>',
                        emptyTable: 'No items to display',
                        processing: '<i class="fal fa-spinner fa-spin"></i> Loading...',
                        loadingRecords: '<i class="fal fa-spinner fa-spin"></i> Loading...',
                        search: '',
                        infoFiltered: '<span class="hidden-sm hidden-xs">(filtered from _MAX_ items)</span>',
                        paginate: {
                            first: '<i class="fal fa-angle-double-left"></i>',
                            previous: 'Prev',
                            next: 'Next',
                            last: '<i class="fal fa-angle-double-right"></i>'
                        }
                    },
                    createdRow: function( row, data, dataIndex ) {
                        if ( data.class !== '' && data.class !== undefined ) {
                            $(row).addClass(data.class);
                        }
                    },
                    initComplete: function(settings, json) {
                        dataTablesComplete(this, settings);
                        this.api().columns.adjust();
                    },
                    drawCallback: function(settings) {
                        if ( this.fnGetData().length > 0 ) {
                            dataTablesDraw(this, settings);
                        }
                    }
                });
                // add our datatable instance to our global object
                self.dataTables[$this.attr('id')] = table;

            });

            /* callback when datatables init is complete */
            let dataTablesComplete = function(table, settings) {
                let $table = table.closest('.dataTables_wrapper');
                // set action column width
                let actionWidth = 0;
                let btnCount = $table.find('td.action_column:first .btn').each(function() {
                    actionWidth += parseFloat($(this).outerWidth());
                });
                $table.find('.action_column').css('width', (actionWidth + 20) + 'px');
                // show our info div
                $table.find('.dataTables_info').show();
                // set up the refresh div
                $table.find('.dataTables_header .dataTables_filter').append('<span class="dataTables_refresh"><a href="#"><i class="fal fa-sync"></i></a></div>');
                $table.find('.dataTables_refresh a').on('click', function(e) {
                    e.preventDefault();
                    self.reloadDataTables(table);
                });
                // set up the clear search div
                $table.find('.dataTables_header .dataTables_filter input').after('<span class="dataTables_clear_search hidden-xs"><a href="#"><i class="fal fa-search"></i></a></span>');
                // maybe we need to show the clear search link on page load
                if ( $table.find('.dataTables_filter input').val() !== '' ) {
                    $table.find('.dataTables_clear_search i').removeClass('fa-search').addClass('fa-times');
                }
                // set up clear search functionality when typing in the serach field
                $table.find('.dataTables_filter input').on('keyup', function() {
                    let value = $(this).val();
                    if ( value !== '' ) {
                        $table.find('.dataTables_clear_search i').removeClass('fa-search').addClass('fa-times');
                        $(document).on('keyup.dataTable', function(e) {
                            if ( e.keyCode === 27 ) {
                                $table.find('.dataTables_filter input').val('').keyup();
                            }
                        });
                    } else {
                        $table.find('.dataTables_clear_search i').removeClass('fa-times').addClass('fa-search');
                        $(document).off('keyup.dataTable');
                    }
                });
                // attach the click event to our clear search link
                $table.find('.dataTables_clear_search a').on('click', function(e) {
                    e.preventDefault();
                    let hash = window.location.hash;
                    if ( hash !== '' ) {
                        window.location.hash = hash.replace(/&?search=[^&]*/, '');
                    }
                    $table.find('.dataTables_filter input').val('').keyup();
                });

                // setup click functionality on filters
                $table.prev('.dataTable-filters').find('input:checkbox, select').on('change', function(e) {
                    let params = $.parseJSON($table.find('.dataTable').attr('data-params'));
                    params[$(this).attr('id')] = $(this).is('select') ? $(this).val() : ($(this).prop('checked') ? 1 : 0);
                    let params_json = JSON.stringify(params);
                    localStorage.setItem('datatable_filters', params_json);
                    $table.find('.dataTable').attr('data-params', params_json);
                    $table.find('.dataTables_refresh a').click();
                });
                // show our filters
                $table.prev('.dataTable-filters').show();

            };

            /* callback when datatabales draw is complete */
            let dataTablesDraw = function(table, settings) {
                // create our table varaible
                let $table = table.closest('.dataTables_wrapper');
                // when hovering over row, add hover class and cursor
                $table.find('tr').unbind('mouseenter mouseleave').hover(
                    function() {
                        // add cursor if the location is set
                        if ( $(this).find('.click-location').length && $(this).find('.click-location').val() !== '' ) {
                            $(this).css('cursor', 'pointer');
                        }
                        // show our buttons
                        $(this).find('.btn').removeClass('invisible');
                    },
                    function() {
                        // reset cursor
                        $(this).css('cursor', 'default');
                        // hide our buttons
                        $(this).find('.btn').addClass('invisible');
                    }
                );
                // redirect to location if it's set when they click on the row cells or the overlay
                $table.find('td').not('.checkbox_column, .action_column').off('click').on('click', function(e) {
                    let location = $(this).closest('tr').find('.click-location').val();
                    if ( location !== '' && location !== undefined ) {
                        window.location = location;
                    }
                });
                // add tooltip functionality
                $table.find('[data-toggle="tooltip"]').tooltip({
                    container: 'body'
                });
                // add form validation to results
                $table.find('form').each(function() {
                    self.validation(this);
                });
            };
        }

    }

    /**
     * reload current datatables
     */
    reloadDataTables(table) {
        const self = this;
        // return false if no tables found
        if ( !$('.dataTable').length ) {
            return false;
        }
        let tables = table !== undefined ? [table] : self.dataTables;
        $.each(tables, function(tableId, table) {
            let $table = $(tableId).closest('.dataTables_wrapper');
            table.api().ajax.reload(function() {
                // reload complete callback
            });
        });
    }

    /**
     * setup form validation plugin
     */
    ajaxSettings() {
        $.ajaxSetup({
            data: { _token: config._token }
        });
    }


    /**
     * growl type notifications
     */
    notify(type, message, timeout) {
        const self = this;
        let icon = 'fa-info-circle';
        switch ( type ) {
            case 'success':
                icon = 'fa-check';
                break;
            case 'info':
                icon = 'fa-info-circle';
                break;
            case 'warning':
            case 'danger':
            case 'error':
                icon = 'fa-exclamation-triangle';
                break;
        }
        type = type === 'danger' ? 'error' : type;
        self.noty = new Noty({
            type: type,
            timeout: timeout === undefined ? 2000 : (timeout < 1 ? false : timeout),
            text: '<i class="fa ' + icon + ' mr-1"></i> ' + message
        }).show();
    }


    /**
     * clear notifications
     */
    clearNotify() {
        this.noty.close();
    }


    /**
     * Helper methods
     */
    url(url) {
        return `${config.url}/${url}`;
    }

}

/******************************************************
 * launch core, create instance
 ******************************************************/
$(function() {
    window.Core = new Core();
});

/******************************************************
 * custom button plugin
 ******************************************************/
$.fn.button = function(method) {
    let btn = this;
    let methods = {
        reset: function() {
            let initial = btn.attr('data-initial-text');
            btn.removeAttr('data-initial-text');
            btn.html(initial);
            btn.removeClass('disabled');
            btn.prop('disabled', false);
        }
    };
    if ( methods[method] ) {
        methods[method]();
    } else {
        let initial = btn.html();
        let state = btn.attr(`data-${method}-text`);
        if ( state ) {
            btn.attr('data-initial-text', initial);
            btn.html(state);
            if ( btn.is('a') ) {
                btn.addClass('disabled');
            } else {
                btn.prop('disabled', true);
            }
        }
    }
};