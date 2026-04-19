export const editor_config = {
    //Editor configuration description -> https://ckeditor.com/latest/samples/toolbarconfigurator/#basic

    get_big_editor_config(){
        const token = document.head.querySelector('meta[name="csrf-token"]').content;
        return {
            height: 400,
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                'undo', 'redo'
            ],
            simpleUpload: {
                url: '/api/ckeditor/upload/',
                headers: {
                    'X-CSRF-TOKEN': token
                }
            },
            // https://www.tutsmake.com/laravel-8-ckeditor-image-upload-tutorial-example/
            simpleUpload: {
                url: '/api/ckeditor/upload/',
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                }
            },

            // https://ckeditor.com/docs/ckeditor4/latest/examples/mediaembed.html
// Removed incompatible CK4 extraPlugins: embed,autoembed - use CK5 mediaEmbed
            
            // contentsCss: [
            //     'http://cdn.ckeditor.com/4.20.2/full-all/contents.css',
            //     'https://ckeditor.com/docs/ckeditor4/4.20.2/examples/assets/css/widgetstyles.css'
            // ],
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
            
            image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
            image2_disableResizer: true,
            removeButtons: 'PasteFromWord',
            // colorButton: 'CF5D4E,454545,FFF,DDD,CCEAEE,66AB16',
            // toolbar: []
        }
    },

    get_small_editor_config(){
        const token = document.head.querySelector('meta[name="csrf-token"]').content;
        return {
            height: 200,
            toolbar: [
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'undo', 'redo'
            ],
            simpleUpload: {
                url: '/api/ckeditor/upload/',
                headers: {
                    'X-CSRF-TOKEN': token
                }
            },
            removeButtons: 'Link,Unlink,Anchor,Image,Table,Underline,Strike,Subscript,Superscript,RemoveFormat,NumberedList,BulletedList,Outdent,Indent,Blockquote,Scayt,HorizontalRule,SpecialChar,Styles,Format,Font,FontSize',
            // colorButton_colors: 'CF5D4E,454545,FFF,DDD,CCEAEE,66AB16'
        }
    },
}