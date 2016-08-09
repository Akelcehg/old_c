$( document ).ready(function() {
   $("#counterFileSelect").change(function (e) {
            if(this.disabled) return alert('File upload not supported!');
            var F = this.files;
            if(F && F[0]) for(var i=0; i<F.length; i++) readImage( F[i] );
        });

    function readImage(file) {

        var reader = new FileReader();
        var image  = new Image();

        reader.readAsDataURL(file);
        reader.onload = function(_file) {

            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext("2d");

            image.src    = _file.target.result;              // url.createObjectURL(file);
            image.onload = function() {

                var maxWidth = 1000;
                var maxHeight = 700;
                var ratio = 0;
                var width = image.width;
                var height = image.height;

                if(width > maxWidth){
                    ratio = maxWidth / width;
                    image.width = maxWidth;
                    image.height = height * ratio;
                    height = height * ratio;
                    width = width * ratio;
                }


                if(height > maxHeight){
                    ratio = maxHeight / height;
                    image.height = maxHeight;
                    image.width = width * ratio;
                    width = width * ratio;
                    height = height * ratio;
                }

                canvas.width = image.width;
                canvas.height = image.height;
                ctx.drawImage(image, 0, 0, image.width, image.height);
                var dataurl = canvas.toDataURL("image", 1.0);

                var hidden = document.getElementById("imageInput");
                hidden.value = dataurl;

            };
            image.onerror= function() {
                alert('Invalid file type: '+ file.type);
            };
        };

    }
});