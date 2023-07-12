function Konfigurator(config){

    var _this = this;
    _this.$form = config.form;
    _this.$progressbar = config.progressbar;
    _this.$footer = config.footer;
    _this.urls = config.urls || {}
    _this.current_step = null;
    _this.step_delay = 200;

    this.initiate = function initiate(){
        _this.events();
        _this.hash();
    }

    this.hash = function hash(){

        // get current step
        let hash_step = window.location.hash.substring(1) || 'index';
        hash_step = hash_step.split('&');

        // Set current step
        _this.current_step = hash_step[0];

        // sidebar
        $('.btn-help').attr('data-content', _this.current_step);

        // Hide previos error message
        $('.error-message').hide();

        // Get step
        let step_url = _this.urls.step.replace('%step%', _this.current_step);
        $.get(step_url, function(data){
            // Scroll to top
            window.scrollTo(0, 0);
            _this.$form.html(data.html);
            _this.add_step_active();
            // Update body (probably used for progressbar)
            _this.add_step_to_body(_this.current_step);
            if(data.progressbar === null){
                //_this.disable_footer();
            } else {
                //_this.enable_footer();
                _this.update_progressbar(data.progressbar);
            }
        });
    }

    // For css transition
    this.add_step_active = function add_step_active()
    {
        setTimeout(function(){
            _this.$form.find('.frame').addClass('active');
        }, _this.step_delay);
    }

    this.remove_step_active = function remove_step_active()
    {
        _this.$form.find('.frame').removeClass('active');
    }

    this.changeurl = function changeurl(page, url){
        if (typeof (history.pushState) != 'undefined') {
            var obj = {
                Page: page,
                Url: url
            };
            history.pushState(obj, obj.Page, obj.Url);
        }
    }

    this.add_step_to_body = function add_step_to_body(step)
    {
        console.log('running body check');
        $('body').removeClass().addClass(step);
    }

    this.disable_footer = function disable_footer(progress)
    {
        /*
        if($('body').hasClass('show-footer')){
            $('body').removeClass('show-footer');
        }
        */
    }

    this.enable_footer = function enable_footer(progress)
    {
        /*
        if(!$('body').hasClass('show-footer')){
            $('body').addClass('show-footer');
        }
        */
    }

    this.update_progressbar = function update_progressbar(progress)
    {
        _this.$progressbar.find('.progress-fill').animate({
            width: progress+'%',
        }, 400);
    }

    this.show_error_message = function show_error_message(errors)
    {

        let show_custom_message = false;
        let custom_message = '';

        // show message
        $.each(errors, function(id, message) {
            if(id === 'age'){
                custom_message = message[0];
                show_custom_message = true;
            }
            if(id === 'startdatum'){
                custom_message = message[0];
                show_custom_message = true;
            }
            if(id === 'livvarde'){
                custom_message = message[0];
                show_custom_message = true;
            }
            if(id === 'regnr'){
                custom_message = message[0];
                show_custom_message = true;
            }
        });

        if(show_custom_message){
            $('.validation-error-message').hide();
            $('.custom-error-message').html(custom_message).show();
        } else {
            $('.custom-error-message').hide();
            $('.validation-error-message').show();
        }

        let timer_index = Math.floor(Math.random() * 100);
        $('.error-message-wrapper').attr('data-timer-index', timer_index).slideDown(300);
        setTimeout(function(){
            $('.error-message-wrapper[data-timer-index='+timer_index+']').slideUp(300);
        },10000);

    }

    // Close sidebar
    this.sidebar_close = function sidebar_close()
    {
        $('body').removeClass('sidebar-open');
        $('.app-sidebar-content').fadeOut(300);
    }

    this.disable_form_next_button = function disable_form_next_button($button, text)
    {
        $button.html('<i class="fa fa-spinner fa-spin"></i> '+text);
        $button.addClass('disabled');
        $button.prop('disabled', true);
    }

    this.enable_form_next_button = function disable_form_next_button($button, text)
    {
        $button.html(text);
        $button.removeClass('disabled');
        $button.prop('disabled', false);
    }

    this.go_back = function go_back()
    {
        history.back();
    }

    this.reset_steps = function reset_steps()
    {
        _this.remove_step_active();
        setTimeout(function(){
            _this.changeurl('index','/')
            _this.hash();
            _this.sidebar_close();
            _this.update_progressbar(10);
        }, _this.step_delay);
    }

    this.events = function events()
    {

        // On hash change
        window.onhashchange = _this.hash;

        // Ajax setup for csrf verification
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // On click back
        $('.btn-back').on('click', function(e){
            e.preventDefault();
            _this.go_back();
        });

        // On reset step button
        $('.btn-reset').on('click', function(e){
            e.preventDefault();
            _this.reset_steps();
        });

        // On click trigger submit
        _this.$form.on('click', '.btn-select', function(e) {
            e.preventDefault();
            _this.$form.trigger('submit');
        });

        // On next, trigger submit
        _this.$form.on('click', '.btn-next', function(e) {
            e.preventDefault();
            let skip = parseInt($(this).data('skip') || 0);
            if(_this.$form.find('input[name=skip]').length > 0){
                _this.$form.find('input[name=skip]').remove();
            }
            if(skip === 1){
                _this.$form.append('<input type="hidden" name="skip" value="1" />');
            }
            _this.$form.trigger('submit');
        });

        // On next, trigger submit
        _this.$form.on('click', '.btn-next-bubble', function(e) {
            e.preventDefault();
            let skip = parseInt($(this).data('skip') || 0);
            if(_this.$form.find('input[name=skip]').length > 0){
                _this.$form.find('input[name=skip]').remove();
            }
            if(skip === 1){
                _this.$form.append('<input type="hidden" name="skip" value="1" />');
            }
            _this.$form.trigger('submit');
        });

        // On click bubble
        _this.$form.on('click', '.bubble-select label', function() {
            var ancestor = $(this).parents('.bubble-select');
            parent = $(this).parent('li');
            ancestor.children('li').removeClass('selected');
            ancestor.find('input[type=checkbox]').prop('checked', false);
            parent.addClass('selected');
            parent.next('input[type=checkbox]').prop('checked', true);
        });

        // on bubble skip to next step
        _this.$form.on('click', '.bubble-select-next', function() {
            _this.$form.trigger('submit');
        });

        // On form validation
        _this.$form.on('submit', function(e){
            e.preventDefault();

            // Hide previous error message
            $('.error-message-wrapper').slideUp(300);
            $('.question-wrapper').removeClass('error');

            let step_validate_url = _this.urls.validate.replace('%step%', _this.current_step);
            // Form
            var $form = $(this);

            // Add spinning to button
            let $button = $form.find('button.btn-next').not('[data-skip=1]');
            let buttonText = $button.html();

            _this.disable_form_next_button($button, buttonText);

            // Check validation
            $.post(step_validate_url, $form.serialize(), function(data){
                    // If status == 1 and next_step is not null
                    if(data.status == 1){
                        if(data.next_step != ''){
                            _this.remove_step_active();
                            setTimeout(function(){
                                _this.changeurl(data.next_step,'#'+data.next_step)
                                _this.hash();
                                _this.sidebar_close();
                            }, _this.step_delay);
                        }
                    } else {
                        // error to submit validation
                        if(data.errors){
                            _this.show_error_message(data.errors);

                            // Handle errors on hälsodeklarationen
                            $.each(data.errors, function(id, message) {
                                key_split = id.split('.');
                                if(key_split[0] === 'questions'){
                                    question_id = key_split[1];
                                    _this.$form.find('.question-wrapper[data-question-id='+question_id+']').addClass('error');
                                }
                            });
                        }
                        // enable button
                        _this.enable_form_next_button($button, buttonText);
                    }

                }, 'json')
                .fail(function(){
                    // enable button
                    _this.enable_form_next_button($button, buttonText);
                });
        });
    }

    // Initiate this object
    this.initiate();
}

$(document).ready(function(){

    $('body').on('click', '.btn-sidebar', function() {
        var data = $(this).attr('data-content');
        $('.app-sidebar-content').hide();
        $('.app-sidebar-'+data).show();
        $('body').addClass('sidebar-open');
    });

    $('#app-sidebar').on('click', '.app-sidebar-x', function(e) {
        e.preventDefault();
        $('body').removeClass('sidebar-open');
        $('.app-sidebar-content').fadeOut(300);
    });

});
