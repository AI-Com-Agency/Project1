/*global $, jQuery, alert */
jQuery(document).ready(function ($) {
        "use strict";

        function media_upload(button_class) {
            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;
            $('body').on('click', '.custom_media_upload', function (e) {
                var button_id = '#' + $(this).attr('id'),
                    button_id_s = $(this).attr('id'),
                    self = $(button_id),
                    send_attachment_bkp = wp.media.editor.send.attachment,
                    button = $(button_id),
                    id = button.attr('id').replace('_button', '');
                _custom_media = true;

                wp.media.editor.send.attachment = function (props, attachment) {
                    if (_custom_media) {
                        $('.' + button_id_s + '_media_id').val(attachment.url).trigger('change');
                        $('.' + button_id_s + '_media_image').attr('src', attachment.url).css('display', 'block');
                    } else {
                        return _orig_send_attachment.apply(button_id, [props, attachment]);
                    }
                };
                wp.media.editor.open(button);
                return false;
            });
        }

        media_upload('.custom_media_upload');

    }
);
