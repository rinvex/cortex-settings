    let isExpended = 0;
    function initSettings(data) {
        $('#setting-select2').select2({
            placeholder: "Select the settings type you want to edit",
            allowClear: true,
            closeOnSelect: false,
            data: data,
        }).on("select2:closing", function(e) {
            e.preventDefault();
        }).select2("open");
    }

    try {
        initSettings(groups);
    }catch (e) {}

    $('#setting-toggle').on('click', function (e) {
        e.preventDefault();
        let data = groups;
        if (isExpended == 0) {
            data = expended_groups;
            $('#setting-toggle').html(Lang.get('cortex/settings::common.collapse_settings'));
            isExpended = 1;
        }
        else {
            $('#setting-toggle').html(Lang.get('cortex/settings::common.expand_settings'))
            isExpended = 0;
        }
        $('#setting-select2').select2('destroy').empty();
        initSettings(data);
    })

    $('#restore-settings-file').on('change', function (e) {
        $('#restore-settings-form').submit();
    });
    $('#import-settings-file').on('change', function (e) {
        $('#import-settings-form').submit();
    });

    $(document).on('change', '.setting-update', function (e) {
        unsaved = true;
    });

    $('.bulk-settings-from').on('submit', function (e) {
        unsaved = false;
    });

    $('a').on('click', function (e) {
        e.preventDefault();
        let loc = $(this).attr('href');
        if(unsaved) {
            unsavedPopup(loc)
        }
        else {
            window.location.href = loc;
        }
    })

    function unsavedPopup(location) {
        var result = confirm(Lang.get('cortex/settings::common.changes_you_made_not_saved'));

        if (result) {
            window.location.href = location;
        }
    }

    $(document).on('click', '#go-setting-group', function () {
        let val = $('.select-setting-type').val();
        if (val == 0) {
            val = '';
        }

        let loc = routes.route('adminarea.cortex.settings.settings.index', {group_key:val});

        if (unsaved !== undefined && unsaved === true) {
            unsavedPopup(loc)
        }
        else {
            window.location.href = loc;
        }
    });

    $('.setting-key').on('keyup', function () {
        let val = $(this).val();
        val = val.toLowerCase()
            .replace(/ /g,'.')
            .replace(/[^\w.]+/g,'');
        $(this).val(val);
    })
    $('#content-type-el').on('change', function (e) {
        let el = $(this);
        if ($.inArray(el.val(), ['radio', 'checkbox', 'dropdown', 'multi-select']) !== -1)
        {
            $('.option-section').removeClass('hidden')
            $('#resource-select').addClass('hidden');
            $('.add-options').html('');
            appendOption();
        }
        else if(el.val() === 'resource') {
            $('#resource-select').removeClass('hidden');
            $('.option-section').addClass('hidden');
        }
        else
        {
            $('#resource-select').addClass('hidden')
            $('.option-section').addClass('hidden')
        }
    });

    $('#add-option-btn').on('click', function () {
        appendOption();
    });

    $(document).on('click', '.option-delete-btn', function () {
        let parentEl = $(this).closest('.content-option');
        parentEl.remove();
        rerenderOptions();
    })

    function rerenderOptions() {
        $('.content-option').each( function (index) {
            let length = index+1;
            $(this).find('label').html(Lang.get('Option')+length);
            $(this).find('input').attr('name', 'options['+length+']');
        });
    }

    function appendOption() {
        let parentEl = $('.add-options');
        let optionCount = $('.content-option').length + 1;
        let lang = Lang;
        let content = `
                <div class="row content-option margin-bottom">
                    <div class="col-md-6">
                        <div class="input-group input-group-multi">
                            <div class="input-group-addon">${lang.get('Option')} ${optionCount}</div>
                            <div class="col-xs-6">
                                <input type="text" name="options[${optionCount}][key]" placeholder="${lang.get('cortex/settings::common.key')}" class="form-control">
                            </div>
                            <div class="col-xs-6 no-gutters">
                                <input type="text" name="options[${optionCount}][text]" placeholder="${lang.get('cortex/settings::common.option_text')}" class="form-control">
                            </div>
                            <div class="input-group-addon btn btn-danger option-delete-btn">
                                <span class="fa fa-trash"></span>
                            </div>
                        </div>
                    </div>
                </div>
                `
        parentEl.append(content);
    }
