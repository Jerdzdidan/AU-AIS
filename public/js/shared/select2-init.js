/**
 * Simple Select2 Initializer with Badge Styling
 * Clean and reusable for Laravel projects
 */

function initSelect2(selector, options = {}) {
    const {
        url = null,
        badgeKey = 'code',
        badgeClass = 'bg-primary',
        placeholder = 'Select an option'
    } = options;

    const $element = $(selector);
    if (!$element.length) return;


    // Base config
    const config = {
        allowClear: true,
        placeholder: placeholder,
        templateResult: formatWithBadge,
        templateSelection: formatWithBadge
    };

    // Add AJAX if URL provided
    if (url) {
        config.ajax = {
            url: url,
            dataType: 'json',
            delay: 250,
            processResults: (response) => ({
                results: (Array.isArray(response) ? response : response.data || []).map(item => ({
                    id: item.id,
                    text: item.name || item.text,
                    code: item[badgeKey] || ''
                }))
            })
        };
    }

    $element.select2(config);

    // Format function
    function formatWithBadge(option) {
        if (!option.id) return option.text;
        
        const code = option.code || $(option.element).data('code') || '';
        if (!code) return $('<span>' + option.text + '</span>');

        return $(
            '<span>' + 
                code + ' - ' + option.text +
            '</span>'
        );
    }
}

// Reset Select2
function resetSelect2(selector) {
    $(selector).val(null).trigger('change');
}

// Set Select2 value
function setSelect2Value(selector, value) {
    if (value) $(selector).val(value).trigger('change');
}