<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<style>
    .content {
        background-color: #f8f9fa;
        position: relative;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-tachometer" aria-hidden="true"></i> Quản Lý Mã Vạch
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <video id="preview" width="100%" height="300"></video>
            </div>
            <div class="col-md-12" style="text-align: center;">
                <label>SCAN QRCODE DEMO</label>
                <input type="text" class="form-control" id="result" readonly="" placeholder="result">
            </div>
        </div>
    </section>
</div>

<script>
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });

    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            alert('No cameras found!');
        }
    }).catch(function(e) {
        console.error(e);
    });

    scanner.addListener('scan', function(content) {
        let decodedContent = decodeURIComponent(escape(content));
        console.log('Scanned data:', decodedContent);
        document.getElementById('result').value = decodedContent;
    });
</script>
</body>
</html>
