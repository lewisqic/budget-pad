/******************************************************
 * Adminly template class
 ******************************************************/
class Adminly {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        if ( $('body.adminly').length ) {
            // declare our class properties
            // call init
            this.init();
        }
    }

    /**
     * We run init when our class is first instantiated
     */
    init() {
        // bind events
        this.bindEvents();
        // clone submenu items
        this.cloneSubmenu();
        // position the submenu on load
        this.positionSubmenu();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;

        // show floating submenu on click
        $('.has-submenu > a').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            self.showSubmenu($(this));
        });

        // hide floating submenu on document click
        $(document).click(function(){
            $('.has-submenu').removeClass('show-submenu');
        });

        // prevent closing the submenu when clickin within the submenu
        $('.has-submenu .submenu').on('click', function(e) {
            e.stopPropagation();
        });

        // apply fixed header
        $(window).scroll(function() {
            self.applyFixedHeader();
        });

        // configurator open/close
        $('.configurator .handle').on('click', function(e) {
            e.preventDefault();
            self.toggleConfigurator($(this));
        });

        // show/hide configurator colors list
        $('.color-wrapper .toggle-colors').on('click', function(e) {
            e.preventDefault();
            self.toggleColorsList($(this));
        });

        // hide color list after selecting one
        $('.color-list input').on('change', function(e) {
            self.applySelectedColor($(this));
        });

        // save our configurator settings
        $('#save_configurator').on('submit', function() {
            self.submitConfiguratorForm($(this));
            return false;
        });
        
        // hover page title
        $('body').on('mouseenter', '.content-wrapper > h2 > span, .sidebar-wrapper > h2 > span', function() {
            let $icon = $(this).find('i');
            $icon.attr('data-class', $icon.attr('class'));
            $icon.removeClass().addClass('fas fa-heart-circle fa-fw');
        });
        $('body').on('mouseleave', '.content-wrapper > h2 > span, .sidebar-wrapper > h2 > span', function() {
            let $icon = $(this).find('i');
            $icon.removeClass().addClass($icon.attr('data-class'));
        });

        // add page to favorites
        $('body').on('click', 'h2 > span > i.fa-heart-circle', function(e) {
             e.preventDefault();
             self.addToFavorites($(this));
        });

        // delete favorite
        $('body').on('click', '.delete-favorite', function(e) {
            e.preventDefault();
            e.stopPropagation();
            self.deleteFavorite($(this));
        });
        
        // global search
        $(document).on('keypress', function(e) {
            let tag = e.target.tagName.toLowerCase();
            if ( e.which === 47 && tag !== 'input' && tag !== 'textarea' ) {
                $('#global_search_form input').focus();
                return false;
            }
        });

    }

    /**
     * show/hide the floating submenu dropdown items
     */
    showSubmenu($this) {
        let $li = $this.parent();
        $('.show-submenu').not($li).removeClass('show-submenu');
        if ( !$li.hasClass('active') || $('body').hasClass('fixed-header') || $('body').hasClass('submenu-dropdown-only') ) {
            $li.toggleClass('show-submenu');
        }
    }

    /**
     * apply our fixed header on page scroll
     */
    applyFixedHeader() {
        if ( $('body').hasClass('allow-fixed-header') ) {
            if ($(window).scrollTop() > 0) {
                $('body').addClass('fixed-header');
            } else {
                $('body').removeClass('fixed-header');
                $('.has-submenu.active').removeClass('show-submenu');
            }
        }
    }

    /**
     * toggle the configurator menu
     */
    toggleConfigurator($this) {
        let $conf = $('.configurator');
        $conf.animate({
            right: $conf.css('right') == '0px' ? '-240px' : '0px'
        }, 'fast');
        $this.toggleClass('open');
    }

    /**
     * show/hide our colors list
     */
    toggleColorsList($this) {
        $wrapper = $this.closest('.color-wrapper');
        $wrapper.toggleClass('open');
        $wrapper.find('.color-list').slideToggle('fast');
    }

    /**
     * apply changes after selecting color
     */
    applySelectedColor($this) {
        let color = $this.val();
        $this.closest('.color-wrapper').find('.toggle-colors').removeClass(function(index, className) {
            return (className.match(/(^|\s)text-\S+/g) || []).join(' ');
        }).addClass('text-' + color);
        $this.closest('.color-list').slideUp('fast');
    }

    /**
     * clone submenu into menu bar if needed
     */
    cloneSubmenu() {
        if ( $('.menu > li.active .submenu').length ) {
            let $menuSub = $('.menu > li.active .submenu').clone();
            $menuSub.removeClass('animated fadeInUp');
            $('.submenu-placeholder').remove();
            $('.submenu-bar-inner').append($menuSub).addClass('not-empty');
        }
    }

    /**
     * horizontally position sub menu
     */
    positionSubmenu() {
        if ( $('.menu > li.active').length ) {
            let mainActiveLeft = $('.menu > li.active').position().left + ($('.menu > li.active').outerWidth() / 2);
            let totalWidth = $('.submenu-bar-wrapper').outerWidth();
            let menuSubWidth = $('.submenu-bar-wrapper .submenu').outerWidth();
            if (menuSubWidth / 2 < mainActiveLeft) {
                let marginLeft = mainActiveLeft - (menuSubWidth / 2);
                let float = 'none';
                let paddingLeft = $('.submenu-bar-wrapper .submenu li:first').css('padding-right');
                if (menuSubWidth + marginLeft > totalWidth) {
                    marginLeft = 0;
                    float = 'right';
                    $('.submenu-bar-wrapper .submenu li:last').css({paddingRight: 0});
                }
                $('.submenu-bar-wrapper .submenu').css({marginLeft: marginLeft, float: float});
                $('.submenu-bar-wrapper .submenu li:first').css({paddingLeft: paddingLeft});
            }
            $('.submenu-bar-wrapper .submenu').css({opacity: 1});
        }
    }

    addToFavorites($icon) {
        let icon = $icon.attr('data-class');
        let title = $icon.parent().text();
        let path = $icon.closest('.sidebar-wrapper').length ? config.ajax_path : config.path;
        let query = $.url().attr('query');
        if ( query !== '' ) {
            path += '?' + query;
        }

        // check for duplicate
        if ( $('.favorites a[href="' + Core.url(path) + '"]').length ) {
            Core.notify('warning', 'Page is already on your favorites list');
            return false;
        }

        // save page to favorites
        $.ajax({
            url: Core.url('admin/save-favorite'),
            data: {
                icon: icon,
                title: title,
                path: path
            },
            method: 'POST',
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown) {
                Core.notify('error', jqXHR.responseJSON.message, 0);
            },
            success: function(data) {
                Core.notify('success', data.message);
                $('.favorites p').hide();
                $('.favorites').append(($('.favorites a').length ? '<div class="dropdown-divider"></div>' : '') + '<a href="' + Core.url(path) + '" class="dropdown-item"><i class="' + icon + '"></i> ' + title + ' <small class="delete-favorite text-danger"><i class="fal fa-trash-alt"></i></small></a>');
            }
        });

    }

    /**
     * delete our favorite from menu list
     */
    deleteFavorite($this) {
        let $link = $this.closest('a');
        let path = $link.attr('href').replace(/.*\.\w{2,}\//, '');
        // remove page from favorites
        $.ajax({
            url: Core.url('admin/delete-favorite'),
            data: {
                path: path
            },
            method: 'POST',
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown) {
                Core.notify('error', jqXHR.responseJSON.message, 0);
            },
            success: function(data) {
                Core.notify('success', data.message);
                if ( $link.prev().is('.dropdown-divider') ) {
                    $link.prev().remove();
                } else if ( $link.next().is('.dropdown-divider') ) {
                    $link.next().remove();
                }
                $link.remove();
                if ( $('.favorites a').length < 1 ) {
                    $('.favorites p').show();
                }
            }
        });
    }

    /**
     * Ajax submit our configurator settings form
     */
    submitConfiguratorForm($form) {
        let self = this;
        let timeout = null;

        $form.ajaxSubmit({
            dataType: 'json',
            beforeSubmit: function() {
                $form.find('button[data-loading-text]').button('loading');
                timeout = setTimeout(function() {
                    $('.building-alert-wrapper').slideDown('fast');
                }, 1500);
            },
            error: function(jqXHR, textStatus, errorThrown) {
            },
            success: function(data) {
                if ( !data.success ) {
                    alert('Unable to build new styles.');
                    return false;
                }

                let css = $('#adminly_css').attr('href');
                let newCss = css.replace(/css\/.*\.css/, 'css/' + data.filename);
                $('#adminly_css').attr('href', newCss);

                $form.find('button[data-loading-text]').button('reset');

                $('.configurator .handle').trigger('click');

                $('.building-alert-wrapper').hide();
                if ( timeout !== null ) {
                    clearTimeout(timeout);
                }

                // update layout with new settings
                if ( data.settings.layout.header_style === 'sticky' ) {
                    $('body').addClass('allow-fixed-header');
                } else {
                    $('body').removeClass('allow-fixed-header fixed-header');
                }
                if ( data.settings.layout.submenu_style === 'dropdown' ) {
                    $('body').addClass('submenu-dropdown-only');
                } else {
                    $('body').removeClass('submenu-dropdown-only');
                    self.positionSubmenu();
                }

            }

        });
    }

}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new Adminly();
});