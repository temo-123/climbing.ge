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
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                }
            },
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    '|', 
                    'imageStyle:blockImage',
                    'imageStyle:side',
                    '|',
                    'toggleImageCaption'
                ]
            },
            removeButtons: 'PasteFromWord'
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
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                }
            },
            removeButtons: 'Link,Unlink,Anchor,Image,Table,Underline,Strike,Subscript,Superscript,RemoveFormat,NumberedList,BulletedList,Outdent,Indent,Blockquote,Scayt,HorizontalRule,SpecialChar,Styles,Format,Font,FontSize'
        }
    },
}
