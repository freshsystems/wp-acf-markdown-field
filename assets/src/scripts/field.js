import easyMDE from 'easymde';
import hljs from 'highlight.js';
// import hljs from 'highlight.js/lib/core';
// import javascript from 'highlight.js/lib/languages/javascript';
// hljs.registerLanguage('javascript', javascript);

(function ($) {
  /**
   *  initialize_field
   *  This function will initialize the custom field.
   */
  function initialize_field($field) {
    var $inputEl = $field.find('.fresh-acf-markdown-input');
    if (!$inputEl.length) {
      console.error(
        'Cannot initialise the markdown field, .fresh-acf-markdown-input element does not exist.'
      );
      return;
    }
    var frame;
    var editor = new easyMDE({
      element: $inputEl[0],
      toolbarTips: false,
      sideBySideFullscreen: false,
      spellChecker: false,
      nativeSpellcheck: true,
      forceSync: true,
      maxHeight: '300px',
      syncSideBySidePreviewScroll: true,
      toolbar: [
        'bold',
        'italic',
        'heading',
        '|',
        'quote',
        'unordered-list',
        'ordered-list',
        '|',
        'link',
        // 'image',
        {
          name: 'wp-media',
          action: (editor) => {
            if (frame) {
              frame.open();
              return;
            }
            // Create a new media frame
            frame = wp.media({
              multiple: false, // Set to true to allow multiple files to be selected
            });
            frame.on('select', function () {
              var attachment = frame.state().get('selection').first().toJSON();
              var altText = attachment.alt
                ? attachment.alt
                : attachment.title
                ? attachment.title
                : '';
              editor.codemirror.replaceSelection('![' + altText + '](' + attachment.url + ')');
            });
            frame.open();
          },
          className: 'fa fa-picture-o',
          title: 'Media Library',
        },
        'table',
        'code',
        '|',
        'side-by-side',
        'preview',
        '|',
        'undo',
        'redo',
      ],
      renderingConfig: {
        codeSyntaxHighlighting: true,
        hljs: hljs,
      },
    });
  }

  if (typeof acf.add_action !== 'undefined') {
    /*
     *  ready & append (ACF5)
     *
     *  These two events are called when a field element is ready for initizliation.
     *  - ready: on page load similar to $(document).ready()
     *  - append: on new DOM elements appended via repeater field or other AJAX calls
     *
     *  @param	n/a
     *  @return	n/a
     */
    acf.add_action('ready_field/type=markdown', initialize_field);
    acf.add_action('append_field/type=markdown', initialize_field);

    // See: https://www.advancedcustomfields.com/resources/javascript-api/#actions-disable_field
    // acf.addAction('disable_field/type=markdown', function ($field) {
    //   var $inputEl = $field.find('.fresh-acf-markdown-input');
    //   if (!$inputEl.length) {
    //     console.error(
    //       'Cannot disable the markdown field, .fresh-acf-markdown-input element does not exist.'
    //     );
    //     return;
    //   }
    // });
  } else {
    console.error('Cannot initialise the markdown field, ACF v4 is not supported.');
  }
})(jQuery);
