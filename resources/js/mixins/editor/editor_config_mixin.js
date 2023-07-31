export const editor_config = {
    //Editor configuration description -> https://ckeditor.com/latest/samples/toolbarconfigurator/#basic

    get_big_editor_config(){
        return {
            // https://www.tutsmake.com/laravel-8-ckeditor-image-upload-tutorial-example/
            filebrowserUploadUrl: "../../../../../../../../../../api/ckeditor/upload",

            // https://ckeditor.com/docs/ckeditor4/latest/examples/mediaembed.html
            extraPlugins: 'embed,autoembed',
            
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
        return {
            removeButtons: 'Link,Unlink,Anchor,Image,Table,Underline,Strike,Subscript,Superscript,RemoveFormat,NumberedList,BulletedList,Outdent,Indent,Blockquote,Scayt,HorizontalRule,SpecialChar,Styles,Format,Font,FontSize',
            // colorButton_colors: 'CF5D4E,454545,FFF,DDD,CCEAEE,66AB16'
        }
    },
}