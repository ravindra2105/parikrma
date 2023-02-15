<html>
<head>
    <title>Convert HTML To PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
</head>
<body>
    
   <form class="form">
    
    <table class="table">
                        <tr style="background:red">
                            <td>Logo</td>
                            <td>Text</td>
                            <td>Guru</td>
                        </tr>
                        <tr style="background:blue">
                            <td>Name</td>
                            <td colspan="3"><input type="text" class="form-control" value="{{$data->name}}"></td>
                            
                        </tr>
                        <tr style="background:blue">
                            <td>Age</td>
                            <td><input type="text" class="form-control" value="{{$data->age}}"></td>
                            <td>Cell No</td>
                            <td><input type="text" class="form-control" value="{{$data->mobile}}"></td>
                        </tr>
                        <tr style="background:blue">
                            <td>RG.N</td>
                            <td><input type="text" class="form-control"></td>
                            <td>Period</td>
                            <td><input type="text" class="form-control"></td>
                        </tr>
                        <tr style="background:pink">
                            <td colspan="4">Enquiry: +91 25255 25252 (Vrindavan CH.Das)</td>
                        </tr>
                        <tr style="background:blue">
                            <td><div style="box-radius:5px">
                            {{QrCode::generate("Name: ".$data->name.",Mobile: ".$data->mobile." Age: ".$data->age)}}
                            </div></td>
                            <td colspan="2">Registration Card</td>
                            <td><div style="box-radius:5px"><img style="max-width:200px" src="{{$data->img_url}}"></div></td>
                        </tr>
                    </table>
</form>

</body>

</html>

<center>
  <p>
  
    <button class="btn btn-danger" id="create_pdf">Download PDF</button>
  </p>
</center>
            


<script>
    (function () {
        var
         form = $('.form'),
         cache_width = form.width(),
         a4 = [595.28, 841.89]; // for a4 size paper width and height

        $('#create_pdf').on('click', function () {
            $('body').scrollTop(0);
            createPDF();
        });
        //create pdf
        function createPDF() {
            getCanvas().then(function (canvas) {
                var
                 img = canvas.toDataURL("image/png"),
                 doc = new jsPDF({
                     unit: 'px',
                     format: 'a4'
                 });
                doc.addImage(img, 'JPEG', 20, 20);
                doc.save('idcard-html-to-pdf.pdf');
                form.width(cache_width);
            });
        }

        // create canvas object
        function getCanvas() {
            form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
            return html2canvas(form, {
                imageTimeout: 2000,
                removeContainer: true
            });
        }

    }());
</script>
<script>
    (function ($) {
        $.fn.html2canvas = function (options) {
            var date = new Date(),
            $message = null,
            timeoutTimer = false,
            timer = date.getTime();
            html2canvas.logging = options && options.logging;
            html2canvas.Preload(this[0], $.extend({
                complete: function (images) {
                    var queue = html2canvas.Parse(this[0], images, options),
                    $canvas = $(html2canvas.Renderer(queue, options)),
                    finishTime = new Date();

                    $canvas.css({ position: 'absolute', left: 0, top: 0 }).appendTo(document.body);
                    $canvas.siblings().toggle();

                    $(window).click(function () {
                        if (!$canvas.is(':visible')) {
                            $canvas.toggle().siblings().toggle();
                            throwMessage("Canvas Render visible");
                        } else {
                            $canvas.siblings().toggle();
                            $canvas.toggle();
                            throwMessage("Canvas Render hidden");
                        }
                    });
                    throwMessage('Screenshot created in ' + ((finishTime.getTime() - timer) / 1000) + " seconds<br />", 4000);
                }
            }, options));

            function throwMessage(msg, duration) {
                window.clearTimeout(timeoutTimer);
                timeoutTimer = window.setTimeout(function () {
                    $message.fadeOut(function () {
                        $message.remove();
                    });
                }, duration || 2000);
                if ($message)
                    $message.remove();
                $message = $('<div ></div>').html(msg).css({
                    margin: 0,
                    padding: 10,
                    background: "#000",
                    opacity: 0.7,
                    position: "fixed",
                    top: 10,
                    right: 10,
                    fontFamily: 'Tahoma',
                    color: '#fff',
                    fontSize: 12,
                    borderRadius: 12,
                    width: 'auto',
                    height: 'auto',
                    textAlign: 'center',
                    textDecoration: 'none'
                }).hide().fadeIn().appendTo('body');
            }
        };
    })(jQuery);
</script>
