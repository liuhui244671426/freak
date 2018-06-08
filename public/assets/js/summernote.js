$('.summernote').summernote({
    height:300,
    minHeight:50
    /*onImageUpload: function(files, editor, $editable) {
        var formData = new FormData();
        formData.append('file', files[0]);
        $.ajax({
            data: formData,
            type: "POST",
            url: "/?m=admin&a=editor_post", //图片上传出来的url，返回的是图片上传后的路径，http格式
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                console.log(data);
                //把图片放到编辑框中。editor.insertImage 是参数，写死。后面的http是网上的图片资源路径。
                //网上很多就是这一步出错。
                $('.summernote').summernote('editor.insertImage', data);

            },
            error: function () {
                alert("上传失败");

            }
        });
    }*/
});