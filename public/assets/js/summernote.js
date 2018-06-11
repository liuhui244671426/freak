$(document).ready(function(){
    $('.summernote').summernote({
        lang: 'zh-CN',
        height:300,
        minHeight:50,
        onImageUpload: function(files, editor, $editable) {
            var formData = new FormData();
            formData.append('file', files[0]);
            $.ajax({
                data: formData,
                type: "POST",
                url: "/?m=admin&c=upload&a=file", //图片上传出来的url，返回的是图片上传后的路径，http格式
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if(data['status'] == 1) {
                        //把图片放到编辑框中。editor.insertImage 是参数，写死。后面是图片资源路径。
                        $('.summernote').summernote('editor.insertImage', data['data'][0]['savepath']+data['data'][0]['savename']);
                    }
                    if(data['status'] == -1) {
                        console.error(data['data']);
                    }
                },
                error: function () {
                    alert("上传失败");

                }
            });
        }
    });
});