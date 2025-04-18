jQuery(document).ready(function ($) {
    "use strict";

    /**
     * Googe Font Select Custom Control
     *
     * @since Roofix 1.0
     *
     */

    $('.google-fonts-list').each(function (i, obj) {
        if (!$(obj).hasClass('select2-hidden-accessible')) {
            $(obj).select2();
        }
    });

    $('.google-fonts-list').on('change', function () {
        var elementRegularWeight = $(this).parent().parent().find('.google-fonts-regularweight-style');
        var elementItalicWeight = $(this).parent().parent().find('.google-fonts-italicweight-style');
        var elementBoldWeight = $(this).parent().parent().find('.google-fonts-boldweight-style');
        var selectedFont = $(this).val();
        var customizerControlName = $(this).attr('control-name');
        var elementItalicWeightCount = 0;
        var elementBoldWeightCount = 0;

        // Clear Weight/Style dropdowns
        elementRegularWeight.empty();
        elementItalicWeight.empty();
        elementBoldWeight.empty();
        // Make sure Italic & Bold dropdowns are enabled
        elementItalicWeight.prop('disabled', false);
        elementBoldWeight.prop('disabled', false);

        // Get the Google Fonts control object
        var bodyfontcontrol = _wpCustomizeSettings.controls[customizerControlName];

        // Find the index of the selected font
        var indexes = $.map(bodyfontcontrol.rtthemefontslist, function (obj, index) {
            if (obj.family === selectedFont) {
                return index;
            }
        });
        var index = indexes[0];

        // For the selected Google font show the available weight/style variants
        $.each(bodyfontcontrol.rtthemefontslist[index].variants, function (val, text) {
            elementRegularWeight.append(
                $('<option></option>').val(text).html(text)
            );
            if (text.indexOf("italic") >= 0) {
                elementItalicWeight.append(
                    $('<option></option>').val(text).html(text)
                );
                elementItalicWeightCount++;
            } else {
                elementBoldWeight.append(
                    $('<option></option>').val(text).html(text)
                );
                elementBoldWeightCount++;
            }
        });

        if (elementItalicWeightCount == 0) {
            elementItalicWeight.append(
                $('<option></option>').val('').html('Not Available for this font')
            );
            elementItalicWeight.prop('disabled', 'disabled');
        }
        if (elementBoldWeightCount == 0) {
            elementBoldWeight.append(
                $('<option></option>').val('').html('Not Available for this font')
            );
            elementBoldWeight.prop('disabled', 'disabled');
        }

        // Update the font category based on the selected font
        $(this).parent().parent().find('.google-fonts-category').val(bodyfontcontrol.rtthemefontslist[index].category);

        rtthemeGetAllSelects($(this).closest('.google_fonts_select_control'));
    });

    $('.google_fonts_select_control select').on('change', function () {
        rtthemeGetAllSelects($(this).closest('.google_fonts_select_control'));
    });

    $('.google_fonts_select_control input[type=number]').on('input', function (){
        rtthemeGetAllSelects($(this).closest('.google_fonts_select_control'));
    })

    function rtthemeGetAllSelects($element) {
        var selectedFont = {
            font: $element.find('.google-fonts-list').val(),
            regularweight: $element.find('.google-fonts-regularweight-style').val(),
            italicweight: $element.find('.google-fonts-italicweight-style').val(),
            boldweight: $element.find('.google-fonts-boldweight-style').val(),
            category: $element.find('.google-fonts-category').val(),
            size: $element.find('.google-font-size').val(),
            lineheight: $element.find('.google-font-line-height').val(),
        };

        console.log(selectedFont)

        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.customize-control-google-font-selection').val(JSON.stringify(selectedFont)).trigger('change');
    }

});
