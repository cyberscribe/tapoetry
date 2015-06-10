<script>
jQuery( document ).ready(function() {
    if ( wp && wp.media && wp.media.editor) {
        jQuery('<button/>', {
                id:     'PoetImageUrlButton',
                class:  'button',
                html:   'Choose/Upload Image'
        }).insertAfter('#PoetImageUrl');
        var poet_image_wrapper = jQuery('#PoetImageUrl').parent();
        jQuery('<div id="PoetImagePreviewWrapper" style="display: none;">' +
                    '<label for="PoetImagePreview">Preview</label>' +
                    '<img id="PoetImagePreview" style="max-width: 200px;"></div>'
        ).insertAfter(poet_image_wrapper);
        jQuery('.wrap').on('click', '#PoetImageUrlButton', function(e) {
            e.preventDefault();
            wp.media.editor.send.attachment = function(props, attachment) {
                console.log(attachment);
                jQuery('#PoetImageUrl').val(attachment.sizes['medium-sepia'].url).trigger('change');
            };
            wp.media.editor.open( jQuery('#PoetImageUrlButton') );
            return false;
        }).on('change','#PoetImageUrl', function(e) {
            if(this.value.length > 0) {
                jQuery('#PoetImagePreview').attr('src',this.value);
                jQuery('#PoetImagePreviewWrapper').fadeIn();
            }
        });
        if (jQuery('#PoetImageUrl').val().length > 0) {
            jQuery('#PoetImageUrl').trigger('change');
        }
    }
});
</script>
